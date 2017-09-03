<header class="main-header">
    <!-- Logo -->
    <a class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">
        <b>
           <img style="display:block;height:50px;width:50px" src="{{asset('images')}}/logo.png" />     
        </b>
      </span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">
        <b>Mitsubishi Admin</b>
      </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">{{session('total_notif')}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have {{session('total_notif')}} notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  @if(session('spk_notif'))
                  <li>
                    <a href="{{route('order.index')}}?type=approval">
                      <i class="fa fa-file"></i> {{session('spk_notif')}} SPK to Approve
                    </a>
                  </li>
                  @endif
                  @if(session('do_notif'))
                  <li>
                    <a href="{{route('delivery-order.index')}}?type=checked">
                      <i class="fa fa-thumbs-o-up"></i> {{session('do_notif')}} DO to Check 
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- User Account: style can bepside found in dropdown.less -->
          <li class="dropdown user user-menu" style="background-color:#000;">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs">{{$user['first_name'] . ' '. $user['last_name']}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="{{url('/logout')}}" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>