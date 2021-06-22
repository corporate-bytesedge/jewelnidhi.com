<div class="col-md-8"> <?php if(session()->has('banner_created')): ?> <div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <strong><?php echo e(session('banner_created')); ?></strong> </div> <?php endif; ?> <?php echo $__env->make('includes.form_errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> <?php echo Form::open(['method'=>'post', 'action'=>'ManageBannersController@store', 'files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']); ?> <div class="form-group<?php echo e($errors->has('title') ? ' has-error' : ''); ?>"> <?php echo Form::label('title', __('Title / Alt Image Text:')); ?> <?php echo Form::text('title', null, ['class'=>'form-control', 'placeholder'=>__('Enter banner title')]); ?> </div> <div class="form-group"> <?php echo Form::label('photo', __('Choose Banner Image'), ['class'=>'btn btn-default btn-file']); ?> <em style="color:red;">*</em> <?php echo Form::file('photo',['class'=>'form-control', 'style'=>'display: none;','onchange'=>'$("#upload-file-info").html(files[0].name)']); ?> <span class='label label-info' id="upload-file-info"><?php echo app('translator')->getFromJson('No image chosen'); ?></span> </div> <div class="form-group<?php echo e($errors->has('link') ? ' has-error' : ''); ?>"> <?php echo Form::label('link', __('URL Link:')); ?><em style="color:red;">*</em> <?php echo Form::text('link', null, ['class'=>'form-control', 'placeholder'=>__('Enter URL link')]); ?> </div> <div class="form-group<?php echo e($errors->has('description') ? ' has-error' : ''); ?>"> <?php echo Form::label('description', __('Description:')); ?> <?php echo Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>__('Enter banner description')]); ?> </div> <?php echo Form::hidden('position', 'Main Slider', []); ?> <div class="form-group<?php echo e($errors->has('priority') ? ' has-error' : ''); ?>"> <?php echo Form::label('priority', __('Priority:')); ?><em style="color:red;">*</em> <?php echo Form::number('priority', 1, ['class'=>'form-control', 'placeholder'=>__('Enter priority'), 'min'=>'1']); ?> </div> <div class="form-group<?php echo e($errors->has('status') ? ' has-error' : ''); ?>"> <?php echo Form::label('status', __('Status:')); ?> <?php echo Form::select('status', [0=>__('inactive'), 1=>__('active')], 1, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']); ?> </div> <div class="form-group"> <?php echo Form::submit(__('Add Banner'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']); ?> </div> <?php echo Form::close(); ?> </div>