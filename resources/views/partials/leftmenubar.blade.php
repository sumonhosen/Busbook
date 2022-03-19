      <!-- ========== Left Sidebar Start ========== -->
      <div class="left-side-menu">

        <div class="h-100" data-simplebar>

            <!--- Sidemenu -->
            <div id="sidebar-menu">

              <ul id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="{{ url('/admin') }}" class="{{ (request()->is('/sdmin')) ? 'active' : '' }}">
                        <i data-feather="box"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('/user/new/view') }}" class="{{ (request()->is('/user/new/view')) ? 'active' : '' }}">
                        <i data-feather="user"></i>
                        <span> User </span>
                    </a>
                </li>

                <li>
                  <a href="{{ url('/business/new/view') }}" class="{{ (request()->is('/business/new/view')) ? 'active' : '' }}">
                      <i data-feather="briefcase"></i>
                      <span> Business </span>
                  </a>
                </li>

                <li>
                  <a href="{{ url('/banner/new/view') }}" class="{{ (request()->is('/banner/new/view')) ? 'active' : '' }}">
                      <i data-feather="message-square"></i>
                      <span> Banner </span>
                  </a>
                </li>
                
                <li>
                  <a href="{{ url('/bus/new/view') }}" class="{{ (request()->is('/bus/new/view')) ? 'active' : '' }}">
                      <i data-feather="truck"></i>
                      <span> Bus </span>
                  </a>
                </li>

                <li>
                  <a href="{{ url('/place/new/view') }}" class="{{ (request()->is('/place/new/view')) ? 'active' : '' }}">
                      <i data-feather="map-pin"></i>
                      <span> Place </span>
                  </a>
                </li>

                <li>
                  <a href="{{ url('/counter/new/view') }}" class="{{ (request()->is('/counter/new/view')) ? 'active' : '' }}">
                      <i data-feather="umbrella"></i>
                      <span> Counter </span>
                  </a>
                </li>

                <li>
                  <a href="{{ url('/trip/new/view') }}" class="{{ (request()->is('/trip/new/view')) ? 'active' : '' }}">
                      <i data-feather="trending-up"></i>
                      <span> Trip </span>
                  </a>
                </li>

                <li>
                  <a href="{{ url('/role/new/view') }}" class="{{ (request()->is('/role/new/view')) ? 'active' : '' }}">
                      <i data-feather="users"></i>
                      <span> Roles </span>
                  </a>
                </li>
        		<li>
                  <a href="{{ url('/role/privacy/policy') }}" class="{{ (request()->is('/role/privacy/policy')) ? 'active' : '' }}">
                      <i data-feather="users"></i>
                      <span> Privacy Policy </span>
                  </a>
                </li>
                <li>
                  <a href="{{ url('/role/privacy/policy/content') }}" class="{{ (request()->is('/role/privacy/policy/content')) ? 'active' : '' }}">
                      <i data-feather="users"></i>
                      <span> Privacy Policy Content </span>
                  </a>
                </li>
                <li>
                  <a href="{{ url('/sms_settings') }}" class="{{ (request()->is('sms_settings')) ? 'active' : '' }}">
                      <i data-feather="settings"></i>
                      <span> Settings </span>
                  </a>
                </li>

                <li>
                  <a href="#" class="" onclick="onclick=event.preventDefault(); document.getElementById('logout-form').submit();">
                      <i data-feather="log-out"></i>
                      <span> {{ __('logout') }} </span>
                  </a>
                  
                </li>


              </ul>

            </div>
            <!-- End Sidebar -->

            <div class="clearfix"></div>

        </div>
        <!-- Sidebar -left -->

    </div>
    <!-- Left Sidebar End -->

    
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
      @csrf
    </form>