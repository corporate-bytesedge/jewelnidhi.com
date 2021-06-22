
<div class="col-md-6">
    <?php if(session()->has('category_created')): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong><?php echo e(session('category_created')); ?></strong>
        </div>
    <?php endif; ?>

    <?php echo $__env->make('includes.form_errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php echo Form::open(['method'=>'post', 'action'=>'ManageCategoriesController@store','id'=>'category_form', 'files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']); ?>


    <div class="form-group<?php echo e($errors->has('category') ? ' has-error' : ''); ?>">
        <?php echo Form::label('name', __('Category Name:')); ?>

        <?php echo Form::text('name', null, ['class'=>'form-control', 'placeholder'=>__('Enter category name'), 'required']); ?>

    </div>
    <?php echo Form::hidden('parent', 0, []); ?>

    

    <div class="form-group<?php echo e($errors->has('priority') ? ' has-error' : ''); ?>">
    <?php echo Form::label('priority', __('Order No: (to display under Menus)')); ?>

        <?php echo Form::number('priority', 1, ['class'=>'form-control', 'placeholder'=>__('Enter order by'), 'min'=>'1']); ?>

    </div>

    <div class="form-group<?php echo e($errors->has('top_category_priority') ? ' has-error' : ''); ?>">
        <?php echo Form::label('top_category_priority', __('Order No: (to display under Top Categories Home Page)')); ?>

        <?php echo Form::number('top_category_priority', 1, ['class'=>'form-control', 'placeholder'=>__('Enter order by top category'), 'min'=>'1']); ?>

    </div>

    <div class="form-group<?php echo e($errors->has('min_price') ? ' has-error' : ''); ?>">
        <?php echo Form::label('min_price', __('Min Price:')); ?>

        <?php echo Form::number('min_price', null, ['class'=>'form-control','step'=>'5000', 'placeholder'=>__('Enter Min Price')]); ?>

    </div>

    <div class="form-group<?php echo e($errors->has('max_price') ? ' has-error' : ''); ?>">
        <?php echo Form::label('max_price', __('Max Price:')); ?>

        <?php echo Form::number('max_price', null, ['class'=>'form-control','step'=>'5000', 'placeholder'=>__('Enter Max Price')]); ?>

    </div>

    <!-- <div class="form-group<?php echo e($errors->has('above_price') ? ' has-error' : ''); ?>">
        <?php echo Form::label('above_price', __('Above Max Price:')); ?>

        <?php echo Form::number('above_price', null, ['class'=>'form-control', 'placeholder'=>__('Enter Above Price'), 'min'=>'1']); ?>

    </div> -->

    <!-- <div class="checkbox">
        <label>
            <input type="checkbox" name="show_in_menu"> <strong><?php echo app('translator')->getFromJson('Show in Main Menu'); ?></strong>
        </label>
    </div> -->

    <!-- <div class="checkbox">
        <label>
            <input type="checkbox" name="show_in_footer"> <strong><?php echo app('translator')->getFromJson('Show in Footer Menu'); ?></strong>
        </label>
    </div> -->

    <div class="checkbox">
        <label>
            <input type="checkbox" name="show_in_slider" value="1"> <strong><?php echo app('translator')->getFromJson('Show in Top Categories'); ?></strong>
        </label>
    </div>

    <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_price" value="1">  <strong><?php echo app('translator')->getFromJson('Show Filter Price'); ?></strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_metal" value="1"> <strong><?php echo app('translator')->getFromJson('Show Filter Metal'); ?></strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_purity" value="1"> <strong><?php echo app('translator')->getFromJson('Show Filter Purity'); ?></strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_gender" value="1"> <strong><?php echo app('translator')->getFromJson('Show Filter Gender'); ?></strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_offers" value="1"> <strong><?php echo app('translator')->getFromJson('Show Filter Offers'); ?></strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_short_by" value="1"> <strong><?php echo app('translator')->getFromJson('Show Filter Sort By'); ?></strong>
                </label>
                
            </div>
            <div class="form-group">
                <?php echo Form::label('category_img', __('Choose Category Image'), ['class'=>'btn btn-default btn-file']); ?>

                <?php echo Form::file('category_img',['class'=>'form-control categoryImage', 'style'=>'display: none;','onchange'=>'$("#upload-file-info1").html(files[0].name)']); ?>

                <span id="CatimgError"></span>
                <span class='label label-info' id="upload-file-info1"><?php echo app('translator')->getFromJson('No image chosen'); ?></span><em style="color:red;">(Dimensions:- 370*300)</em>
            </div>

            <div class="form-group">
                    <?php echo Form::label('banner', __('Choose Banner Image'), ['class'=>'btn btn-default btn-file']); ?> 
                    <?php echo Form::file('banner',['class'=>'form-control bannerImage', 'style'=>'display: none;','onchange'=>'$("#upload-file-info").html(files[0].name)']); ?>

                    <span id="imgError"></span>
                    <span class='label label-info' id="upload-file-info"><?php echo app('translator')->getFromJson('No image chosen'); ?></span> <em style="color:red;">(Dimensions:- 1600*400)</em>
            </div>

            <div class="form-group<?php echo e($errors->has('status') ? ' has-error' : ''); ?>">
                <?php echo Form::label('status', __('Status:')); ?>

                <?php echo Form::select('status', [0=>__('inactive'), 1=>__('active')], 1, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']); ?>

            </div>


    


   

    <?php echo $__env->make('partials.manage.meta-fields', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <div class="form-group">
        <?php echo Form::submit(__('Add Category'), ['class'=>'btn btn-primary col-xs-6 col-sm-6 pull-left', 'id'=>'submit_button','name'=>'submit_button']); ?>

    </div>

    <?php echo Form::close(); ?>


</div>