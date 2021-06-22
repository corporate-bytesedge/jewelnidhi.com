


<title><?php echo app('translator')->getFromJson('Wishlist'); ?> - <?php echo e(config('app.name')); ?></title>




<?php $__env->startSection('styleg'); ?>
<style>
.emptyBtn {
    color: #fff !important;
    background-color: #D3A012 !important;
    border-color: #D3A012 !important;
}
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="breadcrumb-sec">
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
      </ol>
    </div>
  </div>
<!-- cart main wrapper start -->
<?php if(count($products) > 0): ?>
<div class="cart-main-wrapper section">
    <div class="container">
        <div class="section-bg-color">
            <div class="row">
                <div class="col-lg-12">
                <div id="CartMsg"></div>
                    <!-- Cart Table Area -->
                    <div class="cart-table">
                        <table class="table table-bordered cartDetails">
                            <thead>
                                <tr>
                                    <!-- <th class="pro-thumbnail">Thumbnail</th> -->
                                    <th class="pro-title">Product</th>
                                    <th class="pro-price">Price</th>
                                    <!-- <th class="pro-quantity">Stock Status</th> -->
                                    <th class="pro-subtotal">Add to Cart</th>
                                    <th class="pro-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                             
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo Form::open(['method'=>'patch', 'route'=>['front.cart.add', $product->id], 'id'=>'wishlistcart-form'.$product->id]); ?>

                                <?php echo e(Form::hidden('product_id',isset($product->id) ? $product->id : '',['id'=>'product_id'])); ?>

                                <?php echo e(Form::hidden('new_price',isset($product->new_price) ? $product->new_price : $product->old_price,['id'=>'new_price'])); ?>

                                <tr>
                                    <td>
                                        <div class="pro-title">
                                            <span class="imgsection">
                                            <?php if($product->photo!=null): ?>
                                                <?php if($product->photo->name): ?>
                                                    <?php
                                                        $image_url = \App\Helpers\Helper::check_image_avatar($product->photo->name, 150);
                                                    ?>
                                                        <img class="img-fluid " src="<?php echo e($image_url); ?>" width="140px" alt="<?php echo e($product->photo->name); ?>">
                                                <?php else: ?>
                                                    <img src="<?php echo e(asset('img/no-img.jpg')); ?>" class="product-image img-responsive" width="150"  alt="<?php echo e($cartItem->name); ?>" />
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            </span>
                                            <span class="protitle">
                                            <a href="<?php echo e(route('front.product.show', [$product->slug])); ?>"><?php echo e($product->name); ?> </a>
                                            </span>
                                    
                            
                                        </div>
                                    </td>
                                    <td class="pro-price">
                                    
                                    
                                    <div class="product-caption">

                                        <?php if(isset($product->product_discount) && $product->product_discount!=''): ?>
                                            <div class="price-box">
                                            <?php if($product->old_price!='0'): ?>
                                                <span class="price-old">
                                                    <del> 
                                                    <i class="fa fa-rupee"></i> <?php echo e(isset($product->old_price) && $product->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price) : ''); ?>

                                                    </del>
                                                </span>
                                            <?php endif; ?>  
                                                <span class="price-regular"> 
                                                <i class="fa fa-rupee"></i> <?php echo e(isset($product->new_price) && $product->new_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($product->new_price) : ''); ?></span>
                                            </div>
                                            <?php else: ?>
                                            <div class="price-box">
                                            <span class="price-regular"> 
                                            <i class="fa fa-rupee"></i> <?php echo e(isset($product->old_price) && $product->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price) : ''); ?>

                                            </span>
                                            </div>
                                        <?php endif; ?>

                                                

                                        <?php if(isset($product->old_price) && $product->old_price!=null && $product->product_discount!=''): ?>
                                            <div class="you-save">
                                            Save <span>
                                            <i class="fa fa-rupee"></i> <?php echo e(isset($product->old_price) && $product->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price - $product->new_price) : ''); ?>

                                            </span>
                                            </div>
                                        <?php endif; ?>
                                        </div>


                                    </td>
                                    <!-- <td class="pro-quantity">
                                    <?php if($product->in_stock > 0 ): ?>
                                      <span class="text-success">In Stock</span>
                                    <?php else: ?> 
                                        <span class="text-danger">Stock Out</span>
                                    <?php endif; ?>
                                    </td> -->
                                    <td class="pro-subtotal pro-wishlist">
                                        <?php if(!empty(Cart::content())): ?>
                                        <?php
                                            $classname = '';
                                        ?>
                                            <?php $__currentLoopData = Cart::content(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($value->id == $product->id ): ?>
                                                    <?php 
                                                        $classname .= 'disableBtn';
                                                    ?>
                                                
                                                <?php endif; ?>
                                                
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                        <button class="btn btn-primary btn-xs addCartWishlist <?php echo $classname ?>"  id="add_to_cart_<?php echo e($product->id); ?>"  data-product_id = "<?php echo e($product->id); ?>" name="submit_button" type="button"><?php echo app('translator')->getFromJson('Add to Cart'); ?></button>
                                        
                                        
                                    </td>
                                    <?php echo Form::close(); ?>

                                    <td class="pro-remove remove-wishlist">
                                        <a class="text-danger removeProduct"  data-id = "<?php echo e($product->id); ?>" style="color: #fff!important;" href="javascript:void(0);">
                                                    <i class="fa fa-trash-o"></i>
                                        </a>
                                        <form action="<?php echo e(route('front.product.favourite.destroy', $product)); ?>"
                                            method="POST" 
                                            id="product-favourite-destroy-<?php echo e($product->id); ?>">
                                            <?php echo e(csrf_field()); ?>

                                            <?php echo e(method_field('DELETE')); ?>

                                        </form>
                                    </td>
                                </tr>
                            
                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
                            </tbody>
                        </table>
                    </div>
                    <!-- Cart Update Option -->
                    
                </div>
            </div>
           
        </div>
    </div>
</div>
<?php else: ?>
<br>
<div class="cart-main-wrapper section">
        <h2 class="text-center text-muted">
        <img src="<?php echo e(asset('img/EMPTY_WISHLIST.png')); ?>">
        <br/>
            <a href="<?php echo e(url('/')); ?>" class="btn btn-primary emptyBtn"><?php echo app('translator')->getFromJson('Go to Shop'); ?></a>
        </h2>
    </div>
<?php endif; ?>
<!-- cart main wrapper end -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo $__env->make('includes.cart-submit-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
     
    <script>
        jQuery(document).ready(function() {
            $(".removeProduct").on("click",function() {
                if(confirm('Are you sure you want to delete product from wishlist?') ) {
                    $("#product-favourite-destroy-"+$(this).data('id')).submit();
                } else {
                    return false;
                }
            })

             
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>