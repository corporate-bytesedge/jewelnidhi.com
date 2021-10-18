 <?php $__env->startSection('title'); ?><?php echo e(isset($product->meta_title) ? $product->meta_title : $product->name." - ".config('app.name')); ?><?php $__env->stopSection(); ?> <?php $__env->startSection('meta-tags'); ?> <meta name="description" content="<?php echo e($product->meta_desc ? $product->meta_desc : StringHelper::truncate(trim(strip_tags($product->description)), 160)); ?>"> <?php if($product->meta_keywords): ?> <meta name="keywords" content="<?php echo e($product->meta_keywords); ?>"> <?php endif; ?> <?php $__env->stopSection(); ?> <?php $__env->startSection('meta-tags-og'); ?> <meta property="og:url" content="<?php echo e(url()->current()); ?>" /> <meta property="og:type" content="website" /> <meta property="og:title" content="<?php echo e($product->meta_title ? $product->meta_title : $product->name.' - '.config('app.name')); ?>" /> <meta property="og:description" content="<?php echo e($product->meta_desc ? $product->meta_desc : StringHelper::truncate(trim(strip_tags($product->description)), 160)); ?>" /> <?php if($product->photo): ?> <meta property="og:image" content="<?php echo e($product->photo->name); ?>" /> <?php endif; ?> <?php $__env->stopSection(); ?> <?php $__env->startSection('styleg'); ?> <link rel="stylesheet" href="<?php echo e(asset('css/nice-select.css')); ?>" /> <style> #shipping_success { color:green !important; } .error { color:red; } </style> <?php $__env->stopSection(); ?> <?php $__env->startSection('above_container'); ?><?php $__env->stopSection(); ?> <?php $__env->startSection('content'); ?> <div class="breadcrumb-sec"> <div class="container"> <ol class="breadcrumb"> <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li> <?php if(isset($product->category->name) && $product->category->name!=''): ?> <li class="breadcrumb-item"><a href="/category/<?php echo e(strtolower($product->category->name)); ?>"><?php echo e($product->category->name); ?></a></li> <?php endif; ?> <?php if(isset($category->name) && $category->name!=''): ?> <li class="breadcrumb-item"><a href="/category/<?php echo e(strtolower($category->slug)); ?>"><?php echo e($category->name); ?></a></li> <?php endif; ?> <?php if(isset($product->name) && $product->name!=''): ?> <li class="breadcrumb-item active" aria-current="page"><?php echo e($product->name); ?> </li> <?php endif; ?> </li> </ol> </div> </div> <?php if(session()->has('msg')): ?> <hr> <div class="alert alert-danger alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <span class="glyphicon glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> <strong>&nbsp;<?php echo app('translator')->getFromJson('Error:'); ?></strong> <?php echo e(session('msg')); ?> </div> <?php endif; ?> <div class="product-detail-page"> <div class="cart-message"></div> <div class="container"> <?php echo Form::open(['method'=>'patch', 'route'=>['front.cart.add', $product->id], 'id'=>'cart-form']); ?> <?php echo e(Form::hidden('product_id',isset($product->id) ? $product->id : '',['id'=>'product_id'])); ?> <?php echo e(Form::hidden('new_price',isset($product->new_price) ? $product->new_price : $product->old_price,['id'=>'new_price'])); ?> <div class="row"> <div class="col-md-5"> <div class="img-section"> <div class="sp-img_area"> <div class="zoompro-border"> <?php if(!empty($product->photo)): ?> <?php 
                          if($product->photo->name) {
                            $featured = public_path().'/img/'.basename($product->photo->name);
                          }
                        ?> <?php if(file_exists($featured)): ?> <?php
                                $image_url = \App\Helpers\Helper::check_image_avatar($product->photo->name, 80);
                            ?> <img class="zoompro" src="<?php echo e($image_url); ?>" data-zoom-image="<?php echo e($image_url); ?>" alt="" /> <?php else: ?> <img src="<?php echo e(asset('img/noimage.png')); ?>" class="img-responsive" width=80 height=80 alt="" /> <?php endif; ?> <?php else: ?> <img src="<?php echo e(asset('img/noimage.png')); ?>" class="img-responsive" width=80 height=80 alt="" /> <?php endif; ?> </div> <div id="gallery" class="sp-img_slider"> <?php if(!empty($product->photo)): ?> <?php 
                    if($product->photo->name) {
                      $thumbfeatured = public_path().'/img/'.basename($product->photo->name);
                    }
                    ?> <?php if(file_exists($thumbfeatured)): ?> <?php
                            $image_url = \App\Helpers\Helper::check_image_avatar($product->photo->name, 80);
                          ?> <a href="<?php echo e($image_url); ?>" class=" " data-image="<?php echo e($image_url); ?>" data-zoom-image="<?php echo e($image_url); ?>"> <img src="<?php echo e($image_url); ?>" alt=""> </a> <?php endif; ?> <?php endif; ?> <?php if(!empty($product->overlayphoto)): ?> <?php 
                      if($product->overlayphoto->name) {
                        $thumboverlay = public_path().'/img/'.basename($product->overlayphoto->name);
                      } 
                    ?> <?php if(file_exists($thumboverlay)): ?> <?php 
                            $overlayimage_url = \App\Helpers\Helper::check_image_avatar($product->overlayphoto->name, 80);
                          ?> <a href="<?php echo e($overlayimage_url); ?>" class=" " data-image="<?php echo e($overlayimage_url); ?>" data-zoom-image="<?php echo e($overlayimage_url); ?>"> <img src="<?php echo e($overlayimage_url); ?>" alt=""> </a> <?php endif; ?> <?php endif; ?> <?php if(count($product->photos) > 0): ?> <?php $__currentLoopData = $product->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($photo->name): ?> <?php
                                    if($photo->name) {
                                      $thumb = public_path().'/img/'.basename($photo->name);
                                    }
                                  ?> <?php if(file_exists($thumb)): ?> <?php
                                        $image_url = \App\Helpers\Helper::check_image_avatar($photo->name, 80);
                                      ?> <a href="<?php echo e($image_url); ?>" class=" <?php echo e($k == '0' ? 'active' : ''); ?> " data-image="<?php echo e($image_url); ?>" data-zoom-image="<?php echo e($image_url); ?>"> <img src="<?php echo e($image_url); ?>" alt=""> </a> <?php endif; ?> <?php else: ?> <img src="<?php echo e(asset('img/80x80.png')); ?>" class="img-responsive" width=80 height=80 alt="" /> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?> </div> </div> <?php if(count($product->certificate_products) > 0): ?> <div class="certification"> <span>Certified By</span> <ul> <?php $__currentLoopData = $product->certificate_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php if($value->image): ?> <li><img src="<?php echo e(asset('storage/certificate/'.$value->image)); ?>" alt="<?php echo e(strtoupper($value->name)); ?>"/> </li> <?php else: ?> <li><?php echo e(strtoupper($value->name)); ?></li> <?php endif; ?> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> </ul> </div> <?php endif; ?> </div> </div> <div class="col-md-7"> <div class="product-details-desc"> <div class="d-flex"> <?php if(\Auth::user()): ?> <?php
                      $selected1 = false;
                      foreach(\Auth::user()->favouriteProducts as $taxonomy1) {
                        if($taxonomy1->id == $product->id ) {
                          $selected1 = true;
                          }
                        }
                    ?> <a href="javascript:void(0);" <?php echo $selected1 == true ? '' :'hidden' ?> id="removeProductBtn_<?php echo e($product->id); ?>" data-toggle="tooltip" style="color:goldenrod " onclick="removeProductWishlist('<?php echo e($product->id); ?>')" data-placement="left" title="Remove from your wishlist"> <i class="fa fa-heart"></i> </a> <a href="javascript:void(0);" <?php echo $selected1 == false ? '' :'hidden' ?> id="addProductBtn_<?php echo e($product->id); ?>" data-toggle="tooltip" data-placement="left" title="Add to wishlist" onclick="addProductWishlist('<?php echo e($product->id); ?>')"> <i class="fa fa-heart-o"></i> </a> <?php else: ?> <span data-toggle="modal" data-target="#login-modal"> <a href="javascript:void(0);" data-toggle="tooltip" data-placement="left" title="Add to wishlist" > <i class="fa fa-heart-o"></i> </a> </span> <?php endif; ?> <h3 class="product-name"><?php echo e(isset($product->name) && $product->name!='' ? $product->name : ''); ?></h3> </div> <div class="price-charge"> <?php if(isset($product->product_discount) && $product->product_discount!=''): ?> <div class="price-box"> <?php if($product->old_price): ?> <span class="price-old productPriceOld"><del> ₹ <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price)); ?></del></span> <?php endif; ?> <span class="price-regular productPriceRegular">₹ <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format($product->new_price); ?></span> </div> <?php else: ?> <div class="price-box"> <span class="price-regular productPriceRegular"> <?php
                  /*  $amount = '100000';
                      setlocale(LC_MONETARY, 'en_IN');
                      $amount = money_format('%!i', $amount);
                      echo $amount; 
                      */
                  ?>
                  ₹ <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price)); ?>

                  </span>
                </div>
           <?php endif; ?>

           <?php if(isset($product-> product_discount) && $product-> product_discount!='0'): ?>
           <div class="making-charge">
              <span> <?php echo e($product-> product_discount .'% '.$product-> product_discount_on); ?>  </span>
           </div>
           <?php endif; ?>
          </div>
          
            <div class="store-avaliable">
              <div class="your-pincode">
                <div class="static-text">Your Pincode</div>
                <div class="find-nearest-store">
                  <input class="form-control" type="text" name="pincode" id="shipping_pincode"   placeholder="Please enter your pincode"/>
                  <button class="btn btn-primary" type="button" id="checkShippingAvailbility" onclick="checkShippingAvailability()">Update</button>
                </div>
              </div>
              <div class="delivery-info">
                <p class="text-success" id="shipping_success" style="display: none;">* We ship to your entered pincode</p>
                <p class="text-danger" id="shipping_error" style="display: none;">* Sorry, we don't ship to your entered pincode </p>
                <!-- <p>Delivery By <b>Thu, Jun 25</b> for <b> Pincode 302016 </b></p> -->
              </div>
            </div>
              
            <?php if( ($product->category->name == 'Gold' || $product->category->name == 'Diamond'  || $product->category->name == 'Platinum' || $product->category->name == 'Silver Articles' || $product->category->name == 'Silver Jewellery') && $category->name == 'Rings' ): ?>
              <a href="<?php echo e(route('front.product.sizeguide')); ?>" class="sizeguide"   target="_blank"><strong>Size Guide</strong></a>
            <?php endif; ?>
            <div class="d-flex flex-wrap">
             
           
              <?php if( !empty(array_filter($groupSizeArr))): ?>
                  <?php 
                    $defaultSize = ''; 
                    $pivotVal = array();
                    $array = array(); 
                    $uniqueSize = array();
                    
                  ?>
                  <div class="pro-size mr-3">

                      <div class="d-flex justify-content-between groupVal">
                      <h6 class="option-title">Size :</h6>
                     
                      
                    
                    
                      </div>
                  
                    <select class="nice-select groupVal">
                      <option value="">Select Size</option>
                      <?php $__currentLoopData = $groupSizeArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php 
                            $defaultSize = isset($value->product_group_default) ? $value->product_group_default : '' ;
                            
                          ?>

                            <?php if(!empty($value->specificationTypes)): ?>
                            
                                <?php $__currentLoopData = $value->specificationTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ky => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  
                                  <?php if($value->group_by_size == $val->pivot->specification_type_id && !in_array($val->pivot->value,$uniqueSize)): ?>
                                      <?php
                                      array_push($uniqueSize,$val->pivot->value);
                                         
                                      ?>
                                       <option <?php echo ($defaultSize==1 ? 'selected="selected"' : '' ) ?> data-product_id = "<?php echo e($value->id); ?>" value="<?php echo e($val->pivot->value); ?>"><?php echo e($val->pivot->value); ?></option>
                                  <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                     
                      
                        
                     
                    </select>
                  </div>
              <?php endif; ?>
             
            <?php if($product->category->name == 'Diamond'): ?>
           
                 
                <?php if( !empty(array_filter($groupPurityArr))): ?>
                  <?php 
                    $defaultPurity = ''; 
                    $pivotVal = array();
                    $array = array(); 
                    $uniquePurity = array();
                  ?>
                    <div class="pro-size ">
                      <h6 class="option-title">Purity :</h6>
                      <select class="nice-select groupVal">
                        <option value="">Select Purity</option>
                        <?php $__currentLoopData = $groupPurityArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php 
                              $defaultPurity = isset($value->product_group_default) ? $value->product_group_default : '' ; 
                            ?>
                            <?php if(!empty($value->specificationTypes)): ?>
                              <?php $__currentLoopData = $value->specificationTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ky => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($value->group_by_purity == $val->pivot->specification_type_id && !in_array($val->pivot->value,$uniquePurity)): ?>
                                    <?php
                                      array_push($uniquePurity,$val->pivot->value);
                                    ?>
                                    <option <?php echo ($defaultPurity==1 ? 'selected="selected"' : '' ) ?> data-product_id = "<?php echo e($value->id); ?>" value="<?php echo e($val->pivot->value); ?>"><?php echo e($val->pivot->value); ?></option>
                                <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                      </select>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
			    </div>
          
          <?php if($product->in_stock !='0'): ?>

                
          <div class="add-to-cart">
                  <?php if( (!isset($product->vendor->user_id) || \Auth::user()==null) || (\Auth::user()->id!=$product->vendor->user_id)  ): ?> 
                    
                    <button class="btn btn-primary" id="add_cart" name="submit_button" type="button"><?php echo app('translator')->getFromJson('Add to Cart'); ?></button>
                
                      <?php if(\Auth::check()): ?> 
                            
                        <button class="btn btn-primary buyNow" id="buy_now" name="buynow_button" value="buynow" type="submit"><?php echo app('translator')->getFromJson('Buy Now'); ?></button>
                        
                      <?php else: ?>
                          <a href="javascript:void(0)" class="btn btn-primary buyNow" data-toggle="modal" data-target="#login-modal"><?php echo app('translator')->getFromJson('Buy Now'); ?></a> 
                      <?php endif; ?>
                  <?php endif; ?>
            </div>
          <?php else: ?>
          <label><h4 style="color: red;font-size: 18px"><?php echo 'Out of stock' ?><h4></label>
                        
          <?php endif; ?>
            
           <div class="d-f-detail">
           

            <?php if(isset($product->description) && $product->description!=''): ?>
            <p class="notes"><?php echo e(isset($product->description) && $product->description!='' ? strip_tags($product->description) : ''); ?></p>
            <?php endif; ?>

            <?php if($product->metal_id == '2' || $product->metal_id == '14' ): ?>
              <p class="notes productNote">Slight variations in gold weight may be experienced and would be applicable on final prices. For the clear visibility of the product design, few products may appear bigger or smaller than the actual size, though we always ensure to provide the accurate information.</p>
            <?php else: ?>
            <p class="notes productNote">For the clear visibility of the product design, few products may appear bigger or smaller than the actual size, though we always ensure to provide the accurate information</p>
            <?php endif; ?>

              
            </div>
        </div>
        </div>
     </div>
     <?php echo Form::close(); ?>

