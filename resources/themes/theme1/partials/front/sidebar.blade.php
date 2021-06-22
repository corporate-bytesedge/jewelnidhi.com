<div class="col-md-3">
			<div class="bg-light left-sidebar" id="sidebar-wrapper">
				<div class="list-group list-group-flush">
					<ul id="main-menu">
                    <li>
						<a class="list-group-item bg-light {{Html::isActive([route('front.account')])}}" href="{{route('front.account')}}"><i class="fa fa-dashboard fa-2x"></i>@lang('Dashboard')</a>
					</li>
					<li>
						<a class="list-group-item bg-light {{Html::isActive([route('front.settings.profile')])}}" href="{{route('front.settings.profile')}}"><i class="fa fa-wrench fa-2x"></i> @lang('Profile Settings')<span class="fa arrow"></span></a>
					</li>
		            
					@if (Auth::user()->can('read', App\Customer::class) )
					<li>
		                <a class="list-group-item bg-light {{Html::isActive([route('front.customers.customer')])}}" href="{{route('front.customers.customer')}}"><i class="fa fa-user fa-2x"></i> @lang('Customer')<span class="fa arrow"></span></a>
					</li>
					@endif

					
					 
					<li>
						<a class="list-group-item bg-light {{Html::isActive([route('front.orders.index')])}}" href="{{route('front.orders.index')}}"><i class="fa fa-shopping-cart fa-2x"></i> @lang('Orders')<span class="fa arrow"></span></a>
					</li>
					 
					@if (!Auth::user()->isApprovedVendor() && Auth::user()->isSuperAdmin())
					<li>
						<a class="list-group-item bg-light {{Html::isActive([route('front.wallet-history.index')])}}" href="{{route('front.wallet-history.index')}}"><i class="fa fa-google-wallet fa-2x"></i> @lang('Wallet History')<span class="fa arrow"></span></a>
					</li> 
					@endif
					@if (Auth::user()->can('read', App\Address::class) &&  !Auth::user()->isSuperAdmin() )
					<li>
						<a class="list-group-item bg-light {{Html::isActive([route('front.addresses.index')])}}" href="{{route('front.addresses.index')}}"><i class="fa fa-truck fa-2x"></i> @lang('Addresses')<span class="fa arrow"></span></a>
					</li>
					@endif
					<li>
						<!-- <a class="list-group-item bg-light {{Html::isActive([route('manage.settings.profile')])}}" href="{{route('manage.settings.profile')}}"><i class="fa fa-user fa-2x"></i> <em>{{'@'.Auth::user()->username}}</em><span class="fa arrow"></span></a> -->
						<ul>
							<li>
								<a onclick="logout();" class="list-group-item bg-light" href="#">@lang('Logout') <i class="fa fa-sign-out"></i></a>
							</li>
						
							<form id="logout-form" action="{{ route('logout') }}" method="POST">
								{{ csrf_field() }}
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
