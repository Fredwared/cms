<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Models\Services\Globals;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BackendController;
use App\Models\Services\Jobs\SendMail;
use Illuminate\Support\Facades\Session;

class IndexController extends BackendController
{
    use AuthenticatesUsers;
    
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo;
    
    /**
     * Where to redirect users after logout.
     *
     * @var string
     */
    protected $redirectAfterLogout;
    
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:backend', ['except' => ['logout', 'forgotpass', 'resetpass', 'message']]);
        
        $this->redirectTo = route('backend.index');
        $this->redirectAfterLogout = route('backend.auth.login');
    }
    
    public function getLogin()
    {
        return view('backend.auth.login');
    }
    
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => trans('validation.auth.email.required'),
            'email.email' => trans('validation.auth.email.email'),
            'password.required' => trans('validation.auth.password.required')
        ]);
        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            
            return $this->sendLockoutResponse($request);
        }
        
        if (Auth::guard('backend')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => config('cms.backend.user.status.active')], $request->has('remember'))) {
            // Authentication passed...
            return $this->sendLoginResponse($request);
        }
        
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if (!$lockedOut) {
            $this->incrementLoginAttempts($request);
        }
        
        return $this->sendFailedLoginResponse($request);
    }
    
    public function logout(Request $request)
    {
        //write log
        $businessLog = Globals::getBusiness('Log');
        $businessLog->writeLog([
            'log_type' => 'logout',
            'log_ip' => $request->getClientIp(),
            'user_id' => auth('backend')->user()->getAccountId(),
            'user_agent' => $request->header('User-Agent'),
            'cookie_val' => auth('backend')->getSession()->getId(),
        ]);

        auth('backend')->logout();

        $request->session()->flush();

        $request->session()->regenerate();
        
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : route('backend.login'));
    }
    
    public function authenticated(Request $request)
    {
        $user = auth('backend')->user();
        $language = $user->getDefaultLanguage();

        // write session
        $request->session()->put('backend-locale', $language);
        
        // set locale
        app()->setLocale($language);
        
        // write log
        $businessLog = Globals::getBusiness('Log');
        $businessLog->writeLog([
            'log_type' => 'login',
            'log_content' => [
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ],
            'log_ip' => $request->getClientIp(),
            'user_id' => $user->getAccountId(),
            'user_agent' => $request->header('User-Agent'),
            'cookie_val' => auth('backend')->getSession()->getId(),
        ]);
        
        //redirect to landing page
        return redirect()->intended(route($user->getLandingPage()));
    }
    
    public function forgotPass(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('backend.auth.forgotpass');
        } else {
            $this->validate($request, [
                'email' => 'required|email|exists:user,email,status,' . config('cms.backend.user.status.active') . ',id,!' . config('cms.backend.super_admin_id'),
                'captcha' => 'required|captcha',
            ], [
                'email.required' => trans('validation.auth.email.required'),
                'email.email' => trans('validation.auth.email.email'),
                'email.exists' => trans('validation.auth.email.exists'),
                'captcha.required' => trans('validation.captcha.required'),
                'captcha.captcha' => trans('validation.captcha.invalid'),
            ]);

            $businessUser = Globals::getBusiness('User');
            $businessToken = Globals::getBusiness('Token');
            
            $userInfo = $businessUser->findByAttributes([
                'email' => $request->email
            ]);
            
            $token_key = $businessToken->insertTokenKey([
                'token_type' => config('cms.token.type.reset_password'),
                'user_id' => $userInfo->id
            ]);
            
            $arrToken = array('user_id' => $userInfo->id, 'token_key' => $token_key);
            $strToken = json_encode($arrToken);
            $keyReset = base64_encode($strToken);
            
            dispatch(new SendMail('backend.mails.forgot-password', [
                'subject' => 'Cách đặt lại mật khẩu của bạn',
                'mail_to' => $userInfo->email,
                'recipient_name' => $userInfo->fullname,
                'url' => route('backend.auth.resetpass', [$keyReset])
            ]));
        
            //set status user to 3: wait reset password
            $userInfo->update([
                'status' => config('cms.backend.user.status.forgotpass')
            ]);
            
            flash()->success(trans('common.messages.auth.resetpass'));
            
            return redirect(route('backend.auth.message'));
        }
    }
    
    public function resetPass(Request $request)
    {
        $key = $request->key;
        
        //get param
        $decode = base64_decode($key);
        $data = json_decode($decode, true);
        $user_id = !empty($data['user_id']) ? (int)$data['user_id'] : 0;
        $token_key = !empty($data['token_key']) ? $data['token_key'] : '';

        //get model
        $businessUser = Globals::getBusiness('User');
        $businessToken = Globals::getBusiness('Token');
        
        //check key is valid or not
        $arrData = $businessToken->getTokenKey([
            'token_type' => config('cms.token.type.reset_password'),
            'user_id' => $user_id
        ]);
        
        if (empty($arrData)) {
            flash()->error(trans('common.messages.auth.key.expired'));
            return redirect(route('backend.auth.message'));
        } elseif ($token_key != $arrData['token_key']) {
            flash()->error(trans('common.messages.auth.key.invalid'));
            return redirect(route('backend.auth.message'));
        }
        
        if ($request->isMethod('get')) {
            return view('backend.auth.resetpass', compact('key'));
        } else {
            $this->validate($request, [
                'password' => 'required|between:6,20',
                're_password' => 'required|same:password',
            ], [
                'password.required' => trans('validation.user.password.required'),
                'password.between' => trans('validation.user.password.rangelength'),
                're_password.required' => trans('validation.user.re_password.required'),
                're_password.same' => trans('validation.user.re_password.equalTo'),
            ]);

            //get user info and update password
            $userInfo = $businessUser->find($user_id);
            $userInfo->update([
                'status' => config('cms.backend.user.status.active'),
                'password' => bcrypt($request->password),
                'password_updated_at' => Carbon::now()
            ]);

            //update token after change pass
            $businessToken->deleteTokenKey([
                'token_type' => config('cms.token.type.reset_password'),
                'user_id' => $user_id
            ]);

            flash()->success(trans('common.messages.auth.changepass_success'));

            return redirect(route('backend.auth.message'));
        }
    }

    public function message(Request $request)
    {
        if (!Session::has('flash_notification.message')) {
            return redirect(route('backend.auth.login'));
        }

        return view('backend.auth.message');
    }
}
