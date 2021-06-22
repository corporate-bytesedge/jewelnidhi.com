

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('Edit User'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('Edit User'); ?> <a class="btn btn-info btn-sm" href="<?php echo e(route('manage.users.index')); ?>"><?php echo app('translator')->getFromJson('View User'); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('Edit User'); ?> <a href="<?php echo e(route('manage.users.index')); ?>"><?php echo app('translator')->getFromJson('Go Back'); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php if(config('settings.toast_notifications_enable')): ?>
    <script>
        toastr.options.closeButton = true;
        <?php if(session()->has('user_updated')): ?>
            toastr.success("<?php echo e(session('user_updated')); ?>");
        <?php endif; ?>
    </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.manage.users.edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>