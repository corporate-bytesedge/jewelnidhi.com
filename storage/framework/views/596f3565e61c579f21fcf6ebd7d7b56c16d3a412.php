<ul> <?php $__currentLoopData = $childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php 
        
        
        
        $product =  \App\Product::whereHas('product_category_styles', function ($query) use($child) {
                     $query->where('category_id',$child->category_id)->where('product_style_id', $child->id);
                    })->where(function ($query) {
                                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                    })->where('category_id',$child->category_id)->where('is_active','1')->count();

                    
        ?> <li> <?php echo e($child->name . ' ('.$product.')'); ?> </li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> </ul>