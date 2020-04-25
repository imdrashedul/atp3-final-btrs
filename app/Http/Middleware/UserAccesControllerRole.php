<?php

namespace App\Http\Middleware;

use Closure;

class UserAccesControllerRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$role)
    {


        if(!empty($role) && !user_has_role($role)) {
            return redirect()->route('system');
        }

        return $next($request);
    }
}
