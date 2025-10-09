@php
  $user = auth()->user();
  $company = $user->company;
  $now = \Carbon\Carbon::now();
  $oneMonthFromNow = $now->copy()->addMonth();
  $threeMonthsFromNow = $now->copy()->addMonths(3);
  $fourMonthsFromNow = $now->copy()->addMonths(4);
@endphp

@if($user->hasAnyRole(['supervisor', 'inspektor']))
<div class="row mb-4">
  <div class="col-12">
    @php
      $companyExpiredDate = $company->activeCertificate->expired_date ? \Carbon\Carbon::parse($company->activeCertificate->expired_date) : null;
      $userExpiredDate = $user->latestQualification ? \Carbon\Carbon::parse($user->latestQualification->expired_date) : null;
    @endphp

    {{-- Company Certificate Alerts --}}
    @if($companyExpiredDate)
      @if($now->gt($companyExpiredDate))
        <div class="alert alert-danger" role="alert">
          <strong>PERINGATAN!</strong> Sertifikat Persetujuan Penyedia Jasa perusahaan Anda telah kadaluarsa pada {{ $companyExpiredDate->isoFormat('D MMMM YYYY') }}. <b>Harap segera menghubungi cabang BKI terdekat untuk memperbarui sertifikat.</b>
        </div>
      @elseif($companyExpiredDate->lte($oneMonthFromNow))
        <div class="alert alert-warning" role="alert" style="background-color: #FFA500; border-color: #FFA500;">
          <strong>MOHON DIPERHATIKAN!</strong> Sertifikat Persetujuan Penyedia Jasa perusahaan Anda akan kadaluarsa dalam 1 bulan pada {{ $companyExpiredDate->isoFormat('D MMMM YYYY') }}. <b>Harap segera menghubungi cabang BKI terdekat untuk memperbarui sertifikat.</b>
        </div>
      @elseif($companyExpiredDate->lte($threeMonthsFromNow))
        <div class="alert alert-warning" role="alert" style="background-color: #FFD700; border-color: #FFD700;">
          <strong>PERHATIAN!</strong> Sertifikat Persetujuan Penyedia Jasa perusahaan Anda akan kadaluarsa dalam 3 bulan pada {{ $companyExpiredDate->isoFormat('D MMMM YYYY') }}. <b>Harap segera menghubungi cabang BKI terdekat untuk memperbarui sertifikat.</b>
        </div>
      @elseif($companyExpiredDate->lte($fourMonthsFromNow))
        <div class="alert alert-warning" role="alert" style="background-color: #FFFFE0; border-color: #FFFFE0;">
          <strong>PEMBERITAHUAN!</strong> Sertifikat Persetujuan Penyedia Jasa perusahaan Anda akan kadaluarsa dalam 4 bulan pada {{ $companyExpiredDate->isoFormat('D MMMM YYYY') }}. <b>Harap segera menghubungi cabang BKI terdekat untuk memperbarui sertifikat.</b>
        </div>
      @endif
    @endif

    {{-- User Competency Certificate Alerts --}}
    @if($userExpiredDate)
      @if($now->gt($userExpiredDate))
        <div class="alert alert-danger" role="alert">
          <strong>PERINGATAN!</strong> Sertifikat Kompetensi Anda telah kadaluarsa pada {{ $userExpiredDate->isoFormat('D MMMM YYYY') }}. <b>Harap segera memperbarui sertifikat kompetensi Anda.</b>
        </div>
      @elseif($userExpiredDate->lte($oneMonthFromNow))
        <div class="alert alert-warning" role="alert" style="background-color: #FFA500; border-color: #FFA500;">
          <strong>MOHON DIPERHATIKAN!</strong> Sertifikat Kompetensi Anda akan kadaluarsa dalam 1 bulan pada {{ $userExpiredDate->isoFormat('D MMMM YYYY') }}. <b>Harap segera memperbarui sertifikat kompetensi Anda.</b>
        </div>
      @elseif($userExpiredDate->lte($threeMonthsFromNow))
        <div class="alert alert-warning" role="alert" style="background-color: #FFD700; border-color: #FFD700;">
          <strong>PERHATIAN!</strong> Sertifikat Kompetensi Anda akan kadaluarsa dalam 3 bulan pada {{ $userExpiredDate->isoFormat('D MMMM YYYY') }}. <b>Harap segera memperbarui sertifikat kompetensi Anda.</b>
        </div>
      @elseif($userExpiredDate->lte($fourMonthsFromNow))
        <div class="alert alert-warning" role="alert" style="background-color: #FFFFE0; border-color: #FFFFE0;">
          <strong>PEMBERITAHUAN!</strong> Sertifikat Kompetensi Anda akan kadaluarsa dalam 4 bulan pada {{ $userExpiredDate->isoFormat('D MMMM YYYY') }}. <b>Harap segera memperbarui sertifikat kompetensi Anda.</b>
        </div>
      @endif
    @endif

  </div>
</div>
@endif