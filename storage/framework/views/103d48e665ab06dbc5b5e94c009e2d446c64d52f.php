<div class="col-md-3">
			<div class="bg-light left-sidebar" id="sidebar-wrapper">
				<div class="list-group list-group-flush">
					<ul id="main-menu">
                    <li>
						<a class="list-group-item bg-light <?php echo e(Html::isActive([route('front.account')])); ?>" href="<?php echo e(route('front.account')); ?>"><i class="fa fa-dashboard fa-2x"></i><?php echo app('translator')->getFromJson('Dashboard'); ?></a>
					</li>
					<li>
						<a class="list-group-item bg-light <?php echo e(Html::isActive([route('front.settings.profile')])); ?>" href="<?php echo e(route('front.settings.profile')); ?>"><i class="fa fa-wrench fa-2x"></i> <?php echo app('translator')->getFromJson('Profile Settings'); ?><span class="fa arrow"></span></a>
					</li>
		            
					<?php if(Auth::user()->can('read', App\Customer::class) ): ?>
					<li>
		                <a class="list-group-item bg-light <?php echo e(Html::isActive([route('front.customers.customer')])); ?>" href="<?php echo e(route('front.customers.customer')); ?>"><i class="fa fa-user fa-2x"></i> <?php echo app('translator')->getFromJson('Customer'); ?><span class="fa arrow"></span></a>
					</li>
					<?php endif; ?>

					
					 
					<li>
						<a class="list-group-item bg-light <?php echo e(Html::isActive([route('front.orders.index')])); ?>" href="<?php echo e(route('front.orders.index')); ?>"><i class="fa fa-shopping-cart fa-2x"></i> <?php echo app('translator')->getFromJson('Orders'); ?><span class="fa arrow"></span></a>
					</li>
					 
					<?php if(!Auth::user()->isApprovedVendor() && Auth::user()->isSuperAdmin()): ?>
					<li>
						<a class="list-group-item bg-light <?php echo e(Html::isActive([route('front.wallet-history.index')])); ?>" href="<?php echo e(route('front.wallet-history.index')); ?>"><i class="fa fa-google-wallet fa-2x"></i> <?php echo app('translator')->getFromJson('Wallet History'); ?><span class="fa arrow"></span></a>
					</li> 
					<?php endif; ?>
					<?php if(Auth::user()->can('read', App\Address::class) &&  !Auth::user()->isSuperAdmin() ): ?>
					<li>
						<a class="list-group-item bg-light <?php echo e(Html::isActive([route('front.addresses.index')])); ?>" href="<?php echo e(route('front.addresses.index')); ?>"><i class="fa fa-truck fa-2x"></i> <?php echo app('translator')->getFromJson('Addresses'); ?><span class="fa arrow"></span></a>
					</li>
					<?php endif; ?>
					<li>
						<!-- <a class="list-group-item bg-light <?php echo e(Html::isActive([route('manage.settings.profile')])); ?>" href="<?php echo e(route('manage.settings.profile')); ?>"><i class="fa fa-user fa-2x"></i> <em><?php echo e('@'.Auth::user()->username); ?></em><span class="fa arrow"></span></a> -->
						<ul>
							<li>
								<a onclick="logout();" class="list-group-item bg-light" href="#"><?php echo app('translator')->getFromJson('Logout'); ?> <i class="fa fa-sign-out"></i></a>
							</li>
						
							<form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST">
								<?php echo e(csrf_field()); ?>

							</form>

							<script>
								function logout() {
									var logoutForm = $('#logout-form');
									if (!logoutForm.hasClass('form-submitted')) {
										logoutForm.addClass('form-submitted');
										logoutForm.submit();
									}
								}
							</script>
						</ul>
					</li>
						
					</ul>
			
					
				</div>
			</div>
        </div>
