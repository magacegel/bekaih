<?php $user = auth()->user(); ?>
<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
    <a href="#">Thickness - {{ Session::get('cabang') }}</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
    <a href="#">BKI</a>
    </div>
    <ul class="sidebar-menu">
        @if($user->hasRole('user'))
            <li class="menu-header">Home</li>
            <li class="dropdown active">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li class=active>
                        <a class="nav-link" href="{{ url('user/dashboard') }}">Performance Dashboard</a>
                    </li>
                    <li>
                        <a class="nav-link" href="index-0.html">Finance Dashboard</a>
                    </li>
                </ul>
            </li>
            <li class="menu-header">Client</li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-columns"></i>
                    <span>Permohonan</span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="nav-link" href="{{ url('user/permohonanbarangjasa') }}">Permohonan Barang & Jasa</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ url('user/permohonanbarang') }}">Permohonan Barang</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ url('user/permohonanjasa') }}">Permohonan Jasa</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link">
                    <i class="fas fa-th-large"></i>
                    <span>Hasil Verifikasi</span>
                </a>
            </li>
        @endif
        @if($user->hasRole('administrator'))
            {{-- <li class="menu-header">Admin Cabang</li>
            <li>
                <a href="{{ url('admin/customer') }}" class="nav-link"><i class="fas fa-users"></i><span>Customer</span></a>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-file-alt"></i> <span>Permohonan</span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="nav-link" href="{{ url('a-permohonanbarangjasa') }}">Permohonan Barang & Jasa</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ url('a-permohonanbarang') }}">Permohonan Barang</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ url('a-permohonanjasa') }}">Permohonan Jasa</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="{{ url('a-tagihan') }}" class="nav-link"><i class="far fa-square"></i><span>Tagihan</span></a>
            </li> --}}
            <li class="menu-header">Home</li>
            <li class="dropdown active">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li class=active>
                        <a class="nav-link" href="{{ url('dashboard') }}" >Dashboard</a>
                    </li>
                    <!-- <li>
                        <a class="nav-link" href="{{ url('sa-dashboard-finance') }}">Finance Dashboard</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ url('ship') }}">Ship</a>
                    </li> -->
                    <li>
                        <a class="nav-link" href="{{ url('report') }}">Report</a>
                    </li>
                </ul>
            </li>
        @endif
        @if(auth()->user()->hasRole('manajemenproyek'))
            <li class="menu-header">Inspektor / MP</li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="far fa-user"></i> <span>Reporting</span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ url('mp/permohonanbarang') }}">Buku Besar</a>
                    </li>
                </ul>
            </li>
        @endif
        @if(auth()->user()->hasAnyRole(['superadmin', 'administrator', 'inspektor']))
            <li class="menu-header">Home</li>
            <li class="dropdown active">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="nav-link" href="{{ url('report') }}">Report UT</a>
                    </li>
                    <li class=active>
                        <a class="nav-link" href="{{ url('sa-dashboard-performance') }}" >Performance Dashboard</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ url('sa-dashboard-finance') }}">Finance Dashboard</a>
                    </li>
                    @if(auth()->user()->hasRole('superadmin'))
                      <li>
                        <a class="nav-link" href="{{ url('user_management') }}">User Management</a>
                      </li>
                    @endif
                </ul>
            </li>
            @if(auth()->user()->hasRole('superadmin'))
              <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-file-alt"></i> <span>Settings</span></a>
                <ul class="dropdown-menu">
                  <li>
                    <a class="nav-link" href="{{ url('settings/form_type') }}">Form Type</a>
                  </li>
                  <li>
                    <a class="nav-link" href="{{ url('settings/ship_type') }}">Ship Type</a>
                  </li>
                  <li>
                    <a class="nav-link" href="{{ url('settings/category') }}">Category</a>
                  </li>
                  <li>
                    <a class="nav-link" href="{{ url('settings/report') }}">Report Settings</a>
                  </li>
                </ul>
              </li>
            @endif
            {{-- <li class="menu-header">Client</li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-file-alt"></i> <span>Permohonan</span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="nav-link" href="{{ url('sa-permohonanbarangjasa') }}">Permohonan Barang & Jasa</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ url('sa-permohonanbarang') }}">Permohonan Barang</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ url('sa-permohonanjasa') }}">Permohonan Jasa</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link"><i class="fas fa-th-large"></i><span>Hasil Verifikasi</span></a>
            </li>
            <li class="menu-header">Admin Cabang</li>
            <li>
                <a href="{{ url('sa-customer') }}" class="nav-link"><i class="fas fa-users"></i><span>Customer</span></a>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="far fa-file-alt"></i> <span>Permohonan</span></a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="nav-link" href="{{ url('sa-permohonanbarangjasa') }}">Permohonan Barang & Jasa</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ url('sa-permohonanbarang') }}">Permohonan Barang</a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{ url('sa-permohonanjasa') }}">Permohonan Jasa</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link"><i class="fas fa-th"></i><span>Hasil Verifikasi</span></a>
            </li>
            <li class="menu-header">Inspektor / MP</li>
            <li class="dropdown">
                <a href="{{ url('sa-verifikasi') }}" class="nav-link"><i class="fas fa-th"></i><span>Verifikasi</span></a>
            </li>
            <li class="dropdown">
                <a href="{{ url('sa-tagihan') }}" class="nav-link"><i class="far fa-square"></i><span>Tagihan</span></a>
            </li>
            <li class="menu-header">Administrator</li>
            <li class="dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-bicycle"></i> <span>Features</span></a>
                <ul class="dropdown-menu">
                <li><a class="nav-link" href="features-activities.html">Activities</a></li>
                <li><a class="nav-link" href="features-post-create.html">Post Create</a></li>
                <li><a class="nav-link" href="features-posts.html">Posts</a></li>
                <li><a class="nav-link" href="features-profile.html">Profile</a></li>
                <li><a class="nav-link" href="features-settings.html">Settings</a></li>
                <li><a class="nav-link" href="features-setting-detail.html">Setting Detail</a></li>
                <li><a class="nav-link" href="features-tickets.html">Tickets</a></li>
                </ul>
            </li> --}}
@endif
    </ul>

    <!-- <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
    <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
        <i class="fas fa-rocket"></i> Documentation
    </a>
    </div> -->
</aside>
