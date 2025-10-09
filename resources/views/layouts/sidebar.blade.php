<?php
$user = auth()->user();
$route = Route::current()->uri;
?>
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
  <!-- LOGO -->
  <div class="navbar-brand-box">
    <!-- Dark Logo-->
    <a href="{{ $user->hasRole('inspektor') ? route('inspektor') : ($user->hasRole('supervisor') ? route('supervisor') : route('dashboard')) }}" class="logo logo-dark">
      <span class="logo-sm">
        <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
      </span>
      <span class="logo-lg">
        <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
      </span>
    </a>
    <!-- Light Logo-->
    <a href="{{ $user->hasRole('inspektor') ? route('inspektor') : ($user->hasRole('supervisor') ? route('supervisor') : route('dashboard')) }}" class="logo logo-light">
      <span class="logo-sm">
        <img src="{{ URL::asset('images/bki_header_logo_small.png') }}" alt="BKI - Biro Klasifikasi Indonesia">
      </span>
      <span class="logo-lg">
        <img src="{{ URL::asset('images/bki_header_logo.png') }}" alt="BKI - Biro Klasifikasi Indonesia">
      </span>
    </a>
    <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
    id="vertical-hover">
    <i class="ri-record-circle-line"></i>
  </button>
</div>

<div class="dropdown sidebar-user m-1 rounded">
{{--  <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown"--}}
{{--  aria-haspopup="true" aria-expanded="false">--}}
{{--  <span class="d-flex align-items-center gap-2">--}}
{{--    <img class="rounded header-profile-user" src="@if (Auth::user()->avatar != ''){{ URL::asset('images/' . Auth::user()->avatar) }}@else{{ URL::asset('build/images/users/avatar-1.jpg') }}@endif" alt="Header Avatar">--}}
{{--    <span class="text-start">--}}
{{--      <span class="d-block fw-medium sidebar-user-name-text">{{Auth::user()->name}}</span>--}}
{{--      <span class="d-block fs-14 sidebar-user-name-sub-text"><i--}}
{{--        class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span--}}
{{--        class="align-middle">Online</span></span>--}}
{{--      </span>--}}
{{--    </span>--}}
{{--  </button>--}}
  <div class="dropdown-menu dropdown-menu-end">
    <!-- item-->
    <h6 class="dropdown-header">Welcome {{Auth::user()->name}}!</h6>
    <a class="dropdown-item" href="pages-profile"><i
      class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
      class="align-middle">Profile</span></a>
      <a class="dropdown-item" href="apps-chat"><i
        class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span
        class="align-middle">Messages</span></a>
        <a class="dropdown-item" href="apps-tasks-kanban"><i
          class="mdi mdi-calendar-check-outline text-muted fs-16 align-middle me-1"></i> <span
          class="align-middle">Taskboard</span></a>
          <a class="dropdown-item" href="pages-faqs"><i
            class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span
            class="align-middle">Help</span></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="pages-profile"><i
              class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Balance :
                <b>$5971.67</b></span></a>
                <a class="dropdown-item" href="pages-profile-settings"><span
                  class="badge bg-success-subtle text-success mt-1 float-end">New</span><i
                  class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span
                  class="align-middle">Settings</span></a>
                  <a class="dropdown-item" href="auth-lockscreen-basic"><i
                    class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Lock
                    screen</span></a>

                    <a class="dropdown-item " href="javascript:void();"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                    class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                    key="t-logout">@lang('translation.logout')</span></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                    </form>
                  </div>
                </div>

                <div id="scrollbar">
                  <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                      <!-- <li class="menu-title"><span>Home</span></li> -->


                      <?php if($user->hasAnyRole([['superadmin', 'administrator'], 'inspektor', 'supervisor'])){ ?>

                        <li class="menu-title"><i class="ri-more-fill"></i> <span>Menu</span></li>

                        <li class="nav-item">
                          <a class="nav-link menu-link <?=$route==['superadmin', 'administrator'] || $route=='administrator' || $route=='inspektor'?'active':'';?>" href="{{ url('/') }}">
                            <i class="ri-dashboard-line"></i> <span>Dashboard</span>
                          </a>
                        </li>

                        <li class="nav-item">
                          <a class="nav-link menu-link <?=$route=='report'?'active':'';?>" href="{{ url('/report') }}">
                            <i class="ri-folder-chart-line"></i> <span>Report UT</span>
                          </a>
                        </li>



                        @if($user->hasAnyRole([['superadmin', 'administrator'], 'inspektor', 'supervisor']))
                        <li class="menu-title"><i class="ri-more-fill"></i> <span>Company Settings</span></li>
                        @if($user->hasAnyRole(['inspektor', 'supervisor']))
                        <li class="nav-item">
                          <a class="nav-link menu-link <?=$route=='company.show'?'active':'';?>" href="{{ route('company.show', ['id' => Auth::user()->company_id]) }}">
                            <i class="ri-building-2-line"></i> <span>Perusahaan Saya</span>
                          </a>
                        </li>
                        @endif
                        @if($user->hasAnyRole(['superadmin', 'administrator']))
                        <li class="nav-item">
                          <a class="nav-link menu-link <?=$route=='company.index'?'active':'';?>" href="{{ route('company.index') }}">
                            <i class="ri-building-line"></i> <span>Kelola Perusahaan</span>
                          </a>
                        </li>
                        @endif
                        <li class="nav-item">
                          <a class="nav-link menu-link <?=$route=='equipment.index'?'active':'';?>" href="{{ route('equipment.index') }}">
                            <i class="ri-tools-line"></i> <span>Equipment</span>
                          </a>
                        </li>
                        @endif

                        <?php if($user->hasAnyRole(['superadmin', 'administrator'])){ ?>

                          <li class="menu-title"><i class="ri-more-fill"></i> <span>Settings</span></li>

                          <?php if($user->hasAnyRole(['superadmin', 'administrator'])){?>
                            <li class="nav-item">
                              <a class="nav-link menu-link <?=$route=='user_management'?'active':'';?>" href="{{ url('user_management') }}">
                                <i class="ri-account-circle-line"></i> <span>User Management</span>
                              </a>
                            </li>
                          <?php } ?>

                          <?php if($user->hasAnyRole(['superadmin'])){ ?>
                          <li class="nav-item">
                            <a class="nav-link menu-link <?=$route=='settings/form_type'?'active':'';?>" href="{{ url('/settings/form_type') }}">
                              <i class="ri-survey-line"></i> <span>Form Type</span>
                            </a>
                          </li>


                          <li class="nav-item">
                            <a class="nav-link menu-link <?=$route=='settings/ship_type'?'active':'';?>" href="{{ url('/settings/ship_type') }}">
                              <i class="ri-ship-2-line"></i> <span>Ship Type</span>
                            </a>
                          </li>

                          <li class="nav-item">
                            <a class="nav-link menu-link <?=$route=='settings/category'?'active':'';?>" href="{{ url('/settings/category') }}">
                              <i class="ri-book-read-line"></i> <span>Category</span>
                            </a>
                          </li>

                          <li class="nav-item">
                            <a class="nav-link menu-link <?=$route=='settings/report'?'active':'';?>" href="{{ url('/settings/report') }}">
                              <i class="ri-pencil-ruler-2-line"></i> <span>Report Settings</span>
                            </a>
                          </li>
                          <?php } ?>





                        <?php } ?>



                      <?php } ?>


                    </ul>
                  </div>
                  <!-- Sidebar -->
                </div>
                <div class="sidebar-background"></div>
              </div>
              <!-- Left Sidebar End -->
              <!-- Vertical Overlay-->
              <div class="vertical-overlay"></div>
