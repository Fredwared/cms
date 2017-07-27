<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Route;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 403);
            } else {
                return redirect(route('backend.auth.login'));
            }
        }
        
        //Check Permission to link here, and check is admin
        if (env('APP_ENV') != 'local') {
            /*$route_name = $request->route()->getName();
            $account_cd = auth('superstar')->user()->getAccountCode();
            $arrListRoute = Globals::getMenuByAccountID($account_cd, 2);
        
            if (!empty($arrListRoute) && in_array($route_name, $arrListRoute)) {
                return $next($request);
            } else {
                return redirect('backend.503');
            }*/
        }
        
        return $next($request);
    }
}
