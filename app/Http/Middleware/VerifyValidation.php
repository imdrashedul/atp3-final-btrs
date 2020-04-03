<?php

namespace App\Http\Middleware;

use Closure;

class VerifyValidation
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
        if($request->session()->has('user') && is_array($user = $request->session()->get('user')) && isset($user['validated']) && $user['validated']==1){
            return $next($request);
        }

        return route('login');
    }
}
