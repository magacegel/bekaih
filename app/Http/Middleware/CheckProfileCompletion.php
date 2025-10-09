<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CheckProfileCompletion
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
        $user = Auth::user();

        if ($user && ($user->hasRole('inspektor') || $user->hasRole('supervisor'))) {
            if (!$this->isProfileComplete($user)) {
                return redirect()->route('profile.edit')->with('warning', 'Harap lengkapi profil Anda terlebih dahulu.');
            }
        }

        return $next($request);
    }
    private function isProfileComplete($user)
    {
        // Sesuaikan dengan field-field yang harus diisi
        return !empty($user->ktp) && !empty($user->email) && !empty($user->signature);
    }
}