<div class="product-description">

     
 
     <div class="row">
       <div class="col-md-6">
         <div class="product-desc">
           <h4 align="center">Product Details</h4>
          
           <!-- <table class="table table-bordered detail">
             <tr>
               <td>Product Code</td>
               <td id="sku"><?php echo e(isset($product->sku) && $product->sku!='' ? $product->sku : ''); ?></td>
             </tr>
             <?php if(isset($product->product_height)): ?>
             <tr>
              <td>Height</td>
              <td id="product_height"><?php echo e(number_format($product->product_height)); ?> mm</td>
            </tr>
            <?php endif; ?>

            <?php if(isset($product->product_width)): ?>
            <tr>
              <td>Width</td>
              <td id="product_width"><?php echo e(number_format($product->product_width)); ?> mm</td>
            </tr>
            <?php endif; ?>

            <?php if(isset($product->total_weight)): ?>
            <tr>
              <td>Product Weight (Approx.)</td>
              <td id="total_weight"><?php echo e(number_format($product->total_weight)); ?>  gram</td>
            </tr>
            <?php endif; ?>
            
           </table> -->

            <div  id="metalInfo">
              <div class="table-responsive" >
                  <h6> <strong>Metal Information</strong> </h6>
                <table class="table table-bordered detail ">
                  <?php if($product->jn_web_id): ?>
                  <tr>
                    <td> JN Web ID </td>
                    <td> <?php echo e(isset($product->jn_web_id) ? $product->jn_web_id : ''); ?> </td>
                  </tr>
                  <?php endif; ?>
                  <!-- <tr>
                    <td> Metal </td>
                    <td> <?php echo e(isset($product->metal->name) ? strtoupper($product->metal->name) : ''); ?> </td>
                  </tr> -->
                 
                  
                  
                    
                    <?php if($product->specificationTypes): ?>
                      <?php $__currentLoopData = $product->specificationTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php if($value->id =='42' || $value->id =='9' || $value->id =='21' || $value->id=='11' || $value->id=='28' || $value->id=='12' || $value->id=='13' || $value->id=='10' || $value->id =='25' || $value->id =='41' || $value->id =='29' || $value->id =='37' || $value->id =='39' || $value->id =='53'   || $value->id =='35' || $value->id =='30' || $value->id =='31'  || $value->id =='32'  || $value->id =='34' || $value->id =='67' || $value->id =='68' || $value->id =='66' || $value->id =='69' || $value->id =='20'  || $value->id =='26' || $value->id =='58' || $value->id =='54' || $value->id == '19' || $value->id == '77' || $value->id == '78'): ?>
                        
                            <tr>
                              <td><?php echo e($value['name'] ? $value['name'] : ''); ?> </td>
                              <td id="<?php echo e(isset($value['name']) ? str_replace(' ', '-', $value['name']) : ''); ?>"><?php echo e($value->pivot->value); ?> <?php echo e($value->pivot->unit); ?></td>
                            </tr>
                          <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                    

                    
                     
                  </table>
              </div>
 
                <div class="table-responsive">
                    <h6> <strong>Weight Details</strong> </h6>
                      <table class="table table-bordered detail ">
                         
                    <?php if($product->specificationTypes): ?>
                      <?php 
                      $weightRowID = '';
                      $weightId = ''; ?>
                      
                      <?php $__currentLoopData = $product->specificationTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php if($value->id =='14' || $value->id =='15' || $value->id =='23'  || $value->id =='38' || $value->id =='59' || $value->id=='27' || $value->id == '40' || $value->id == '52' || $value->id == '55' || $value->id == '57' || $value->id == '70' || $value->id == '74' || $value->id == '76'  ): ?>
                          <?php $weightId =  isset($value['name']) ? str_replace(' ', '-', $value['name']) : '' ;
                              
                           ?> 
                                <tr class="<?php echo e($weightId); ?>">
                                  <td><?php echo e($value['name'] ? ucwords($value['name']) : ''); ?>  </td>
                                  <td id="<?php echo e($weightId); ?>"> <?php echo e($value->pivot->value); ?> <?php echo e($value->pivot->unit); ?></td>
                                
                                </tr>
                          <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                  </table>
                </div>

            
           
           <h6><strong>Price Breakup</strong></h6>
           <table class="table table-bordered detail">

           <?php
            $total = 0;
           
           
           ?>
            
                <?php if(isset($product->price) && $product->price!='0'): ?>
                  <tr>
                    <td> 
                    
                    <?php echo e((  isset($product->metal->name) &&  $product->metal->name =='Gold' ||  $product->metal->name =='Diamond' ? "Gold" : "Metal")); ?>

                     Price </td>
                    <td id="gold_price"><i class="fa fa-rupee"></i>  
                    <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($product->price)); ?>

                    
                    </td>
                  </tr>
                <?php endif; ?>
                
                <?php if(isset($product->diamond_price) && $product->diamond_price!='0'): ?>
                  <tr>
                    <td>Diamond Price</td>
                    <td id="diamond_price"><i class="fa fa-rupee"></i> <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($product->diamond_price)); ?>

                    
                    </td>
                  </tr>
                <?php endif; ?>


                <?php if($product->specificationTypes): ?>
                    <?php $priceId = ''; ?>
                    
                      <?php $__currentLoopData = $product->specificationTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           
                      
                            <?php if($value->id =='16'  || $value->id =='46' || $value->id =='36' || $value->id =='24'     || $value->id =='33' ): ?>
                                <?php $priceId =  isset($value['name']) ? str_replace(' ', '-', $value['name']) : '' ;
                                
                                ?> 
                                
                                    <tr>
                                      <td><?php echo e($value['name'] ? ucwords($value['name']) : ''); ?>  </td>
                                      <td id="<?php echo e($priceId); ?>"> <i class="fa fa-rupee"></i>  <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($value->pivot->value)); ?> </td>
                                    
                                    </tr>
                              
                            <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

                <?php if(isset($product->vat_rate) && $product->vat_rate!='0'): ?>
                  <tr>
                    <td>VA</td>
                    <td id="vat_rate"><i class="fa fa-rupee"></i>  <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($product->vat_rate)); ?>

                    </td>
                  </tr>
                <?php endif; ?>

                <?php if(isset($product->gst_three_percent) && $product->gst_three_percent!='0'): ?>
                  <tr>
                    <td>GST</td>
                    <td id="gst_three_percent"><i class="fa fa-rupee"></i> <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($product->gst_three_percent)); ?>

                    
                    </td>
                  </tr>
                <?php endif; ?>
                
                
                <?php if(isset($product->product_discount) && $product->product_discount != '' && $product->product_discount != '0'): ?>
                  <?php if($product->subtotal!='' && $product->subtotal!='0'): ?>

                  <?php 
                    $subTotal = $product->subtotal + $product->gst_three_percent;
                   
                  ?>
                    <tr>
                      <td>Subtotal</td>
                      <td id="subtotal"><i class="fa fa-rupee"></i> <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($subTotal)); ?></td>
                    </tr>
                  <?php endif; ?>
                  <tr>
                    <td>Discount</td>
                    <td id="discount_price"><i class="fa fa-rupee"></i> <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price - $product->new_price)); ?>

                    </td>
                  </tr>
                <?php endif; ?>

               
          
          
               
 
                <?php if(isset($product->old_price) && $product->old_price!='0'): ?>
                  <tr>
                    <td>Total</td>
                    <?php if(isset($product->product_discount) && $product->product_discount!=''): ?> 
                       
                      <td id="total_price">₹ <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($product->new_price)); ?> </td>
                    <?php else: ?>
                      <td id="total_price">₹ <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price)); ?>

                      </td>
                    <?php endif; ?>
                  
                  </tr>
                <?php endif; ?>
           </table>
          </div>

         </div>
       </div>

       <div class="col-md-6">
         <div class="jewel-promise">
           <h4>JEWELNIDHI BENEFITS</h4>
           <div class="policy-detail">
             <ul>
               <li>
                 <i class="fa fa-sign-out"></i>
                 <span>15-Day Money Back</span>
               </li>
               <li>
                <i class="fa fa-support"></i>
                <span>Lifetime Exchange </span>
              </li>
              <li>
                <i class="fa fa-certificate"></i>
                <span>Lifetime Buyback</span>
              </li>
              <li>
                <i class="fa fa-certificate"></i>
                <span>Certified Jewellery</span>
              </li>
              <li>
                <i class="fa fa-truck"></i>
                <span>Free Shipping</span>
              </li>
              <!-- <li>
                <i class="fa fa-thumbs-up"></i>
                <span>100% Refund</span>
              </li> -->
              
              <li>
                <i class="fa fa-heart"></i>
                <span>100% Returns</span>
              </li>
             </ul>
           </div>
         </div>

          

         <div class="have-question">
         
           <h4>Have a Question?</h4>
           <p>Call us at <?php if(config('settings.contact_number')): ?><?php echo e(config('settings.contact_number')); ?><?php endif; ?></p>
           <span id="enquiryMsg" ></span>
            <?php echo Form::open(['method'=>'post', 'route'=>'front.enquiry', 'id'=>'enquiryform', 'onsubmit'=>' submit_button.value = "' . __('Please Wait...') . '"; return true;']); ?>

            <?php echo Form::hidden('product_id', $product->id , []); ?>

              
              <div class="form-group">
                <?php echo Form::label('name', __('Name:')); ?>

                <?php echo Form::text('name', null , ['class'=>'form-control','id'=>'name','placeholder'=>__("Enter Name")]); ?>

                 
              </div>
              <div class="form-group">
                <?php echo Form::label('email', __('Email:')); ?>

                <?php echo Form::email('email', null , ['class'=>'form-control','id'=>'email','placeholder'=>__("Enter Email")]); ?>

                 
              </div>
              <div class="form-group">
                <?php echo Form::label('phone', __('Mobile:')); ?>

                <?php echo Form::text('phone', null , ['class'=>'form-control','id'=>'phoneno','placeholder'=>__(" Enter Mobile Number ")]); ?>

                  <span id="errormsg" style="color:#D3A012; margin-left:100px;"></span>
              </div>
              <div class="form-group textarea">
                <?php echo Form::label('query', __('Query:')); ?>

                <?php echo Form::textarea('query', null , ['class'=>'form-control','id'=>'query','rows'=> '2','placeholder'=>__(" Enter Query ")]); ?>

            
              </div>
              <button class="btn btn-primary" id="enquiryBtn" type="submit">Submit</button>
            <?php echo e(Form::close()); ?>

         </div>
         <div class="d-f-detail mt-3">
            <p>Redeem a <span style="font-weight:bold;font-size:16px;">WELCOME BONUS</span> of <span style="color:#DAA520; font-size:20px; font-weight:bold;">Rs.250/-</span> on your purchase.</p>
          </div>
       </div>

     </div>
    </div>
    </div>

    <!-- related products area start -->
    <?php if(count($similarProduct) > 0): ?>

      <section class="section category-page related-products ">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="heading-sec">
                      <h2>Similar Products</h2>
                      
                      <img src="<?php echo e(URL::asset('img/home_line.png')); ?>" alt=""/>
                      
                    </div>
                    <span><center>
                    
                    <?php if(session()->has('wishlist_msg')): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong><?php echo e(session('wishlist_msg')); ?></strong> 
            </div>
        <?php endif; ?>

                    </center></span>
                </div>
                
            </div>
              
            <div class="row">
                <div class="col-12">
                    <div class="product-carousel-4 slick-row-10 slick-arrow-style">
                    
                      <?php $__currentLoopData = $similarProduct; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="cat-item">
                          
                            <figure class="product-thumb">
                                    <?php if(!empty($val->photo)): ?>

                                      <?php 
                                        if($val->photo->name) {
                                          $featured = public_path().'/img/'.basename($val->photo->name);
                                        }
                                      ?>
                                      <?php if(file_exists($featured)): ?>
                                          <?php
                                            $image_url = \App\Helpers\Helper::check_image_avatar($val->photo->name, 80);
                                          ?>
                                            <img src="<?php echo e($image_url); ?> " alt="product-details" />
                                      <?php else: ?>
                                          <img src="<?php echo e(asset('img/80x80.png')); ?>" class="img-responsive" width=80 height=80 alt="" />
                                      <?php endif; ?>
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
                                  
                                  <a href="javascript:void(0);" <?php echo $selected == true ? '' :'hidden' ?> data-toggle="tooltip" id="removeBtn_<?php echo e($val->id); ?>" style="color:goldenrod " onclick="removeWishlist('<?php echo e($val->id); ?>')"   data-placement="left" title="Removed from your wishlist">
                                        <i class="fa fa-heart"></i>
                                    </a>
                                  
                                  <a href="javascript:void(0);" <?php echo $selected == false ? '' :'hidden' ?> class="wishlistProduct1"  id="addBtn_<?php echo e($val->id); ?>" data-toggle="tooltip" data-product_id = "<?php echo e($val->id); ?>"  onclick="addWishlist('<?php echo e($val->id); ?>')"   data-placement="left" title="Add to wishlist">
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
                                <a href="/product/<?php echo e($val->slug); ?>"><button class="btn btn-cart">View Details</button></a>
                              </div>
                          </figure>

  
                          <div class="product-caption">

                              <?php if(isset($val->product_discount) && $val->product_discount!=''): ?>
                                  <div class="price-box">
                                  <?php if($val->old_price!=''): ?>
                                      <span class="price-old"><del>
                                        ₹ <?php echo e(isset($val->old_price) && $val->old_price!='' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($val->old_price) : ''); ?></del></span>
                                  <?php endif; ?>  
                                      <span class="price-regular"> 
                                        ₹ <?php echo e(isset($val->new_price) && $val->new_price!='' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($val->new_price) : ''); ?></span>
                                    </div>
                              <?php else: ?>
                                  <div class="price-box">
                                      <span class="price-regular">
                                        ₹ <?php echo e(isset($val->old_price) && $val->old_price!='' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($val->old_price) : ''); ?>

                                      </span>
                                  </div>
                              <?php endif; ?>
                      
                                <?php if(isset($val->old_price) && $val->old_price!=null && $val->product_discount!=''): ?>
                                      <div class="you-save">
                                        Save <span>
                                        ₹ <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($val->old_price - $val->new_price)); ?>

                                        </span>
                                      </div>
                                <?php endif; ?>
                            
                          </div>
                        </div>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                      
                    
                </div>
            </div>
        </div>
      </section>
   

  <?php endif; ?>
  <!-- related products area end -->
  </div>

   <!-- Modal -->
