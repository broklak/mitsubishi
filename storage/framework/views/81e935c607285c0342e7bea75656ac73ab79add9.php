<!DOCTYPE html>
<html>
<head>
    <!-- META AND TITLE
    ================================================== -->
    <?php echo $__env->make("layout.meta", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <!-- CSS
    ================================================== -->
    <?php echo $__env->make("layout.css", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</head>
<body class="hold-transition skin-red sidebar-mini">
<div class="wrapper">

    <!-- HEADER
    ================================================== -->
    <?php echo $__env->make("layout.header", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <!-- SIDEBAR
    ================================================== -->
    <?php echo $__env->make("layout.sidebar", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <div class="content-wrapper">
        <!-- BREADCRUMB
        ================================================== -->
        <?php echo $__env->make("layout.bread", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <!-- CONTENT
        ================================================== -->
        <?php echo $__env->yieldContent("content"); ?>
    </div>
  

    <!-- FOOTER
    ================================================== -->
    <?php echo $__env->make("layout.footer", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>  

</div>
    <!-- JS
    ================================================== -->
    <?php echo $__env->make("layout.js", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

</body>
</html>
