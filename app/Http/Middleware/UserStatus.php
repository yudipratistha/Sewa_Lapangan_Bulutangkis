<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->user_status == 1){
            return $next($request);
        }else if(auth()->user()->user_status == 2){
            return $next($request);
        }else if(auth()->user()->user_status == 3){
            return $next($request);
        }

        return redirect('home')->with('error',"You don't have admin access.");
    }
}
