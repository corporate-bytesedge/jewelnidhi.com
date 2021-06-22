<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Original;

class CheckForMaintenanceMode extends Original
{
    protected $excludedNames = [];
    protected $except = ['login', 'logout'];
    protected $excludedIPs = [];
    protected function shouldPassThrough($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }
            if ($request->is($except)) {
                return true;
            }
        }
        return false;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        if ($this->app->isDownForMaintenance()) {
            $response = $next($request);
            if (in_array($request->ip(), $this->excludedIPs)) {
                return $response;
            }
            $route = $request->route();
            if ($route instanceof Route) {
                if (in_array($route->getName(), $this->excludedNames)) {
                    return $response;
                }
            }
            if ($this->shouldPassThrough($request)) 
            {
                return $response;
            }
            if(isset(Auth::user()->role_id) && Auth::user()->role_id > 0) {
                return $response;
            }
            throw new HttpException(503);
        }
        return $next($request);
    }
}
