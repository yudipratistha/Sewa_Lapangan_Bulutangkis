<?php

namespace App\Http\Middleware;

use App;
use Closure;

use App\Models\Lapangan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        if(Auth::user()->RolePengguna->first()->role_tag == 'field_owner'){
            return $next($request);
        }

        return response("You don't have Field Owner access.", 404);
    }
}
