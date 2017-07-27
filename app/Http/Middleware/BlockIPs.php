<?php
namespace App\Http\Middleware;

use App\Models\Services\Globals;
use Closure;

class BlockIPs
{
    /**
     * Check ip exist in list ips on system
     *
     * @param unknown $request
     * @param Closure $next
     */
    public function handle($request, Closure $next)
    {
        // get IP of Client
        $ip = $request->getClientIp();
        $modelBlockIp = Globals::getBusiness('BlockIp');
        
        // Check not exist
        if ($modelBlockIp->checkIp($ip) && env('APP_ENV') != 'local') {
            return redirect(503);
        }
        
        return $next($request);
    }
}
