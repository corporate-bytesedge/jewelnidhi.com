

<?php $__env->startSection('title'); ?><?php echo app('translator')->getFromJson('Vendor Dashboard'); ?> - <?php echo e(config('app.name')); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
	<style>
		.action-btn {
			margin-bottom: 5px;
		}
		#message {
			resize: vertical;
		}
		.payout-submission-message {
			margin-top: 25px;
		}
		.venderDescription {
			margin-top: 10px;
		}
		.edit-counter .panel-back {
			background-color: #fff;
			border-radius: 0px;
			box-shadow: 0px 0px 20px 0px #d8d8d8;
		}
		.edit-counter .noti-box {
			min-height: 100px;
			padding: 4px;
		}
		.edit-counter .panel-back>a {
			display: inline-block;
			padding: 7px;
			text-decoration: none
		}
		.edit-counter a {
			color:#656565;
		}
		.edit-counter .noti-box .icon-box {
			display: block;
			line-height: 43px;
			border-radius: 0px;
			box-shadow: 0px 0px 14px 0px #d8d8d8;
			margin-top: -22px;
			margin-left: 6px;
		}
		.edit-counter p.text-muted {
			display : inline;
		}
		.edit-counter .text-box {
			text-align: right;
			padding-right: 10px;
		}
	</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php if(config('settings.toast_notifications_enable')): ?>
    <script>
        toastr.options.closeButton = true;
        <?php if(session()->has('success')): ?>
            toastr.success("<?php echo e(session('success')); ?>");
        <?php endif; ?>
    </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
	<?php echo app('translator')->getFromJson('Vendor Dashboard'); ?>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
	
	<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-shipment-orders', App\Other::class)): ?>
	<?php if(count(auth()->user()->shipments) > 0): ?>
	<div class="row">
		<div class="col-md-12">
	        <?php if(session()->has('order_passed_to_shipment')): ?>
	            <div class="alert alert-success alert-dismissible" role="alert">
	                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	                    <span aria-hidden="true">&times;</span>
	                </button>
	                <strong><?php echo e(session('order_passed_to_shipment')); ?></strong>
	            </div>
	        <?php endif; ?>
			<blockquote><?php echo app('translator')->getFromJson('Orders received to your shipments'); ?></blockquote>
			<?php $__currentLoopData = auth()->user()->shipments()->orderBy('id', 'asc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="col-md-4 well">
				<h4><div class="label label-primary"><?php echo e($shipment->name.' (' . __('ID:') . ' '.$shipment->id.')'); ?></div></h4>
				<ul class="list-group" id="orders-list">
					<?php $__currentLoopData = $shipment->orders()->where('location_id', Auth::user()->location_id)->where('is_processed', 0)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php if($order->shipments()->orderBy('id', 'desc')->first()->id == $shipment->id): ?>
						<a href="<?php echo e(route('manage.orders.edit', $order->id)); ?>"><li class="list-group-item">
							#<?php echo e($order->getOrderId()); ?>

						<span class="text-muted pull-right"><?php echo e($order->created_at->format('H:i M d, Y')); ?></span></li></a>
						<?php endif; ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul>
			</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
	</div>
	<hr>
	<?php endif; ?>
	<?php endif; ?>



	 <div class="row"> 
	 <div class="col-md-12 edit-counter"> 
	 
		<div class="row">
		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('read', App\Product::class)): ?>  
			<div class="col-lg-4 col-xs-12">
				<div class="panel panel-back panel-success noti-box">
					<span class="icon-box panel-heading set-icon">
						<i class="fa fa-sitemap"></i>
					</span>
					<div class="text-box">
						<p class="main-text"> <?php echo e($products_count); ?> <?php echo app('translator')->getFromJson('Products'); ?></p>
						<p class="text-muted"><?php echo app('translator')->getFromJson('Total Products!'); ?></p>
					</div>
					<a href="<?php echo e(route('manage.products.index')); ?>"> <?php echo app('translator')->getFromJson('More Details'); ?> </a>
				</div>
				
			</div>
		<?php endif; ?>
		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('read', App\Order::class)): ?>  
			<div class="col-lg-4 col-xs-12">
				<div class="panel panel-back panel-success noti-box">
					<span class="icon-box panel-heading set-icon">
						<i class="fa fa-sitemap"></i>
					</span>
					<div class="text-box">
						<p class="main-text"> <?php echo e($order_count); ?> <?php echo app('translator')->getFromJson('Order'); ?></p>
						<p class="text-muted"><?php echo app('translator')->getFromJson('Total Order!'); ?></p>
					</div>
					<a href="<?php echo e(route('manage.orders.index')); ?>"> <?php echo app('translator')->getFromJson('More Details'); ?> </a>
				</div>
				
			</div>
		</div>
		<?php endif; ?>
	

	

	<?php if(Auth::user()->can('read', App\Banner::class) || Auth::user()->can('view-subscribers', App\Other::class) || Auth::user()->can('read-discount', App\Voucher::class)): ?>
	<br><br>
	<?php endif; ?>

	<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-sales', App\Other::class)): ?>
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1 col-md-12">
			<div class="chartjs-top-selling-products">
				<div class="panel panel-primary">
					<div class="panel-heading"><strong><?php echo app('translator')->getFromJson('Top Selling Products'); ?></strong></div>
						<?php echo $chartjs_top_selling_products->render(); ?>

					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	
	<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-sales', App\Other::class)): ?>
	<br><br>
	<?php endif; ?>

	<?php if(Auth::user()->can('read', App\Category::class) && Auth::user()->can('read', App\Brand::class) && Auth::user()->can('read', App\Product::class) && Auth::user()->can('read', App\User::class) && Auth::user()->can('read', App\Banner::class)): ?>
	<div class="row">
		<div class="col-lg-10 col-lg-offset-1 col-md-12">
			<div class="chartjs-count-data">
				<?php echo $charts_count_data->render(); ?>

			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('read', App\Category::class)): ?>
	<?php if(count($root_categories) > 0): ?>
	<div class="row">
		<div class="col-md-12">
			<h4 class="text-info"><?php echo app('translator')->getFromJson('Categories Tree:'); ?></h4>
			<ul id="tree1">
				<?php $__currentLoopData = $root_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<li>
						<?php echo e($category->name . ' (' . __('ID:') . ' '.$category->id.')'); ?>

						<?php if(count($category->categories)): ?>
							<?php echo $__env->make('partials.manage.subcategories', ['childs' => $category->categories], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
						<?php endif; ?>
					</li>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</ul>
		</div>
	</div>
	<?php endif; ?>
	<?php endif; ?> 

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>