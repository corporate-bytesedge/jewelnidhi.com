

<?php $__env->startSection('title'); ?><?php echo app('translator')->getFromJson('Shipping Details'); ?> - <?php echo e(config('app.name')); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <?php echo $__env->make('partials.phone_style', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <link href="<?php echo e(asset('css/front-sidebar-content.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo $__env->make('partials.phone_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
        <?php if($customer->country): ?>
        $('#country').val("<?php echo e($customer->country); ?>");
        <?php endif; ?>

         
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
    <div class="row dashboard-page">
        <?php echo $__env->make('partials.front.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
         
          
            
            <div class="col-sm-9">
            
            
                <div class="content">
                    <div class="page-title">
                        <h2><?php echo app('translator')->getFromJson('Shipping Address'); ?></h2>
                    </div>
                    <div class="card">
                        <div class="card-body shipping-address-form">

                        <?php echo $__env->make('includes.form_errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                        <?php if(session()->has('address_updated')): ?>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <?php echo e(session('address_updated')); ?>

                            </div>
                        <?php endif; ?>
                        
                            <?php echo Form::model($customer, ['method'=>'patch', 'action'=>['FrontCustomersController@update', $customer->id], 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait') . '"; return true;']); ?>

                        
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group<?php echo e($errors->has('first_name') ? ' has-error' : ''); ?>">
                                        <?php echo Form::label('first_name', __('First Name:')); ?>

                                        <?php echo Form::text('first_name', null, ['class'=>'form-control', 'placeholder'=>__('Enter first name'), 'required']); ?>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group<?php echo e($errors->has('last_name') ? ' has-error' : ''); ?>">
                                        <?php echo Form::label('last_name', __('Last Name:')); ?>

                                        <?php echo Form::text('last_name', null, ['class'=>'form-control', 'placeholder'=>__('Enter last name'), 'required']); ?>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group<?php echo e($errors->has('address') ? ' has-error' : ''); ?>">
                                <?php echo Form::label('address', __('Address:')); ?>

                                <?php echo Form::textarea('address', null, ['class'=>'form-control', 'placeholder'=>__('Enter address'), 'rows'=>'6', 'required']); ?>

                            </div>

                            <div class="form-group<?php echo e($errors->has('city') ? ' has-error' : ''); ?>">
                                <?php echo Form::label('city', __('City:')); ?>

                                <?php echo Form::text('city', null, ['class'=>'form-control', 'placeholder'=>__('Enter city'), 'required']); ?>

                            </div>

                            <div class="form-group<?php echo e($errors->has('state') ? ' has-error' : ''); ?>">
                                <?php echo Form::label('state', __('State:')); ?>

                                <?php echo Form::text('state', null, ['class'=>'form-control', 'placeholder'=>__('Enter state'), 'required']); ?>

                            </div>

                            <div class="form-group<?php echo e($errors->has('zip') ? ' has-error' : ''); ?>">
                                <?php echo Form::label('zip', __('Zip:')); ?>

                                <?php echo Form::text('zip', null, ['class'=>'form-control', 'placeholder'=>__('Enter zip'), 'required']); ?>

                            </div>

                            <?php echo $__env->make('partials.countries_field', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

                            <div class="form-group<?php echo e($errors->has('phone') ? ' has-error' : ''); ?>">
                                <?php echo Form::label('', __('Phone:')); ?>

                                <?php echo Form::text('phone', $customer->phone, ['class'=>'form-control', 'placeholder'=>__('Enter your phone number'), 'required']); ?>

                            </div>

                            <!-- <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                                <?php echo Form::label('email', __('Email:')); ?>

                                <?php echo Form::email('email', null, ['class'=>'form-control', 'placeholder'=>__('Enter your email address'), 'required']); ?>

                            </div> -->
                        </div>
                        <div class="panel-footer">
                            <center><button type="submit" class="btn btn-info" name="submit_button" id="update_btn"><?php echo app('translator')->getFromJson('Update Address'); ?></button></center>
                        </div>
                    </div>
                </div>
               
               
                <?php echo Form::close(); ?>

            </div>
         
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>