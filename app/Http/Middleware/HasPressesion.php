<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;


class HasPressesion
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

        // Middleware to check if you have a pressesion, if have and reload you lost the pressesion and have to make the process again
        if(Auth::check()){
            return redirect('/logged');

        } else{
             if ( $request->session()->get('2fa:user:guest') == 'guest' && $request->session()->get('2fa:auth:attempt',false)){
                return $next($request);

            } else if ( $request->session()->get('2fa:user:guest') == 'logged') {
                return redirect('/logged');
    
            } else {
                return redirect('/login');

            }
        }
       

    }
}
