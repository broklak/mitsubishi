<!-- jQuery 3 -->
<script src="<?php echo e(asset('lte')); ?>/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo e(asset('lte')); ?>/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo e(asset('lte')); ?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo e(asset('lte')); ?>/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo e(asset('lte')); ?>/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('lte')); ?>/dist/js/adminlte.min.js"></script>

<script src="<?php echo e(asset('lte')); ?>/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo e(asset('lte')); ?>/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<script>
  $(function () {
    $('#example1').DataTable({
    	"order":[[0, 'desc']]
    })
  })
</script>

<?php if ($__env->exists($js_name)) echo $__env->make($js_name, array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>