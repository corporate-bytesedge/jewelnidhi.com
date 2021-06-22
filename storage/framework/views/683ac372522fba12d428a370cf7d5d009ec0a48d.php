 <?php  
$rowidArr = array();
$qtyArr = array();
?> <?php if(Cart::count() > 0): ?> <div class="cart-main-wrapper section"> <div class="container"> <div class="section-bg-color"> <div class="row"> <div class="col-lg-12"> <div class="cart-table "> <div class=""> <table class="table table-bordered cartDetails" id=""> <thead> <tr> <th class="pro-title"><?php echo app('translator')->getFromJson('Product'); ?></th> <th class="pro-price"><?php echo app('translator')->getFromJson('Price'); ?></th> <th class="pro-quantity"><?php echo app('translator')->getFromJson('Quantity'); ?></th> <th class="pro-subtotal"><?php echo app('translator')->getFromJson('Total'); ?></th> <th class="pro-remove"><?php echo app('translator')->getFromJson('Remove'); ?></th> </tr> </thead> <tbody> <?php 
                                    $productIdArr = array(); 
                                    $GST = 0;
                                    $valueAdded = 0;
                                    $cartTotal = 0;
                                    $subTotal = 0;
                                    
                                ?> <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <?php $rowidArr[] = $cartItem->rowId;
                                    $qtyArr[] = $cartItem->qty;
                                     
                                    array_push($productIdArr,$cartItem->options->vendor_id);

                                    session(['vendor_id'=>$productIdArr]);
                                    
                                    $GST += floor($cartItem->options->gst) * $cartItem->qty ;
                                    $valueAdded += floor($cartItem->options->va) * $cartItem->qty;
                                    
                                    
                                    
                                    ?> <?php 
                                        /* $product_data = \App\Product::select('file_id','virtual','downloadable')->where('id',$cartItem->id)->get()->first()->toArray();*/
                                    ?>
 <tr> <td> <div class="pro-title"> <span class="imgsection"> <?php if($cartItem->options->has('photo')): ?> <?php 
                                                         $featured = public_path().'/img/'.basename($cartItem->options->photo);
                                                          
                                                         ?> <?php if(file_exists($featured)): ?> <img class="img-fluid" src="<?php echo e(asset('img/'.basename($cartItem->options->photo))); ?>" width="140px" alt="Product" /> <?php else: ?> <img src="https://via.placeholder.com/150x150?text=No+Image" class="product-image img-responsive" width="100" alt="" /> <?php endif; ?> <?php endif; ?> </span> <span class="protitle"> <a href="/product/<?php echo e($cartItem->options->slug); ?>"><?php echo e($cartItem->name); ?></a> </span> </div> </td> <td class="pro-price"> <span>₹ <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format($cartItem->options->unit_price); ?></span></td> <td class="pro-quantity"> <?php
                                        $productQty = \App\Product::where('id',$cartItem->id)->first();
                                         
                                        ?>
 <div class="pro-qty"> <select name="pro_qty" class="pro_qty" id="pro_qty_<?php echo e($k); ?>"> <?php 
                                                    for($i = 1; $i <=$productQty->in_stock; $i++) {
                                                ?>
 <option <?php echo $cartItem->qty == $i ? 'selected="selected"' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option> <?php    }
                                                ?>
                                            </select>
                                            </div>
                                        </td>
                                        <td class="pro-subtotal"><span> <i class="fa fa-rupee"></i> <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format($cartItem->options->unit_price*$cartItem->qty); ?> </span></td>
                                        <td class="pro-remove">
                                                <?php echo Form::open(['method'=>'delete','id'=>'cartForm-'.$cartItem->rowId, 'route'=>['front.cart.destroy', $cartItem->rowId]]); ?>

                                                
                                                    <?php echo e(Form::button('<i class="fa fa-trash"></i>', ['type' => 'button','data-rowid'=>$cartItem->rowId, 'class' => 'btn btn-square removeProduct', 'style' => 'color: #fff'] )); ?>

                                            
                                                <?php echo Form::close(); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    
                                
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Cart Update Option -->
                    <div class="cart-update-option d-block d-md-flex justify-content-between">
                        <div class="apply-coupon-wrapper">
                        <?php if(session()->has('coupon_invalid')): ?>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <?php echo e(session('coupon_invalid')); ?>

                            </div>
                        <?php endif; ?>
                        <?php if(session()->has('use_coupon_one_time')): ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            Coupon apply only one time
                        </div>
                        <?php echo e(session()->forget('use_coupon_one_time')); ?>

                        <?php endif; ?>
                            <?php if(session()->has('coupon_amount') && session()->has('coupon_valid_above_amount') && session('coupon_valid_above_amount') < Cart::total()): ?>
                                <?php if(session()->has('coupon_code')): ?>
                                    <h6 class="text-success"><?php echo app('translator')->getFromJson('Coupon Applied:'); ?> <span class="text-muted"><?php echo e(session('coupon_code')); ?></span></h6>
                                    <h6 class="text-success"><?php echo app('translator')->getFromJson('Discount:'); ?> <span class="text-muted"> <?php echo e(session('coupon_amount')); ?></span></h6>
                                <?php else: ?>
                                    <h4><?php echo app('translator')->getFromJson('Coupon Discount:'); ?> <?php echo e(session('coupon_amount')); ?></h4>
                                <?php endif; ?>
                                <a id="have-coupon" href=""><?php echo app('translator')->getFromJson('Change Coupon?'); ?></a>
                        <?php else: ?>
                            <a id="have-coupon" href=""><?php echo app('translator')->getFromJson('Have a Coupon?'); ?></a>
                        <?php endif; ?>
                    
                        <?php echo Form::open(['method'=>'post', 'action'=>'FrontCouponsController@checkCoupon', 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait') . '"; return true;', 'class'=>'d-block d-md-flex coupon-sec']); ?>

                            
                            
                            <?php echo Form::text('coupon', null, ['class'=>'form-control', 'placeholder'=>__('Enter Your Coupon Code'), 'required']); ?>

                            <?php if(\Auth::user()): ?> 
                                <?php echo Form::submit(__('Apply Coupon'), ['id'=>'coupon-btn', 'class'=>'btn btn-primary', 'name'=>'submit_button']); ?>

                            <?php else: ?>
                                <a href="javascript:void(0)" class="btn btn-sqr d-block" data-toggle="modal" data-target="#login-modal"><?php echo app('translator')->getFromJson('Apply Coupon'); ?></a> 
                            <?php endif; ?>
                        <?php echo Form::close(); ?>

                        </div>
                        <?php echo Form::open(['method'=>'patch','id'=>'updateCart', 'route'=>['front.cart.update']]); ?>

                            <?php $__currentLoopData = $rowidArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $rowitem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="hidden" name="rowid[]" id="rowid_<?php echo e($rowitem); ?>" value="<?php echo e($rowitem); ?>">
                                <input type="hidden" name="qty[]" id="qty_<?php echo e($rowitem); ?>" value="<?php echo e($qtyArr[$k]); ?>">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                                  
                            <!-- <div class="cart-update">
                                <button type="button" class="btn btn-sqr updateCart">Update</button>
                            </div> -->
                        <?php echo Form::close(); ?>

                    </div>
                </div>
                
                
            </div>
             <?php 
             $cal_wallet_amount = 0;
             
            $subTotal = Cart::total() - $GST - $valueAdded;
			
            
			
			
			
				$cartTotal += $subTotal + $GST + $valueAdded ;
                                                                    
        
             ?>
            <div class="row">
                <div class="col-lg-5 ml-auto">
                    <!-- Cart Calculation Area -->
                    <div class="cart-calculator-wrapper">
                        <div class="cart-calculate-items">
                            <h6>Cart Total</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td><?php echo app('translator')->getFromJson('Sub Total'); ?></td>
                                        <td><b><i class="fa fa-rupee"></i> <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format($subTotal); ?></b></td>
                                    </tr>

                                    <tr>
                                        <td><?php echo app('translator')->getFromJson('GST'); ?></td>
                                        <td><i class="fa fa-rupee"></i> <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format($GST); ?></td>
                                    </tr>

                                    <tr>
                                        <td><?php echo app('translator')->getFromJson('Value Added'); ?></td>
                                        <td><i class="fa fa-rupee"></i> <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format($valueAdded); ?></td>
                                    </tr>

                                    
                               

                                    
                                     
                                    <tr>
                                        <td><?php echo app('translator')->getFromJson('Shipping Cost'); ?></td>
                                         
                                        <?php if(config('settings.shipping_cost_valid_below') > Cart::total()): ?>
                                            <td> 
                                            <i class="fa fa-rupee"></i> <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format(config('settings.shipping_cost')); ?>

                                            </td>
                                         <?php else: ?>
                                             <td> 
                                             <i class="fa fa-rupee"></i> <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format(0); ?>

                                            </td>

                                         <?php endif; ?>
                                         
                                    </tr>
                                     
                                    <tr class="total">
                                        <td><?php echo app('translator')->getFromJson('Total'); ?></td>

                                        <!-- <?php if(session()->has('coupon_amount')): ?> 
                                            <td class="total-amount">&#8377; <?php echo e($cartTotal - session()->has('coupon_amount')); ?></td>
                                        <?php else: ?>
                                        <td class="total-amount">&#8377; <?php echo e(number_format($cartTotal + (Cart::total() * config('settings.tax_rate')) / 100 )); ?></td>
                                        <?php endif; ?> -->

                                    <?php if(config('settings.shipping_cost_valid_below') > $cartTotal && session()->has('coupon_amount') && session()->has('coupon_valid_above_amount') && session('coupon_valid_above_amount') < $cartTotal): ?>
                                      <td class="total-amount">
                                      <i class="fa fa-rupee"></i> <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format(config('settings.shipping_cost') - $cal_wallet_amount + $cartTotal - session('coupon_amount') + ($cartTotal * config('settings.tax_rate')) / 100); ?>

                                     
                                      <?php 
                                        config('settings.shipping_cost') - $cal_wallet_amount + $cartTotal - session('coupon_amount') + ($cartTotal * config('settings.tax_rate')) / 100;
                                      ?>
                                      
                                      </th>
                                    
                                  <?php elseif(config('settings.shipping_cost_valid_below') > $cartTotal): ?>
                                      <td class="total-amount">
                                      <i class="fa fa-rupee"></i> <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format(config('settings.shipping_cost') + $cartTotal - $cal_wallet_amount + ($cartTotal * config('settings.tax_rate')) / 100); ?>


                                      
                                        <?php 
                                            $totalAmount = config('settings.shipping_cost') + $cartTotal - $cal_wallet_amount + ($cartTotal * config('settings.tax_rate')) / 100;
                                        ?>
                                      
                                      </th>
                                  <?php else: ?>
                                      <td class="total-amount">
                                      <i class="fa fa-rupee"></i> <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format($cartTotal - $cal_wallet_amount + ($cartTotal * config('settings.tax_rate')) / 100); ?>

                                      
                                    <?php 
                                        $totalAmount = $cartTotal - $cal_wallet_amount + ($cartTotal * config('settings.tax_rate')) / 100;
                                    ?>

                                      </th>
                                  <?php endif; ?>
                                        
                                    </tr>

                                    <?php if(session()->has('coupon_amount') || session()->has('coupon_percent') && session()->has('coupon_valid_above_amount') && session('coupon_valid_above_amount') < Cart::total()): ?>
                              
                                   
                                        <?php if(session('coupon_amount')): ?>
                                            <tr>
                                            
                                                <td> <?php echo app('translator')->getFromJson('Coupon Amount'); ?></td>
                                                <td>-   
                                                    ₹ <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format(session('coupon_amount')); ?>

                                                </td>
                                            </tr>
                                        <?php else: ?>
                                        <?php 
                                            $couponAmount  = (Cart::total() * session('coupon_percent'))/100;
                                            
                                            session(['coupon_amount' => $couponAmount]);
                                            
                                        ?>
                                            <tr>
                                                
                                                <td ><?php echo app('translator')->getFromJson('Coupon Amount'); ?></th>
                                                <td>-    
                                                <i class="fa fa-rupee"></i> <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format(session('coupon_amount')); ?>

                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php if(session()->has('coupon_amount') && session()->has('coupon_valid_above_amount') && session('coupon_valid_above_amount') < $cartTotal): ?>
                                    <tr class="total">
                                        <td> Total After Discount  
                                        
                                        
                                        </td>
                                        <td class="total-amount">
                                        
                                            <i class="fa fa-rupee"></i> <?php echo \App\Helpers\IndianCurrencyHelper::IND_money_format($cartTotal - session('coupon_amount')  - $cal_wallet_amount); ?>

                                        </td>
                                    </tr>

                                    <?php 
                                    
                                        $totalAmount = $cartTotal - session('coupon_amount')  - $cal_wallet_amount;
                                    ?>
                                    
                                    <?php endif; ?>

                                    <?php 
                                            session()->forget('totalAmount');
                                            session()->put('totalAmount',$totalAmount);
                                        ?>
                                    
                                </table>
                            </div>
                        </div>
                        <?php if(\Auth::user()): ?> 
                            <a href="<?php echo e(route('checkout.shipping')); ?>" class="btn btn-sqr d-block"><?php echo app('translator')->getFromJson('Proceed Checkout'); ?></a>
                        <?php else: ?>
                            <a href="javascript:void(0)" class="btn btn-sqr d-block" data-toggle="modal" data-target="#login-modal"><?php echo app('translator')->getFromJson('Proceed Checkout'); ?></a> 
                        <?php endif; ?>
                        <!-- <a href="#" class="btn btn-sqr d-block"><?php echo app('translator')->getFromJson('Proceed Checkout'); ?></a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php else: ?>
<br>
<div class="cart-main-wrapper section">
        <h2 class="text-center text-muted">
        <img src="<?php echo e(asset('img/cart-empty1.png')); ?>">
        <br/>
            <a href="<?php echo e(url('/')); ?>" class="btn btn-primary emptyBtn"><?php echo app('translator')->getFromJson('Go to Shop'); ?></a>
        </h2>
    </div>
<?php endif; ?>
<!-- login -->
<div class="modal fade login-register" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="login-modalTitle">Login</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="LoginMsg"></div>
        <form method="POST" name="loginform" id="loginform" action="<?php echo e(route('login')); ?>">
          
          <div class="form-group  input-group<?php echo e($errors->has('username') || $errors->has('email') ? ' has-error' : ''); ?>">
            <input type="text" class="form-control " name="username" id="username" placeholder="<?php echo app('translator')->getFromJson('Your Username'); ?>"/>
              <?php if($errors->has('username')): ?>
                  <span class="help-block">
                      <strong class="text-danger">
                          <?php echo e($errors->first('username')); ?>

                      </strong>
                  </span>
              <?php endif; ?>
              <?php if($errors->has('email')): ?>
                  <span class="help-block">
                      <strong class="text-danger">
                          <?php echo e($errors->first('email')); ?>

                      </strong>
                  </span>
              <?php endif; ?>
          </div>
          <div class="form-group input-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
            <input type="password" class="form-control" name="password" id="password" placeholder="<?php echo app('translator')->getFromJson('Your Password'); ?>"/>
            <?php if($errors->has('password')): ?>
                <span class="help-block">
                    <strong class="text-danger">
                        <?php echo e($errors->first('password')); ?>

                    </strong>
                </span>
            <?php endif; ?>
          </div>
          <div class="form-group">
              <label class="checkbox-inline">
                  <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?> />
                  <?php echo app('translator')->getFromJson('Remember me'); ?>
              </label>
          </div>
          <div class="form-group">
            <button id="login-button" type="submit"  class="btn btn-primary"><?php echo app('translator')->getFromJson('Login'); ?></button>
          </div>
          <div class="forgot-link">
            <!-- <a href="<?php echo e(route('password.request')); ?>"><?php echo app('translator')->getFromJson('Forgot Your Password?'); ?></a> -->
            <a href="#"><?php echo app('translator')->getFromJson('Forgot Your Password?'); ?></a>
            <p>Don't have an account with us ? <a href="javascript:void(0)" data-toggle="modal" data-target="#register-modal"><?php echo app('translator')->getFromJson('Sign Up'); ?></a></p>
          </div>
        </form>
           
      </div>
     
    </div>
  </div>
</div>

<?php $__env->startSection('scripts'); ?>
<script>
jQuery(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    $("#login-button").on('click',function(e) {
        e.preventDefault();
        $.ajax({

            type:$("#loginform").attr('method'),
            url:$("#loginform").attr('action'),
            dataType:'json',
            data:$("#loginform").serializeArray(),
            
            success:function(data) {
                    
                if(data.status==true) {
                    $("#LoginMsg").show();
                    $("#LoginMsg").html(data.msg);
                    setTimeout(function() {
                    $("#login-button").text('<?php echo e(__('Login')); ?>').prop('disabled', false);
                    $("#LoginMsg").slideUp();
                    }, 2000); 
                    window.location.reload();
                } else {
                    $("#LoginMsg").show();
                    $("#LoginMsg").html(data.msg);
                    setTimeout(function() {
                    $("#login-button").text('<?php echo e(__('Login')); ?>').prop('disabled', false);
                    $("#LoginMsg").slideUp();
                    }, 2000); 
                    return false;
                }
                
            }

        });
    });

    $(".removeProduct").on("click",function() {
        if(confirm('Are you sure you want to delete product from cart?') ) {
            $("#cartForm-"+$(this).data('rowid')).submit();
        } else {
            return false;
        }
    });

    $(".pro_qty").on("change",function() {
        $("#updateCart").submit();
    });

    $(".updateCart").on("click",function() {
        if(confirm('Are you sure you want to update product from cart?') ) {
            $("#updateCart").submit();
        } else {
            return false;
        }
    });


});
</script>
<?php $__env->stopSection(); ?>

 

