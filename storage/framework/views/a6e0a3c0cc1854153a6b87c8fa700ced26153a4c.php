 

 <div class="section our-bestselling" data-aos="fade-up" data-aos-once="true">
    <div class="container">
    <div id="CartMsg"></div>
      <div class="heading-sec">
        <h2>Our Bestselling items</h2>
        <img src="<?php echo e(URL::asset('img/home_line.png')); ?>" alt=""/>
      </div>
      <div class="row">
        <div class="col-lg-12 columns">
          <div class="owl-carousel owl-theme">
              <?php if(!empty($best_selling_products)): ?>
                <?php $__currentLoopData = $best_selling_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php echo Form::open(['method'=>'patch', 'route'=>['front.cart.add', $value->id], 'id'=>'frontcart-form'.$value->id]); ?>

                  <?php 
                    if($value->photo->name) {
                      $file = public_path().'/img/'.basename($value->photo->name);
                    }
                  ?>
                    <div class="item">
                      <a href="/product/<?php echo e($value->slug); ?>">
                          <?php if(file_exists($file)): ?>
                              <?php
                                $image_url = \App\Helpers\Helper::check_image_avatar($value->photo->name, 200);
                              ?>
                                <img src="<?php echo e($image_url); ?>" alt=""  />
                            <?php else: ?>
                              <img src="https://via.placeholder.com/200x200?text=No+Image" alt=""   />
                          <?php endif; ?>
                          <!-- <p> <?php echo e(ucwords($value->name)); ?></p> -->

                          <div class="product-caption">

                              <?php if(isset($value->product_discount) && $value->product_discount!=''): ?>
                                  <div class="price-box">
                                  <?php if($value->old_price!='0'): ?>
                                      <span class="price-old">
                                          <del> 
                                          <i class="fa fa-rupee"></i> <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($value->old_price)); ?>

                                          </del>
                                        </span>
                                    <?php endif; ?>  
                                      <span class="price-regular"> 
                                      <i class="fa fa-rupee"></i> <?php echo e(isset($value->new_price) && $value->new_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($value->new_price) : ''); ?></span>
                                  </div>
                                  <?php else: ?>
                                  <div class="price-box">
                                    <span class="price-regular"> 
                                    <i class="fa fa-rupee"></i> <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($value->old_price)); ?>

                                    </span>
                                  </div>
                              <?php endif; ?>

                                        

                                <?php if(isset($value->old_price) && $value->old_price!=null && $value->product_discount!=''): ?>
                                  <div class="you-save">
                                    Save <span>
                                    <i class="fa fa-rupee"></i> <?php echo e(isset($value->old_price) && $value->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($value->old_price - $value->new_price) : ''); ?>

                                    </span>
                                  </div>
                                <?php endif; ?>
                              </div>
                          </a>
                    </div>
                  
                  <!-- <div class="add-to-cart">
                    <button class="btn btn-primary btn-xs" id="add_to_cart" data-product_id = "<?php echo e($value->id); ?>" name="submit_button" type="button"><?php echo app('translator')->getFromJson('Add to Cart'); ?></button>
                  </div> -->
                  <?php echo Form::close(); ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php endif; ?>
               
            </div>
            
              <script>
                $(document).ready(function() {
                  var owl = $('.owl-carousel');
                  owl.owlCarousel({
                    items: 4,
                    loop: true,
                    margin: 25,
                    autoplay: true,
                    autoplayTimeout: 4000,
                    autoplayHoverPause: true,
                    nav:true,
                    navText : ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
                    responsive:{
                        0:{
                            items:1,
                            nav:true
                        },
                        600:{
                            items:2
                            
                        },
                        1000:{
                            items:4
                            
                        }
                      }
                  });
                   
                });
              </script>
        </div>
      </div>
  </div>
</div>


     

  <?php echo $__env->make('includes.cart-submit-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
 