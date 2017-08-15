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
            <li @if($page == 'customer') class="active" @endif ><a href="{{route('customer.index')}}"><i class="fa fa-users"></i> Customer</a></li>
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>