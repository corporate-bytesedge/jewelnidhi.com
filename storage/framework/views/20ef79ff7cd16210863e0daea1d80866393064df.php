

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('Price Settings'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('Price Settings'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('View or Change Price Settings'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php if(config('settings.toast_notifications_enable')): ?>
    <script>
        toastr.options.closeButton = true;
        <?php if(session()->has('profile_updated')): ?>
            toastr.success("<?php echo e(session('profile_updated')); ?>");
        <?php endif; ?>
    </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.manage.settings.pricesetting', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>