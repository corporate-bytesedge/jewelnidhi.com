

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('Edit Vendor'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('Edit Vendor'); ?> <a class="btn btn-info btn-sm" href="<?php echo e(route('manage.vendors.index')); ?>"><?php echo app('translator')->getFromJson('View Vendors'); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('Edit Vendor'); ?> <a href="<?php echo e(route('manage.vendors.index')); ?>"><?php echo app('translator')->getFromJson('Go Back'); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <?php echo $__env->make('partials.phone_style', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo $__env->make('includes.tinymce', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php if(config('settings.toast_notifications_enable')): ?>
    <script>
        toastr.options.closeButton = true;
        <?php if(session()->has('vendor_updated')): ?>
            toastr.success("<?php echo e(session('vendor_updated')); ?>");
        <?php endif; ?>
        <?php if(session()->has('vendor_not_updated')): ?>
            toastr.success("<?php echo e(session('vendor_not_updated')); ?>");
        <?php endif; ?>
    </script>
    <?php endif; ?>
    <?php echo $__env->make('partials.phone_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
    var regExp = /[a-z]/i;
        $('.phone_number').on('keydown keyup', function(e) {
            var value = String.fromCharCode(e.which) || e.key;
            // No letters
            if (regExp.test(value)) {
                e.preventDefault();
                return false;
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.manage.vendors.edit', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>