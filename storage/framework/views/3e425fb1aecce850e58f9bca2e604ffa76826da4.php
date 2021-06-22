<?php $__env->startComponent('mail::message'); ?>
# <?php echo e(config('custom.mail_message_title_order_placed')); ?>


![](<?php echo e(asset('/img/'.config('custom.mail_logo'))); ?>)

<?php echo e(config('custom.mail_message_order_placed')); ?> <?php echo app('translator')->getFromJson('Your Order Number is :order_id', ['order_id'=>$order->getOrderId()]); ?>.
<hr>
<?php $__env->startComponent('mail::table'); ?>
| <?php echo app('translator')->getFromJson('Item'); ?>      | <?php echo app('translator')->getFromJson('Price'); ?>                                   | <?php echo app('translator')->getFromJson('Quantity'); ?>             | 
<?php echo app('translator')->getFromJson('Total'); ?>                                                                                   |
| :----------------: | :----------------------------------------------: | :---------------------------: | :----------------------------------------------------------------------------------------------: |
<?php $__currentLoopData = $order->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
| <?php echo e($product->name); ?> | <?php echo e(currency_format($product->pivot->unit_price)); ?> | <?php echo e($product->pivot->quantity); ?> | <?php echo e(currency_format($product->pivot->total, $order->currency)); ?>                                                      |
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
|                    |                                                  | **<?php echo app('translator')->getFromJson('Subtotal'); ?>**         | <?php echo e(currency_format($order->total, $order->currency)); ?>                                                               |
|                    |                                                  | **<?php echo app('translator')->getFromJson('Tax'); ?>**              | + <?php echo e($order->tax); ?> %                                                                              |
|                    |                                                  | **<?php echo app('translator')->getFromJson('Shipping Cost'); ?>**    | <?php echo e(isset($order->shipping_cost) ? currency_format($order->shipping_cost, $order->currency) : currency_format(0, $order->currency)); ?>   |
<?php if($order->coupon_amount && $order->coupon_amount > 0): ?>
|                    |                                                  | **<?php echo app('translator')->getFromJson('Coupon Discount'); ?>**  | - <?php echo e(currency_format($order->coupon_amount, $order->currency)); ?>                                             |
<?php endif; ?>
<?php if($order->wallet_amount && $order->wallet_amount > 0): ?>
|                    |                                                  | **<?php echo app('translator')->getFromJson('Wallet Used'); ?>**      | - <?php echo e(currency_format($order->wallet_amount, $order->currency)); ?>                                                     |
<?php endif; ?>
|                    |                                                  | **<?php echo app('translator')->getFromJson('Total'); ?>**            | <?php echo e(currency_format($order->shipping_cost + $order->total - $order->wallet_amount - $order->coupon_amount + ($order->total * $order->tax) / 100, $order->currency)); ?> |
|                    |                                                  | **<?php echo app('translator')->getFromJson('Payment Method'); ?>**   | <?php echo e($order->payment_method); ?> |
|                    |                                                  | **<?php echo app('translator')->getFromJson('Payment Status'); ?>**   | <?php echo e($order->paid ? __('Paid') : __('Unpaid')); ?> |
<?php echo $__env->renderComponent(); ?>

<?php $__env->startComponent('mail::panel'); ?>
**<?php echo app('translator')->getFromJson('Shipping Info'); ?>**<br>
<hr>
**<?php echo e($order->address->first_name . ' ' . $order->address->last_name); ?>**,<br>
<?php echo e($order->address->address); ?><br>
<?php echo e($order->address->city . ', ' . $order->address->state . ' - ' . $order->address->zip); ?><br>
<?php echo e($order->address->country); ?>.<br>
**<?php echo app('translator')->getFromJson('Phone'); ?>:** <?php echo e($order->address->phone); ?><br>
**<?php echo app('translator')->getFromJson('Email'); ?>:** <?php echo e($order->address->email); ?><br>
<?php echo $__env->renderComponent(); ?>

<?php $__env->startComponent('mail::button', ['url' => url('/orders')]); ?>
<?php echo app('translator')->getFromJson('View Your Orders'); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo app('translator')->getFromJson('Thanks,'); ?><br>
<?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?>
