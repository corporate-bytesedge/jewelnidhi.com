<!DOCTYPE html>
<html lang="<?php echo e(Config::get('app.locale')); ?>">
<head>
    <?php if(config('analytics.google_analytics_script') != ""): ?>
        <?php echo $__env->make('partials.front.google-analytics', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/> -->
   <meta name = "viewport" content = "width=device-width, minimum-scale=1.0, maximum-scale = 1.0, user-scalable = no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <?php echo $__env->yieldContent('meta-tags'); ?>

    <title><?php echo $__env->yieldContent('title'); ?></title>
     
    <!-- <title><?php echo e(config('app.name')); ?></title> -->
    <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
    <?php echo $__env->yieldContent('meta-tags-og'); ?>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
     
     
    <link rel="stylesheet" href="<?php echo e(asset('css/slick-theme.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('css/slick.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('css/nice-select.css')); ?> " />
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('css/owl.carousel.min.css')); ?> ">
    <link rel="stylesheet" href="<?php echo e(asset('css/owl.theme.default.css')); ?> ">
    <link href="<?php echo e(asset('css/developer.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldContent('styleg'); ?>
    <script src=" <?php echo e(asset('js/jquery.min.js')); ?> "></script>
    <script src="<?php echo e(asset('js/jquery.elevateZoom-3.0.8.min.js')); ?>"></script>
    <style>
    .item a {
            color:#fff !important;
        }
  
     
    </style>
    <link rel="stylesheet" href="<?php echo e(asset('css/aos.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('css/responsive-tables.css')); ?>" />

    </head>
<body>
<!-- <div id="cover-spin"></div> -->
        <?php if(\Route::currentRouteName() != 'front.orders.show' ): ?>
            <?php echo $__env->make('includes.betheme-header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php endif; ?>
    <!-- <div class="main-wrapper wrapper-cont"> -->
        <!-- <?php echo $__env->yieldContent('sidebar'); ?> -->

        <?php echo $__env->yieldContent('above_container'); ?>

        <?php echo $__env->yieldContent('content'); ?>
    <!-- </div> -->
    <?php if(\Route::currentRouteName() != 'front.orders.show' ): ?>
      <?php echo $__env->make('partials.front.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
    <!-- <?php echo $__env->yieldContent('footer'); ?>

<script src="<?php echo e(asset('js/libs.js')); ?>"></script>
<script src="<?php echo e(asset('js/front.js')); ?>"></script>

<?php echo $__env->make('includes.front.footer-toaster', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<script src="<?php echo e(asset('js/jquery-ui.min.js')); ?>"></script>

<?php echo $__env->make('includes.front.live-chat-widget', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<script type="text/javascript" href="<?php echo e(asset(theme_url('/js/animations.min.js'))); ?>"> </script>
<script src="<?php echo e(asset(theme_url('/js/script.js'))); ?>?v=1.8"></script>
-->
<button   id="myBtn" data-scroll="up" type="button">
<i class="fa fa-chevron-up"></i>
</button>

<?php echo $__env->yieldContent('scripts'); ?> 
<script type="text/javascript">
var mybutton = document.getElementById("myBtn");
window.onscroll = function() {scrollFunction()};
      
      function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            
          mybutton.style.display = "block";
        } else {
          mybutton.style.display = "none";
        }
      }
      
      
$("#myBtn").click(function() {
    
  $("html, body").animate({ scrollTop: 0 }, "slow");
  return false;
});
</script>
</body>
</html>