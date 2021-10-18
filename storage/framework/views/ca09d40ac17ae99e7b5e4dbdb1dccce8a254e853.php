 <?php $__env->startSection('title'); ?><?php echo e('Shopping Cart - '.config('app.name')); ?> <?php $__env->stopSection(); ?> <?php $__env->startSection('meta-tags'); ?><meta name="description" content="<?php echo app('translator')->getFromJson('View your Shopping Cart'); ?>"> <?php $__env->stopSection(); ?> <?php $__env->startSection('meta-tags-og'); ?><meta property="og:url" content="<?php echo e(url()->current()); ?>" /> <meta property="og:type" content="website" /> <meta property="og:title" content="<?php echo app('translator')->getFromJson('Shopping Cart'); ?> - <?php echo e(config('app.name')); ?>" /> <meta property="og:description" content="<?php echo app('translator')->getFromJson('View your Shopping Cart'); ?>" /> <meta property="og:image" content="<?php echo e(url('/img/'.config('settings.site_logo'))); ?>" /> <?php $__env->stopSection(); ?> <?php $__env->startSection('styles'); ?> <style> table a:not(.btn), .table a:not(.btn) { text-decoration: none; } .cart-header { font-size: 1.1em; } .btn-square { width: 24px; height: 24px; } @media(max-width: 991px) { .big-col { min-width:250px } } .img-box img{ height: 80px; width: auto!important; } .product-image{ width:100%!important; } </style> <style> table { border: 1px solid #ccc; border-collapse: collapse; margin: 0; padding: 0; width: 100%; table-layout: fixed; } table caption { font-size: 1.5em; margin: .5em 0 .75em; } table tr { background: #fff; border: 1px solid #ddd; padding: .35em; } table th, table td { padding: .625em; text-align: center; } table th { font-size: .85em; letter-spacing: .1em; text-transform: uppercase; } @media screen and (max-width: 600px) { table { border: 0; } table caption { font-size: 1.3em; } table thead { border: none; clip: rect(0 0 0 0); height: 1px; margin: -1px; overflow: hidden; padding: 0; position: absolute; width: 1px; } table tr { border-bottom: 3px solid #ddd; display: block; margin-bottom: .625em; } table td { border-bottom: 1px solid #ddd; display: block; font-size: .8em; text-align: right; } table td:before { content: attr(data-label); float: left; font-weight: bold; text-transform: uppercase; } table td:last-child { border-bottom: 0; } } .input-group.pull-right.input-group-sm { z-index: 0; } @media (max-width:768px) { .cart-container table tr { display: table-row-group; } } </style> <?php $__env->stopSection(); ?> <?php $__env->startSection('content'); ?> <div class="breadcrumb-sec"> <div class="container"> <ol class="breadcrumb"> <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li> <li class="breadcrumb-item active" aria-current="page"><?php echo app('translator')->getFromJson('Shopping Cart'); ?></li> </ol> </div> </div> <div class="cart-container"> <?php echo $__env->make('partials.front.includes.cart', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> </div> <?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>