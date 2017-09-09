<!-- Content Header (Page header) -->
    <section class="content-header">
   	  <?php if(isset($title)): ?>
      <h1><?php echo e(ucwords(str_replace('-',' ', $title))); ?></h1>
      <?php else: ?>
      <h1><?php echo e((isset($page)) ? ucwords(str_replace('-',' ', $page)) : 'Dashboard'); ?></h1>
      <?php endif; ?>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo e((isset($page)) ? ucwords($page) : 'Dashboard'); ?></li>
      </ol>
    </section>