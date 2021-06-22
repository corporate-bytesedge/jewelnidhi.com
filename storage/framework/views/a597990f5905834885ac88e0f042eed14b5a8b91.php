

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('Edit Page'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('Edit Page'); ?> <a class="btn btn-info btn-sm" href="<?php echo e(route('manage.pages.index')); ?>"><?php echo app('translator')->getFromJson('View Pages'); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('Edit Page'); ?> <a href="<?php echo e(route('manage.pages.index')); ?>"><?php echo app('translator')->getFromJson('Go Back'); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo $__env->make('includes.tinymce', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php if(config('settings.toast_notifications_enable')): ?>
    <script>
        toastr.options.closeButton = true;
        <?php if(session()->has('page_updated')): ?>
            toastr.success("<?php echo e(session('page_updated')); ?>");
        <?php endif; ?>
    </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.manage.pages.edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>