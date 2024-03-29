<?php

namespace App\Http\Middleware;

use Closure;

class VerifyGuest
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
        if(!empty($user = user())){
            return redirect()->route('system');
        }
        return $next($request);
    }
}
