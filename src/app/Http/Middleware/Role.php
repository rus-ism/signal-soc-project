<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public const Admin = '1';
     public const Moderator = '2';
     public const Tutor = '3';
     public function handle(Request $request, Closure $next, ... $roles){
         $user = Auth::user();
         
         $role = Auth::user()->role;

         if (!in_array($user->hasRole(), $roles)) {
            //dd($user->hasRole());
            //dd($user);
            //return view('error.403');
            //return new response(view('error.403'));
            //return redirect()->route('redirect-403');
           //return redirect()->back();
           abort(403, 'Доступ запрещен');
        }

        return $next($request);
     }

}