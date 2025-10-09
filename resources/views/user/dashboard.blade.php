@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')

@if($user->hasAnyRole(['supervisor', 'inspektor', 'superadmin', 'administrator']))
<div class="row mb-3">
  {{-- Card Proyek Berjalan --}}
  <div class="col-xl-4 col-md-6">
    <div class="card card-animate h-100">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-grow-1 overflow-hidden">
            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Proyek Berjalan</p>
          </div>
        </div>
        <div class="d-flex align-items-end justify-content-between mt-4">
          <div>
            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $ongoing }} proyek</h4>
          </div>
          <div class="avatar-sm flex-shrink-0">
            <span class="avatar-title bg-warning-subtle rounded fs-3">
              <i class="ri-loader-4-line text-warning"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Card Proyek Selesai --}}
  <div class="col-xl-4 col-md-6">
    <div class="card card-animate h-100">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-grow-1 overflow-hidden">
            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Proyek Selesai Bulan Ini</p>
          </div>
        </div>
        <div class="d-flex align-items-end justify-content-between mt-4">
          <div>
            <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $completed }} proyek</h4>
          </div>
          <div class="avatar-sm flex-shrink-0">
            <span class="avatar-title bg-success-subtle rounded fs-3">
              <i class="ri-check-double-line text-success"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Card Performa Perusahaan --}}
  <div class="col-xl-4 col-md-6">
    <div class="card card-animate h-100">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="flex-grow-1 overflow-hidden">
            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Performa Perusahaan</p>
          </div>
        </div>
        <div class="d-flex align-items-end justify-content-between mt-4">
          <div>
            @if(isset($companyStats[0]))
              <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                {{ number_format($companyStats[0]->avg_inspector_days, 1) }} hari
                <small class="text-muted fs-14">(rata-rata inspektor)</small>
              </h4>
              <p class="text-muted mb-0">
                {{ number_format($companyStats[0]->avg_supervisor_days, 1) }} hari
                <small>(rata-rata supervisor)</small>
              </p>
            @else
              <h4 class="fs-22 fw-semibold ff-secondary mb-4">-</h4>
              <p class="text-muted mb-0">Belum ada data</p>
            @endif
          </div>
          <div class="avatar-sm flex-shrink-0">
            <span class="avatar-title bg-info-subtle rounded fs-3">
              <i class="ri-time-line text-info"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Grafik Statistik --}}
<div class="row">
  <div class="col-xl-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Statistik Laporan 12 Bulan Terakhir</h5>
      </div>
      <div class="card-body">
        <canvas id="yearlyStatsChart" height="100"></canvas>
      </div>
    </div>
  </div>
</div>

{{-- Section Khusus Superadmin --}}
@if($user->hasAnyRole(['superadmin', 'administrator']))
<div class="row mt-4">
  {{-- Perusahaan Teratas --}}
  <div class="col-xl-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Perusahaan Teratas Bulan Ini</h5>
      </div>
      <div class="card-body">
        @if($topCompanies->isEmpty())
          <div class="text-center text-muted py-4">
            <i class="ri-information-line fs-24 mb-2"></i>
            <p>Belum ada data perusahaan untuk bulan ini</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Perusahaan</th>
                  <th class="text-end">Total Laporan</th>
                </tr>
              </thead>
              <tbody>
                @foreach($topCompanies as $company)
                <tr>
                  <td>{{ $company->company->name }}</td>
                  <td class="text-end">{{ $company->total_reports }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- Inspektor/Supervisor Teratas --}}
  <div class="col-xl-6">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Inspektor/Supervisor Teratas Bulan Ini</h5>
      </div>
      <div class="card-body">
        @if($topUsers->isEmpty())
          <div class="text-center text-muted py-4">
            <i class="ri-information-line fs-24 mb-2"></i>
            <p>Belum ada data pengguna untuk bulan ini</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Role</th>
                  <th>Perusahaan</th>
                  <th class="text-end">Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach($topUsers as $topUser)
                <tr>
                  <td>{{ $topUser->user->name }}</td>
                  <td>{{ ucfirst($topUser->user->getRoleNames()->first() ?? '-') }}</td>
                  <td>{{ $topUser->company->name }}</td>
                  <td class="text-end">{{ $topUser->total_reports }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endif

@else
{{-- Informasi Perusahaan --}}
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Informasi Perusahaan</h5>
        <div class="mt-3">
          <p><strong>Nama:</strong> {{ $company->name }}</p>
          <p><strong>Alamat:</strong> {{ $company->address }}</p>
          <p><strong>Email:</strong> {{ $company->email }}</p>
          <p><strong>Telepon:</strong> {{ $company->phone }}</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endif

@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.3/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Chart Configuration --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('yearlyStatsChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: {!! json_encode($chartLabels) !!},
      datasets: [{
        label: 'Jumlah Laporan',
        data: {!! json_encode($chartData) !!},
        borderColor: '#0ab39c',
        backgroundColor: '#0ab39c20',
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1
          }
        }
      }
    }
  });
});
</script>

{{-- DataTables Configuration --}}
<script>
$(document).ready(function() {
  $('.table').DataTable({
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'
    },
    pageLength: 5,
    ordering: false
  });
});
</script>
@endsection