<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('home') }}">
            <img src="{{ asset('assets') }}/img/logo_rs_fatimah.png" alt="LOGO RS" style="max-height: 5rem">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        <img alt="Image placeholder" src="{{ asset('assets') }}/img/149071.png">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Selamat Datang!') }}</h6>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('Profile') }}</span>
                    </a>
                    {{-- <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Settings') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-calendar-grid-58"></i>
                        <span>{{ __('Activity') }}</span>
                    </a> --}}
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-support-16"></i>
                        <span>{{ __('Panduan Penggunaan') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home') }}">
                            <p class="text-dark font-weight-bold"><img src="{{ asset('assets') }}/img/logosumsel.png"> RSUD Siti Fatimah</p>
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="ni ni-tv-2 text-primary"></i> {{ __('Dashboard') }}
                    </a>
                </li>
            </ul>
            <!-- Divider -->
            <hr class="my-3">
            <!-- Heading -->
            {{-- <h6 class="navbar-heading text-muted">Pasien</h6> --}}
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="#navbar-master-pasien" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-master-pasien">
                        <i class="fas fa-user" style="color: blue;"></i>
                        <span class="nav-link-text" style="color: blue;">{{ __('Pasien') }}</span>
                    </a>
                </li>
                <div class="collapse show" id="navbar-master-pasien">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pasien.index') }}">
                                {{ __('Cari Pasien') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="collapse show" id="navbar-master-pasien">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('rekap.index') }}">
                                {{ __('Rekap') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </ul>
            {{-- <h6 class="navbar-heading text-muted">Master</h6> --}}
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="#navbar-master-lain" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-master-lain">
                        <i class="fas fa-align-justify" style="color: green;"></i>
                        <span class="nav-link-text" style="color: green;">{{ __('Data Master') }}</span>
                    </a>
                </li>
                <div class="collapse show" id="navbar-master-lain">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('master.alat-digunakan.r-alat-digunakan.index') }}">
                                {{ __('Alat Digunakan') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('master.kategori-antibiotik.r-kategori-antibiotik.index') }}">
                                {{ __('Kategori Antibiotik') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('master.antibiotik.r-antibiotik.index') }}">
                                {{ __('Antibiotik') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('master.asa-score.r-asa-score.index') }}">
                                {{ __('Asa Score') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('master.risk-score.r-risk-score.index') }}">
                                {{ __('Risk Score') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('master.jenis-kuman.r-jenis-kuman.index') }}">
                                {{ __('Jenis Kuman') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('master.jenis-operasi.r-jenis-operasi.index') }}">
                                {{ __('Jenis Operasi') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('master.tindakan-operasi.r-tindakan-operasi.index') }}">
                                {{ __('Tindakan Operasi') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('master.kegiatan-sensus.r-kegiatan-sensus.index') }}">
                                {{ __('Kegiatan Sensus') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('master.lama-operasi.r-lama-operasi.index') }}">
                                {{ __('Lama Operasi') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('master.transmisi.r-transmisi.index') }}">
                                {{ __('Transmisi') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('master.ruang.r-ruang.index') }}">
                                {{ __('Ruang') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="#navbar-user" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="navbar-user">
                        <i class="fas fa-user" style="color: #f4645f;"></i>
                        <span class="nav-link-text" style="color: #f4645f;">{{ __('User') }}</span>
                    </a>
                    <div class="collapse show" id="navbar-user">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.edit') }}">
                                    {{ __('User profile') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.index') }}">
                                    {{ __('User Management') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
