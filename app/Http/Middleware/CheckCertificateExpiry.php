<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckCertificateExpiry
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('login');
        }

        // Cek sertifikat perusahaan
        $company = $user->company;
        $companyExpiredDate = $company->activeCertificate->expired_date ? Carbon::parse($company->activeCertificate->expired_date) : null;
        $hasExpiredCompanyCert = $companyExpiredDate && $companyExpiredDate->lt(Carbon::now());

        // Cek sertifikat user
        $userExpiredDate = $user->latestQualification ? Carbon::parse($user->latestQualification->expired_date) : null;
        $hasExpiredUserCert = $userExpiredDate && $userExpiredDate->lt(Carbon::now());

        if ($hasExpiredCompanyCert || $hasExpiredUserCert) {
            // Jika sedang mengakses report detail, izinkan
            if ($request->is('report_detail/*')) {
                return $next($request);
            }
            
            // Untuk request ajax, return json response
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sertifikat Anda atau perusahaan Anda telah expired. Silakan perbarui sertifikat terlebih dahulu.'
                ], 403);
            }

            // Untuk request normal, redirect dengan pesan error
            return redirect()->route('report.index')
                ->with('error', 'Sertifikat Anda atau perusahaan Anda telah expired. Silakan perbarui sertifikat terlebih dahulu.');
        }

        return $next($request);
    }
}