

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('Payment'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styledfsfsfdsfs'); ?>
    <style>
        table a:not(.btn), .table a:not(.btn) {
            text-decoration: none;
        }
        .cart-header {
            font-size: 1.1em;
        }
        .img-box img{
            height: 80px;
            width: auto!important;
        }
        .product-image{
            width:100%!important;
        }
        input#coupon-btn {
            margin-top: 6px;
        }
        <?php if(!$errors->has('bank_transfer_reference_id')): ?>
        .banktransfer-fields {
            display: none;
        }
        <?php endif; ?>
        #bank_transfer_reference_id {
            margin-bottom: 5px;
        }
        .banktransfer-fields ul {
            list-style-type: none;
        }
        @media  screen and (max-width: 767px) {
            input#coupon-btn {
                margin-top: 0px;
            }
        }
    </style>
    <style>
        table {
          border: 1px solid #ccc;
          border-collapse: collapse;
          margin: 0;
          padding: 0;
          width: 100%;
          table-layout: fixed;
        }
        table caption {
          font-size: 1.5em;
          margin: .5em 0 .75em;
        }
        table tr {
          background: #fff;
          border: 1px solid #ddd;
          padding: .35em;
        }
        table th,
        table td {
          padding: .625em;
          text-align: center;
        }
        table th {
          font-size: .85em;
          letter-spacing: .1em;
          text-transform: uppercase;
        }
        @media  screen and (max-width: 600px) {
          table {
            border: 0;
          }
          table caption {
            font-size: 1.3em;
          }
          table thead {
            border: none;
            clip: rect(0 0 0 0);
            height: 1px;
            margin: -1px;
            overflow: hidden;
            padding: 0;
            position: absolute;
            width: 1px;
          }
          table tr {
            border-bottom: 3px solid #ddd;
            display: block;
            margin-bottom: .625em;
          }
          table td {
            border-bottom: 1px solid #ddd;
            display: block;
            font-size: .8em;
            text-align: right;
          }
          table td:before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
            text-transform: uppercase;
          }
          table td:last-child {
            border-bottom: 0;
          }
        }
        .payment-logo {
            width: 100px;
        }
        @media (max-width:768px) {
            .cart-container table tr {
               display: table-row-group;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
        	<ul class="breadcrumb">
        		<li><a href="<?php echo e(url('/')); ?>"><?php echo app('translator')->getFromJson('Home'); ?></a></li>
        		<li><?php echo app('translator')->getFromJson('Checkout'); ?></li>
        	</ul>
        </div>
    </div>
</div>
<div class="">
    <div class="col-md-12 cart-container">
        <?php echo $__env->make('includes.form_errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="custom_checkout col-md-offset-1 col-md-7">
            <?php echo $__env->make('partials.front.includes.checkout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
        <div class="row shipping_details-p col-md-3">
            <div class="col-sm-12 custom_shipping_css">
                <h3><?php echo app('translator')->getFromJson('Shipping Details:'); ?></h3>
                <strong><?php echo e($customer->first_name . ' ' . $customer->last_name); ?></strong>,<br>
                <?php echo e($customer->address); ?><br>
                <?php echo e($customer->city . ', ' . $customer->state . ' - ' . $customer->zip); ?><br>
                <?php echo e($customer->country); ?>.<br>
                <strong><?php echo app('translator')->getFromJson('Phone:'); ?></strong> <?php echo e($customer->phone); ?><br>
                <strong><?php echo app('translator')->getFromJson('Email:'); ?></strong> <?php echo e($customer->email); ?>

                <hr>
                <?php echo Form::open(['method'=>'post', 'action'=>'CheckoutController@changeShippingAddress', 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait') . '"; return true;']); ?>

                    <button type="submit" name="submit_button" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> <?php echo app('translator')->getFromJson('Change Address'); ?></button>
                <?php echo Form::close(); ?>

                <br>
                <?php if(session()->has('coupon_invalid')): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <?php echo e(session('coupon_invalid')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session()->has('coupon_amount') && session()->has('coupon_valid_above_amount') && session('coupon_valid_above_amount') < Cart::total()): ?>
                    <?php if(session()->has('coupon_code')): ?>
                        <h5 class="text-success"><?php echo app('translator')->getFromJson('Coupon Applied:'); ?> <span class="text-muted"><?php echo e(session('coupon_code')); ?></span></h5>
                        <h5 class="text-success"><?php echo app('translator')->getFromJson('Discount:'); ?> <span class="text-muted"><?php echo e(currency_format(session('coupon_amount'))); ?></span></h5>
                    <?php else: ?>
                        <h4><?php echo app('translator')->getFromJson('Coupon Discount:'); ?> <?php echo e(currency_format(session('coupon_amount'))); ?></h4>
                    <?php endif; ?>
                    <a id="have-coupon" href=""><?php echo app('translator')->getFromJson('Change Coupon?'); ?></a>
                <?php else: ?>
                    <a id="have-coupon" href=""><?php echo app('translator')->getFromJson('Have a Coupon?'); ?></a>
                <?php endif; ?>
                <div class="coupon-box">
                    <?php echo Form::open(['method'=>'post', 'action'=>'FrontCouponsController@checkCoupon', 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait') . '"; return true;', 'class'=>'form-inline']); ?>

                        <div class="form-group">
                        <?php echo Form::text('coupon', null, ['class'=>'form-control', 'placeholder'=>__('Enter Coupon'), 'required']); ?>

                        </div>
                        <?php echo Form::submit(__('Apply'), ['id'=>'coupon-btn', 'class'=>'btn btn-primary', 'name'=>'submit_button']); ?>

                    <?php echo Form::close(); ?>

                </div>
            </div>
            <div class="col-sm-12 text-left">
                <h3><?php echo app('translator')->getFromJson('Select Your Payment Method'); ?></h3>
                <hr>

                <?php echo Form::open(['method'=>'post', 'action'=>'CheckoutController@processPayment', 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait') . '"; return true;']); ?>


                    <?php
                    $payment_methods_count = 0;
                    ?>
                    <div class="radio">
                        <?php if(config('cod.enable')): ?>
                        <?php
                        $payment_methods_count++;
                        ?>
                        <label>
                            <input id="cod" type="radio" name="options" value="cod" checked>
                            <img class="payment-logo" src="<?php echo e(route('imagecache', ['original', 'cod.png'])); ?>" alt="<?php echo app('translator')->getFromJson('Cash on Delivery'); ?>" class="img-responsive" />
                            <?php echo app('translator')->getFromJson('Cash on Delivery'); ?>
                        </label>
                        <hr>
                        <?php endif; ?>
                        <?php if(config('banktransfer.enable')): ?>
                        <?php
                        $payment_methods_count++;
                        ?>
                        <label>
                            <input <?php if($errors->has('bank_transfer_reference_id')): ?> checked <?php endif; ?> id="banktransfer" type="radio" name="options" value="banktransfer">
                            <img class="payment-logo" src="<?php echo e(route('imagecache', ['original', 'banktransfer.png'])); ?>" alt="<?php echo app('translator')->getFromJson('Bank Transfer'); ?>" class="img-responsive" />
                            <?php echo app('translator')->getFromJson('Bank Transfer'); ?>
                        </label>
                        <hr>
                        <?php endif; ?>
                        <?php if(config('paypal.enable')): ?>
                        <?php
                        $payment_methods_count++;
                        ?>
                        <label>
                            <input id="paypal" type="radio" name="options" value="paypal">
                            <img class="payment-logo" src="<?php echo e(route('imagecache', ['original', 'paypal.png'])); ?>" alt="<?php echo app('translator')->getFromJson('Paypal'); ?>" class="img-responsive" />
                            <?php echo app('translator')->getFromJson('Paypal'); ?>
                        </label>
                        <hr>
                        <?php endif; ?>


                        <?php if(config('paystack.enable')): ?>
                                <?php
                                    $payment_methods_count++;
                                ?>
                                <label>
                                    <input id="paystack" type="radio" name="options" value="paystack">
                                    <img class="payment-logo" src="<?php echo e(route('imagecache', ['original', 'paystack.png'])); ?>" alt="<?php echo app('translator')->getFromJson('Paystack'); ?>" class="img-responsive" />
                                    <?php echo app('translator')->getFromJson('Paystack'); ?>
                                </label>
                                <hr>
                            <?php endif; ?>



                        <?php if(config('paytm.enable')): ?>
                                <?php
                                    $payment_methods_count++;
                                ?>
                                <label>
                                    <input id="paytm" type="radio" name="options" value="paytm">
                                    <img class="payment-logo" src="<?php echo e(route('imagecache', ['original', 'paytm.png'])); ?>" alt="<?php echo app('translator')->getFromJson('Paytm'); ?>" class="img-responsive" />
                                    <?php echo app('translator')->getFromJson('Paytm'); ?>
                        </label>
                        <hr>
                        <?php endif; ?>

                        <?php if(config('pesapal.enable')): ?>
                        <?php
                        $payment_methods_count++;
                        ?>
                        <label>
                            <input id="pesapal" type="radio" name="options" value="pesapal">
                            <img class="payment-logo" src="<?php echo e(route('imagecache', ['original', 'pesapal.png'])); ?>" alt="<?php echo app('translator')->getFromJson('Pesapal'); ?>" class="img-responsive" />
                            <?php echo app('translator')->getFromJson('Pesapal'); ?>
                                </label>
                                <hr>
                            <?php endif; ?>


                        <?php if(config('stripe.enable')): ?>
                        <?php
                        $payment_methods_count++;
                        ?>
                        <label>
                            <input id="stripe" type="radio" name="options" value="stripe">
                            <img class="payment-logo" src="<?php echo e(route('imagecache', ['original', 'stripe.png'])); ?>" alt="<?php echo app('translator')->getFromJson('Stripe'); ?>" class="img-responsive" />
                            <?php echo app('translator')->getFromJson('Stripe'); ?>
                        </label>
                        <hr>
                        <?php endif; ?>
                        <?php if(config('razorpay.enable')): ?>
                        <?php
                        $payment_methods_count++;
                        ?>
                        <label>
                            <input id="razorpay" type="radio" name="options" value="razorpay">
                            <img class="payment-logo" src="<?php echo e(route('imagecache', ['original', 'razorpay.png'])); ?>" alt="<?php echo app('translator')->getFromJson('Razorpay'); ?>" class="img-responsive" />
                            <?php echo app('translator')->getFromJson('Razorpay'); ?>
                        </label>
                        <hr>
                        <?php endif; ?>
                        <?php if(config('instamojo.enable')): ?>
                        <?php
                        $payment_methods_count++;
                        ?>
                        <label>
                            <input id="instamojo" type="radio" name="options" value="instamojo">
                            <img class="payment-logo" src="<?php echo e(route('imagecache', ['original', 'instamojo.png'])); ?>" alt="<?php echo app('translator')->getFromJson('Instamojo'); ?>" class="img-responsive" />
                            <?php echo app('translator')->getFromJson('Instamojo'); ?>
                        </label>
                        <hr>
                        <?php endif; ?>
                        <?php if(config('payu.enable')): ?>
                        <?php if(config('payu.default') == 'payumoney'): ?>
                        <?php
                        $payment_methods_count++;
                        ?>
                        <label>
                            <input id="payumoney" type="radio" name="options" value="payumoney">
                            <img class="payment-logo" src="<?php echo e(route('imagecache', ['original', 'payumoney.png'])); ?>" alt="<?php echo app('translator')->getFromJson('PayUmoney'); ?>" class="img-responsive" />
                            <?php echo app('translator')->getFromJson('PayUmoney'); ?>
                        </label>
                        <hr>
                        <?php elseif(config('payu.default') == 'payubiz'): ?>
                        <?php
                        $payment_methods_count++;
                        ?>
                        <label>
                            <input id="payubiz" type="radio" name="options" value="payubiz">
                            <img class="payment-logo" src="<?php echo e(route('imagecache', ['original', 'payubiz.png'])); ?>" alt="<?php echo app('translator')->getFromJson('PayUbiz'); ?>" class="img-responsive" />
                            <?php echo app('translator')->getFromJson('PayUbiz'); ?>
                        </label>
                        <hr>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <?php if($payment_methods_count > 0): ?>
                    <div class="banktransfer-fields">
                        <p class="banktransfer-make-payment-msg">
                            <?php echo app('translator')->getFromJson('Make a payment of amount <strong>:amount</strong> to this bank account and provide transaction reference ID.', ['amount' => currency_format(Cart::total() + (Cart::total() * config('settings.tax_rate')) / 100)]); ?>
                        </p>
                        <ul class="list-group list-group-flush">
                            <li>
                                <strong><?php echo app('translator')->getFromJson('Account Number'); ?>:</strong> <?php echo e(config('banktransfer.account_number')); ?>

                            </li>
                            <li>
                                <strong><?php echo e(config('banktransfer.branch_code_label')); ?>:</strong> <?php echo e(config('banktransfer.branch_code')); ?>

                            </li>
                            <li>
                                <strong><?php echo app('translator')->getFromJson('Name'); ?>:</strong> <?php echo e(config('banktransfer.name')); ?>

                            </li>
                        </ul>

                        <div class="form-group">
                            <label for="bank_transfer_reference_id">
                                <strong><?php echo app('translator')->getFromJson('Transaction Reference ID'); ?></strong>
                            </label><br>
                            <input type="text" name="bank_transfer_reference_id" class="form-control<?php echo e($errors->has('bank_transfer_reference_id') ? ' has-error' : ''); ?>" id="bank_transfer_reference_id">
                        </div>
                    </div>
                    <button id="payment_button" class="btn btn-primary" type="submit" name="submit_button"><?php echo app('translator')->getFromJson('Place Order'); ?> <i class="fa fa-shopping-cart"></i></button>
                    <?php else: ?>
                    <div class="text-danger"><?php echo app('translator')->getFromJson('No payment methods available.'); ?></div>
                    <?php endif; ?>
                <?php echo Form::close(); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $('document').ready(function() {
            var couponBox = $('.coupon-box');
            // couponBox.hide();
            $('#have-coupon').on('click', function(e) {
                e.preventDefault();
                // couponBox.fadeIn();
            });

            var banktransferFields = $('.banktransfer-fields');

            var option = $('input[type=radio][name=options]:checked').val();
            if('banktransfer' === option) {
                banktransferFields.show();
            }

            $(document).on('change', 'input[type=radio][name=options]', function(e) {
                var option = this.value;
                if('banktransfer' === option) {
                    banktransferFields.fadeIn();
                } else {
                    banktransferFields.hide();
                }
            });
        });
    </script>
    <script>
        function updateWalletUse(){
            $('#cover-spin').show(0);
            refreshCartPage();
            setTimeout(function(){ $('#cover-spin').hide(0); }, 1000);
        }

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>