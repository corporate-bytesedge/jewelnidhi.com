 <div class="row"> <div class="col-xs-12 col-sm-8"> <?php echo $__env->make('includes.form_errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> <?php if(isset($style)): ?> <?php echo e(Form::model($style, ['route' => ['manage.settings.update_style', $style->id],'method' => 'PUT','files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;'])); ?> <?php else: ?> <?php echo e(Form::open(array('route' => 'manage.settings.store_style','files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;'))); ?> <?php endif; ?> <?php if(isset($style->image) && $style->image!=''): ?> <img src="<?php echo e(asset('storage/style/'.$style->image)); ?>" height="200" width="200" class="img-responsive" alt="Category"> <div class="form-group"> <div class="has-error"> <div class="checkbox"> <label> <?php echo Form::checkbox('remove_photo'); ?> <strong><?php echo app('translator')->getFromJson('Remove Icon Image'); ?></strong> </label> </div> </div> </div> <?php endif; ?> <?php if(isset($style->category_img) && $style->category_img!=''): ?> <img src="<?php echo e(asset('storage/style/topcategory/'.$style->category_img)); ?>" height="200" width="200" class="img-responsive" alt="Category"> <div class="form-group"> <div class="has-error"> <div class="checkbox"> <label> <?php echo Form::checkbox('remove_category_photo'); ?> <strong><?php echo app('translator')->getFromJson('Remove Style Image'); ?></strong> </label> </div> </div> </div> <?php endif; ?> <?php if(isset($style->banner) && $style->banner!=''): ?> <img src="<?php echo e(asset('storage/style/banner/'.$style->banner)); ?>" height="200" width="200" class="img-responsive" alt="Category"> <div class="form-group"> <div class="has-error"> <div class="checkbox"> <label> <?php echo Form::checkbox('remove_banner'); ?> <strong><?php echo app('translator')->getFromJson('Remove Banner Image'); ?></strong> </label> </div> </div> </div> <?php endif; ?> <div class="form-group<?php echo e($errors->has('category_id') ? ' has-error' : ''); ?>"> <?php echo Form::label('name', __('Category :')); ?> <em style="color:red;">*</em> <?php echo Form::select('category_id', $category, null, ['class'=>'form-control','placeholder' => 'Select Category', 'required'] );; ?> </div> <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>"> <?php echo Form::label('name', __('Name:')); ?><em style="color:red;">*</em> <?php echo Form::text('name', isset($style->name) ? $style->name : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Name"), 'required']); ?> </div> <div class="form-group<?php echo e($errors->has('hsn_code') ? ' has-error' : ''); ?>"> <?php echo Form::label('hsn_code', __('HSN Code:')); ?><em style="color:red;">*</em> <?php echo Form::text('hsn_code', isset($style->hsn_code) ? $style->hsn_code : '' , ['class'=>'form-control', 'placeholder'=>__("Enter HSN Code"), 'required']); ?> </div> <div class="checkbox"> <label> <input type="checkbox" name="show_in_slider" value="1" <?php if(isset($style->show_in_slider) && $style->show_in_slider): ?> checked <?php endif; ?>> <strong><?php echo app('translator')->getFromJson('Show in Top Categories'); ?></strong> </label> </div> <div class="checkbox"> <label> <input type="checkbox" name="show_filter_price" value="1" <?php if(isset($style->show_filter_price) && $style->show_filter_price): ?> checked <?php endif; ?>> <strong><?php echo app('translator')->getFromJson('Show Filter Price'); ?></strong> </label> </div> <div class="checkbox"> <label> <input type="checkbox" name="show_filter_metal" value="1" <?php if(isset($style->show_filter_metal) && $style->show_filter_metal): ?> checked <?php endif; ?>> <strong><?php echo app('translator')->getFromJson('Show Filter Metal'); ?></strong> </label> </div> <div class="checkbox"> <label> <input type="checkbox" name="show_filter_purity" value="1" <?php if(isset($style->show_filter_purity) && $style->show_filter_purity): ?> checked <?php endif; ?>> <strong><?php echo app('translator')->getFromJson('Show Filter Purity'); ?></strong> </label> </div> <div class="checkbox"> <label> <input type="checkbox" name="show_filter_gender" value="1" <?php if(isset($style->show_filter_gender) && $style->show_filter_gender): ?> checked <?php endif; ?>> <strong><?php echo app('translator')->getFromJson('Show Filter Gender'); ?></strong> </label> </div> <div class="checkbox"> <label> <input type="checkbox" name="show_filter_offers" value="1" <?php if(isset($style->show_filter_offers) && $style->show_filter_offers): ?> checked <?php endif; ?>> <strong><?php echo app('translator')->getFromJson('Show Filter Offers'); ?></strong> </label> </div> <div class="checkbox"> <label> <input type="checkbox" name="show_filter_short_by" value="1" <?php if(isset($style->show_filter_short_by) && $style->show_filter_short_by): ?> checked <?php endif; ?>> <strong><?php echo app('translator')->getFromJson('Show Filter Sort By'); ?></strong> </label> </div> <div class="form-group<?php echo e($errors->has('priority') ? ' has-error' : ''); ?>"> <?php echo Form::label('priority', __('Order No: (to display under Menus)')); ?> <?php echo Form::text('priority', isset($style->priority) && $style->priority!='0' ? $style->priority : '1', ['class'=>'form-control', 'placeholder'=>__("Enter Order By")]); ?> </div> <div class="form-group<?php echo e($errors->has('top_category_priority') ? ' has-error' : ''); ?>"> <?php echo Form::label('top_category_priority', __('Order No: (to display under Top Categories Home Page)')); ?> <?php echo Form::text('top_category_priority', isset($style->top_category_priority) && $style->top_category_priority!= null ? $style->top_category_priority : 1, ['class'=>'form-control', 'placeholder'=>__("Enter order by top category")]); ?> </div> <div class="form-group<?php echo e($errors->has('is_active') ? ' has-error' : ''); ?>"> <?php echo Form::label('is_active', __('Status:')); ?><em style="color:red;">*</em> <?php echo Form::select('is_active', [ '1' => 'Active','2' => 'Inactive'], isset($style->is_active) ? $style->is_active : '', ['class'=>'form-control','placeholder' => 'Select Status','required'=>true]); ?> </div> <div class="form-group"> <?php echo Form::label('icon', __('Choose Style Icon'), ['class'=>'btn btn-default btn-file']); ?> <?php echo Form::file('icon',['class'=>'form-control', 'style'=>'display: none;','onchange'=>'$("#upload-file-info").html(files[0].name)']); ?> <?php if(isset($style->image) && $style->image!=''): ?> <?php echo Form::hidden('old_icon', isset($style->image) ? $style->image : '' ); ?> <img src="<?php echo e(asset('storage/style/'.$style->image)); ?>" height="50" width="50" class="center" > <?php endif; ?> <span class='label label-info' id="upload-file-info"><?php echo app('translator')->getFromJson('No style icon chosen'); ?></span> </div> <div class="form-group"> <?php echo Form::label('style_image', __('Choose Image'), ['class'=>'btn btn-default btn-file']); ?> <?php echo Form::file('style_image',['class'=>'form-control styleImage', 'style'=>'display: none;','onchange'=>'$("#upload-file-info2").html(files[0].name)']); ?> <?php if(isset($style->category_img) && $style->category_img!=''): ?> <?php echo Form::hidden('old_style_image', isset($style->category_img) ? $style->category_img : '' ); ?> <img src="<?php echo e(asset('storage/style/topcategory/'.$style->category_img)); ?>" height="50" width="50" class="center" > <?php endif; ?> <span id="styleimgError"></span> <span class='label label-info' id="upload-file-info"><?php echo app('translator')->getFromJson('No image chosen'); ?></span><em style="color:red;">(Dimensions:- 370*300)</em> </div> <div class="form-group"> <?php echo Form::label('banner', __('Choose Banner'), ['class'=>'btn btn-default btn-file']); ?> <?php echo Form::file('banner',['class'=>'form-control bannerImage', 'style'=>'display: none;','onchange'=>'$("#upload-file-info1").html(files[0].name)']); ?> <?php if(isset($style->banner) && $style->banner!=''): ?> <?php echo Form::hidden('old_banner', isset($style->banner) ? $style->banner : '' ); ?> <img src="<?php echo e(asset('storage/style/banner/'.$style->banner)); ?>" height="50" width="50" class="center" > <?php endif; ?> <span id="imgError"></span> <span class='label label-info' id="upload-file-info1"><?php echo app('translator')->getFromJson('No image chosen'); ?></span><em style="color:red;">(Dimensions:- 1600*400)</em> </div> <div class="form-group"> <?php if(isset($style)): ?> <?php echo Form::submit(__('Update Style'), ['class'=>'btn btn-primary btn-block', 'id'=>'submit_button', 'name'=>'submit_button']); ?> <?php else: ?> <?php echo Form::submit(__('Add Style'), ['class'=>'btn btn-primary btn-block','id'=>'submit_button', 'name'=>'submit_button']); ?> <?php endif; ?> </div> <?php echo Form::close(); ?> </div> </div>