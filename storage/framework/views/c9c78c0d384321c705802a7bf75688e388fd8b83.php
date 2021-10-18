<script>
    toastr.options.closeButton = true;

    <?php if(session()->has('subscribed')): ?>
    toastr.success("<?php echo e(session('subscribed')); ?>");
    <?php endif; ?>

    <?php if(session()->has('already_subscribed')): ?>
    toastr.success("<?php echo e(session('already_subscribed')); ?>");
    <?php endif; ?>

    <?php if(session()->has('subscribe_failed')): ?>
    toastr.error("<?php echo e(session('subscribe_failed')); ?>");
    <?php endif; ?>

    <?php if(session()->has('payment_success')): ?>
    toastr.success("<?php echo e(session('payment_success')); ?>");
    <?php endif; ?>

    <?php if(session()->has('payment_fail')): ?>
    toastr.error("<?php echo e(session('payment_fail')); ?>");
    <?php endif; ?>

    <?php if(session()->has('success')): ?>
    toastr.success("<?php echo e(session('success')); ?>");
    <?php endif; ?>

    <?php if(session()->has('email_activation')): ?>
    toastr.success("<?php echo e(session('email_activation')); ?>");
    <?php endif; ?>

</script>