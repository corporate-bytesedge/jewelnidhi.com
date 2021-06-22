

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('Add Vendor'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('Add Vendor'); ?> <a class="btn btn-info btn-sm" href="<?php echo e(route('manage.vendors.index')); ?>"><?php echo app('translator')->getFromJson('View Vendors'); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('Add New Vendor'); ?> <a href="<?php echo e(url()->previous()); ?>"><?php echo app('translator')->getFromJson('Go Back'); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <?php echo $__env->make('partials.phone_style', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo $__env->make('includes.tinymce', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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
        $('#existing_user').change(function(){
            if (this.checked == true){
                $('#existing_form').css( 'display', 'none' );
            } else {
                $('#existing_form').css('display', 'block' );
            }
        });
        $('#username').on('blur',function(){
            $('#no_username').css('display','none');
            var existingUser = document.getElementById('existing_user');
            if (existingUser.checked === true){
               getUserData(this.value);
            }
        });
        function getUserData(user_name) {
            $.ajax({
                method: 'get',
                url: APP_URL + '/manage/ajax/user/get-user-data/' + user_name,
                success: function(response) {
                    var response = JSON.parse(response);
                    if(response) {
                        if (response !== 0 || response !== '' ){
                            $('#name').val(response.name);
                            $('#username').val(response.username);
                            $('#email').val(response.email);
                            $('#phone-number').val(response.phone);
                            $('#password').val(response.email);
                            $('#password_confirmation').val(response.email);
                        }else{
                            $('#no_username').css('display','block');
                            $('#username').val('');
                        }
                    }else{
                        $('#no_username').css('display','block');
                        $('#username').val('');
                    }
                },
                error : function (response) {
                    $('#no_username').css('display','block');
                    $('#username').val('');
                }
            });
        }
    </script>
    <?php if(config('settings.toast_notifications_enable')): ?>
        <script>
            toastr.options.closeButton = true;
            <?php if(session()->has('vendor_created')): ?>
            toastr.success("<?php echo e(session('vendor_created')); ?>");
            <?php endif; ?>
            <?php if(session()->has('vendor_not_created')): ?>
            toastr.error("<?php echo e(session('vendor_not_created')); ?>");
            <?php endif; ?>
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.manage.vendors.create', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>