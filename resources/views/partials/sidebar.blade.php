<nav class="sidebar">
   <ul class="nav">
      <!-- START user info-->
      <li>
         <div data-toggle="collapse-next" class="item user-block has-submenu">
            <!-- User picture-->
            <div class="user-block-picture">
               <img src="{{ asset('assets/app/img/user/02.jpg') }}" alt="Avatar" width="60" height="60" class="img-thumbnail img-circle">
               <!-- Status when collapsed-->
               <div class="user-block-status">
                  <div class="point point-success point-lg"></div>
               </div>
            </div>
            <!-- Name and Role-->
            <div class="user-block-info">
               <span class="user-block-name item-text">Welcome, BB</span>
               <span class="user-block-role">ADMIN</span>
               <!-- START Dropdown to change status-->
               <div class="btn-group user-block-status">
                  <button type="button" data-toggle="dropdown" data-play="fadeIn" data-duration="0.2" class="btn btn-xs dropdown-toggle">
                     <div class="point point-success"></div>Online</button>
                  <ul class="dropdown-menu text-left pull-right">
                     <li>
                        <a href="#">
                           <div class="point point-success"></div>Online</a>
                     </li>
                     <li>
                        <a href="#">
                           <div class="point point-warning"></div>Away</a>
                     </li>
                     <li>
                        <a href="#">
                           <div class="point point-danger"></div>Busy</a>
                     </li>
                  </ul>
               </div>
               <!-- END Dropdown to change status-->
            </div>
         </div>
         <!-- START User links collapse-->
         <ul class="nav collapse">
            <li><a href="#">Profile</a>
            </li>
            <li><a href="#">Settings</a>
            </li>
            <li><a href="#">Notifications<div class="label label-danger pull-right">120</div></a>
            </li>
            <li><a href="#">Messages<div class="label label-success pull-right">300</div></a>
            </li>
            <li class="divider"></li>
            <li><a href="#">Logout</a>
            </li>
         </ul>
         <!-- END User links collapse-->
      </li>
      <!-- END user info-->
      <!-- START Menu-->

      <li class="active">
         <a href="" title="User" data-toggle="collapse-next" class="has-submenu">
            <em class="fa fa-edit"></em>
            <span class="item-text">User</span>
         </a>
         <!-- START SubMenu item-->
         <ul class="nav collapse ">
            <li class="active">
               <a href="{{ url('/user/new/view') }}" title="Add New User" data-toggle="" class="no-submenu">
                  <span class="item-text">Add New</span>
               </a>
            </li>
            <li>
               <a href="{{ url('/user/list') }}" title="User List" data-toggle="" class="no-submenu">
                  <span class="item-text">List</span>
               </a>
            </li>            
         </ul>
         <!-- END SubMenu item-->
      </li>

      <li>
         <a href="" title="Business" data-toggle="collapse-next" class="has-submenu">
            <em class="fa fa-edit"></em>
            <span class="item-text">Business</span>
         </a>

         <!-- START SubMenu item-->
         <ul class="nav collapse ">
            <li>
               <a href="{{ url('/business/new/view') }}" title="Add New Business" data-toggle="" class="no-submenu">
                  <span class="item-text">Add New</span>
               </a>
            </li>
            <li>
               <a href="{{ url('business/list') }}" title="Business List" data-toggle="" class="no-submenu">
                  <span class="item-text">List</span>
               </a>
            </li>            
         </ul>
         <!-- END SubMenu item-->
      </li>

      <li>
         <a href="" title="Banner" data-toggle="collapse-next" class="has-submenu">
            <em class="fa fa-wrench"></em>
            <span class="item-text">Banner</span>
         </a>
         <!-- START SubMenu item-->
         <ul class="nav collapse ">
            <li>
               <a href="{{ url('/banner/new/view') }}" title="Add New Banner" data-toggle="" class="no-submenu">
                  <span class="item-text">Add New</span>
               </a>
            </li>
            <li>
               <a href="{{ url('/banner/list') }}" title="Banner List" data-toggle="" class="no-submenu">
                  <span class="item-text">List</span>
               </a>
            </li>            
         </ul>
         <!-- END SubMenu item-->
      </li>

      <li>
         <a href="" title="Bus" data-toggle="collapse-next" class="has-submenu">
            <em class="fa fa-wrench"></em>
            <span class="item-text">Bus</span>
         </a>
         <!-- START SubMenu item-->
         <ul class="nav collapse ">
            <li>
               <a href="{{ url('/bus/new/view') }}" title="Add New Bus" data-toggle="" class="no-submenu">
                  <span class="item-text">Add New</span>
               </a>
            </li>
            <li>
               <a href="{{ url('/bus/list') }}" title="Banner List" data-toggle="" class="no-submenu">
                  <span class="item-text">List</span>
               </a>
            </li>            
         </ul>
         <!-- END SubMenu item-->
      </li>

      <li>
         <a href="" title="Trip" data-toggle="collapse-next" class="has-submenu">
            <em class="fa fa-wrench"></em>
            <span class="item-text">Trip</span>
         </a>
         <!-- START SubMenu item-->
         <ul class="nav collapse ">
            <li>
               <a href="{{ url('/trip/new/view') }}" title="Add New Trip" data-toggle="" class="no-submenu">
                  <span class="item-text">Add New</span>
               </a>
            </li>
            <li>
               <a href="{{ url('/trip/list') }}" title="Banner List" data-toggle="" class="no-submenu">
                  <span class="item-text">List</span>
               </a>
            </li>            
         </ul>
         <!-- END SubMenu item-->
      </li>

      <li>
         <a href="" title="Trip" data-toggle="collapse-next" class="has-submenu">
            <em class="fa fa-wrench"></em>
            <span class="item-text">Place</span>
         </a>
         <!-- START SubMenu item-->
         <ul class="nav collapse ">
            <li>
               <a href="{{ url('/place/new/view') }}" title="Add New Place" data-toggle="" class="no-submenu">
                  <span class="item-text">Add New</span>
               </a>
            </li>
            <li>
               <a href="{{ url('/place/list') }}" title="Place List" data-toggle="" class="no-submenu">
                  <span class="item-text">List</span>
               </a>
            </li>            
         </ul>
         <!-- END SubMenu item-->
      </li>

      <li>
         <a href=""  title="Counter" data-toggle="collapse-next" class="has-submenu">
            <em class="fa fa-wrench"></em>
            <span class="item-text">Counter</span>
         </a>
         <!-- START SubMenu item-->
         <ul class="nav collapse ">
            <li>
               <a href="{{ url('/counter/new/view') }}" title="Add New Counter" data-toggle="" class="no-submenu">
                  <span class="item-text">Add New</span>
               </a>
            </li>
            <li>
               <a href="{{ url('/counter/list') }}" title="Banner List" data-toggle="" class="no-submenu">
                  <span class="item-text">List</span>
               </a>
            </li>            
         </ul>
         <!-- END SubMenu item-->
      </li>


      <li class="">
         <a href="{{ url('/dashboard') }}" title="Dashboard" class="">
            <em class="fa fa-dashboard"></em>
            <span class="item-text">Dashboard</span>
         </a>
      </li>
      

      
      <!-- END Menu-->
      <!-- Sidebar footer    -->
      <li class="nav-footer">
         <div class="nav-footer-divider"></div>
         <!-- START button group-->
         <div class="btn-group text-center">
            <button type="button" data-toggle="tooltip" data-title="Add Contact" class="btn btn-link">
               <em class="fa fa-user text-muted"><sup class="fa fa-plus"></sup>
               </em>
            </button>
            <button type="button" data-toggle="tooltip" data-title="Settings" class="btn btn-link">
               <em class="fa fa-cog text-muted"></em>
            </button>
            <button type="button" data-toggle="tooltip" data-title="Logout" class="btn btn-link">
               <em class="fa fa-sign-out text-muted"></em>
            </button>
         </div>
         <!-- END button group-->
      </li>
   </ul>
</nav>




        