 
 

 
 
 
    <div class="form-group">
        <div class="style_box">
            <label for="style_id"><?php echo app('translator')->getFromJson('Style:'); ?></label>
            <select name="style_id[]" id="style_id" placholder="Choose Style" multiple>

                <option value=""><?php echo app('translator')->getFromJson('Choose Style'); ?></option>
                    <?php if(!empty($styleArr)): ?>
                        <?php $__currentLoopData = $styleArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                            <option value="<?php echo e($value['id']); ?>"  ><?php echo e($value['name']); ?> </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
            </select>
        </div>
    </div>
    