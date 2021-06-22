<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
    <title><?php echo $__env->yieldContent('title'); ?></title>
    
    <link href="<?php echo e(asset('css/libs.css')); ?>?v=1.1" rel="stylesheet">
    <?php echo $__env->make('includes.default-manage-color-settings', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <link href="<?php echo e(asset('css/custom/manage-panel.css')); ?>" rel="stylesheet">
    <?php if(config('settings.loading_animation_enable')): ?>
    <style>
        #loader {
            border-top: 16px solid #C90000;
            border-bottom: 16px solid #C90000;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
        }
        @keyframes  spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }
        #page-inner {
            opacity: 0;
        }
    </style>
    <?php endif; ?>
    <style>
        .nav-tabs > li > a {
            background-color: #158cba;
            border-color: #127ba3;
            color: #fff;
            transition: 0.3s all;
        }
        .nav-tabs > li > a:hover {
            color: #158cba;
            font-weight: bold;
            border-color: #127ba3;
        }
        .nav-tabs > li.active > a {
            color: #158cba !important;
            font-weight: bold !important;
            border-color: #127ba3 !important;
            border-bottom: none !important;
        }
        .alert-success > a {
            color: #fff;
            font-weight: bold;
        }
        #page-inner {
            min-height: 1500px
        }
    </style>
    <?php echo $__env->yieldContent('styles'); ?>
    <?php if(config('customcss.css_manage') != ""): ?>
    <?php echo $__env->make('partials.manage.custom-css', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
    <?php echo $__env->make('includes.app_url_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
</head>
<?php if(config('settings.loading_animation_enable')): ?>
<body onload="myFunction()">
<?php else: ?>
<body>
<?php endif; ?>
<div id="wrapper">
    <?php echo $__env->make('partials.manage.top-navbar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('partials.manage.side-navbar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div id="page-wrapper">
        <?php if(config('settings.loading_animation_enable')): ?>
        <div id="loader"></div>
        <?php endif; ?>
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <h2><?php echo $__env->yieldContent('page-header-title'); ?></h2>
                    <h5><?php echo $__env->yieldContent('page-header-description'); ?></h5>
                </div>
            </div>
            <hr/>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
    <!-- <div class="custom_manage_footer text-danger"><?php echo e(date('Y-m-d H-i-s')); ?></div> -->
</div>
<?php if(config('settings.loading_animation_enable')): ?>
    <script>
        var myVar;
        function myFunction() {
            myVar = setTimeout(showPage, 0);
        }
        function showPage() {
            document.getElementById("loader").style.display = "none";
            document.getElementById("page-inner").style.opacity = 1;
        }
    </script>
<?php endif; ?>

<script src="<?php echo e(asset('js/libs.js')); ?>"></script>
<?php echo $__env->yieldContent('scripts'); ?>
<?php echo $__env->yieldContent('colorPickerJs'); ?>
</body>
</html>
