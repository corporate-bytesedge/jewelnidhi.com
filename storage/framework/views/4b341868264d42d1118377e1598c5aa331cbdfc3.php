<?php $__env->startSection('scripts'); ?>
  <script type="text/javascript">
var addWishlist;
var removeWishlist;
jQuery(document).ready(function() {

    addWishlist =function (id) {
      
          $.ajax({
            type:"POST",
            url:$("#product-favourite-form_"+id).attr('action'),
            data:{id:id},
            success:function(data) {
                $(".toast").toast({
                  autohide:true,
                  delay:2000
                });
                $(".toast").toast('show');
                $(".toast .toast-body").html('<i class="fa fa-check-circle"></i>&nbsp;&nbsp;Added to your wishlist');
                $("#removeBtn_"+id).removeAttr('hidden');
                $("#removeBtn_"+id).show();
                $("#addBtn_"+id).hide();
				var wishlistCount = parseInt($("#wishlistCount").text());
				$("#wishlistCount").text(wishlistCount + 1);
            } 
          });
         
       }
  
       removeWishlist = function (id) {
          $.ajax({
          type:"GET",
          url:"<?php echo e(route('front.product.favourite.destroywishlist')); ?>"+'?id='+id,
          success:function(data) {
              $(".toast").toast({
                 autohide:true,
                 delay:2000
                });
                 
              $(".toast").toast('show');
              $(".toast .toast-body").html('<i class="fa fa-check-circle"></i>&nbsp;&nbsp;Removed from your wishlist');
              $("#removeBtn_"+id).hide();
              $("#addBtn_"+id).removeAttr('hidden');
              $("#addBtn_"+id).show();
               var wishlistCount = parseInt($("#wishlistCount").text());
			$("#wishlistCount").text(wishlistCount - 1);
               
             } 
           });
       }

       

       

      
});
  </script>
