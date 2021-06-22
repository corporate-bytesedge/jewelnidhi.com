<div class="section testimonial-sec" data-aos="fade-up" data-aos-once="true"> <div class="container"> <div class="heading-sec"> <h2>Testimonials</h2> <img src="<?php echo e(URL::asset('img/home_line1.png')); ?> " alt=""/> <span><?php echo app('translator')->getFromJson('What our customers say'); ?></span> </div> <?php if(count($testimonials) > 0): ?> <?php 
        $testimonialArray = array();
        
      ?> <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php
              if(isset($testimonial->photo->name) && $testimonial->photo->name!='') {
                $testimonialArray[$testimonial->id]['image'] = $testimonial->photo->name;
              } else {
                $testimonialArray[$testimonial->id]['image'] = '';
              }
              $testimonialArray[$testimonial->id]['review'] = $testimonial->review;
              $testimonialArray[$testimonial->id]['author'] = $testimonial->author;
            ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <div class="row"> <div class="col-12"> <div class="testimonial-thumb-wrapper"> <div class="testimonial-thumb-carousel"> <?php $__currentLoopData = $testimonialArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$testimoniall): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <div class="testimonial-thumb"> <?php if($testimoniall['image']): ?> <?php
                          $image_url = \App\Helpers\Helper::check_image_avatar($testimoniall['image'], 150, $default_photo);
                        ?> <img src="<?php echo e($image_url); ?>" alt=""/> <?php else: ?> <img src="https://via.placeholder.com/150x150?text=No+Image" alt="" /> <?php endif; ?> </div> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> </div> </div> <div class="testimonial-content-wrapper"> <div class="testimonial-content-carousel"> <?php $__currentLoopData = $testimonialArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonialsss): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <div class="testimonial-content"> <p> <?php echo e($testimonialsss['review']); ?></p> <h5 class="testimonial-author"><?php echo e($testimonialsss['author']); ?></h5> </div> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> </div> </div> </div> </div> <?php endif; ?> </div> </div>