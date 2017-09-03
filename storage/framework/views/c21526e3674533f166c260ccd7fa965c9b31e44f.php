<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>

<section class="content">
	<div class="col-md-12">
		<?php echo session('displayMessage'); ?>

		<div class="box">
            <div class="box-header">
              <a href="<?php echo e(route($page.'.create')); ?>" class="btn btn-info">Add New Approver</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form class="form-horizontal" action="<?php echo e(route("$page.change-level")); ?>" method="post">
              <?php echo e(csrf_field()); ?>

                <table class="table table-bordered table-hover table-striped">
                  <thead>
                  <tr>
                    <th>Role</th>
                    <th>Approval level</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php $__currentLoopData = $result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                  <td><?php echo e(\App\Role::getName($val->job_position_id)); ?></td>
                  <td>
                    <input style="width:30px;text-align:center" type="number" name="level[<?php echo e($val->id); ?>]" value="<?php echo e($val->level); ?>" />
                  </td>
                  <td>
                  	<div class="btn-group">
  	                  <a href="<?php echo e(route("$page.delete", ['id' => $val->id])); ?>" onclick="return confirm('You will delete this approver, continue')" class="btn btn-danger">Remove</a>
                  	</div>
                  </td>
                  </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
                <div class="box-footer">
                  <button type="submit" class="btn btn-info pull-right">Update Level</button>
                </div>
                <?php echo e(method_field('PUT')); ?>

              </form>
            </div>
            <!-- /.box-body -->
          </div>
	</div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.main', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>