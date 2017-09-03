<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="@if(\Illuminate\Support\Facades\Request::segment(1) == 'master') active @endif treeview">
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
            <li @if($page == 'credit-month') class="active" @endif ><a href="{{route('credit-month.index')}}"><i class="fa fa-clock-o"></i> Credit Duration</a></li>
            <li @if($page == 'area') class="active" @endif ><a href="{{route('area.index')}}"><i class="fa fa-map"></i> Area</a></li>
            <li @if($page == 'default-admin-fee') class="active" @endif ><a href="{{route('default-admin-fee.edit', ['id' => 1])}}"><i class="fa fa-money"></i> Default Admin Fee</a></li>
            <li @if($page == 'customer') class="active" @endif ><a href="{{route('customer.index')}}"><i class="fa fa-users"></i> Customer</a></li>
          </ul>
        </li>

        <li class="@if(\Illuminate\Support\Facades\Request::segment(1) == 'order') active @endif treeview">
          <a href="#">
            <i class="fa fa-file"></i> <span>SPK</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li @if($page == 'order') class="active" @endif><a href="{{route('order.create')}}"><i class="fa fa-file-word-o"></i> Create SPK</a></li>
            <li @if($page == 'order') class="active" @endif><a href="{{route('order.index')}}"><i class="fa fa-file-word-o"></i> View SPK</a></li>
            <li @if($page == 'leasing-rate') class="active" @endif><a href="{{route('leasing-rate.index')}}"><i class="fa fa-percent"></i> Leasing Interest Formula</a></li>
            <li @if($page == 'insurance-rate') class="active" @endif><a href="{{route('insurance-rate.index')}}"><i class="fa fa-ambulance"></i> Insurance Cost Formula</a></li>
            <li @if($page == 'simulation') class="active" @endif><a href="{{route('simulation.index')}}"><i class="fa fa-calculator"></i> Credit Simulation</a></li>
          </ul>
        </li>

        <li class="@if(\Illuminate\Support\Facades\Request::segment(1) == 'insentif') active @endif treeview">
          <a href="#">
            <i class="fa fa-money"></i> <span>Insentif</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li @if($page == 'delivery-order') class="active" @endif><a href="{{route('delivery-order.index')}}"><i class="fa fa-thumbs-o-up"></i> Delivery Order</a></li>
            <li @if($page == 'fleet-rate') class="active" @endif ><a href="{{route('fleet-rate.edit', ['id' => 1])}}"><i class="fa fa-percent"></i> Default Fleet Rate</a></li>
            <li @if($page == 'sales-bonus') class="active" @endif><a href="{{route('sales-bonus.index')}}"><i class="fa fa-calculator"></i> Sales Salary Formula</a></li>
          </ul>
        </li>

        <li class="@if(\Illuminate\Support\Facades\Request::segment(1) == 'report') active @endif treeview">
          <a href="#">
            <i class="fa fa-file-archive-o"></i> <span>Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li @if($page == 'insentif') class="active" @endif><a href="{{route('report.insentif')}}"><i class="fa fa-money"></i> Insentive Report</a></li>
            <li @if($page == 'order') class="active" @endif><a href="{{route('report.order')}}"><i class="fa fa-file"></i> SPK Report</a></li>
            <li @if($page == 'do') class="active" @endif><a href="{{route('report.delivery')}}"><i class="fa fa-thumbs-o-up"></i> DO Report</a></li>
          </ul>
        </li>

        <li class="@if(\Illuminate\Support\Facades\Request::segment(1) == 'setting') active @endif treeview">
          <a href="#">
            <i class="fa fa-wrench"></i> <span>Setting</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li @if($page == 'user') class="active" @endif ><a href="{{route('user.index')}}"><i class="fa fa-user"></i> User Management</a></li>
            <li @if($page == 'role') class="active" @endif ><a href="{{route('role.index')}}"><i class="fa fa-user-circle-o"></i> Role Permission Setting</a></li>
          </ul>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>