<?php
namespace App\Http\Middleware;

use App\Models\Services\Globals;
use Closure;

class Role
{
    /**
     * Check role
     *
     * @param unknown $request
     * @param Closure $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        //get controller and action
        $action = $request->route()->getAction();
        $controller = class_basename($action['controller']);

        $namespace = strtolower(class_basename($action['namespace']));
        if ($namespace != 'backend') {
            $namespace = 'backend_' . $namespace;
        }

        list($controller, $action) = explode('@', $controller);
        $controller = strtolower(str_replace('Controller', '', $controller));

        if ($namespace == 'backend_auth' || $controller == 'index' || ($namespace == 'backend_utils' && $controller != 'jobs') || ($namespace == 'backend' && $controller == 'user' && $action == 'userInfo')) {
            return $response;
        }

        //get model
        $businessRole = Globals::getBusiness('Role');

        //get role by action
        $role = $businessRole->getRoleByAction($action);

        if ((auth('backend')->user()->getAccountId() != config('cms.backend.super_admin_id')) && (!$role || !check_permission($controller, $role))) {
            return response()->view('backend.index.nopermission', [], 403);
        }

        return $response;
    }
}
