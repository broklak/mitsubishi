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
            <li <?php if($page == 'banner'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('banner.index')); ?>"><i class="fa fa-file-image-o"></i> Banner</a></li>
            <li <?php if($page == 'news'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('news.index')); ?>"><i class="fa fa-newspaper-o"></i> News</a></li>
            <li <?php if($page == 'car-category'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('car-category.index')); ?>"><i class="fa fa-car"></i> Car Category</a></li>
            <li <?php if($page == 'car-model'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('car-model.index')); ?>"><i class="fa fa-car"></i> Car Model</a></li>
            <li <?php if($page == 'car-type'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('car-type.index')); ?>"><i class="fa fa-car"></i> Car Type</a></li>
            <li <?php if($page == 'company'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('company.index')); ?>"><i class="fa fa-building"></i> Company</a></li>
            <li <?php if($page == 'dealer'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('dealer.index')); ?>"><i class="fa fa-building-o"></i> Dealer</a></li>
            <li <?php if($page == 'leasing'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('leasing.index')); ?>"><i class="fa fa-credit-card"></i> Leasing</a></li>
            <li <?php if($page == 'bbn'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('bbn.index')); ?>"><i class="fa fa-truck"></i> BBN Type</a></li>
            <li <?php if($page == 'area'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('area.index')); ?>"><i class="fa fa-map"></i> Area</a></li>
            <li <?php if($page == 'default-admin-fee'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('default-admin-fee.edit', ['id' => 1])); ?>"><i class="fa fa-money"></i> Default Admin Fee</a></li>
            <li <?php if($page == 'customer'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('customer.index')); ?>"><i class="fa fa-users"></i> Customer</a></li>
            <li <?php if($page == 'user'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('user.index')); ?>"><i class="fa fa-user"></i> User</a></li>
            <li <?php if($page == 'job-position'): ?> class="active" <?php endif; ?> ><a href="<?php echo e(route('job-position.index')); ?>"><i class="fa fa-user-circle-o"></i> Job Position</a></li>
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
            <li <?php if($page == 'spk'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('order.create')); ?>"><i class="fa fa-file-word-o"></i> Create SPK</a></li>
            <li <?php if($page == 'spk'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('order.index')); ?>"><i class="fa fa-file-word-o"></i> View SPK</a></li>
            <!-- <li <?php if($page == 'spk'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('order.index')); ?>?type=approval"><i class="fa fa-file-word-o"></i> Approve SPK</a></li> -->
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
            <li <?php if($page == 'approval'): ?> class="active" <?php endif; ?>><a href="<?php echo e(route('approval.index')); ?>"><i class="fa fa-check"></i> Approval Setting</a></li>
          </ul>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>