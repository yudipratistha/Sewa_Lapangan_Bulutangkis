    @yield('sidebar')    
        <!-- Page Sidebar Start-->
        <header class="main-nav">
          <div class="sidebar-user text-center"><a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a><img class="img-90 rounded-circle" src="{{url('/assets/images/dashboard/1.png')}}" alt="">
            <!-- <div class="badge-bottom"><span class="badge badge-primary">New</span></div><a href="user-profile.html"> -->
              <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->name }}</h6></a>
            <p class="mb-0 font-roboto">{{ Auth::user()->email }}</p>
          </div>
          <nav>
            <div class="main-navbar">
              <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
              <div id="mainnav">           
                <ul class="nav-menu custom-scrollbar">
                  <li class="back-btn">
                    <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                  </li>
                  <li class="sidebar-main-title">
                    <div>
                      <h6>Menu</h6>
                    </div>
                  </li>
                  @if (Auth::user()->user_status == 2)
                  
                    <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('pemilikLapangan.dashboard')}}"><i data-feather="home"></i><span>Dashboard</span></a></li>
                    <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i data-feather="user"></i><span>Manajemen Lapangan</span></a>
                      <ul class="nav-submenu menu-content">
                        <li><a href="{{route('pemilikLapangan.profil')}}">Edit Profil Lapangan</a></li>
                        <li><a href="{{route('pemilikLapangan.listPaymentMethodPemilikLapangan')}}">Daftar Metode Pembayaran</a></li>
                        <li><a href="{{route('pemilikLapangan.manajemenPaketBulananPemilikLapangan')}}">Manajemen Paket Bulanan</a></li>
                      </ul>
                    </li>
                    <li class="dropdown "><a class="nav-link menu-title {{ isset($activeMenu) ? 'active' : '' }}" href="javascript:void(0)"><i class="icofont icofont-history" style="margin-right: 15px;vertical-align: bottom;float: none;margin-left: -3px;font-size: 21px;"></i><span>Reports</span></a>
                      <ul class="nav-submenu menu-content">
                        <li><a href="{{route('pemilikLapangan.riwayatPenyewaan')}}">Riwayat Penyewaan</a></li>
                        <li><a href="{{route('pemilikLapangan.pemilikLapanganRiwayatTotalPemasukan')}}">Total Pemasukan</a></li>
                        <li><a href="{{route('pemilikLapangan.pemilikLapanganRiwayatPenggunaBookingTerbanyak')}}">Riwayat Pengguna Booking Terbanyak</a></li>
                        <li><a href="{{route('pemilikLapangan.pemilikLapanganRiwayatBookingJamTerbanyak')}}">Riwayat Booking Jam Terbanyak</a></li>
                      </ul>
                    </li>
                  
                  @elseif (Auth::user()->user_status == 3)
                    <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('penyewaLapangan.dashboard')}}"><i data-feather="home"></i><span>Dashboard</span></a></li>
                    <li class="dropdown"><a class="nav-link menu-title link-nav" href="{{route('penyewaLapangan.editProfil')}}"><i data-feather="user"></i><span>Profil</span></a></li>
                    <li class="dropdown "><a class="nav-link menu-title link-nav {{ isset($activeMenu) ? 'active' : '' }}" href="{{route('penyewaLapangan.riwayatPenyewaan')}}"><i class="icofont icofont-history" style="margin-right: 15px;vertical-align: bottom;float: none;margin-left: -3px;font-size: 21px;"></i><span>Riwayat Penyewaan</span></a></li>
                  @endif
                  
                  
                </ul>
              </div>
              <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
            </div>
          </nav>
        </header>
        <!-- Page Sidebar Ends-->