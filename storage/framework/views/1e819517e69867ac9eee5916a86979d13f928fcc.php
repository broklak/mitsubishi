<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="<?php if(\Illuminate\Support\Facades\Request::segment(1) == 'master'): ?> active <?php endif; ?> treeview">
          <a href="#">
            <i class="fa fa-database"></i> <span>Master</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($page == 'banner'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('banner.index')); ?>"><i class="fa fa-file-image-o"></i> Banner</a></li>
            <li <?php if($page == 'news'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('news.index')); ?>"><i class="fa fa-newspaper-o"></i> News</a></li>
            <li <?php if($page == 'car-category'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('car-category.index')); ?>"><i class="fa fa-car"></i> Car Category</a></li>
            <li <?php if($page == 'car-model'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('car-model.index')); ?>"><i class="fa fa-car"></i> Car Model</a></li>
            <li <?php if($page == 'car-type'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('car-type.index')); ?>"><i class="fa fa-car"></i> Car Type</a></li>
            <li <?php if($page == 'company'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('company.index')); ?>"><i class="fa fa-building"></i> Company</a></li>
            <li <?php if($page == 'dealer'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('dealer.index')); ?>"><i class="fa fa-building-o"></i> Dealer</a></li>
            <li <?php if($page == 'leasing'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('leasing.index')); ?>"><i class="fa fa-credit-card"></i> Leasing</a></li>
            <li <?php if($page == 'bbn'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('bbn.index')); ?>"><i class="fa fa-truck"></i> BBN Type</a></li>
            <li <?php if($page == 'credit-month'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('credit-month.index')); ?>"><i class="fa fa-clock-o"></i> Credit Duration</a></li>
            <li <?php if($page == 'area'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('area.index')); ?>"><i class="fa fa-map"></i> Area</a></li>
            <li <?php if($page == 'default-admin-fee'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('default-admin-fee.edit', ['id' => 1])); ?>"><i class="fa fa-money"></i> Default Admin Fee</a></li>
            <li <?php if($page == 'customer'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('customer.index')); ?>"><i class="fa fa-users"></i> Customer</a></li>
          </ul>
        </li>

        <li class="<?php if(\Illuminate\Support\Facades\Request::segment(1) == 'order'): ?> active <?php endif; ?> treeview">
          <a href="#">
            <i class="fa fa-file"></i> <span>SPK</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($page == 'order'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('order.create')); ?>"><i class="fa fa-file-word-o"></i> Create SPK</a></li>
            <li <?php if($page == 'order'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('order.index')); ?>"><i class="fa fa-file-word-o"></i> View SPK</a></li>
            <li <?php if($page == 'leasing-rate'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('leasing-rate.index')); ?>"><i class="fa fa-percent"></i> Leasing Interest Formula</a></li>
            <li <?php if($page == 'insurance-rate'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('insurance-rate.index')); ?>"><i class="fa fa-ambulance"></i> Insurance Cost Formula</a></li>
            <li <?php if($page == 'simulation'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('simulation.index')); ?>"><i class="fa fa-calculator"></i> Credit Simulation</a></li>
          </ul>
        </li>

        <li class="<?php if(\Illuminate\Support\Facades\Request::segment(1) == 'insentif'): ?> active <?php endif; ?> treeview">
          <a href="#">
            <i class="fa fa-money"></i> <span>Insentif</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($page == 'delivery-order'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('delivery-order.index')); ?>"><i class="fa fa-thumbs-o-up"></i> Delivery Order</a></li>
            <li <?php if($page == 'fleet-rate'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('fleet-rate.edit', ['id' => 1])); ?>"><i class="fa fa-percent"></i> Default Fleet Rate</a></li>
            <li <?php if($page == 'sales-bonus'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('sales-bonus.index')); ?>"><i class="fa fa-calculator"></i> Sales Salary Formula</a></li>
          </ul>
        </li>

        <li class="<?php if(\Illuminate\Support\Facades\Request::segment(1) == 'report'): ?> active <?php endif; ?> treeview">
          <a href="#">
            <i class="fa fa-file-archive-o"></i> <span>Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($page == 'insentif'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('report.insentif')); ?>"><i class="fa fa-money"></i> Insentive Report</a></li>
            <li <?php if($page == 'order'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('report.order')); ?>"><i class="fa fa-file"></i> SPK Report</a></li>
            <li <?php if($page == 'do'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('report.delivery')); ?>"><i class="fa fa-thumbs-o-up"></i> DO Report</a></li>
          </ul>
        </li>

        <li class="<?php if(\Illuminate\Support\Facades\Request::segment(1) == 'setting'): ?> active <?php endif; ?> treeview">
          <a href="#">
            <i class="fa fa-wrench"></i> <span>Setting</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($page == 'user'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('user.index')); ?>"><i class="fa fa-user"></i> User Management</a></li>
            <li <?php if($page == 'role'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('role.index')); ?>"><i class="fa fa-user-circle-o"></i> Role Permission Setting</a></li>
          </ul>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>