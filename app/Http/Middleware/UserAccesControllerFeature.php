<?php

namespace App\Http\Middleware;

use Closure;

class UserAccesControllerFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param array $featires
     * @return mixed
     */
    public function handle($request, Closure $next, ...$features)
    {
        if(user_has_access($features))
        {
            return $next($request);
        }

        return redirect()->route('system');
    }
}
