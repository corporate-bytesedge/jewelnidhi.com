

<?php $__env->startSection('title'); ?><?php echo app('translator')->getFromJson('Track Orders'); ?> - <?php echo e(config('app.name')); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <link href="<?php echo e(asset('css/front-sidebar-content.css')); ?>" rel="stylesheet">
    <?php echo $__env->make('includes.order_tracker_style', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <style>
        .orders-details-inner > h3 {
            padding: 6px !important;
            margin: 0px !important;
            font-size: 18px;
            border-bottom: 1px solid #eee;
        }

        .orders-details-inner > ul > li {
            float: left;
            list-style: none;
            margin-bottom: 20px;
            padding: 10px !important;
            border-right: 1px solid #eee;
        }

        .orders-details-inner ul li a {
            color: #333;
            font-size: 16px;
        }

        .orders-details {
            background-color: #f9f9f9;
            padding: 15px;
            margin-top: 15px;
        }

        .bar1, .bar2, .bar3 {
            width: 22px;
            height: 3px;
            background-color: #333;
            margin: 4px 0;
            transition: 0.4s;
        }

        .change .bar1 {
            -webkit-transform: rotate(-45deg) translate(-7px, 3px);
            transform: rotate(-45deg) translate(-7px, 3px);
        }

        .change .bar2 {
            opacity: 0;
        }

        .change .bar3 {
            -webkit-transform: rotate(45deg) translate(-7px, -3px);
            transform: rotate(45deg) translate(-7px, -3px);
        }

        .btn-toggle-close a {
            margin-top: 20px !important;
            display: block;
        }

        .orders-details-inner-sub-2 {
            margin-top: 15px;
        }

        .orders-details-inner-sub-2 img {
            width: 60px;
            float: left;
            margin-right: 15px;
        }

        .orders-details-inner-sub-2 h4 a {
            color: #888;
        }

        .orders-details-inner-sub-2 p {
            padding-top: 0px;
            font-size: 14px;
            color: #333;
        }

        @media (max-width: 767px) {
            .orders-details-inner-sub-2 {
                height: 100%;
                display: block;
                margin: 40px auto;
            }
        }

        .panel-body-row {
            text-align: left;
        }

        .img-box {
            text-align: center;
        }

        .img-box .product-img {
            height: 80px;
            width: auto !important;
        }

        .product-img {
            width: 100% !important;
        }

        .grand-total p {
            text-align: left;
            padding: 0px;
        }

        .panel-heading, .panel-body {
            border: 1px solid #e5e5e5;
        }

        .panel-heading {
            border-bottom: none;
        }

        .orders-details {
            border: 1px solid #158cba;
            border-radius: 5px;
        }

        .media-body {
            text-align: center;
        }

        .order_detail_item_name {
            color: #444;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        function myFunction(x) {
            
            x.classList.toggle("change");
        }
    </script>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row dashboard-page">
            
                <?php echo $__env->make('partials.front.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <div class="col-md-9 content">
                        <div class="page-title">
                            <h2><?php echo app('translator')->getFromJson('Your Orders'); ?></h2>
                        </div>
                        <div class="card">
                           
                            <div class="card-body">
                                
                                <div class="table-responsive">
                                    <table class="table table-borderless ">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th><?php echo app('translator')->getFromJson('Order No.'); ?></th>
                                                <th><?php echo app('translator')->getFromJson('Order Date'); ?></th>
                                                <!-- <th><?php echo app('translator')->getFromJson('Products'); ?></th> -->
                                                <th><?php echo app('translator')->getFromJson('Order Status'); ?></th>
                                                <th><?php echo app('translator')->getFromJson('Payment Status'); ?></th>
                                                <th><?php echo app('translator')->getFromJson('Total'); ?></th>
                                                
                                                <th><?php echo app('translator')->getFromJson('Invoice'); ?></th>
                                                <!-- <th>Action</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                            $orderTotal = 0;
                                            $totOrder = 0;
                                            $orderTotalPrice = 0;
                                            
                                        ?>
                                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <?php 

                                            $orderProduct = \DB::table('order_product')->select('unit_price', 'quantity')->where('order_id',$order->id)->first();
                                           
                                            
                                        ?>
                                        
                                      
                                        
                                            <tr>
                                                
                                                <td>
                                                    <a href="javascript:void();" class="btn-toggle-close" data-toggle="modal" data-target="#multiCollapseExample<?php echo e($key+1); ?>">#<?php echo e($order->getOrderId()); ?></a>
                                                </td>
                                                <td>
                                                    <?php echo e($order->created_at->toFormattedDateString()); ?>

                                                </td>
                                                
                                                <td>

                                                    <?php if($order->is_processed =='0'): ?> 
                                                        <span class="text-danger"><?php echo app('translator')->getFromJson('Pending'); ?></span>
                                                    <?php elseif($order->is_processed =='1'): ?>
                                                        <span class="text-primary"><?php echo app('translator')->getFromJson('Refunded'); ?></span>
                                                    <?php elseif($order->is_processed =='2'): ?>
                                                        <span class="text-danger"><?php echo app('translator')->getFromJson('Cancelled'); ?></span>
                                                    <?php elseif($order->is_processed =='3'): ?>
                                                        <span class="text-success"><?php echo app('translator')->getFromJson('Delivered'); ?></span>
                                                    <?php elseif($row->is_not_online_payment() && $row->paid == 0): ?>
                                                        <span class="text-warning"><?php echo app('translator')->getFromJson('Failed'); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            
                                                <td>
                                                    <?php if($order->is_not_online_payment() && $order->paid == 0): ?>
                                                        <span class="text-warning"><?php echo app('translator')->getFromJson('Failed'); ?></span>
                                                    <?php else: ?>
                                                        <?php if($order->paid): ?>
                                                            <span class="text-success"><?php echo app('translator')->getFromJson('Paid'); ?></span>
                                                        <?php else: ?>
                                                            <span class="text-danger"><?php echo app('translator')->getFromJson('Unpaid'); ?></span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                
                                                <td>
                                                <?php 
              
                                                    
                                                    $orderTotal = $order->total -  ($order->coupon_amount + $order->wallet_amount)+ $order->shipping_cost;
                                                ?>
                                                <i class="fa fa-rupee"></i> <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($orderTotal)); ?>

                                                
                                                 
                                                </td>

                                                

                                                <td>
                                                <?php if($order->is_processed == 3): ?>
                                            
                                                    <a target="_blank" title="View Invoice" class ="btn btn-info btn-sm" href="<?php echo e(route('front.orders.show', ['id'=>$order->id])); ?>"><i class="fa fa-eye"></i></a>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                                </td>

                                                <!-- <td>
                                                    <?php if(isset($order->vendor->user_id) && $order->vendor->user_id == \Auth::user()->id && Auth::user()->can('update', App\Order::class)): ?> 
                                                        <a title="Edit" class ="btn btn-info btn-sm" href="<?php echo e(route('front.orders.edit', ['id'=>$order->id])); ?>"><i class="fa fa-pencil"></i></a>
                                                    <?php endif; ?>
                                                
                                                    <?php echo Form::model($order, ['method'=>'patch', 'action'=>['FrontOrdersController@hide', $order->id], 'id'=> 'hide-form-'.$order->id, 'style'=>'display: none;']); ?>

                                                    <?php echo Form::close(); ?>

                                                    <?php if(Auth::user()->can('delete', App\Order::class)): ?>
                                                    <a href="" class='btn btn-sm btn-danger'
                                                    onclick="
                                                            if(confirm('<?php echo app('translator')->getFromJson('Are you sure you want to delete this?'); ?>')) {
                                                            event.preventDefault();
                                                            $('#hide-form-<?php echo e($order->id); ?>').submit();
                                                            } else {
                                                            event.preventDefault();
                                                            }
                                                            "
                                                    ><i class="fa fa-trash"> </i></a>
                                                    <?php endif; ?>
                                                </td> -->
                                            </tr>
                                            <td>
                                            <div class="modal fade" id="multiCollapseExample<?php echo e($key+1); ?>">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <?php
                                                            $tracking_data = '';
                                                            if(!empty( $order->tracking_id ) ){
                                                                $tracking_data = '<span class="pull-right">'.__("Track Your Order Using this Tracking ID :").' <strong>'. $order->tracking_id.'</strong></span>';
                                                            }
                                                        ?>
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" style="color:#000;" id="exampleModalLabel"><strong> <?php echo app('translator')->getFromJson('Order'); ?> #<?php echo e($order->getOrderId()); ?> </strong> <?php echo $tracking_data; ?></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body table-responsive">
                                                                <table class="table table-bordered">
                                                                    <thead  class="thead-dark">
                                                                        <tr>
                                                                            
                                                                            <th><?php echo app('translator')->getFromJson('Image:'); ?></th>
                                                                            <th><?php echo app('translator')->getFromJson('JN WEB ID:'); ?></th>
                                                                            <th><?php echo app('translator')->getFromJson('Name:'); ?></th>
                                                                            <th><?php echo app('translator')->getFromJson('Price:'); ?></th>
                                                                            <th><?php echo app('translator')->getFromJson('Qty:'); ?></th>
                                                                            <th><?php echo app('translator')->getFromJson('Total:'); ?></th>
                                                                            
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php 
                                                                        $totalPrice = 0;
                                                                        $orderTotal = 0;
                                                                        $subTotal = 0;
                                                                        $GST = 0 ;
                                                                        $valueAdded = 0;
                                                                        $cartTotal = 0;
                                                                        $totalAmount = 0;
                                                                    ?>
                                                                        <?php $__currentLoopData = $order->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        
                                                                            <?php 
                                                                                $GST += $product->gst_three_percent;
                                                                                $valueAdded += $product->vat_rate;
                                                                                $totalPrice = $product->product_discount != null ? $product->new_price : $product->old_price ;   
                                                                                 
                                                                                
                                                                            ?>

                                                                        <tr>
                                                                            
                                                                        
                                                                            <td>
                                                                                <a target="_blank" href="<?php echo e(route('front.product.show', [$product->slug])); ?>">
                                                                                    <?php if($product->photo): ?>
                                                                                        <?php
                                                                                            $image_url = \App\Helpers\Helper::check_image_avatar($product->photo->name, 150);
                                                                                        ?>
                                                                                        <img class="img-responsive product-img" height="50" width="50"   src="<?php echo e($image_url); ?>" alt="<?php echo e($product->name); ?>"  />
                                                                                    <?php else: ?>
                                                                                        <img src="https://via.placeholder.com/150x150?text=No+Image"  class="img-responsive product-img" alt="<?php echo e($product->name); ?>" />
                                                                                    <?php endif; ?>
                                                                                </a>
                                                                            </td>
                                                                            <td><?php echo e($product->jn_web_id); ?></td>
                                                                            <td><a target="_blank" href="<?php echo e(route('front.product.show', [$product->slug])); ?>"><?php echo e($product->name); ?></a></td>
                                                                            
                                                                            <td> <i class="fa fa-rupee"></i><?php echo e(isset($totalPrice) && $totalPrice!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($totalPrice) : '0'); ?>

                                                                            </td>
                                                                            <td><?php echo e($product->pivot->quantity); ?></td>
                                                                            <td>
                                                                            <?php 
                                                                            $totalPriceAmount =  $totalPrice * $product->pivot->quantity;
                                                                            $orderTotal +=$totalPriceAmount;
                                                                            ?>
                                                                            <i class="fa fa-rupee"></i><?php echo e(isset($totalPriceAmount) && $totalPriceAmount!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($totalPriceAmount) : '0'); ?>

                                                                            </td>
                                                                        </tr>   
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                        <?php 
                                                                         
                                                                             
                                                                            $subTotal = $orderTotal - $GST - $valueAdded;
                                                                            $cartTotal += config('settings.shipping_cost') + $subTotal + $GST + $valueAdded ;
                                                                        ?>
                                                                        <tr>
                                                                            <td align="right" colspan="5"><strong><?php echo app('translator')->getFromJson('Sub Total:'); ?></strong></td>
                                                                            <th> 
                                                                                <i class="fa fa-rupee"></i><?php echo e(isset($subTotal) && $subTotal!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($subTotal) : '0'); ?>

                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  align="right" colspan="5"><strong><?php echo app('translator')->getFromJson('GST'); ?></strong></td>
                                                                            <th> 
                                                                                <i class="fa fa-rupee"></i><?php echo e(isset($GST) && $GST!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format(round($GST)) : '0'); ?>

                                                                            </th>
                                                                        </tr>

                                                                        <tr>
                                                                            <td  align="right" colspan="5"><strong><?php echo app('translator')->getFromJson('Value Added'); ?></strong></td>
                                                                            <th> 
                                                                                <i class="fa fa-rupee"></i><?php echo e(isset($valueAdded) && $valueAdded!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format(round($valueAdded)) : '0'); ?>

                                                                            </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td  align="right" colspan="5"><strong><?php echo app('translator')->getFromJson('Order Total:'); ?></strong></td>
                                                                            <th> 
                                                                                <i class="fa fa-rupee"></i><?php echo e(isset($orderTotal) && $orderTotal!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($orderTotal) : '0'); ?>

                                                                            
                                                                            </th>
                                                                        </tr>
                                                                        <!-- <tr>
                                                                            <td  align="right" colspan="4"><strong><?php echo app('translator')->getFromJson('Tax:'); ?></strong></td>
                                                                            <th> <?php echo e($order->tax); ?> % </th>
                                                                        </tr> -->
                                                                        <tr>
                                                                            <td  align="right" colspan="5"><strong><?php echo app('translator')->getFromJson('Shipping Cost:'); ?></strong></td>
                                                                            <th> <?php echo e(isset($order->shipping_cost) ? currency_format($order->shipping_cost, $order->currency) : currency_format(0, $order->currency)); ?> </th>
                                                                        </tr>
                                                                         
                                                                        <?php if($order->wallet_amount && $order->wallet_amount > 0): ?>
                                                                            <tr>
                                                                                <td  align="right" colspan="5"><strong><?php echo app('translator')->getFromJson('Wallet Used:'); ?></strong></td>
                                                                                <th> 
                                                                                <i class="fa fa-rupee"></i> <?php echo e(isset($order->wallet_amount) && $order->wallet_amount!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($order->wallet_amount) : '0'); ?>

                                                                                </th>
                                                                            </tr>
                                                                        <?php endif; ?>
                                                                        <?php if($order->coupon_amount && $order->coupon_amount > 0): ?>
                                                                            <tr>
                                                                                <td  align="right" colspan="5"><strong><?php echo app('translator')->getFromJson('Coupon Discount:'); ?></strong></td>
                                                                                <th> 
                                                                                <i class="fa fa-rupee"></i> <?php echo e(isset($order->coupon_amount) && $order->coupon_amount!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format(number_format($order->coupon_amount)) : '0'); ?>

                                                                                
                                                                                </th>
                                                                            </tr>
                                                                    
                                                                        <?php endif; ?>
                                                                        <?php
                                                                        
                                                                            $totalAmount = $orderTotal -  ($order->coupon_amount + $order->wallet_amount)+$order->shipping_cost;
                                                                            
                                                                        ?>
                                                                    
                                                                        <tr>
                                                                            <td  align="right" colspan="5"><strong><?php echo app('translator')->getFromJson('Total After Discount:'); ?></strong></td>
                                                                            <th> 
                                                                            
                                                                            <i class="fa fa-rupee"></i><?php echo e(isset($totalAmount) && $totalAmount!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($totalAmount) : '0'); ?>

                                                                              </th>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                        </div>
                                                    </div>        
                                                </div>
                                            </div>
                                            </td>                
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        
                                        <!-- <tr>
                                            <th colspan="3"><?php echo app('translator')->getFromJson('Order Total:'); ?></th>
                                            <td ><strong><?php echo e(currency_format($totOrder)); ?></strong></td>
                                        </tr> -->
                                            
                                            
                                        
                                        </tbody>
                                    </table>
                                </div>
                                
                                        
                            
                                <div class="clearfix"></div>
                                
                            
                            </div>
                        </div>
                </div>
             
        </div>
          
    </div>
    <!-- Modal -->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

<script src="<?php echo e(asset('js/script.js')); ?> "></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>