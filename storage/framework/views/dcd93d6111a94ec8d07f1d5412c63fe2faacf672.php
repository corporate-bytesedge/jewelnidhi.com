

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('Edit Order'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('Edit Order'); ?> <a class="btn btn-info btn-sm" href="<?php echo e(route('manage.orders.index')); ?>"><?php echo app('translator')->getFromJson('View Orders'); ?></a>
    <h3 class="pull-right" style="margin-top:-20px;"><?php echo app('translator')->getFromJson('Order'); ?> # <?php echo e($order->getOrderId()); ?></h3>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('Edit Order'); ?> <a href="<?php echo e(route('manage.orders.index')); ?>"><?php echo app('translator')->getFromJson('Go Back'); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <link href="<?php echo e(asset('css/jquery.dropdown.min.css')); ?>" rel="stylesheet">
    <?php echo $__env->make('includes.order_tracker_style', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('js/jquery.dropdown.min.js')); ?>"></script>
    <?php if(config('settings.toast_notifications_enable')): ?>
    <script>
        toastr.options.closeButton = true;
        <?php if(session()->has('order_updated')): ?>
            toastr.success("<?php echo e(session('order_updated')); ?>");
        <?php endif; ?>
        <?php if(session()->has('order_not_updated')): ?>
            toastr.error("<?php echo e(session('order_not_updated')); ?>");
        <?php endif; ?>
    </script>
    <?php endif; ?>
    <script>
        $('.shipment_box').dropdown({
            // options here
        });

        var delivered = $('#delivered');
        var deliveredBox = $('#delivered-box');
        var notDelivered = $('#not-delivered');
        var notDeliveredBox = $('#not-delivered-box');
        var shipmentBox = $('#shipment_box');
        var receiverDetail = $('#receiver_detail_box');
        receiverDetail.hide();
        $(document).on('change', '#delivered', function() {
            if(delivered.is(':checked')) {
                shipmentBox.hide();
                notDeliveredBox.hide();
                receiverDetail.fadeIn();
            } else {
                receiverDetail.hide();
                notDeliveredBox.fadeIn();
                shipmentBox.fadeIn();
            }
        });
        $(document).on('change', '#not-delivered', function() {
            if(notDelivered.is(':checked')) {
                deliveredBox.hide();
            } else {
                deliveredBox.fadeIn();
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.manage.orders.edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>