
<?php if(isset($style)): ?>
        <?php $__env->startSection('title'); ?>
            <?php echo app('translator')->getFromJson('Edit Style'); ?>
        <?php $__env->stopSection(); ?>
<?php else: ?>
    <?php $__env->startSection('title'); ?>
        <?php echo app('translator')->getFromJson('Add Style'); ?>
    <?php $__env->stopSection(); ?>
<?php endif; ?>
<?php if(isset($style)): ?>
    <?php $__env->startSection('page-header-title'); ?>
        <?php echo app('translator')->getFromJson('Edit Style'); ?> 
    <?php $__env->stopSection(); ?>
<?php else: ?>

    <?php $__env->startSection('page-header-title'); ?>
        <?php echo app('translator')->getFromJson('Add Style'); ?> 
    <?php $__env->stopSection(); ?>

<?php endif; ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('Add New Style'); ?> <a href="<?php echo e(url()->previous()); ?>"><?php echo app('translator')->getFromJson('Go Back'); ?></a>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script>
        jQuery(document).ready(function() {

              
            var _URL = window.URL || window.webkitURL;
            var _CATURL = window.URL || window.webkitURL;

            $('.styleImage').change(function () {
                
                $("#submit_button").attr('disabled',false);
                $("#styleimgError").html('');
                var file = $(this)[0].files[0];
                img = new Image();
                var imgwidth = 0;
                var imgheight = 0;
                var maxwidth = 640;
                var maxheight = 640;
                img.src = _CATURL.createObjectURL(file);
                img.onload = function() {
                    imgwidth = this.width;
                    imgheight = this.height;
                    if(imgwidth != '370' && imgheight != '300') {
                        $("#styleimgError").html('<span class="label label-danger">image diamonsion did not match</span>');
                        $("#submit_button").attr('disabled',true);
                    }
                };

            });

            $('.bannerImage').change(function () {
                console.log('here');
                $("#submit_button").attr('disabled',false);
                $("#imgError").html('');
                var file = $(this)[0].files[0];
                img = new Image();
                var imgwidth = 0;
                var imgheight = 0;
                var maxwidth = 640;
                var maxheight = 640;
                img.src = _URL.createObjectURL(file);
                img.onload = function() {
                    imgwidth = this.width;
                    imgheight = this.height;
                    if(imgwidth != '1600' && imgheight != '400') {
                        $("#imgError").html('<span class="label label-danger">image diamonsion did not match</span>');
                        $("#submit_button").attr('disabled',true);
                    }
                };
            
            });

         
            

             
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.manage.settings.CreateOrUpdateStyle', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>