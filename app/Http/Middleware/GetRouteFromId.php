<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\RouteController;

class GetRouteFromId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!isset($_SERVER[ 'HTTP_X_HUB_SIGNATURE' ])) {
            abort(404);
        }

        $request->routeFromId = RouteController::get($request->id);

        if (!$request->routeFromId) {
            abort(404);
        }
        return $next($request);
    }
}