<div class="modal fade " id="exampleModal-bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
             
<div class="container">

<div class="size-table">
  <div class="content">
    <h4 style="color:#cd1f0f;"><u>JewelNidhi Ring Size Guide</u></h4>
    <p>
      JewelNidhi ring size handbook would assist you to measure your ring size right at
      home so you can get your perfect ring to wear comfortably. Below are the common
      and easy ways to measure your ring size with minimal and available things at home.
    </p>
    <h5 style="color:#cd1f0f;">Method #1 : Knowing your ring size with a string or thread or strip of paper</h5>
    <ul>
      <li>Take a thin non-elastic string or thread or strip of paper</li>
      <li> Wrap it around your finger</li>
      <li>Use a pen to mark the non-elastic string or thread or strip of paper where it overlaps or cut the small piece of it with scissors</li>
      <li> Now, measure the marked length of the non-elastic string or thread or strip of paper with a ruler and note the measurement
      </li>
      <li>Compare the measurement from the below chart to know your ring size</li>
    </ul>

    <i style="color:#D3A012;">Useful tip: When you are opting this method, check the table to compare your measurement with the circumference parameter.
    </i>
    <h5 style="color:#cd1f0f;">Method #2 : Knowing your ring size with your old ring</h5>
    <ul>
      <li> Take your already owned ring and a ruler </li>
      <li>Keep your ring on the ruler and measure the inside diameter in mm/in and take a note of it</li>
      <li> Compare the measurement from the below chart to know your ring size </li>
    </ul>
    <i style="color:#D3A012;">
    Useful tip: When you are opting this method, check the table to compare your measurement with the diameter parameter.
    </i>
  </div>



  <table class="table">
    <tr>
      <td style="padding:0;">
        <table>
          <tr class="bg">
            <td><span style="color:#D3A012;">Ring Size(Indian)</span></td>
          </tr>
          <tr class="bg">
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>6</td>
          </tr>
          <tr>
            <td>7</td>
          </tr>
          <tr>
            <td>8</td>
          </tr>
          <tr>
            <td>9</td>
          </tr>
          <tr>
            <td>10</td>
          </tr>
          <tr>
            <td>11</td>
          </tr>
          <tr>
            <td>12</td>
          </tr>
          <tr>
            <td>12</td>
          </tr>
          <tr>
            <td>14</td>
          </tr>
          <tr>
            <td>15</td>
          </tr>
          <tr>
            <td>16</td>
          </tr>
          <tr>
            <td>17</td>
          </tr>
          <tr>
            <td>18</td>
          </tr>
          <tr>
            <td>19</td>
          </tr>
          <tr>
            <td>20</td>
          </tr>
          <tr>
            <td>21</td>
          </tr>
          <tr>
            <td>22</td>
          </tr>
          <tr>
            <td>23</td>
          </tr>
          <tr>
            <td>24</td>
          </tr>
          <tr>
            <td>25</td>
          </tr>
          <tr>
            <td>26</td>
          </tr>
          <tr>
            <td>27</td>
          </tr>
          <tr>
            <td>28</td>
          </tr>
          <tr>
            <td>29</td>
          </tr>
          <tr>
            <td>30</td>
          </tr>
        </table>
      </td>
      <td style="padding:0;">
        <table>
          <tr class="bg">
            <td colspan="2"><span style="color:#D3A012;">Internal Ring Diameter</span></td>
          </tr>
          <tr class="bg">
            <td><span style="color:#D3A012;">(mm)</span></td>
            <td><span style="color:#D3A012;">(in)</span></td>
          </tr>
          <tr>
            <td>14.6</td>
            <td>0.57</td>
          </tr>
          <tr>
            <td>15</td>
            <td>0.59</td>
          </tr>
          <tr>
            <td>15.3</td>
            <td>0.6</td>
          </tr>
          <tr>
            <td>15.6</td>
            <td>0.61</td>
          </tr>
          <tr>
            <td>16</td>
            <td>0.63</td>
          </tr>
          <tr>
            <td>16.2</td>
            <td>0.64</td>
          </tr>
          <tr>
            <td>16.5</td>
            <td>0.65</td>
          </tr>
          <tr>
            <td>16.8</td>
            <td>0.66</td>
          </tr>
          <tr>
            <td>17.2</td>
            <td>0.68</td>
          </tr>
          <tr>
            <td>17.4</td>
            <td>0.69</td>
          </tr>
          <tr>
            <td>17.8</td>
            <td>0.7</td>
          </tr>
          <tr>
            <td>18.1</td>
            <td>0.71</td>
          </tr>
          <tr>
            <td>18.5</td>
            <td>0.73</td>
          </tr>
          <tr>
            <td>18.8</td>
            <td>0.74</td>
          </tr>
          <tr>
            <td>19.1</td>
            <td>0.75</td>
          </tr>
          <tr>
            <td>19.5</td>
            <td>0.77</td>
          </tr>
          <tr>
            <td>19.7</td>
            <td>0.78</td>
          </tr>
          <tr>
            <td>20</td>
            <td>0.79</td>
          </tr>
          <tr>
            <td>20.3</td>
            <td>0.8</td>
          </tr>
          <tr>
            <td>20.6</td>
            <td>0.81</td>
          </tr>
          <tr>
            <td>21</td>
            <td>0.83</td>
          </tr>
          <tr>
            <td>21.2</td>
            <td>0.84</td>
          </tr>
          <tr>
            <td>21.6</td>
            <td>0.85</td>
          </tr>
          <tr>
            <td>22</td>
            <td>0.87</td>
          </tr>
          <tr>
            <td>22.3</td>
            <td>0.88</td>
          </tr>
        </table>
      </td>
      <td style="padding:0;">
        <table>
          <tr class="bg">
            <td colspan="2"><span style="color:#D3A012;">Internal Ring circumference</span></td>
          </tr>
          <tr class="bg">
            <td><span style="color:#D3A012;">(mm)</span></td>
            <td><span style="color:#D3A012;">(in)</span></td>
          </tr>
          <tr>
            <td>45</td>
            <td>1.77</td>
          </tr>
          <tr>
            <td>47.1</td>
            <td>1.85</td>
          </tr>
          <tr>
            <td>48.1</td>
            <td>1.89</td>
          </tr>
          <tr>
            <td>49</td>
            <td>1.93</td>
          </tr>
          <tr>
            <td>50</td>
            <td>1.97</td>
          </tr>
          <tr>
            <td>50.9</td>
            <td>2</td>
          </tr>
          <tr>
            <td>51.8</td>
            <td>2.04</td>
          </tr>
          <tr>
            <td>52.8</td>
            <td>2.08</td>
          </tr>
          <tr>
            <td>54</td>
            <td>2.13</td>
          </tr>
          <tr>
            <td>54.6</td>
            <td>2.15</td>
          </tr>
          <tr>
            <td>55.9</td>
            <td>2.2</td>
          </tr>
          <tr>
            <td>56.8</td>
            <td>2.24</td>
          </tr>
          <tr>
            <td>58.1</td>
            <td>2.29</td>
          </tr>
          <tr>
            <td>59</td>
            <td>2.32</td>
          </tr>
          <tr>
            <td>60</td>
            <td>2.36</td>
          </tr>
          <tr>
            <td>61.2</td>
            <td>2.41</td>
          </tr>
          <tr>
            <td>61.9</td>
            <td>2.44</td>
          </tr>
          <tr>
            <td>62.8</td>
            <td>2.47</td>
          </tr>
          <tr>
            <td>63.8</td>
            <td>2.51</td>
          </tr>
          <tr>
            <td>64.7</td>
            <td>2.55</td>
          </tr>
          <tr>
            <td>66</td>
            <td>2.6</td>
          </tr>
          <tr>
            <td>66.6</td>
            <td>2.62</td>
          </tr>
          <tr>
            <td>67.9</td>
            <td>2.67</td>
          </tr>
          <tr>
            <td>69.1</td>
            <td>2.72</td>
          </tr>
          <tr>
            <td>70</td>
            <td>2.76</td>
          </tr>
        </table>
      </td>
    </tr>
    
  </table>
