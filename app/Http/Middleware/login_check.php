<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // baru


class login_check
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        //return $next($request);

        if (!Auth::check()) {
            return redirect('login');
        }
        $user = Auth::user();
    
        // Check if user has the required role using Spatie Permission
        if($user->hasRole($roles))
            return $next($request);

            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->name);
            $request->session()->put('username', $user->username);
            $request->session()->put('email', $user->email);
            $request->session()->put('phone', $user->phone);
            $request->session()->put('role', $user->getRoleNames()->first()); // Store role instead of level
            $request->session()->put('bulan', date("m"));
            $request->session()->put('tahun', date("Y"));
    
        return redirect('login')->with('error',"You Don't Have Access!!!");
    }
    
}
