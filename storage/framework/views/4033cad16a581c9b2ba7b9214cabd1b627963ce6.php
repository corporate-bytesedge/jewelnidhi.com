

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('Settings Overview'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('Save Settings'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('View and Update Settings'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php if(config('settings.toast_notifications_enable')): ?>
    <script>
        toastr.options.closeButton = true;
        <?php if(session()->has('settings_saved')): ?>
            toastr.success("<?php echo e(session('settings_saved')); ?>");
        <?php endif; ?>
        <?php if(session()->has('settings_not_saved')): ?>
            toastr.error("<?php echo e(session('settings_not_saved')); ?>");
        <?php endif; ?>
    </script>
    <?php endif; ?>
    <?php echo $__env->make('includes.tab_system_scripts', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.manage.settings.index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>