</div>

</div>               
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
   
   <div class="toast-body">
     
   </div>
 </div>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<script src="<?php echo e(asset('js/nice-select.min.js')); ?>"></script>

<script src="<?php echo e(asset('js/xzoom.js')); ?> "></script>
<!-- <script src="<?php echo e(asset('js/image-zoom.min.js')); ?>"></script> -->

<script src="<?php echo e(asset('js/jquery.validate.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/additional-methods.js')); ?>"></script>

<script src="<?php echo e(asset('js/script.js')); ?> "></script>
<script>
  $(".customize-product a").click(function(){
    $(".customize-sec").slideToggle();
  });
  $(".customize-product a").click(function(){
      $("#toggle").toggleClass("minus");
  });
  $("#shipping_pincode").on('keyup',function(e) {
    if(e.keyCode == 8) {
      $(".delivery-info").fadeOut();
    } else {
      $("#checkShippingAvailbility").on("click",function() {
        $(".delivery-info").fadeIn();
      });
      
    }
  });
  $("#shipping_pincode").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      return false;
    }  
  });
</script>

    <script>
        async function checkShippingAvailability() {
            this.disabled = true;
            this.innerText ='Please Wait...';
            $('#shipping_success').css('display','none');
            $('#shipping_error').css('display','none');
            var shipping_pincode = $('#shipping_pincode').val();
            var product_id = $('#product_id').val();
              
            if(shipping_pincode != ''){
                let response = await fetch("<?php echo e(url('/ajax/checkShippingAvailability')); ?>/"+ shipping_pincode+'/'+product_id);
                let output  = await response.json();
                    <?php if(config('settings.enable_zip_code')): ?>{
                    if(output === 1){
                        $('#add_cart').removeClass('btn-danger');
                        $('#add_cart').addClass('btn-success');
                        $('#add_cart').removeAttr('disabled');
                        $('#shipping_success').css('display','block');
                    }else{
                        $('#add_cart').removeClass('btn-success');
                        $('#add_cart').addClass('btn-danger');
                        $('#add_cart').attr('disabled','true');
                        $('#shipping_error').css('display','block');
                    }
                }
                <?php else: ?>
                $('#add_cart').addClass('btn-success');
                $('#add_cart').removeAttr('disabled');
                if(output === 1){
                    $('#shipping_success').css('display','block');
                }else{
                    $('#shipping_error').css('display','block');
                }
                <?php endif; ?>
            }else{
               
                $('#add_cart').removeClass('btn-success');
                $('#add_cart').addClass('btn-danger');
                $('#add_cart').attr('disabled','true');
                $('#shipping_error').css('display','block');
            }

        }
    </script>

    <!-- <script src="<?php echo e(asset('js/xZoom/xzoom.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/xZoom/jquery.hammer.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/xZoom/magnific-popup.js')); ?>"></script> -->
    <script>
    var addWishlist;
    var removeWishlist;
    var addProductWishlist;
    var removeProductWishlist;
        (function ($) {
            $(document).ready(function() {
              if($("#gallery img").length == 1) {
                $("#gallery").hide();
              }
             
              
                var variant_elem = $(document).find('.variant_input[type="radio"]');
                // var variant_hidden = $(document).find('.variant_input[type="hidden"]');

                if(variant_elem.length !== 0){
                    
                }
                $('.xzoom, .xzoom-gallery').xzoom({zoomWidth: 500, title: false, tint: '#333', Xoffset: 28});

                //Integration with hammer.js
                var isTouchSupported = 'ontouchstart' in window;

                if (isTouchSupported) {
                    $('.xzoom').each(function() {
                        var xzoom = $(this).data('xzoom');
                        $(this).hammer().on("tap", function(event) {
                            event.pageX = event.gesture.center.pageX;
                            event.pageY = event.gesture.center.pageY;
                            var s = 1, ls;

                            xzoom.eventmove = function(element) {
                                element.hammer().on('drag', function(event) {
                                    event.pageX = event.gesture.center.pageX;
                                    event.pageY = event.gesture.center.pageY;
                                    xzoom.movezoom(event);
                                    event.gesture.preventDefault();
                                });
                            }

                            var counter = 0;
                            xzoom.eventclick = function(element) {
                                element.hammer().on('tap', function() {
                                    counter++;
                                    if (counter == 1) setTimeout(openmagnific,300);
                                    event.gesture.preventDefault();
                                });
                            }

                            function openmagnific() {
                                if (counter == 2) {
                                    xzoom.closezoom();
                                    var gallery = xzoom.gallery().cgallery;
                                    var i, images = new Array();
                                    for (i in gallery) {
                                        images[i] = {src: gallery[i]};
                                    }
                                    $.magnificPopup.open({items: images, type:'image', gallery: {enabled: true}});
                                } else {
                                    xzoom.closezoom();
                                }
                                counter = 0;
                            }
                            xzoom.openzoom(event);
                        });
                    });
                } else {
                    //If not touch device
                    //Integration with magnific popup plugin
                    $('#xzoom-magnific').bind('click', function(event) {
                        var xzoom = $(this).data('xzoom');
                        xzoom.closezoom();
                        var gallery = xzoom.gallery().cgallery;
                        var i, images = new Array();
                        for (i in gallery) {
                            images[i] = {src: gallery[i]};
                        }
                        $.magnificPopup.open({items: images, type:'image', gallery: {enabled: true}});
                        event.preventDefault();
                    });
                }

                $(document).on('change', '.variant_input', function() {
                    var product = $(this).data('product');
                    updateProductAmount(product);

                });

                addProductWishlist = function(id) {
                  var url = "<?php echo e(route('front.product.favourite.store',"+id+")); ?>";
                  url = url.replace('+id+', id);
                  $.ajax({
                        type:"POST",
                        url:url,
                        data:{id:id},
                        
                        success:function(data) {
                          $(".toast").toast('show');
                          $(".toast .toast-body").html('<i class="fa fa-check-circle"></i>&nbsp;&nbsp;Added to your wishlist');
                          $("#removeProductBtn_"+id).removeAttr('hidden');
                          $("#removeProductBtn_"+id).show();
                          $("#addProductBtn_"+id).hide();
                          var wishlistCount = parseInt($("#wishlistCount").text());
							$("#wishlistCount").text(wishlistCount + 1);
                        } 
                      });
                }

                

                removeProductWishlist = function (id) {
                  
                  var url = "<?php echo e(route('front.product.favourite.destroywishlist',"+id+")); ?>";
                  url = url.replace('+id+', id);
                  
                  $.ajax({
                    type:"GET",
                    url:url,
                    success:function(data) {
                      $(".toast").toast('show');
                      $(".toast .toast-body").html('<i class="fa fa-check-circle"></i>&nbsp;&nbsp;Removed from your wishlist');
                      $("#removeProductBtn_"+id).hide();
                      $("#addProductBtn_"+id).removeAttr('hidden');
                      $("#addProductBtn_"+id).show();
					  var wishlistCount = parseInt($("#wishlistCount").text());
					  $("#wishlistCount").text(wishlistCount - 1);
                      } 
                    });
                  }

                addWishlist =function (id) {
                   console.log('=====');
                    $.ajax({
                        type:"POST",
                        url:$("#product-favourite-form_"+id).attr('action'),
                        data:{id:id},
                        
                        success:function(data) {
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
        })(jQuery);

        function updateProductAmount(product) {

            var variants = [];
            var values = [];
            $('.variant_input').each(function(input) {
                if($(this).is(':checked')) {
                    var variant = $(this).data('variant');
                    var value = $(this).val();
                    variants.push(variant);
                    values.push(value);
                }
            });

            $.ajax({
                method: 'get',
                url: APP_URL + '/ajax/product/get-variant/' + product + '/' + variants.join(',') + '/' + values.join(','),
                success: function(response) {
                    if(response.success) {
                        $('.product-price-box').html('<span class="product-price">' + response.data + '</span>');
                    }
                }
            });
        }

        $(function(){
            // Product Details Product Color Active Js Code
            $(document).on('click', '.color-variation-list .box', function () {
                colors = $(this).data('color');
                var parent = $(this).parent();
                $('.color-variation-list li').removeClass('active');
                parent.addClass('active');

                var color_var_inp = parent.parent().find('.variant_input[type="radio"]');

                color_var_inp.each(function(input) {
                    this.removeAttribute('checked');
                    // this.setAttribute('value','0');
                });
                // console.log($(this).parent().find('.variant_input[type="hidden"]'));
                var checked_inp = parent.find('.variant_input[type="radio"]');
                checked_inp.attr('checked','true');
                var product = checked_inp.data('product');
                updateProductAmount(product);
                // checked_inp.attr('value','1');
            });

            $(".groupVal").on("change",function() {
              var product_id = $(this).find(':selected').data('product_id');;
               
              if($(this).val()!='') {
                  $.ajax({
                    url:"<?php echo e(route('front.product.getproductprice')); ?>",
                    method:"POST",
                    datatype:"JSON",
                    data:{product_id:product_id,group_val:$(this).val()},
                    success:function(result) {
                      console.log(result.priceArr.price);
                      

                       
                      $(".product-name").html(result.priceArr.name);
                      $(".making-charge").html(result.priceArr.discount);
                      $("#new_price").val(result.priceArr.price);
                       
                      if(result.priceArr.product_discount!='') {
                        
                        $(".productPriceOld").html('<del> ₹ '+result.priceArr.old_price+'</del>');
                        $(".productPriceRegular").html(result.priceArr.new_price);
                      } else {
                        $(".productPriceOld").html('');
                        $(".productPriceRegular").html(result.priceArr.old_price);
                      }
                      
                      $("#metalInfo").html(result.metalInfo); 
                       

                       
                       
                      
                      
                    }
                });
              } else {
                confirm('Select ring size');
                return false;
              }
               
            });

            $("#enquiryBtn").on("click",function() {

              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });

                $("#enquiryform").validate({
                
                  submitHandler: function(form) { // ONLY FOR DEMO
                    
                    $.ajax({

                      method : $("#enquiryform").attr('method'),
                      url : $("#enquiryform").attr('action'),
                      data : $("#enquiryform").serialize(),
                      success:function(response) {
                          if(response.success== '1') {
                            $("#enquiryMsg").html(response.msg);
                            setTimeout(function() {
                              $("#enquiryMsg").slideUp();
                              }, 10000);
                          } else  if(response.success== '2') { 
                            $("#enquiryMsg").html(response.msg);
                            setTimeout(function() {
                              $("#enquiryMsg").slideUp();
                              }, 10000);
                          } else {
                            $("#enquiryMsg").html(response.msg);
                            setTimeout(function() {
                              $("#enquiryMsg").slideUp();
                              }, 10000);
                          }
                      }
                    });
                  
                  },
                  rules: {
                      
                      "name": "required",
                      "email": "required",
                      "phone": "required",
                      "query": "required"
                  },
                  ignore: ":hidden:not(.ignore)",
                  errorClass: 'error',
                  messages: {
                      
                      "name": "Please enter name",
                      "email": "Please enter email",
                      "phone": "Please enter mobile no",
                      "query": "Please enter your query"
                  },
                });
              
            }); 

            $('#phoneno').keypress(function(e) {

              if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                $("#errormsg").html("Digits Only").show().fadeOut("slow");
                return false;
              }
              if($(e.target).prop('value').length>=10) {
                  if(e.keyCode!=32) {
                    $("#errormsg").html("Allow 10 Digits only").show().fadeOut("slow");
                    return false;
                  } 
              }
             
            });

            $(".wishlistProduct").on("click",function() {
              if(confirm('Are you sure that you want to add product in wishlist') ) {
                $("#product-favourite-form_"+$(this).data('product_id')).submit();
              } else {
                return false;
              }
            });
        });

    </script>

    <?php echo $__env->make('includes.reviews-submit-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('includes.reviews-pagination-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
     
    <?php echo $__env->make('includes.cart-submit-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- <?php if( !empty( $comparision_group_types ) && count( $comparision_products ) > 1): ?>
        <script src="<?php echo e(asset('css/comparision-group/js/modernizer.js')); ?>"></script>
        <script src="<?php echo e(asset('css/comparision-group/js/main.js')); ?>"></script>
    <?php endif; ?> -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>