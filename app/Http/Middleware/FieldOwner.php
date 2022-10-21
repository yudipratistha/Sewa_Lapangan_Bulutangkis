<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;

class FieldOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->user_status == 2){
            return $next($request);
        }
   
        return response("You don't have Field Owner access.", 404);
    }
}
