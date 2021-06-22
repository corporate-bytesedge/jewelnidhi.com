<div class="row"> <div class="col-xs-12 col-sm-8"> <?php if(session()->has('testimonial_updated')): ?> <div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <strong><?php echo e(session('testimonial_updated')); ?></strong> </div> <?php endif; ?> <?php echo $__env->make('includes.form_errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> <?php echo Form::model($testimonial, ['method'=>'patch', 'action'=>['ManageTestimonialsController@update', $testimonial->id], 'files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']); ?> <?php if($testimonial->photo): ?> <?php if($testimonial->photo->name): ?> <?php
                        $image_url = \App\Helpers\Helper::check_image_avatar($testimonial->photo->name, 100);
                    ?> <img src="<?php echo e($image_url); ?>" class="img-responsive" alt="<?php echo e($testimonial->author); ?>" /> <?php else: ?> <img src="https://via.placeholder.com/100x100?text=No+Image" class="img-responsive" alt="<?php echo e($testimonial->author); ?>" /> <?php endif; ?> <div class="form-group"> <div class="has-error"> <div class="checkbox"> <label> <?php echo Form::checkbox('remove_photo'); ?> <strong><?php echo app('translator')->getFromJson('Remove Photo'); ?></strong> </label> </div> </div> </div> <?php endif; ?> <div class="form-group<?php echo e($errors->has('author') ? ' has-error' : ''); ?>"> <?php echo Form::label('author', __('Author:')); ?> <em style="color:red;">*</em> <?php echo Form::text('author', null, ['class'=>'form-control', 'placeholder'=>__("Enter author's name"), 'required']); ?> </div> <div class="form-group<?php echo e($errors->has('review') ? ' has-error' : ''); ?>"> <?php echo Form::label('review', __('Review:')); ?><em style="color:red;">*</em> <?php echo Form::textarea('review', null, ['class'=>'form-control', 'placeholder'=>__("Enter author's review"), 'required']); ?> </div> <div class="form-group"> <?php echo Form::label('status', __('Status:')); ?> <?php echo Form::select('status', [0=>__('inactive'), 1=>__('active')], $testimonial->is_active, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']); ?> </div> <div class="form-group"> <?php echo Form::label('photo', __('Choose photo'), ['class'=>'btn btn-default btn-file']); ?> <?php echo Form::file('photo',['class'=>'form-control', 'style'=>'display: none;','onchange'=>'$("#upload-file-info").html(files[0].name)']); ?> <span class='label label-info' id="upload-file-info"><?php echo app('translator')->getFromJson('No photo chosen'); ?></span> </div> <div class="form-group"> <?php echo Form::submit(__('Update'), ['class'=>'btn btn-primary col-xs-6 col-sm-6 pull-left', 'name'=>'submit_button']); ?> </div> <?php echo Form::close(); ?> </div> </div>