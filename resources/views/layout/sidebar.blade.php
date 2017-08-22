<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="active treeview">
          <a href="#">
            <i class="fa fa-database"></i> <span>Master</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li @if($page == 'banner') class="active" @endif><a href="{{route('banner.index')}}"><i class="fa fa-file-image-o"></i> Banner</a></li>
            <li @if($page == 'news') class="active" @endif ><a href="{{route('news.index')}}"><i class="fa fa-newspaper-o"></i> News</a></li>
            <li @if($page == 'car-category') class="active" @endif ><a href="{{route('car-category.index')}}"><i class="fa fa-car"></i> Car Category</a></li>
            <li @if($page == 'car-model') class="active" @endif ><a href="{{route('car-model.index')}}"><i class="fa fa-car"></i> Car Model</a></li>
            <li @if($page == 'car-type') class="active" @endif ><a href="{{route('car-type.index')}}"><i class="fa fa-car"></i> Car Type</a></li>
            <li @if($page == 'company') class="active" @endif ><a href="{{route('company.index')}}"><i class="fa fa-building"></i> Company</a></li>
            <li @if($page == 'dealer') class="active" @endif ><a href="{{route('dealer.index')}}"><i class="fa fa-building-o"></i> Dealer</a></li>
            <li @if($page == 'leasing') class="active" @endif ><a href="{{route('leasing.index')}}"><i class="fa fa-credit-card"></i> Leasing</a></li>
            <li @if($page == 'bbn') class="active" @endif ><a href="{{route('bbn.index')}}"><i class="fa fa-truck"></i> BBN Type</a></li>
            <li @if($page == 'customer') class="active" @endif ><a href="{{route('customer.index')}}"><i class="fa fa-users"></i> Customer</a></li>
            <li @if($page == 'user') class="active" @endif ><a href="{{route('user.index')}}"><i class="fa fa-user"></i> User</a></li>
            <li @if($page == 'job-position') class="active" @endif ><a href="{{route('job-position.index')}}"><i class="fa fa-user-circle-o"></i> Job Position</a></li>
          </ul>
        </li>

        <li class="active treeview">
          <a href="#">
            <i class="fa fa-file"></i> <span>SPK</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li @if($page == 'spk') class="active" @endif><a href="{{route('order.create')}}"><i class="fa fa-file-word-o"></i> Create SPK</a></li>
            <li @if($page == 'spk') class="active" @endif><a href="{{route('order.index')}}"><i class="fa fa-file-word-o"></i> View SPK</a></li>
            <!-- <li @if($page == 'spk') class="active" @endif><a href="{{route('order.index')}}?type=approval"><i class="fa fa-file-word-o"></i> Approve SPK</a></li> -->
          </ul>
        </li>

        <li class="active treeview">
          <a href="#">
            <i class="fa fa-wrench"></i> <span>Setting</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li @if($page == 'approval') class="active" @endif><a href="{{route('approval.index')}}"><i class="fa fa-check"></i> Approval Setting</a></li>
          </ul>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>