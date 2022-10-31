@yield('sidebar')
<!-- Page Sidebar Start-->
<header class="main-nav">
  <div class="sidebar-user text-center"><a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a><img class="img-90 rounded-circle" src="{{url('/assets/images/dashboard/1.png')}}" alt="">
    <!-- <div class="badge-bottom"><span class="badge badge-primary">New</span></div><a href="user-profile.html"> -->
      <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->name }}</h6></a>
    <p class="mb-0 font-roboto">{{ Auth::user()->email }}</p>
  </div>
</header>
<!-- Page Sidebar Ends-->
