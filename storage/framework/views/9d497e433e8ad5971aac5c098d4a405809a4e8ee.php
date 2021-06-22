

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('Add Category'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('Add Category'); ?> 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('Add Category'); ?> <a href="<?php echo e(route('manage.categories.index')); ?>"><?php echo app('translator')->getFromJson('Go Back'); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>

<link href="<?php echo e(asset('css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('css/dataTables-responsive/fixedHeader.bootstrap.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('css/dataTables-responsive/responsive.bootstrap.min.css')); ?>" rel="stylesheet">
<?php if(config('settings.export_table_enable')): ?>
<link href="<?php echo e(asset('css/dataTables-export/buttons.dataTables.min.css')); ?>" rel="stylesheet">
<?php endif; ?>
<?php echo $__env->make('partials.manage.categories-tree-style', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<style>
    .bolden {
        font-family: "Arial Black";
    }
</style>
<link href="<?php echo e(asset('css/jquery.dropdown.min.css')); ?>" rel="stylesheet">
<?php echo $__env->make('includes.datatable_style', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


     
         
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('js/jquery.dropdown.min.js')); ?>"></script>
    <script>
        $('.category_box').dropdown({
                // options here
        });
        $('.specification_type_box').dropdown({
                // options here
        });
    </script>
    <?php if(config('settings.toast_notifications_enable')): ?>
    <script>
        toastr.options.closeButton = true;
        <?php if(session()->has('category_updated')): ?>
            toastr.success("<?php echo e(session('category_updated')); ?>");
        <?php endif; ?>
    </script>
    <?php endif; ?>
    <script>
        jQuery(document).ready(function() {

             
            var _URL = window.URL || window.webkitURL;
            var _CATURL = window.URL || window.webkitURL;
            $('.bannerImage').change(function () {

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
                    if(imgwidth != '1600' && imgheight != '350') {
                        $("#imgError").html('<span class="label label-danger">image diamonsion did not match</span>');
                        $("#submit_button").attr('disabled',true);
                    }
                };

            });

            $('.categoryImage').change(function () {
                
                $("#submit_button").attr('disabled',false);
                $("#CatimgError").html('');
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
                        $("#CatimgError").html('<span class="label label-danger">image diamonsion did not match</span>');
                        $("#submit_button").attr('disabled',true);
                    }
                };

            });


 
        });
    </script>

<?php echo $__env->make('partials.manage.categories-tree-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>;
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.manage.categories.create', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php if(count($root_categories) > 0): ?>
            <div class="col-md-6">
                <h4 class="text-info"><?php echo app('translator')->getFromJson('Categories Tree:'); ?></h4>
                <ul id="tree1">
                    <?php $__currentLoopData = $root_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php 
                        

                    $product =  \App\Product::whereHas('product_category_styles', function ($query) use($category)  {
                                    $query->where('category_id', $category->id);
                                })->where(function ($query) {
                                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                                })->where('is_active','1')->where('category_id', $category->id)->count();
                    ?>
                        <li>
                            

                            <?php echo e($category->name . ' ('.$product.')'); ?>

                            <?php if(count($category->categories)): ?>
                                <?php echo $__env->make('partials.manage.subcategories', ['childs' => $category->categories], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>