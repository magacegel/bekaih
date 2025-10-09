<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckProfile
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
        $user = auth()->user();

        if ($user && $user->hasAnyRole(['superadmin', 'administrator'])) {
            return $next($request);
        }
        
        // Sesuaikan field yang wajib diisi sesuai kebutuhan
        $allowedRoutes = [
            'company.show',
            'company.save',
            'company.certificate.save', 
            'company.index',
            'company.data',
            'company.store',
            'company.users.data',
            'company.users.update',
            'company.update',
            'company.destroy',
            'profile.edit',
            'profile',
            'upload.certificate',
            'user.certificates'
        ];

        if (!in_array($request->route()->getName(), $allowedRoutes) && empty($user->company->logo)) {
            return redirect()->route('company.show', $user->company->id)
                ->with('warning', 'Harap isi logo perusahaan Anda terlebih dahulu untuk dapat menggunakan sistem.');
        }

        if (!in_array($request->route()->getName(), $allowedRoutes) && 
            (empty($user->name) || empty($user->ktp) || empty($user->signature))) {
            return redirect()->route('profile.edit')
                ->with('warning', 'Mohon lengkapi data profil Anda terlebih dahulu untuk dapat menggunakan sistem.');
        }

        // dd($request);
        return $next($request);
    }
}
