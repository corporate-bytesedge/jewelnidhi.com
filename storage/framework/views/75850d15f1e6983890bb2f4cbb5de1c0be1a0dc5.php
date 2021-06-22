 <?php $__env->startSection('title'); ?><?php echo app('translator')->getFromJson('Shipping Addresses'); ?> - <?php echo e(config('app.name')); ?><?php $__env->stopSection(); ?> <?php $__env->startSection('styles'); ?> <style> table a:not(.btn), .table a:not(.btn) { text-decoration: none; } .shipin-addres h4 { border-bottom: 1px solid #ccc; padding-bottom: 10px; } .shipin-addres strong { padding: 10px 0px; line-height: 30px; font-size: 15px; } .shipping-address { border: 1px solid #ccc; padding: 15px; margin-bottom: 30px; } .btn-area { border-top: 1px solid #ccc; padding-top: 15px; margin-top: 14px; } .btn-area .btn { padding: 4px 10px; } </style> <link href="<?php echo e(asset('css/front-sidebar-content.css')); ?>" rel="stylesheet"> <?php $__env->stopSection(); ?> <?php $__env->startSection('content'); ?> <div class="container"> <div class="row dashboard-page"><?php echo $__env->make('partials.front.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> <div class="content col-md-9"> <div class="page-title"> <h2 style="color:#fff !important;"><?php echo app('translator')->getFromJson('Your Addresses'); ?></h2> </div> <?php if(session()->has('address_deleted')): ?> <div class="alert alert-danger alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <?php echo e(session('address_deleted')); ?> </div> <?php endif; ?> <div class="row"> <?php if(count($customers) > 0 ): ?> <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <div class="col-md-6 mb-4"> <div class="card"> <div class="card-body"> <div class="shipping-address"> <div><h4><?php echo e($key+1); ?>. <?php echo app('translator')->getFromJson('Shipping Address'); ?> </h4> <div class="edit-delete"> <?php if(Auth::user()->can('update', App\Address::class) ): ?> <a class="btn btn-primary btn-xs" href="<?php echo e(route('front.addresses.edit', $customer->id)); ?>"><i class="fa fa-pencil"></i></a> <?php endif; ?> <?php if(Auth::user()->can('delete', App\Address::class) ): ?> <a href="" class='btn btn-xs btn-danger ' onclick=" if(confirm('<?php echo app('translator')->getFromJson('Are you sure you want to delete this?'); ?>')) { event.preventDefault(); $('#delete-form-<?php echo e($customer->id); ?>').submit(); } else { event.preventDefault(); } " ><i class="fa fa-trash-o"></i> </a> <?php endif; ?> </div> </div> <strong><?php echo e($customer->first_name . ' ' . $customer->last_name); ?></strong>, <br> <?php echo e($customer->address); ?><br> <?php echo e($customer->city . ', ' . $customer->state . ' - ' . $customer->zip); ?><br> <?php echo e($customer->country); ?><br> <strong><?php echo app('translator')->getFromJson('Phone:'); ?></strong> <?php echo e($customer->phone); ?><br> <div class="btn-area"> <?php echo Form::model($customer, ['method'=>'delete', 'action'=>['FrontCustomersController@destroy', $customer->id], 'id'=> 'delete-form-'.$customer->id, 'style'=>'display: none;']); ?> <?php echo Form::close(); ?> </div> </div> </div> </div> </div> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php else: ?> <div class="col-md-12"> <div class="card"> <div class="card-body"> <h5 align="center">Record not found</h5> </div> </div> </div> <?php endif; ?> </div> </div> </div> </div> <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>