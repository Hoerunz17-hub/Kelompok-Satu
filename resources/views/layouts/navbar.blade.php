 <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
     <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
         <a class="navbar-brand brand-logo" href="index.html"><img src="{{ asset('assets/images/logo.svg') }}"
                 alt="logo" /></a>
         <a class="navbar-brand brand-logo-mini" href="index.html"><img src="{{ asset('assets/images/logo-mini.svg') }}"
                 alt="logo" /></a>
     </div>
     <div class="navbar-menu-wrapper d-flex align-items-stretch">
         <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
             <span class="mdi mdi-menu"></span>
         </button>

         <ul class="navbar-nav navbar-nav-right">
             <li class="nav-item nav-profile dropdown">
                 <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown"
                     aria-expanded="false">
                     <div class="nav-profile-img">
                         <img src="{{ asset('assets/images/faces/face28.png') }}" alt="image">
                     </div>
                     <div class="nav-profile-text">
                         <p class="mb-1 text-black">Henry Klein</p>
                     </div>
                 </a>
                 <div class="dropdown-menu navbar-dropdown dropdown-menu-right p-0 border-0 font-size-sm"
                     aria-labelledby="profileDropdown" data-x-placement="bottom-end">
                     <div class="p-3 text-center bg-primary">
                         <img class="img-avatar img-avatar48 img-avatar-thumb"
                             src="{{ asset('assets/images/faces/face28.png') }}" alt="">
                     </div>
                     <div class="p-2">
                         <div role="separator" class="dropdown-divider"></div>
                         <h5 class="dropdown-header text-uppercase pl-2 text-dark mt-2">Actions</h5>

                         <a class="dropdown-item py-1 d-flex align-items-center justify-content-between" href="#">
                             <span>Lock Account</span>
                             <i class="mdi mdi-lock ml-1"></i>
                         </a>

                         {{-- Tombol logout --}}
                         <a class="dropdown-item py-1 d-flex align-items-center justify-content-between"
                             href="{{ route('logout') }}"
                             onclick="event.preventDefault(); document.getElementById('logout-form-navbar').submit();">
                             <span>Log Out</span>
                             <i class="mdi mdi-logout ml-1"></i>
                         </a>

                         {{-- Form logout tersembunyi --}}
                         <form id="logout-form-navbar" action="{{ route('logout') }}" method="GET" class="d-none">
                             @csrf
                         </form>
                     </div>

                 </div>
             </li>


         </ul>
         <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
             data-toggle="offcanvas">
             <span class="mdi mdi-menu"></span>
         </button>
     </div>
 </nav>
