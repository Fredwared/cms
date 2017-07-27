<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BackendController;
use App\Models\Services\Jobs\SendMail;
use App\Models\Entity\Business\Business_User;
use App\Models\Services\Globals;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends BackendController
{
    public function index(Request $request)
    {
        $item = check_paging($request->item);
        $page = $request->page ?? 1;
        $status = $request->status ?? null;

        //get model
        $businessUser = Globals::getBusiness('User');

        // get list user;
        $params = [
            'status' => $status,
            'item' => $item,
            'page' => $page
        ];
        $arrListUser = $businessUser->getListUser($params);

        if ($arrListUser->total() > 0) {
            $maxPage = ceil($arrListUser->total() / $item);
            if ($maxPage < $page) {
                return redirect(route('backend.user.index', ['item' => $item, 'page' => $maxPage]));
            }
        }
        $pagination = $arrListUser->appends($params)->links();
        
        return view('backend.user.index', compact('arrListUser', 'pagination', 'item', 'status'));
    }

    public function create()
    {
        //get model
        $businessMenu = Globals::getBusiness('Menu');
        
        $arrListMenu = $businessMenu->getListMenu([
            'parent_id' => 0,
            'status' => config('cms.backend.user.status.active')
        ]);
        
        return view('backend.user.create', compact('arrListMenu'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'fullname' => 'required|max:200',
            'email' => 'required|email|max:200|unique:user,email,null,id,deleted,0',
            'address' => 'max:200',
            'phone' => 'max:20',
            'groups' => 'required',
            'status' => 'required|in:' . implode(',', [config('cms.backend.user.status.active'), config('cms.backend.user.status.inactive')])
        ], [
            'fullname.required' => trans('validation.user.fullname.required'),
            'fullname.max' => trans('validation.user.fullname.maxlength'),
            'email.required' => trans('validation.user.email.required'),
            'email.email' => trans('validation.user.email.email'),
            'email.max' => trans('validation.user.email.maxlength'),
            'email.unique' => trans('validation.user.email.unique'),
            'address.max' => trans('validation.user.address.maxlength'),
            'phone.integer' => trans('validation.user.phone.number'),
            'phone.max' => trans('validation.user.phone.maxlength'),
            'groups.required' => trans('validation.user.group.required'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);

        //get model
        $businessUser = Globals::getBusiness('User');
        $businessGroupUser = Globals::getBusiness('Group_User');

        $params = [
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => bcrypt(config('cms.backend.default_password')),
            'gender' => $request->gender ?? 1,
            'address' => $request->address ?? null,
            'phone' => $request->phone ?? null,
            'landing_page' => $request->landing_page ?? 'backend.index',
            'default_language' => $request->default_language ?? config('cms.backend.default_locale'),
            'days_password_expired' => $request->days_password_expired ?? 365,
            'password_updated_at' => Carbon::now(),
            'status' => $request->status
        ];

        $userInfo = $businessUser->create($params);

        //update table group_user
        $businessGroupUser->addGroupUserByUser($userInfo->id, $request->groups ?? []);

        flash()->success(trans('common.messages.user.created'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.user.edit', [$userInfo->id]));
        }

        return redirect(route('backend.user.index'));
    }

    public function edit(Business_User $userInfo)
    {
        if ($userInfo->getAccountId() == config('cms.backend.super_admin_id') && auth('backend')->user()->getAccountId() != config('cms.backend.super_admin_id')) {
            redirect('404');
        }
        
        //get model
        $businessMenu = Globals::getBusiness('Menu');
        
        $arrListMenu = $businessMenu->getListMenu([
            'parent_id' => 0,
            'status' => config('cms.backend.user.status.active')
        ]);
        
        return view('backend.user.edit', compact('userInfo', 'arrListMenu'));
    }

    public function update(Request $request, Business_User $userInfo)
    {
        if ($userInfo->getAccountId() == config('cms.backend.super_admin_id') && auth('backend')->user()->getAccountId() != config('cms.backend.super_admin_id')) {
            redirect('404');
        }
        
        $this->validate($request, [
            'fullname' => 'required|max:200',
            'email' => 'required|email|max:200|unique:user,email,' . $userInfo->email . ',email,deleted,0',
            'address' => 'max:200',
            'phone' => 'max:20',
            'landing_page' => 'max:200',
            'groups' => 'required',
            'status' => 'required|in:' . implode(',', config('cms.backend.user.status'))
        ], [
            'fullname.required' => trans('validation.user.fullname.required'),
            'fullname.max' => trans('validation.user.fullname.maxlength'),
            'email.required' => trans('validation.user.email.required'),
            'email.email' => trans('validation.user.email.email'),
            'email.max' => trans('validation.user.email.maxlength'),
            'email.unique' => trans('validation.user.email.unique'),
            'address.max' => trans('validation.user.address.maxlength'),
            'phone.integer' => trans('validation.user.phone.number'),
            'phone.max' => trans('validation.user.phone.maxlength'),
            'landing_page.max' => trans('validation.user.landing_page.max'),
            'groups.required' => trans('validation.user.group.required'),
            'status.required' => trans('validation.status.required'),
            'status.in' => trans('validation.status.invalid')
        ]);
        
        //get model
        $businessGroupUser = Globals::getBusiness('Group_User');

        $userInfo->update([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'gender' => $request->gender ?? $userInfo->gender,
            'address' => $request->address ?? $userInfo->address,
            'phone' => $request->phone ?? $userInfo->phone,
            'landing_page' => $request->landing_page ?? $userInfo->landing_page,
            'default_language' => $request->default_language ?? $userInfo->default_language,
            'days_password_expired' => $request->days_password_expired ?? $userInfo->days_password_expired,
            'status' => $request->status
        ]);

        //update table group_user
        $businessGroupUser->addGroupUserByUser($userInfo->id, $request->groups ?? []);

        flash()->success(trans('common.messages.user.updated'));

        if (auth('backend')->user()->stayDetailPage()) {
            return redirect(route('backend.user.edit', [$userInfo->id]));
        }

        return redirect(route('backend.user.index'));
    }

    public function userInfo(Request $request)
    {
        $userInfo = auth('backend')->user();

        if ($request->isMethod('get')) {
            return view('backend.user.profile', compact('userInfo'));
        } else {
            $this->validate($request, [
                'fullname' => 'required|max:200',
                'email' => 'required|email|max:200|unique:user,email,' . $userInfo->email . ',email,deleted,0',
                'address' => 'max:200',
                'phone' => 'max:20',
                'password' => 'between:6,20',
                're_password' => 'same:password',
            ], [
                'fullname.required' => trans('validation.user.fullname.required'),
                'fullname.max' => trans('validation.user.fullname.maxlength'),
                'email.required' => trans('validation.user.email.required'),
                'email.email' => trans('validation.user.email.email'),
                'email.max' => trans('validation.user.email.maxlength'),
                'email.unique' => trans('validation.user.email.unique'),
                'address.max' => trans('validation.user.address.maxlength'),
                'phone.integer' => trans('validation.user.phone.number'),
                'phone.max' => trans('validation.user.phone.maxlength'),
                'password.between' => trans('validation.user.password.rangelength'),
                're_password.same' => trans('validation.user.re_password.equalTo'),
            ]);

            $params = [
                'fullname' => $request->fullname,
                'email' => $request->email,
                'gender' => $request->gender ?? $userInfo->gender,
                'address' => $request->address ?? $userInfo->address,
                'phone' => $request->phone ?? $userInfo->phone,
                'gender' => $request->gender ?? $userInfo->gender,
                'default_language' => $request->default_language ?? $userInfo->default_language,
                'action_after_save' => $request->action_after_save ?? $userInfo->action_after_save
            ];

            //check if password is change
            if (!empty($request->password)) {
                $params['password'] = bcrypt($request->password);
                $params['password_updated_at'] = Carbon::now();
            }

            //check if user upload avatar
            if (!empty($request->avatar) && !str_contains($request->avatar, 'avatar/')) {
                $old_avatar = $request->avatar;
                $old_file = config('cms.backend.media.path') . '/tmp/' . $old_avatar;

                $new_avatar = preg_replace('/^(.[^.]*)\.([a-z]+)$/', 'avatar/$1-' . rand(1111, 9999) . '-' . time() . '.$2', $old_avatar);
                $new_file = config('cms.backend.media.path') . '/' . config('cms.backend.media.name.1') . '/' . $new_avatar;

                // Get disk storage
                $disk = Storage::disk(config('cms.backend.media.storage'));

                $disk->move($old_file, $new_file);
                $disk->delete($old_file);

                $params['avatar'] = $new_avatar;
            }
            
            $userInfo->update($params);

            flash()->success(trans('common.messages.user.updated'));

            return redirect(route('backend.user.profile'));
        }
    }

    public function avatar(Request $request)
    {
        $userInfo = auth('backend')->user();

        if ($request->isMethod('get')) {
            return view('backend.user.avatar', compact('userInfo'));
        } else {
            //check if user upload avatar
            if (!empty($request->avatar) && !str_contains($request->avatar, 'avatar/')) {
                $old_avatar = $request->avatar;
                $old_file = config('cms.backend.media.path') . '/tmp/' . $old_avatar;

                $new_avatar = preg_replace('/^(.[^.]*)\.([a-z]+)$/', 'avatar/$1-' . rand(1111, 9999) . '-' . time() . '.$2', $old_avatar);
                $new_file = config('cms.backend.media.path') . '/' . config('cms.backend.media.name.1') . '/' . $new_avatar;

                // Get disk storage
                $disk = Storage::disk(config('cms.backend.media.storage'));

                $disk->move($old_file, $new_file);
                $disk->delete($old_file);

                $userInfo->update([
                    'avatar' => $new_avatar
                ]);
            }

            return response()->json(['error' => 0, 'message' => trans('common.messages.user.updated')]);
        }
    }

    public function forgotPass(Business_User $userInfo)
    {
        if ($userInfo->getAccountId() == config('cms.backend.super_admin_id') && auth('backend')->user()->getAccountId() != config('cms.backend.super_admin_id')) {
            redirect('404');
        }

        //get model
        $businessToken = Globals::getBusiness('Token');
        
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

        return response()->json(['error' => 0, 'message' => trans('common.messages.auth.resetpass')]);
    }

    public function destroy(Request $request, Business_User $userInfo)
    {
        if ($userInfo->getAccountId() == config('cms.backend.super_admin_id') && auth('backend')->user()->getAccountId() != config('cms.backend.super_admin_id')) {
            redirect('404');
        }
        
        $userInfo->delete();

        if (!$request->ajax()) {
            flash()->success(trans('common.messages.user.deleted'));
            return redirect(route('backend.user.index'));
        } else {
            return response()->json(['error' => 0, 'message' => trans('common.messages.user.deleted')]);
        }
    }

    public function changeStatus(Request $request, $status)
    {
        if (!$request->ajax()) {
            return redirect('404');
        }
        
        if (!in_array($status, array_values(config('cms.backend.user.status')))) {
            return response()->json(['error' => 1, 'message' => trans('validation.status.invalid')]);
        }
        
        $arrId = $request->has('id') ? $request->id : [];

        if (!empty($arrId)) {
            $arrId = (array)$arrId;

            //get model
            $businessUser = Globals::getBusiness('User');
            
            foreach ($arrId as $id) {
                $businessUser->find($id)->update([
                    'status' => $status
                ]);
            }

            return response()->json(['error' => 0, 'message' => trans('common.messages.changestatus_success')]);
        } else {
            return response()->json(['error' => 1, 'message' => trans('common.messages.changestatus_error')]);
        }
    }
}
