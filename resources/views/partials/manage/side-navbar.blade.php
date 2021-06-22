<nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
            <li class="text-center">
              <!--   <img height="128" width="128" src="{{Auth::user()->photo ? Auth::user()->photo->name : $default_photo}}" class="user-image img-responsive"/> -->
            </li>
            @if (Auth::user()->can('view-dashboard', App\Other::class) && \Auth::user()->isSuperAdmin())
                <li>
                    <a class="{{Html::isActive([route('manage.index')])}}" href="{{route('manage.index')}}"><i class="fa fa-dashboard fa-2x"></i> Dashboard</a>
                </li>
            @endif

            @if ((Auth::user()->can('read', App\Category::class)) && ((Auth::user()->can('delete', App\Category::class)) || (Auth::user()->can('update', App\Category::class)) || (Auth::user()->can('create', App\Category::class)) ))
                <li>
                    <a class="{{Html::isActive([route('manage.categories.index'), route('manage.silveritem.index'), route('manage.settings.style'),route('manage.settings.style'),route('manage.settings.metal'),route('manage.settings.puirty')])}}" href="{{route('manage.categories.index')}}"><i class="fa fa-tags fa-2x"></i> Category<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level {{ (request()->is('manage/categories') || request()->is('manage/settings/style') || request()->is('manage/settings/metal') || request()->is('manage/settings/puirty') || request()->is('manage/silveritem') ) ? 'in' : '' }}">

                        @if ((Auth::user()->can('read', App\Category::class)) || (Auth::user()->can('delete', App\Category::class)) || (Auth::user()->can('update', App\Category::class)) || (Auth::user()->can('create', App\Category::class)))
                                <li>
                                    <a class="{{ (request()->is('manage/categories')) ? 'active-menu' : '' }}" href="{{route('manage.categories.index')}}"><i class="fa fa-tags"></i>Categories</a>
                                </li>
                        @endif
                        <li>
                            <a class="{{ (request()->is('manage/settings/style')) ? 'active-menu' : '' }}" href="{{route('manage.settings.style')}}"><i class="fa fa-certificate"></i>Style</a>
                        </li>
                       

                        
                        
                        <!-- <li>
                            <a class="{{ (request()->is('manage/settings/metal')) ? 'active-menu' : '' }}"  href="{{route('manage.settings.metal')}}"><i class="fa fa-certificate"></i>Metal</a>
                        </li> -->
                        <!-- <li>
                            <a  class="{{ (request()->is('manage/settings/puirty')) ? 'active-menu' : '' }}" href="{{route('manage.settings.puirty')}}"><i class="fa fa-certificate"></i>Metal Puirty</a>
                        </li> -->
                        <li>
                            <a class="{{ (request()->is('manage/silveritem')) ? 'active-menu' : '' }}" href="{{route('manage.silveritem.index')}}"><i class="fa fa-money"></i>Silver Items</a>
                        </li>
                            
                    </ul>
                </li>
            @endif

            @if (($vendor = Auth::user()->isApprovedVendor()) || (Auth::user()->can('read', App\Product::class)) || (Auth::user()->can('delete', App\Product::class)) || (Auth::user()->can('update', App\Product::class)) || (Auth::user()->can('create', App\Product::class)) || (Auth::user()->can('read', App\Brand::class)) || (Auth::user()->can('delete', App\Brand::class)) || (Auth::user()->can('update', App\Brand::class)) || (Auth::user()->can('create', App\Brand::class)) || (Auth::user()->can('read', App\Category::class)) || (Auth::user()->can('delete', App\Category::class)) || (Auth::user()->can('update', App\Category::class)) || (Auth::user()->can('create', App\Category::class)) || Auth::user()->can('read-discount', App\Voucher::class) || Auth::user()->can('create-discount', App\Voucher::class) || Auth::user()->can('update-discount', App\Voucher::class) || Auth::user()->can('delete-discount', App\Voucher::class) )
                @if (isset( $vendor ) && $vendor )
                
                <li>
                    <a class="{{Html::isActive([route('manage.vendor.dashboard')])}}" href="{{route('manage.vendor.dashboard')}}"><i class="fa fa-dashboard fa-2x"></i> Vendor Dashboard</a>
                </li>
                
                @endif

                @if (Auth::user()->can('read', App\Product::class) || Auth::user()->can('create', App\Product::class) || Auth::user()->can('update', App\Product::class) || Auth::user()->can('delete', App\Product::class) || (Auth::user()->can('edit', App\Product::class)))
                <li>
                    <a class="{{Html::isActive([route('manage.products.index'), route('manage.products.create'), route('manage.specification-types.index'), route('manage.brands.index'), route('manage.product-discounts.index')])}}" href="{{route('manage.products.index')}}"><i class="fa fa-sitemap fa-2x"></i> Products<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level {{ (request()->is('manage/products') || request()->is('manage/specification-types') || request()->is('manage/products/create')  ) ? 'in' : '' }}">
                        
                        @if ( Auth::user()->can('read', App\Product::class))
                            <li>
                                <a class="{{ (request()->is('manage/products')) ? 'active-menu' : '' }}" href="{{route('manage.products.index')}}"><i class="fa fa-sitemap"></i>View Products</a>
                            </li>
                        @endif

                        @if (Auth::user()->can('create', App\Product::class))
                            <li>
                                <a class="{{ (request()->is('manage/products/create')) ? 'active-menu' : '' }}" href="{{route('manage.products.create')}}"><i class="fa fa-sitemap"></i>Add Product</a>
                            </li>
                            
                        @endif

                            @if (Auth::user()->isSuperAdmin())
                                <li>
                                    <a class="{{ (request()->is('manage/specification-types')) ? 'active-menu' : '' }}" href="{{route('manage.specification-types.index')}}"><i class="fa fa-list-alt"></i>Add Specifications</a>
                                </li>
                            

                                <li>
                                    <a class="{{ (request()->is('manage/number_of_products')) ? 'active-menu' : '' }}" href="{{route('manage.number_of_products.index')}}"><i class="fa fa-list-alt"></i>Number of Products </a>
                                </li>

                                <li>
                                    <a class="{{ (request()->is('manage/number_of_products')) ? 'active-menu' : '' }}" href="{{route('manage.products.vendor_product')}}"><i class="fa fa-list-alt"></i>Products for Approval </a>
                                </li>
                            @endif
                           

                        
                         
                    </ul>
                </li>
                @endif
            @endif
            @if ((Auth::user()->can('read', App\Order::class)) || (Auth::user()->can('delete', App\Order::class)) || (Auth::user()->can('update', App\Order::class)) || (Auth::user()->can('create', App\Order::class)) || Auth::user()->can('read-coupon', App\Voucher::class) || Auth::user()->can('create-coupon', App\Voucher::class) || Auth::user()->can('update-coupon', App\Voucher::class) || Auth::user()->can('delete-coupon', App\Voucher::class) )
                <li>
                    <a class="{{Html::isActive([route('manage.orders.index'), route('manage.orders.pending'), route('manage.orders.invoices'), route('manage.coupons.index'), route('manage.coupons.create')])}}" href="{{route('manage.orders.index')}}"><i class="fa fa-shopping-cart fa-2x"></i> Orders<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level {{ (request()->is('manage/orders') || request()->is('manage/orders/pending-orders') || request()->is('manage/coupons') || request()->is('manage/coupons/create') ) ? 'in' : '' }}">
                        @if ((Auth::user()->can('read', App\Order::class)) )
                            @if (Auth::user()->can('read', App\Order::class))
                            <li>
                                <a class="{{ (request()->is('manage/orders')) ? 'active-menu' : '' }}" href="{{route('manage.orders.index')}}"><i class="fa fa-shopping-cart"></i>View Orders</a>
                            </li>
                            @endif
                            @if (Auth::user()->can('read', App\Order::class))
                            <!-- <li>
                                <a class="{{ (request()->is('manage/orders/pending-orders')) ? 'active-menu' : '' }}" href="{{route('manage.orders.pending')}}"><i class="fa fa-shopping-cart"></i>Pending Orders</a>
                            </li> -->
                            <!-- <li>
                                <a href="{{route('manage.orders.invoices')}}"><i class="fa fa-envelope"></i>Invoices</a>
                            </li> -->
                            @endif
                        @endif
                        
                        @if (Auth::user()->isSuperAdmin())
                            <li>
                                <a class="{{Html::isActive([route('manage.coupons.index'), route('manage.coupons.create')])}}" href="{{route('manage.coupons.index')}}"><i class="fa fa-gift"></i> Coupons<span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level {{ (request()->is('manage/orders') || request()->is('manage/orders/pending') || request()->is('manage/coupons') || request()->is('manage/coupons/create') ) ? 'in' : '' }}">
                                    @if (Auth::user()->can('read-coupon', App\Voucher::class))
                                    <li>
                                        <a class="{{ (request()->is('manage/coupons')) ? 'active-menu' : '' }}" href="{{route('manage.coupons.index')}}"><i class="fa fa-gift"></i>View Coupons</a>
                                    </li>
                                    @endif
                                    @if (Auth::user()->can('create-coupon', App\Voucher::class))
                                    <li>
                                        <a class="{{ (request()->is('manage/coupons/create')) ? 'active-menu' : '' }}" href="{{route('manage.coupons.create')}}"><i class="fa fa-gift"></i>Add Coupon</a>
                                    </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if ((Auth::user()->can('read', App\Vendor::class)) && ((Auth::user()->can('delete', App\Vendor::class)) || (Auth::user()->can('update', App\Vendor::class)) || (Auth::user()->can('create', App\Vendor::class))))
                <li>
                    <a class="{{Html::isActive([route('manage.vendors.index'), route('manage.vendors.create'), route('manage.vendor.vendor_requests')])}}" href="{{route('manage.vendors.index')}}"><i class="fa fa-users fa-2x"></i> Vendors<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level {{ (request()->is('manage/vendors') || request()->is('manage/vendors/create') || request()->is('manage/vendor/vendors_request') ) ? 'in' : '' }}">
                        @if (Auth::user()->can('read', App\Vendor::class))
                            <li>
                                <a class="{{ (request()->is('manage/vendors')) ? 'active-menu' : '' }}" href="{{route('manage.vendors.index')}}"><i class="fa fa-users"></i>View Vendors</a>
                            </li>
                        @endif
                        @if (Auth::user()->can('create', App\Vendor::class))
                            <li>
                                <a class="{{ (request()->is('manage/vendors/create')) ? 'active-menu' : '' }}" href="{{route('manage.vendors.create')}}"><i class="fa fa-users"></i>Add Vendor</a>
                            </li>
                        @endif
                        {{-- @if (Auth::user()->can('read', App\Vendor::class))
                            <li>
                                <a class="{{ (request()->is('manage/vendors/vendors_request')) ? 'active-menu' : '' }}" href="{{route('manage.vendor.vendor_requests')}}"><i class="fa fa-users"></i>Vendor Requests</a>
                            </li>
                        @endif --}}
                    </ul>
                </li>
            @endif
            
           
            
            <!-- @if ((Auth::user()->can('read', App\Shipment::class)) && ((Auth::user()->can('delete', App\Shipment::class)) || (Auth::user()->can('update', App\Shipment::class)) || (Auth::user()->can('create', App\Shipment::class))))
                <li>
                    <a class="{{Html::isActive([route('manage.shipments.index'), route('manage.shipments.create')])}}" href="{{route('manage.shipments.index')}}"><i class="fa fa-truck fa-2x"></i> Shipments<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        @if (Auth::user()->can('read', App\Shipment::class))
                            <li>
                                <a href="{{route('manage.shipments.index')}}"><i class="fa fa-map-marker"></i>View Shipments</a>
                            </li>
                        @endif
                        @if (Auth::user()->can('create', App\Shipment::class))
                            <li>
                                <a href="{{route('manage.shipments.create')}}"><i class="fa fa-map-marker"></i>Add Shipment</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif -->

            <!-- @if ((Auth::user()->can('view', App\DeliveryLocation::class)) && ((Auth::user()->can('delete', App\DeliveryLocation::class)) || (Auth::user()->can('update', App\DeliveryLocation::class)) || (Auth::user()->can('create', App\DeliveryLocation::class))))
                <li>
                    <a class="{{Html::isActive([route('manage.delivery-location.index'), route('manage.delivery-location.create')])}}" href="{{route('manage.delivery-location.index')}}"><i class="fa fa-location-arrow fa-2x"></i> Delivery Location<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        @if (Auth::user()->can('view', App\DeliveryLocation::class))
                            <li>
                                <a href="{{route('manage.delivery-location.index')}}"><i class="fa fa-map-marker"></i>View Delivery Locations</a>
                            </li>
                        @endif
                        @if (Auth::user()->can('create', App\DeliveryLocation::class))
                            <li>
                                <a href="{{route('manage.delivery-location.create')}}"><i class="fa fa-map-marker"></i>Add Delivery Location</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif -->

            <!-- @if (Auth::user()->can('view-sales', App\Other::class) || Auth::user()->can('view-reports', App\Other::class))
                <li>
                    <a class="{{Html::isActive([route('manage.products.sales'), route('manage.reports.product_sales')])}}" href="{{route('manage.products.sales')}}"><i class="fa fa-bar-chart fa-2x"></i> Report<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        @if (Auth::user()->can('view-reports', App\Other::class))
                            <li>
                                <a href="{{route('manage.reports.product_sales')}}"><i class="fa fa-bar-chart"></i>Product Sales Reports</a>
                            </li>
                        @endif
                        @if (Auth::user()->can('view-sales', App\Other::class))
                            <li>
                                <a href="{{route('manage.products.sales')}}"><i class="fa fa-arrow-up"></i>View Sales</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif -->
            @if (Auth::user()->isSuperAdmin())

               
                @if ((Auth::user()->can('read', App\Role::class)) || (Auth::user()->can('delete', App\Role::class)) || (Auth::user()->can('update', App\Role::class)) || (Auth::user()->can('create', App\Role::class)))
                            <li>
                                <a class="{{Html::isActive([route('manage.roles.index'), route('manage.roles.create')])}}" href="{{route('manage.roles.index')}}"><i class="fa fa-users fa-2x"></i> Roles<span class="fa arrow"></span></a>
                               
                                <ul class="nav nav-third-level {{ (request()->is('manage/users') || request()->is('manage/users/create') || request()->is('manage/roles') || request()->is('manage/roles/create') ) ? 'in' : '' }}">
                                    @if (Auth::user()->can('read', App\Role::class))
                                        <li>
                                            <a class="{{ (request()->is('manage/roles')) ? 'active-menu' : '' }}" href="{{route('manage.roles.index')}}"><i class="fa fa-shield"></i>View Roles</a>
                                        </li>
                                    @endif
                                    <!-- @if (Auth::user()->can('create', App\Role::class))
                                        <li>
                                            <a class="{{ (request()->is('manage/roles/create')) ? 'active-menu' : '' }}" href="{{route('manage.roles.create')}}"><i class="fa fa-shield"></i>Add Role</a>
                                        </li>
                                    @endif -->
                                </ul>
                            </li>
                        @endif
            
                <li>
                    <a class="{{Html::isActive([route('manage.users.index'), route('manage.users.create'), route('manage.roles.index'), route('manage.roles.create')])}}" href="{{route('manage.users.index')}}"><i class="fa fa-users fa-2x"></i> User<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level {{ (request()->is('manage/users') || request()->is('manage/users/create') || request()->is('manage/roles') || request()->is('manage/roles/create') ) ? 'in' : '' }}">
                        @if (Auth::user()->can('read', App\User::class)) 
                            <li>
                                <a class="{{ (request()->is('manage/users')) ? 'active-menu' : '' }}" href="{{route('manage.users.index')}}"><i class="fa fa-users"></i>View User</a>
                            </li>
                        @endif
                        @if (Auth::user()->can('create', App\User::class))
                            <li>
                                <a class="{{ (request()->is('manage/users/create')) ? 'active-menu' : '' }}" href="{{route('manage.users.create')}}"><i class="fa fa-users"></i>Add User</a>
                            </li>
                        @endif
                        
                    </ul>
                </li>
            @endif
            @if (Auth::user()->isSuperAdmin())
            <li> 
                    <a class="{{Html::isActive([route('manage.templates.index')])}}"><i class="fa fa-file fa-2x"></i> Template<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level {{ (request()->is('manage/templates')  ) ? 'in' : '' }} ">
                    
                    <li>
                        <a class="{{ (request()->is('manage/templates')) ? 'active-menu' : '' }}" href="{{route('manage.templates.index')}}"><i class="fa fa-file"></i>Template</a>
                    </li>
                       
                        
                    </ul>
                </li>
            @endif

            @if ((Auth::user()->can('read', App\Banner::class)) || (Auth::user()->can('delete', App\Banner::class)) || (Auth::user()->can('update', App\Banner::class)) || (Auth::user()->can('create', App\Banner::class)) || (Auth::user()->can('read', App\Deal::class)) || (Auth::user()->can('create', App\Deal::class)))
                <li>
                    <a class="{{Html::isActive([route('manage.banners.index'),route('manage.collection.index')])}}" href="{{route('manage.banners.index')}}"><i class="fa fa-picture-o fa-2x"></i> Banners<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level {{ (request()->is('manage/banners') || request()->is('manage/collection') || request()->is('manage/home_banner')  ) ? 'in' : '' }}">
                        <!-- @if (Auth::user()->can('read', App\Deal::class))
                            <li>
                                <a href="{{route('manage.deals.index')}}"><i class="fa fa-tags"></i>View Deals</a>
                            </li>
                        @endif
                        @if (Auth::user()->can('create', App\Deal::class))
                            <li>
                                <a href="{{route('manage.deals.create')}}"><i class="fa fa-tags"></i>Add Deal</a>
                            </li>
                        @endif -->
                        @if ((Auth::user()->can('read', App\Banner::class)) || (Auth::user()->can('delete', App\Banner::class)) || (Auth::user()->can('update', App\Banner::class)) || (Auth::user()->can('create', App\Banner::class)))
                            <li>
                                <a class="{{ (request()->is('manage/banners')) ? 'active-menu' : '' }}" href="{{route('manage.banners.index')}}"><i class="fa fa-picture-o"></i>Home Page Slider</a>
                            </li>
                        @endif
                        <li>
                            <a class="{{ (request()->is('manage/home_banner')) ? 'active-menu' : '' }}" href="{{route('manage.home_banner.index')}}"><i class="fa fa-picture-o"></i>Home Page Banner</a>
                        </li>
                        <li>
                            <a class="{{ (request()->is('manage/collection')) ? 'active-menu' : '' }}" href="{{route('manage.collection.index')}}"><i class="fa fa-money"></i>Collection</a>
                        </li>
                    </ul>
                </li>
            @endif
            @if ((Auth::user()->can('read', App\Page::class)) || (Auth::user()->can('delete', App\Page::class)) || (Auth::user()->can('update', App\Page::class)) || (Auth::user()->can('create', App\Page::class)) || (Auth::user()->can('read', App\Section::class)) || (Auth::user()->can('delete', App\Section::class)) || (Auth::user()->can('update', App\Section::class)) || (Auth::user()->can('create', App\Section::class)))
                <li>
                    <a class="{{Html::isActive([route('manage.pages.index'), route('manage.pages.create'), route('manage.sections.index')])}}" href="{{route('manage.pages.index')}}"><i class="fa fa-newspaper-o fa-2x"></i> Pages<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level {{ (request()->is('manage/pages') || request()->is('manage/pages/create')   ) ? 'in' : '' }}">
                        @if (Auth::user()->can('read', App\Page::class))
                            <li>
                                <a class="{{ (request()->is('manage/pages')) ? 'active-menu' : '' }}" href="{{route('manage.pages.index')}}"><i class="fa fa-newspaper-o"></i>View Pages</a>
                            </li>
                        @endif
                        @if (Auth::user()->can('create', App\Page::class))
                            <li>
                                <a class="{{ (request()->is('manage/pages/create')) ? 'active-menu' : '' }}" href="{{route('manage.pages.create')}}"><i class="fa fa-newspaper-o"></i>Add Page</a>
                            </li>
                        @endif

                        @if (Auth::user()->isSuperAdmin())
                            <li>
                                <a class="{{ (request()->is('manage/pages/schemes')) ? 'active-menu' : '' }}" href="{{route('manage.pages.schemes')}}"><i class="fa fa-newspaper-o"></i>Schemes</a>
                            </li>
                        @endif

                        @if (Auth::user()->isSuperAdmin())
                            <li>
                                <a class="{{ (request()->is('manage/pages/schemes')) ? 'active-menu' : '' }}" href="{{route('manage.pages.certifications')}}"><i class="fa fa-newspaper-o"></i>Home Certification</a>
                            </li>
                        @endif
                        <!-- @if ((Auth::user()->can('read', App\Section::class)) || (Auth::user()->can('delete', App\Section::class)) || (Auth::user()->can('update', App\Section::class)) || (Auth::user()->can('create', App\Section::class)))
                            <li>
                                <a href="{{route('manage.sections.index')}}"><i class="fa fa-picture-o"></i>Sections</a>
                            </li>
                        @endif -->
                    </ul>
                </li>
            @endif
            @if ((Auth::user()->can('read', App\Testimonial::class)) && ((Auth::user()->can('delete', App\Testimonial::class)) || (Auth::user()->can('update', App\Testimonial::class)) || (Auth::user()->can('create', App\Testimonial::class))))
                <li>
                    <a class="{{Html::isActive([route('manage.testimonials.index'), route('manage.testimonials.create')])}}" href="{{route('manage.testimonials.index')}}"><i class="fa fa-quote-left fa-2x"></i> Testimonials<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level {{ (request()->is('manage/testimonials') || request()->is('manage/testimonials/create')   ) ? 'in' : '' }}">
                        @if (Auth::user()->can('read', App\Testimonial::class))
                            <li>
                                <a class="{{ (request()->is('manage/testimonials')) ? 'active-menu' : '' }}" href="{{route('manage.testimonials.index')}}"><i class="fa fa-quote-left"></i>View Testimonials</a>
                            </li>
                        @endif
                        @if (Auth::user()->can('create', App\Testimonial::class))
                            <li>
                                <a class="{{ (request()->is('manage/testimonials/create')) ? 'active-menu' : '' }}" href="{{route('manage.testimonials.create')}}"><i class="fa fa-quote-left"></i>Add Testimonial</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            {{-- @if (Auth::user()->can('view-subscribers', App\Other::class) || Auth::user()->can('update-subscribers-settings', App\Other::class))
                <li>
                    <a class="{{Html::isActive([route('manage.subscribers'), route('manage.settings.subscribers')])}}" href="{{route('manage.subscribers')}}"><i class="fa fa-users fa-2x"></i> Subscribers<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level {{ (request()->is('manage/subscribers') || request()->is('manage/settings/subscribers')   ) ? 'in' : '' }}">
                    @if (Auth::user()->can('view-subscribers', App\Other::class))
                        <li>
                            <a class="{{ (request()->is('manage/subscribers')) ? 'active-menu' : '' }}" href="{{route('manage.subscribers')}}"><i class="fa fa-users"></i>View Subscribers</a>
                        </li>
                    @endif
                    @if (Auth::user()->can('update-subscribers-settings', App\Other::class))
                        <li>
                            <a  class="{{ (request()->is('manage/settings/subscribers')) ? 'active-menu' : '' }}" href="{{route('manage.settings.subscribers')}}"><i class="fa fa-wrench"></i>Settings</a>
                        </li>
                    @endif
                    </ul>
                </li>
            @endif --}}
            <li>
            
                <a class="{{Html::isActive([route('manage.settings.index'), route('manage.settings.profile'), route('manage.settings.payment'),  route('manage.settings.email'), route('manage.settings.css')])}}" href="{{route('manage.settings.index')}}"><i class="fa fa-wrench fa-2x"></i> Settings<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level {{ (request()->is('manage/settings') || request()->is('manage/settings/profile')  || request()->is('manage/settings/price-setting') || request()->is('manage/settings/email') || request()->is('manage/settings/certificate') || request()->is('manage/settings/catalog') || request()->is('manage/settings/pincode')) ? 'in' : '' }}">
                    @if (Auth::user()->can('update-settings', App\Other::class) && Auth::user()->isSuperAdmin())
                        <li>
                            <a class="{{ (request()->is('manage/settings')) ? 'active-menu' : '' }}" href="{{route('manage.settings.index')}}"><i class="fa fa-list-alt"></i>Overview</a>
                        </li>
                    @endif
                    
                    <li>
                        <a class="{{ (request()->is('manage/settings/profile')) ? 'active-menu' : '' }}" href="{{route('manage.settings.profile')}}"><i class="fa fa-user"></i>Profile</a>
                    </li>
                      
                    
                    @if(\Auth::user()->isSuperAdmin())
                    <li>
                        <a class="{{ (request()->is('manage/settings/price-setting')) ? 'active-menu' : '' }}" href="{{route('manage.settings.pricesetting')}}"><i class="fa fa-map-marker"></i>Price Setting</a>
                    </li>
                    @endif
                    
                    @if (Auth::user()->can('Certificates', App\Other::class))
                     <li>
                        <a class="{{ (request()->is('manage/settings/certificate')) ? 'active-menu' : '' }}" href="{{route('manage.settings.certificate')}}"><i class="fa fa-certificate"></i>Certificates</a>
                    </li>
                    @endif
                     
                    @if(\Auth::user()->isSuperAdmin())
                     <li>
                        <a class="{{ (request()->is('manage/settings/catalog')) ? 'active-menu' : '' }}" href="{{route('manage.settings.catalog')}}"><i class="fa fa-certificate"></i>Catalog</a>
                    </li>
                    @endif

                    @if(\Auth::user()->isSuperAdmin())
                    <li>
                        <a class="{{ (request()->is('manage/settings/pincode')) ? 'active-menu' : '' }}" href="{{route('manage.settings.pincode')}}"><i class="fa fa-certificate"></i>Pincode</a>
                    </li>
                    @endif

                    @if(\Auth::user()->isSuperAdmin())
                    <li>
                        <a class="{{ (request()->is('manage/settings/shop-by-metal-stone')) ? 'active-menu' : '' }}" href="{{route('manage.settings.shop_by_metal_stone')}}"><i class="fa fa-map-marker"></i>Shop by metal & stone</a>
                    </li>
                    @endif
                   
                </ul>
            </li>
             @if(\Auth::user()->isSuperAdmin())
            <li>
                <a class="{{Html::isActive([route('manage.settings.enquires')])}}" href="{{route('manage.settings.enquires')}}"><i class="fa fa-wrench fa-2x"></i> Enquires<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level {{ (request()->is('manage/settings') || request()->is('manage/settings/enquires')) ? 'in' : '' }}">
                    <li>
                        <a class="{{ (request()->is('manage/settings')) ? 'active-menu' : '' }}" href="{{route('manage.settings.enquires')}}"><i class="fa fa-list-alt"></i>Enquiry</a>
                    </li>
                   
                     
                   
                </ul>
            </li>
            @endif
             
        </ul>
    </div>
</nav>
