

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('Edit Coupon'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('Edit Coupon'); ?> <a class="btn btn-info btn-sm" href="<?php echo e(route('manage.coupons.index')); ?>"><?php echo app('translator')->getFromJson('View Coupons'); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('Edit Coupon'); ?> <a href="<?php echo e(route('manage.coupons.index')); ?>"><?php echo app('translator')->getFromJson('Go Back'); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <link href="<?php echo e(asset('css/jquery.datetimepicker.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('js/jquery.datetimepicker.full.min.js')); ?>"></script>
    <script>
        $('#datetimepicker_start').datetimepicker({
            // options here
        });
        $('#datetimepicker_end').datetimepicker({
            // options here
        });
        $('.product_box').dropdown({
            // options here
        });

        jQuery(document).ready(function() {

            // $("#discount_flat_rate").hide();
            // $("#discount_percentage").hide();

            $(".discountRadio").on("click",function() {

                var radioValue = $("input[name='type']:checked").val();
                
                if(radioValue == '0') {
                    
                    $("#discount_flat_rate").show();
                    $("#discount_percentage").hide();

                } else { 

                    $("#discount_flat_rate").hide();
                    $("#discount_percentage").show();
                }

            });
           
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.manage.coupons.edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>