<?php $__env->stopSection(); ?>
 
  <div class="row">
  
    <?php if(count($products) > 0 ): ?>
           
               
                   
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                          <div class="col-lg-3 col-md-4 col-6" id="itemList">
                              <div class="cat-item">
                              <?php if(isset($val-> product_discount) && $val-> product_discount!='0'): ?>
                                   <span class="offers-wrapper"> <span class="offer"> <span><?php echo e($val-> product_discount .'% '.$val-> product_discount_on); ?></span> </span> </span>
                              <?php endif; ?>
                                  <figure class="product-thumb">
                                      <a href="/product/<?php echo e($val->slug); ?>">
                                     
                                          <?php if(!empty($val->photo)): ?>

                                              <?php 
                                              
                                                if($val->photo->name) {
                                                  $featured = public_path().'/img/'.basename($val->photo->name);
                                                } 
                                                
                                              ?>

                                                <?php if(file_exists($featured)): ?>
                                                  
                                                  <?php
                                                  
                                                      $image_url = \App\Helpers\Helper::check_image_avatar($val->photo->name, 150);
                                                  ?>
                                                    <img class="<?php echo e($val->overlayphoto!='' ? 'pri-img' : ''); ?> " src="<?php echo e($image_url); ?>" alt="<?php echo e($val->photo->name); ?>">
                                                
                                                <?php else: ?>
                                                    <img src="<?php echo e(asset('img/noimage.png')); ?>" class="img-responsive product-feature-image" alt="" />
                                                
                                                
                                                    
                                                <?php endif; ?>
                                            <?php else: ?>
                                              <img src="<?php echo e(asset('img/noimage.png')); ?>" class="img-responsive product-feature-image" alt="" />
                                          <?php endif; ?>

                                          <?php if(!empty($val->overlayphoto)): ?>

                                            <?php 
                                                if($val->overlayphoto->name) {
                                                  $overlay = public_path().'/img/'.basename($val->overlayphoto->name);
                                                }
                                              ?>
                                              <?php if(file_exists($overlay)): ?>
                                                    <?php
                                                        $image_url = \App\Helpers\Helper::check_overlayimage_avatar($val->overlayphoto->name, 150);
                                                    ?>
                                                    <img class="sec-img" src="<?php echo e($image_url); ?>" alt="<?php echo e($val->overlayphoto->name); ?>">
                                            
                                              <?php endif; ?>
                                          <?php endif; ?>
                                      </a>
                                      <?php if(isset($val->is_new) && $val->is_new=='1'): ?>
                                        <div class="product-badge">
                                            <div class="product-label new">
                                                <span>new</span>
                                            </div>
                                        </div>
                                      <?php endif; ?>
                                      <div class="button-group">
                                            <?php if(\Auth::user()): ?>
                                            <?php
                                              $selected = false;
                                              foreach(\Auth::user()->favouriteProducts as $taxonomy) {
                                                if($taxonomy->id == $val->id )
                                                {
                                                $selected = true;
                                                }
                                              }
                                  
                                      
                                            ?> 

                                            <a href="javascript:void(0);" <?php echo $selected == true ? '' :'hidden' ?>  id="removeBtn_<?php echo e($val->id); ?>" data-toggle="tooltip" style="color:goldenrod " onclick="removeWishlist('<?php echo e($val->id); ?>')"   data-placement="left" title="Remove from your wishlist">
                                                <i class="fa fa-heart"></i>
                                            </a>
                                         
                                              <a href="javascript:void(0);" <?php echo $selected == false ? '' :'hidden' ?>   id="addBtn_<?php echo e($val->id); ?>" data-toggle="tooltip" data-placement="left" title="Add to wishlist" onclick="addWishlist('<?php echo e($val->id); ?>')">
                                                  <i class="fa fa-heart-o"></i>
                                              </a>
                                                
                                              <form id="product-favourite-form_<?php echo e($val->id); ?>" class="hidden" action="<?php echo e(route('front.product.favourite.store', $val->id)); ?>" method="POST">
                                                <?php echo e(csrf_field()); ?>

                                            
                                              </form>
                                            <?php else: ?>
                                            <span data-toggle="modal" data-target="#login-modal">
                                              <a href="javascript:void(0);" data-toggle="tooltip" data-placement="left" title="Add to wishlist"  >
                                                <i class="fa fa-heart-o"></i>          
                                              </a>
                                            </span>
                                                 
                                            <?php endif; ?>
                                          
                                      </div>
                                      <div class="cart-hover">
                                            <a href="/product/<?php echo e($val->slug); ?>"> <button class="btn btn-cart">View Details</button></a>
                                      </div>
                                  </figure>
                                    <div class="product-caption">
                      
                                    <?php if(isset($val->product_discount) && $val->product_discount!=''): ?>
                                        <div class="price-box">
                                        <?php if($val->old_price!='0'): ?>
                                            <span class="price-old">
                                              <del style="font-size: 11px;">
                                              <i class="fa fa-rupee"></i> <?php echo e(isset($val->old_price) && $val->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($val->old_price) : ''); ?>

                                              </del>
                                            </span>
                                          <?php endif; ?>  
                                            <span class="price-regular" style="font-size: 13px;"> 
                                            <i class="fa fa-rupee"></i> <?php echo e(isset($val->new_price) && $val->new_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($val->new_price) : ''); ?>

                                            </span>
                                        </div>
                                        <?php else: ?>
                                        <div class="price-box">
                                          <span class="price-regular" style="font-size: 13px;"> 
                                          <i class="fa fa-rupee"></i> <?php echo e(isset($val->old_price) && $val->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($val->old_price) : ''); ?>

                                          </span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(isset($val->old_price) && $val->old_price!=null && $val->product_discount!=''): ?>
                                      <div class="you-save">
                                        Save 
                                          <span style="font-size: 12px;">
                                          <i class="fa fa-rupee"></i> <?php echo e(isset($val->old_price) && $val->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($val->old_price - $val->new_price) : ''); ?>

                                          </span>
                                      </div>
                                    <?php endif; ?>
                                    </div>
                                  
                              </div>
                          </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php else: ?> 
                      <div class="col-lg-12 col-md-12 col-sm-12">
                      <h1 class="mb-2 text-center">Oops!</h1>
                      <h2 class="mb-5 text-center"> We could not find what you are looking for!</h2>
                      <a href="<?php echo e(url('/')); ?>" class="btn btn-primary ShoppingBtn ">CONTINUE SHOPPING</a>
                    
                  </div>
                     <?php endif; ?>
                
           
        </div>
        
     
      
     
 