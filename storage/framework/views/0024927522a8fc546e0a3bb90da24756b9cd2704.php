<header>
  <div class="top-scroll-sec">
    <div class="scroll-rate-sec">
      <p class="1_slider">Today’s Gold rate 22K per gm:  <?php echo e(isset($gold_rate->value) ? currency_format(number_format($gold_rate->value)) : currency_format('0.00')); ?></p>
       
      <p class="inactive 2_slider"> Today’s Silver rate per gm: <?php echo e(isset($silver_rate->value) ? currency_format(number_format($silver_rate->value)) : currency_format('0.00')); ?></p>
      <!-- <p class="inactive 3_slider"> Today’s Platinum rate per gm: <?php echo e(isset($silver_rate->value) ? currency_format(number_format($silver_rate->value)) : currency_format('0.00')); ?></p> -->
      
    </div>
   </div>
  <div class="desktop-header">
    <div class="top-header">
      <div class="container">
        <div class="t-header-sec">
        
          <div class="logo">
            <?php if(config('settings.site_logo_enable') == 1): ?>
              <a href="<?php echo e(url('/')); ?>"><img src="<?php echo e(asset('img/logo_new.gif')); ?>" alt="<?php echo e(config('settings.site_logo_name')); ?>" height="<?php echo e(config('settings.site_logo_height')); ?>" width="<?php echo e(config('settings.site_logo_width')); ?>"/></a>
            <?php endif; ?>
          </div>
       
        
          <div class="top-right">
            
            
            <div class="top-right-link-search">
             <ul class="link-search">
                <li class="search">
                    <?php echo Form::open(['method'=>'get', 'action'=>['FrontController@search'], 'role'=>'search', 'class'=>'navbar-form', 'autocomplete'=>'off' ,'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']); ?>

                      <div class="search-box">
                        <?php echo Form::text('keyword', null, ['class'=>'search-keyword' ,'placeholder'=>__('Enter Search...'), 'required', 'autofocus']); ?>

                        
                        <button type="submit" name="submit_button" class="search-icon"><img src="<?php echo e(URL::asset('img/search-icon.png')); ?>" alt=""/></button>
                      </div>
                    <?php echo Form::close(); ?>

                </li>
              
              </ul>
              
              <ul class="top-icon">
                <li class="cart-count">
                  <?php if(\Auth::user()): ?> 
					   <?php if(\Auth::user()->favouriteProducts()->count() != '0'): ?> 
                    <a href="<?php echo e(url('/products/wishlist')); ?>" style="color:goldenrod " ><img src="<?php echo e(URL::asset('img/heart.png')); ?>" alt=""/>
					<span id="wishlistCount"><?php echo e(\Auth::user()->favouriteProducts()->count()); ?></span></a>
					<?php else: ?>
						<a href="<?php echo e(url('/products/wishlist')); ?>"><img src="<?php echo e(URL::asset('img/heart.png')); ?>" alt=""/>
					<span id="wishlistCount"><?php echo e(\Auth::user()->favouriteProducts()->count()); ?></span></a>
					<?php endif; ?>
                  <?php else: ?>
                    <a href="javascript:void(0)"  data-toggle="modal" data-target="#login-modal"><img src="<?php echo e(URL::asset('img/heart.png')); ?>" alt=""/></a> 
                  <?php endif; ?>
                </li>
                <li class="cart-count">
                  <a href="<?php echo e(route('front.cart.index')); ?>"> <img src="<?php echo e(URL::asset('img/bascket.png')); ?>" alt=""/>
                    <span id="cartCount"><?php echo e(Cart::content()->count()); ?></span>
                  </a>
                </li>
                <li><a href="#"></a><img src="<?php echo e(URL::asset('img/india-flag.png')); ?>" alt=""/></a></li>
                  
                <?php if(\Auth::check()): ?>
                  <li class="dropdown">
                    <ul class="">
                      
                      <li>
                        <a href="#"><img src="<?php echo e(URL::asset('img/user-icon.png')); ?>" alt=""/> <?php echo e(Auth::user()->name); ?></a>
                        <ul class="dropdown-list">
                        <?php if(!Auth::user()->isSuperAdmin()  && !Auth::user()->vendor): ?>
                          <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo e(route('front.account')); ?>"><i class="fa fa-user"></i> <?php echo app('translator')->getFromJson('Account Overview'); ?></a></li>
                        <?php endif; ?>
                          <?php if(Auth::user()->vendor && Auth::user()->can('read', App\Customer::class)): ?>
                            <li>
                                <a  href="<?php echo e(route('front.customers.customer')); ?>"><i class="fa fa-user"></i> <?php echo app('translator')->getFromJson('Customer'); ?></a>
                            </li>
                          <?php endif; ?>
                          <?php if(!Auth::user()->isSuperAdmin() && !Auth::user()->vendor): ?>
                               
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo e(route('front.orders.index')); ?>"><i class="fa fa-shopping-cart"></i> <?php echo app('translator')->getFromJson('Orders'); ?></a></li>
                               

                              <?php if(Auth::user()->can('read', App\Address::class) ): ?>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo e(route('front.addresses.index')); ?>"><i class="fa fa-truck"></i> <?php echo app('translator')->getFromJson('Addresses'); ?></a></li>
                              <?php endif; ?>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo e(route('front.settings.profile')); ?>"><i class="fa fa-wrench"></i> <?php echo app('translator')->getFromJson('Edit Profile'); ?></a></li>
                           <?php endif; ?>
                          <!-- <?php if(Auth::user()->vendor && !Auth::user()->isSuperAdmin()): ?>
                          <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo e(route('front.vendor.profile')); ?>"><i class="fa fa-user"></i> <?php echo app('translator')->getFromJson('Vendor Profile'); ?></a></li>
                          <?php endif; ?> -->
                          <?php if(Auth::user()->role): ?>
                              <li role="presentation" class="divider"></li>
                              <?php if(Auth::user()->vendor && isset(Auth::user()->vendor->approved) && Auth::user()->isApprovedVendor()): ?>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo e(url('/manage/vendor/dashboard')); ?>"><i class="fa fa-wrench"></i> <?php echo app('translator')->getFromJson('Manage'); ?></a></li>
                              <?php elseif(Auth::user()->isSuperAdmin() || Auth::user()->can('view-dashboard', \App\Other::class) ): ?>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo e(url('/manage')); ?>"><i class="fa fa-wrench"></i> <?php echo app('translator')->getFromJson('Manage'); ?></a></li>
                              <?php endif; ?>
                          <?php endif; ?>
                          <li role="presentation"><a role="menuitem" tabindex="-1" onclick="logout();" href="#"><i class="fa fa-sign-out"></i> <?php echo app('translator')->getFromJson('Logout'); ?></a></li>
                          <form id="logout_form" action="<?php echo e(route('logout')); ?>" method="POST">
                              <?php echo e(csrf_field()); ?>

                          </form>
                          <script>
                              function logout() {
                                  var logoutForm = $('#logout_form');
                                  if (!logoutForm.hasClass('form-submitted')) {
                                      logoutForm.addClass('form-submitted');
                                      logoutForm.submit();
                                  }
                              }
                          </script>
                        </ul>
                      </li>

                    </ul>
                  </li>
                  
                <?php else: ?>

                  <!-- <li><a href="javascript:void(0)" data-toggle="modal" data-target="#login-modal"><?php echo app('translator')->getFromJson('Login'); ?></a></li>
                  <li><a href="javascript:void(0)" data-toggle="modal" data-target="#register-modal"><?php echo app('translator')->getFromJson('Signup'); ?></a></li> -->
                    <ul class="top-icon">
                      
                      <li><a href="#"><img src="<?php echo e(URL::asset('img/user-icon.png')); ?>" alt=""/></i></a>
                        <ul class="dropdown-list">
                          <div class="login-dropdown">
                            <h4>Your Account</h4>
                            <p>Access account & manage your orders.</p>
                            <div class="btn-sec">
                              <a href="javascript:void(0)" data-toggle="modal" data-target="#login-modal"><?php echo app('translator')->getFromJson('Login'); ?></a>
                              <a href="javascript:void(0)" data-toggle="modal" data-target="#register-modal"><?php echo app('translator')->getFromJson('Signup'); ?></a>
                            </div>
                          </div>
                          
                        </ul>
                      </li>
                    </ul>
                    
                <?php endif; ?>
                 
              </ul>
            </div>
          </div>

        </div>

      </div>

    </div>
 
     <div class="container">
        <div class="menu-bar main-menu">
          <ul class="menu">
         
            <?php if(!empty($categories)): ?>
            <?php $priceRange = 0;?>
            
              <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               
              <?php $priceRange = range($value->min_price,$value->max_price);
             
              ?>
             
               
              <?php if(!$value->categories->isEmpty()): ?>
              <li><a href="/category/<?php echo e($value->slug); ?>"><?php echo e($value->name); ?> <i class="fa fa-angle-down"></i></a>
              
                    <div class="sub-menu">
                      <div class="inner-menu">
                        <div class="menu-box first">
                          <h5>Shop by style</h5>
                          <ul>
                            
                            <?php $__currentLoopData = $value->categories->sortBy('priority'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php if($val->is_active == 1): ?>
                              <?php
                              if($val->image!='') {
                                $file = public_path().'/storage/style/'.$val->image;
                              } else {
                                $file = '';
                              }
                                 
                                  
                              ?>
                                  <li>
                                    <a href="/category/<?php echo e($val->slug); ?>">
                                      <?php if(file_exists($file)): ?>
                                        <img src="<?php echo e(asset('storage/style/'.$val->image)); ?> " style="max-width:20px!important;" >
                                      <?php else: ?>
                                        <img src="<?php echo e(asset('img/noimage.png')); ?> " style="max-width:20px!important;" >
                                      <?php endif; ?>
                                      <span><?php echo e($val->name); ?></span>
                                    </a>
                                  </li>
								  <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                             
                          </ul>
                        </div>
                         <?php 
                              $shopByMetal = \App\ShopByMetalStone::where('is_active',true)->where('category_id',$value->id)->get();
                              
                         ?>
                         <?php if($value->name != 'Silver Articles'  ): ?>
                            <?php if(count($shopByMetal) > 0): ?>
                              <div class="menu-box second">
                                <h5>SHOP BY METAL & STONE </h5>
                                <ul>
                                  <?php $__currentLoopData = $shopByMetal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                      
                                        <li> 
                                          <a  href="/category/<?php echo e($value->slug); ?>/<?php echo e($metals->id); ?>"><img src="<?php echo e(URL::asset('img/'.$metals->image)); ?>" alt=""/> <span> <?php echo e(strtoupper($metals->name)); ?></span></a>
                                        </li>
                                      
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  
                                </ul>
                              </div>
                            <?php endif; ?>
                          <?php endif; ?>
                          
                        <?php if($value->min_price!=null && $value->max_price!=null): ?>
                        <div class="menu-box third">
                          <h5>By Price Range</h5>
                          <ul>
                             
                             
                            <?php  
                                
                                $start = $value->min_price;
                                $end = $value->max_price;
                                $difference = $value->max_price - $value->min_price;
                                $modules = $difference%10000;
                                 
                                if($modules == 0) {
                                  $step = 10000;
                                } else {
                                  $step = 5000;
                                }
                                 
                                 
                                
                                
                                $minVal = '';
                             ?>
                            
                            <?php if(isset($start)): ?>
                              <li><a href="/filter/<?php echo e($value->slug); ?>/0/<?php echo e($start); ?>">Below  <?php echo e(number_format($start)); ?> </a></li>
                            <?php endif; ?>

                            <?php for($i=$start; $i<$end; $i): ?>

                            <?php
                              $firstNum = $i;
                              $minPrice = str_split($firstNum);
                              $lastNum = $i + $step;
                              $MaxPrice = str_split($lastNum);
                              
                              if($i < $end) {
                                 
                                $minVal = $firstNum/1000;
                              }  
                            ?>
                              <li><a href="/filter/<?php echo e($value->slug); ?>/<?php echo e($firstNum); ?>/<?php echo e($lastNum); ?>"> Between <?php echo e($minVal); ?>K -  <?php echo e($MaxPrice[0].$MaxPrice[1]); ?>K </a></li>
                              <?php 
                                $i=$i+$step
                              ?> 
                            <?php endfor; ?>
                            <?php if(isset($end)): ?>
                            <li><a href="/filter/<?php echo e($value->slug); ?>/<?php echo e($end); ?>">  <?php echo e(number_format($end)); ?> and above </a></li>
                            <?php endif; ?>
                          </ul>
                        </div>
                        <?php endif; ?>
                        <?php if($value->name != 'Silver Articles' ): ?>
                          <div class="menu-box third">
                            <h5>Popular Type</h5>
                            <ul>
                                <li><a href="/filter/<?php echo e($value->slug); ?>/men">For Men </a></li>
                                <li><a href="/filter/<?php echo e($value->slug); ?>/women"> For Women </a></li>
                                <li><a href="/filter/<?php echo e($value->slug); ?>/kids"> For Kids </a></li>
                            </ul>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
              
                  </li>
              <?php else: ?>
                <li><a href="/category/<?php echo e($value->slug); ?>"><?php echo e($value->name); ?></a></li>
              <?php endif; ?>
               
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
              <?php if(!empty($scheme)): ?>
                <li><a href="<?php echo e(route('front.schemes')); ?>">Schemes</a></li>
              <?php endif; ?>
                <li><a href="<?php echo e(route('front.catalog')); ?>">Catalog Order</a></li>
          </ul>
            <?php if(config('settings.contact_number')): ?>
              <span class="call-no"><a target="_blank" style="color:white;" href="https://api.whatsapp.com/send?phone=919111222818&amp;text=Hello"><img src="<?php echo e(URL::asset('img/whatsapp.svg')); ?> " style="width:20px;" alt=""/> 9111222818</a></span>
            <?php endif; ?>
        </div>      
     </div>
    
  </div>
 <!-- mobile header start -->
        <!-- mobile header start -->
      <div class="mobile-header d-lg-none d-md-block sticky">
          <!--mobile header top start -->
          <div class="container-fluid">
              <div class="row align-items-center">
                  <div class="col-12">
                      <div class="mobile-main-header">
                          <div class="mobile-logo">
                          
                              <a href="<?php echo e(url('/')); ?>">
                                  <img src="<?php echo e(asset('img/logo_new.gif')); ?>" alt="<?php echo e(config('settings.site_logo_name')); ?>">
                              </a>
                          </div>
                          <div class="mobile-menu-toggler">
                              <div class="mini-cart-wrap">
                              <?php if(\Auth::user()): ?> 
                                    <a href="<?php echo e(url('/products/wishlist')); ?>"><img src="<?php echo e(URL::asset('img/heart.png')); ?>" alt=""/></a>
                                <?php else: ?>
                                    <a href="javascript:void(0)"  data-toggle="modal" data-target="#login-modal"><img src="<?php echo e(URL::asset('img/heart.png')); ?>" alt=""/></a> 
                                <?php endif; ?>
                              
                                <a href="<?php echo e(route('front.cart.index')); ?>">
                                      <img src="<?php echo e(URL::asset('img/bascket.png')); ?>" alt=""/>
                                      <span id="cartCount"><?php echo e(Cart::content()->count()); ?></span>
                                  </a>

                              <a href="#"></a><img src="<?php echo e(URL::asset('img/india-flag.png')); ?>" alt=""/></a>
                               </div>
                              <button class="mobile-menu-btn">
                                  <span></span>
                                  <span></span>
                                  <span></span>
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <!-- mobile header top start -->
      </div>
      <!-- mobile header end -->
      <!-- mobile header end -->

      <!-- offcanvas mobile menu start -->
      <!-- off-canvas menu start -->
      <aside class="off-canvas-wrapper">
          <div class="off-canvas-overlay"></div>
          <div class="off-canvas-inner-content">
              <div class="btn-close-off-canvas">
                  <i class="fa fa-close"></i>
              </div>

              <div class="off-canvas-inner">
                  <!-- search box start -->
                  <div class="search-box-offcanvas" style="display:none;">
                  <?php echo Form::open(['method'=>'get', 'action'=>['FrontController@search'], 'role'=>'search', 'class'=>'navbar-form', 'autocomplete'=>'off' ,'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']); ?>

                    <?php echo Form::text('keyword', null, ['class'=>'search-keyword' ,'placeholder'=>__('Enter Search...'), 'required', 'autofocus']); ?>

                    <button type="submit" name="submit_button" class="search-btn"><img src="<?php echo e(URL::asset('img/search-icon.png')); ?>" alt=""/></button>
                  <?php echo Form::close(); ?>

                  </div>
                  <!-- search box end -->

                  <!-- mobile menu start -->
                  <div class="mobile-navigation">

                      <!-- mobile menu navigation start -->
                      <nav>
                          <ul class="mobile-menu">
                            <?php if(!empty($categories)): ?>
                              <?php $priceRange = 0;?>
                                  <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $priceRange = range($value->min_price,$value->max_price);
                                     
                                    ?>
                                    <?php if(!$value->categories->isEmpty()): ?>
                                      <li class="menu-item-has-children"><a href="/category/<?php echo e($value->slug); ?>"><?php echo e($value->name); ?></a>
                                        <ul class="dropdown">
                                          <li class="menu-item-has-children"><a href="#">Shop by style</a>
                                              <ul class="dropdown">
                                              <?php $__currentLoopData = $value->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                              <?php 
                                                $file = public_path().'/storage/style/'.$val->image;
                                              ?>
                                                  <li>
                                                    <a href="/category/<?php echo e($val->slug); ?>">
                                                    <?php if(file_exists($file)): ?>
                                                        <img src="<?php echo e(asset('storage/style/'.$val->image)); ?> " style="max-width:20px!important;" >
                                                      <?php endif; ?>
                                                      <?php echo e($val->name); ?>

                                                    </a>
                                                  </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                              </ul>
                                          </li>
                                                <?php 
                                                  $shopByMetal = \App\ShopByMetalStone::where('is_active',true)->where('category_id',$value->id)->get();
                                    
                                                ?>
                                                <?php if(count($shopByMetal) > 0): ?>
                                                  <li class="menu-item-has-children"><a href="#">Shop By Metal & Stone</a>
                                                      <ul class="dropdown">
                                                      <?php $__currentLoopData = $shopByMetal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                              
                                                        <li> 
                                                          <a  href="/category/<?php echo e($value->slug); ?>/<?php echo e($metals->id); ?>"><img src="<?php echo e(URL::asset('img/'.$metals->image)); ?>" alt=""/> <span> <?php echo e(strtoupper($metals->name)); ?></span></a>
                                                        </li>
                                            
                                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                      </ul>
                                                  </li>
                                                <?php endif; ?>
                                          <?php if($value->min_price!=null && $value->max_price!=null): ?>
                                          <?php  
                                
                                              $start = $value->min_price;
                                              $end = $value->max_price;
                                              $step = 10000;
                                          ?>
                                          <li class="menu-item-has-children"><a href="#">By Price Range</a>
                                              <ul class="dropdown">
                                                  <?php  
                                                    
                                                    $start = $value->min_price;
                                                    $end = $value->max_price;
                                                    $difference = $value->max_price - $value->min_price;
                                                    $modules = $difference%10000;
                                                    if($modules == 0) {
                                                      $step = 10000;
                                                    } else {
                                                      $step = 5000;
                                                    }
                                                    $minVal = '';
                                                  ?>
                                                  <?php if(isset($start)): ?>
                                                    <li><a href="/filter/<?php echo e($value->slug); ?>/0/<?php echo e($start); ?>">Below  <?php echo e(number_format($start)); ?> </a></li>
                                                  <?php endif; ?>
                                                    <?php for($i=$start; $i<$end; $i): ?>

                                                      <?php
                                                        $firstNum = $i;
                                                        $minPrice = str_split($firstNum);
                                                        $lastNum = $i + $step;
                                                        $MaxPrice = str_split($lastNum);
                                                        
                                                        if($i < $end) {
                                                          
                                                          $minVal = $firstNum/1000;
                                                        }  
                                                      ?>
                                                        <li><a href="/filter/<?php echo e($value->slug); ?>/<?php echo e($firstNum); ?>/<?php echo e($lastNum); ?>"> Between <?php echo e($minVal); ?>K -  <?php echo e($MaxPrice[0].$MaxPrice[1]); ?>K </a></li>
                                                        <?php 
                                                          $i=$i+$step
                                                        ?> 
                                                    <?php endfor; ?>
                                                    <?php if(isset($end)): ?>
                                                      <li><a href="/filter/<?php echo e($value->slug); ?>/<?php echo e($end); ?>">  <?php echo e(number_format($end)); ?> and above </a></li>
                                                    <?php endif; ?>
                                              
                                              </ul>
                                          </li>
                                            <?php if($value->name != 'Silver Articles'   ): ?>
                                                <li class="menu-item-has-children"><a href="#">Popular Type</a>
                                                    <ul class="dropdown">
                                                        <li><a href="/filter/<?php echo e($value->slug); ?>/men">For Men </a></li>
                                                        <li><a href="/filter/<?php echo e($value->slug); ?>/women"> For Women </a></li>
                                                        <li><a href="/filter/<?php echo e($value->slug); ?>/kids"> For Kids </a></li>

                                                      
                                                      
                                                    </ul>
                                                </li>
                                            <?php endif; ?>
                                          <?php endif; ?>
                                        </ul>
                                      </li>
                                    <?php else: ?>
                                    <li><a href="/category/<?php echo e($value->slug); ?>"><?php echo e($value->name); ?></a></li>
                                  <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            <?php if(!empty($scheme)): ?>
                              <li><a href="<?php echo e(route('front.schemes')); ?>">Schemes</a></li>
                            <?php endif; ?>
                              <li><a href="<?php echo e(route('front.catalog')); ?>">Catalog Order</a></li>
                          </ul>
                      </nav>
                      <!-- mobile menu navigation end -->
                  </div>
                  <!-- mobile menu end -->

                  <div class="mobile-settings">

                  
                      <ul class="nav">

                      <?php if(Auth::check() ): ?>

                       

                      <li>
                              <div class="dropdown mobile-top-dropdown">

                                  <a href="#" class="dropdown-toggle" id="myaccount" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo e(Auth::user()->name); ?>

                                      <i class="fa fa-angle-down"></i>
                                  </a>
                                  
                                  <div class="dropdown-menu" aria-labelledby="myaccount">

                                    <?php if(!Auth::user()->isSuperAdmin()): ?>
                                      <a class="dropdown-item" href="<?php echo e(route('front.account')); ?>"><i class="fa fa-user"></i> <?php echo app('translator')->getFromJson('Account Overview'); ?></a>
                                    <?php endif; ?>

                                    <?php if(Auth::user()->vendor && Auth::user()->can('read', App\Customer::class)): ?>
                                      <a class="dropdown-item"  href="<?php echo e(route('front.customers.customer')); ?>"><i class="fa fa-user"></i> <?php echo app('translator')->getFromJson('Customer'); ?></a>
                                    <?php endif; ?>

                                    <?php if(!Auth::user()->isSuperAdmin()): ?>
                                      <?php if(Auth::user()->can('read', App\Order::class) ): ?>
                                        <a class="dropdown-item" href="<?php echo e(route('front.orders.index')); ?>"><i class="fa fa-shopping-cart"></i> <?php echo app('translator')->getFromJson('Orders'); ?></a>
                                      <?php endif; ?>

                                      <?php if(Auth::user()->can('read', App\Address::class) ): ?>
                                        <a class="dropdown-item" tabindex="-1" href="<?php echo e(route('front.addresses.index')); ?>"><i class="fa fa-truck"></i> <?php echo app('translator')->getFromJson('Addresses'); ?></a>
                                      <?php endif; ?>
                                       <a class="dropdown-item" tabindex="-1" href="<?php echo e(route('front.settings.profile')); ?>"><i class="fa fa-wrench"></i> <?php echo app('translator')->getFromJson('Edit Profile'); ?></a>
                                    <?php endif; ?>

                                    <!-- <?php if(Auth::user()->vendor && !Auth::user()->isSuperAdmin()): ?>
                                        <a class="dropdown-item" tabindex="-1" href="<?php echo e(route('front.vendor.profile')); ?>"><i class="fa fa-user"></i> <?php echo app('translator')->getFromJson('Vendor Profile'); ?></a>
                                    <?php endif; ?> -->
                                    <?php if(Auth::user()->role): ?>
                                        
                                        <?php if(Auth::user()->vendor && isset(Auth::user()->vendor->approved) && Auth::user()->isApprovedVendor()): ?>
                                            <a  class="dropdown-item" href="<?php echo e(url('/manage/vendor/dashboard')); ?>"><i class="fa fa-wrench"></i> <?php echo app('translator')->getFromJson('Manage'); ?></a>
                                        <?php elseif(Auth::user()->isSuperAdmin() || Auth::user()->can('view-dashboard', \App\Other::class) ): ?>
                                            <a class="dropdown-item" href="<?php echo e(url('/manage')); ?>"><i class="fa fa-wrench"></i> <?php echo app('translator')->getFromJson('Manage'); ?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <a class="dropdown-item" onclick="logout();" href="#"><i class="fa fa-sign-out"></i> <?php echo app('translator')->getFromJson('Logout'); ?></a>
                                      <form id="logout_form" action="<?php echo e(route('logout')); ?>" method="POST">
                                          <?php echo e(csrf_field()); ?>

                                      </form>
                                      <script>
                                          function logout() {
                                              var logoutForm = $('#logout_form');
                                              if (!logoutForm.hasClass('form-submitted')) {
                                                  logoutForm.addClass('form-submitted');
                                                  logoutForm.submit();
                                              }
                                          }
                                      </script>

                                 
                                      
                                       
                                      
                                  </div>
                              </div>
                          </li>
                        
                          

                      
                      <?php else: ?>
                      <li>
                              <div class="dropdown mobile-top-dropdown">
                                  <a href="#" class="dropdown-toggle" id="myaccount" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      My Account
                                      <i class="fa fa-angle-down"></i>
                                  </a>
                                  <div class="dropdown-menu" aria-labelledby="myaccount">
                                  
                                       
                                      <a class="dropdown-item loginBtn" href="javascript:void(0)" data-toggle="modal" data-target="#login-modal"><?php echo app('translator')->getFromJson('Login'); ?></a>
                                      <a class="dropdown-item registerBtn" href="javascript:void(0)" data-toggle="modal" data-target="#register-modal"><?php echo app('translator')->getFromJson('Signup'); ?></a>
                                  </div>
                              </div>
                          </li>

                      <?php endif; ?>
                          <!-- <li>
                              <div class="dropdown mobile-top-dropdown">
                                  <a href="#" class="dropdown-toggle" id="currency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      Currency
                                      <i class="fa fa-angle-down"></i>
                                  </a>
                                  <div class="dropdown-menu" aria-labelledby="currency">
                                      <a class="dropdown-item" href="#">$ USD</a>
                                      <a class="dropdown-item" href="#">$ EURO</a>
                                  </div>
                              </div>
                          </li> -->
                          
                      </ul>
                  </div>

                  <!-- offcanvas widget area start -->
                  <div class="offcanvas-widget-area">
                      <div class="off-canvas-contact-widget">
                          <ul>
                              <li><i class="fa fa-phone"></i>
                                <?php if(config('settings.contact_number')): ?>
                                  <?php echo e(config('settings.contact_number')); ?>

                                <?php endif; ?>
                              </li>
                              <li><i class="fa fa-envelope"></i>
                                <?php if(config('settings.contact_email')): ?>
                                  <?php echo e(config('settings.contact_email')); ?>

                                <?php endif; ?>
                              </li>
                              
                          </ul>
                      </div>
                  </div>
                  <!-- offcanvas widget area end -->
              </div>
          </div>
      </aside>
      <!-- off-canvas menu end -->
      <!-- offcanvas mobile menu end -->


   </header>

   <!-- login -->
<div class="modal fade login-register" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="login-modalTitle">Login</h5>
        <button type="button" class="close closeLogin" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="LoginMsg"></div>
        <form method="POST" name="loginform" id="loginform" action="<?php echo e(route('login')); ?>">
        <input type="hidden" name="_token" id="csrf-token" value="<?php echo e(Session::token()); ?>" />
        
          <div class="form-group  input-group<?php echo e($errors->has('username') || $errors->has('email') ? ' has-error' : ''); ?>">
            <input type="text" class="form-control " name="username" id="username" placeholder="<?php echo app('translator')->getFromJson('Your Email/Mobile'); ?>"/>
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
            <input type="password" class="form-control " name="password" id="password" placeholder="<?php echo app('translator')->getFromJson('Your Password'); ?>"/>
            <?php if($errors->has('password')): ?>
                <span class="help-block">
                    <strong class="text-danger">
                        <?php echo e($errors->first('password')); ?>

                    </strong>
                </span>
            <?php endif; ?>
          </div>
           
          <div class="form-group">
            <button id="login-button" type="submit"  class="btn btn-primary login-button"><?php echo app('translator')->getFromJson('Login'); ?></button>
          </div>
          <div class="forgot-link">
            <!-- <a href="<?php echo e(route('password.request')); ?>"><?php echo app('translator')->getFromJson('Forgot Your Password?'); ?></a> -->
            <a href="javascript:void(0)" data-toggle="modal" data-dismiss="modal" data-target="#forgot-modal"><?php echo app('translator')->getFromJson('Forgot Password?'); ?></a>
            <p>Don't have an account with us? <a href="javascript:void(0)" data-dismiss="modal" data-toggle="modal" data-target="#register-modal"><?php echo app('translator')->getFromJson('Sign Up'); ?></a></p>
          </div>
        </form>
          <script>
            jQuery(document).ready(function() {

                $.ajaxSetup({
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
                });

                $(".login-button").on('click',function(e) {
               
               e.preventDefault();
             
                 $.ajax({
                 
                   type:$("#loginform").attr('method'),
                   url:$("#loginform").attr('action'),
                   dataType:'json',
                   data:$("#loginform").serializeArray(),
                   
                   success:function(data) {
                           console.log(data.role);
                       if(data.status==true && data.role =='') {
                           $("#LoginMsg").show();
                           $("#LoginMsg").html(data.msg);
                           setTimeout(function() {
                             $(".login-button").text('<?php echo e(__('Login')); ?>').prop('disabled', false);
                             $("#LoginMsg").slideUp();
                           }, 2000); 
                           window.location.reload();
                       } else if(data.role == 'super_admin') {
                           
                            
                         window.open('<?php echo e(URL::to("/manage")); ?>','_blank');
                         setTimeout(function() {
                             $(".login-button").text('<?php echo e(__('Login')); ?>').prop('disabled', false);
                             $("#LoginMsg").slideUp();
                           }, 2000); 
                           window.location.reload();
                            
                       } else if(data.role == 'vendor') {
                           
                            
                           window.open('<?php echo e(URL::to("/manage/vendor/dashboard")); ?> ','_blank');
                           setTimeout(function() {
                               $(".login-button").text('<?php echo e(__('Login')); ?>').prop('disabled', false);
                               $("#LoginMsg").slideUp();
                             }, 2000); 
                             window.location.reload();
                              
                         } else {
                           $("#LoginMsg").show();
                           $("#LoginMsg").html(data.msg);
                           setTimeout(function() {
                             $(".login-button").text('<?php echo e(__('Login')); ?>').prop('disabled', false);
                             $("#LoginMsg").slideUp();
                           }, 2000); 
                           return false;
                       }
                       
                   }
                    

                 });
             });

            });
            
                     
                </script>
      </div>
     
    </div>
  </div>
</div>
<!-- register -->
<div class="modal fade login-register" id="register-modal" tabindex="-1" role="dialog" aria-labelledby="register-modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="register-modalTitle">Register</h5>
        <button type="button" class="close closeRegister" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="RegisterMsg"></div>
        <div id="termconditionmsg" style="color:#D3A012;"></div>
        <form  class="form-horizontal" name="registerform" id="registerform" method="POST" action="">
         <input type="hidden" name="is_term_service" id="is_term_service" value="0">  
           
          <div class="form-group input-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
            <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name')); ?>" required placeholder="<?php echo app('translator')->getFromJson('Your Name*'); ?>"/>
            <?php if($errors->has('name')): ?>
                <span class="help-block">
                    <strong class="text-danger">
                        <?php echo e($errors->first('name')); ?>

                    </strong>
                </span>
            <?php endif; ?>
          </div>
          <span id="errmsg" style="color:#D3A012;"></span>
          
          <div class="form-group input-group<?php echo e($errors->has('phone') ? ' has-error' : ''); ?>">
          
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo e(old('phone')); ?>" required placeholder="<?php echo app('translator')->getFromJson('Your Mobile No.*'); ?>"/>
            <?php if($errors->has('phone')): ?>
                <span class="help-block">
                    <strong class="text-danger">
                        <?php echo e($errors->first('phone')); ?>

                    </strong>
                </span>
            <?php endif; ?>
          </div>
           
          
          <span id="emailerrmsg" style="color:#D3A012;"></span>
          
          <div class="form-group input-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
             <input placeholder="<?php echo app('translator')->getFromJson('Your Email'); ?>" type="email" id="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" required>
             <?php if($errors->has('email')): ?>
                  <span class="help-block">
                      <strong class="text-danger">
                          <?php echo e($errors->first('email')); ?>

                      </strong>
                  </span>
              <?php endif; ?>
          </div>
           
           
          <div class="form-group input-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
              <input required type="password" id="password" name="password" class="form-control"
                     placeholder="<?php echo app('translator')->getFromJson('Enter Password*'); ?>" required>
                     <?php if($errors->has('password')): ?>
                  <span class="help-block">
                      <strong class="text-danger">
                          <?php echo e($errors->first('password')); ?>

                      </strong>
                  </span>
              <?php endif; ?>
              
          </div>
          
          <div class="form-group input-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
              <input placeholder="<?php echo app('translator')->getFromJson('Retype Password*'); ?>" type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
          </div>
          
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input"   id="customCheck" value="1" required="required" name="example1">
              <label class="custom-control-label" for="customCheck">I accept terms of service</label>

            </div>
             
          </div>
          <div class="form-group">
            <button type="button" class="btn btn-primary" id="register-button">Register</button>
          </div>
          <div class="forgot-link register-link">
            <p>Already have an account? <a href="javascript:void(0)" data-dismiss="modal" data-toggle="modal" data-target="#login-modal">Login</a></p>
          </div>
        </form>
         <script>
         jQuery(document).ready(function() {

          $(".verify_otp").modal('hide');

          $(".loginBtn").on("click",function() {
            $(".off-canvas-wrapper").removeClass('open');
             $(".off-canvas-wrapper").addClass('hidden');
          });

          $(".registerBtn").on("click",function() {
            $(".off-canvas-wrapper").removeClass('open');
             $(".off-canvas-wrapper").addClass('hidden');
          });
          /*  open menu body scroll close */

          $(".mobile-menu-btn").on("click",function() {
            $("body").addClass("body-hidden");
          });
            

          $(".btn-close-off-canvas").on("click",function() {
            $("body").removeClass("body-hidden");
          });
         /*  open menu body scroll close */

          function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
          }

            $('#email').change(function(e) {
              if (isEmail($("#email").val())) {
                return true;
              } else {

                $("#emailerrmsg").html("please enter valid email address").show().fadeOut("slow");
                return false;
              }
              
            });
         });
         
         $('#phone').keypress(function(e) {
           
           if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                $("#errmsg").html("Digits Only").show().fadeOut("slow");
               return false;
           }
          if($(e.target).prop('value').length>=10) {
            if(e.keyCode!=32) {
              $("#errmsg").html("Allow 10 Digits only").show().fadeOut("slow");
              return false;
            } 
          }
             
         });
		 
          $('input[type="checkbox"]').click(function() {
                    
            if($(this).prop("checked") == true) {
               
              $("#is_term_service").val('1');
              $("#customCheck").attr('required',false);
            } else if($(this).prop("checked") == false) {
              $("#is_term_service").val('0');
              $("#customCheck").attr('required',true);
            }
          });

          $("#register-button").on('click',function(e) {
            
            e.preventDefault();
            
            $.ajax({
              
              type:"POST",
              url:"<?php echo e(route('register')); ?>",
              dataType: "JSON",
              data:$("#registerform").serialize(),
              success:function(data) {
                console.log(data.msg);
                if(data.status =='0') {
                  $("#termconditionmsg").html(data.msg);

                } else if(data.status =='1') { 
                  
                  $("#termconditionmsg").hide();
                  $("#RegisterMsg").show();
                  $("#RegisterMsg").html(data.msg);
                  setTimeout(function() {
                    $("#RegisterMsg").slideUp();
                    }, 10000);
                  
                  
                  if(data.phone !='') {
                    $("#register-modal").modal('hide');
                    $(".verify_otp").modal('show');
                  } 
                  
                }
              },
              error :function( data ) {

                if( data.status === 422 ) {

                  var errorsHtml = '';
                  var errors = $.parseJSON(data.responseText);
                  console.log(errors);
                  
                  $.each( errors.errors, function( key, value ) {

                      if(value[0] == 'The name field is required.') {
                          value[0] = 'Enter your name';
                      } else if(value[0] == 'The phone field is required.') {
                        value[0] = 'Enter your mobile no.';
                      } else if(value[1] == 'The email field is required.') {
                        value[0] = 'Enter your email';
                      } else if(value[0] == 'The password field is required.') {
                        value[0] = 'Enter your password';
                      } else if(value[0] == 'The email has already been taken.') {
                        value[0] = 'Email already exits!';
                      } 
                      errorsHtml += '<span>'+ value[0] + '</span><br/>';
                  });
                  
                  $("#RegisterMsg").show();
                  $("#RegisterMsg").html(errorsHtml);
                  
                  setTimeout(function() {
                    $("#RegisterMsg").slideUp();
                  }, 10000);
                    
                }
              }
            });

          });  
                

        
            
          </script>
      </div>
     
    </div>
  </div>
</div>
 

<div class="modal fade login-register verify_otp" id="verify_otp" tabindex="-1" role="dialog" aria-labelledby="verify_otpTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="login-modalTitle">Verify OTP</h5>
        <button type="button" class="close closeLogin" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="VerifyMsg" style="color:#D3A012;"></div>
        <form method="POST" name="" id="" action="">
          
          
           
          <div class="form-group input-group<?php echo e($errors->has('otp') ? ' has-error' : ''); ?>">
            <input type="number" class="form-control" name="otp" id="otp" placeholder="<?php echo app('translator')->getFromJson('Ente Your OTP'); ?>"/>
            <?php if($errors->has('otp')): ?>
                <span class="help-block">
                    <strong class="text-danger">
                        <?php echo e($errors->first('otp')); ?>

                    </strong>
                </span>
            <?php endif; ?>
          </div>
           
          <div class="form-group">
            <button id="verify_OTP_Btn" type="submit"  class="btn btn-primary login-button"><?php echo app('translator')->getFromJson('Verify'); ?></button>
          </div>
          
        </form>
          
      </div>
     
    </div>
  </div>
</div>

<!-- congrats model-->
<div class="modal fade login-register congrats" id="congrats" tabindex="-1" role="dialog" aria-labelledby="verify_otpTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="login-modalTitle" style="font-size:28px;">Congratulations</h5>
        <button type="button" class="close closeCongrats " data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5 style="text-align:center">Welcome to JewelNidhi</h5><br>
		<div>You just got a <span style="font-weight:bold;font-size:16px;">WELCOME BONUS</span> of <span style="color:#DAA520; font-size:20px; font-weight:bold;">Rs.250/-</span>. Redeem it on your purchase.</div><br>
		<div class="form-group">
        <button id="close_congrats" type="button"  class="btn btn-primary"><?php echo app('translator')->getFromJson('Okay'); ?></button>
		</div>
      </div>
     
    </div>
  </div>
</div>


<!-- forgot modal -->
<div class="modal fade login-register" id="forgot-modal" tabindex="-1" role="dialog" aria-labelledby="login-modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="login-modalTitle">Forgot Password</h5>
        <button type="button" class="close closeLogin" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="forgotMsg" style="color:#D3A012;"></div>
        <form method="POST" name="showforgotform" id="showforgotform" action="">
        
        <span id="forgoterrmsg" style="color:#D3A012;"></span>
          <div class="form-group  input-group<?php echo e($errors->has('username') || $errors->has('email') ? ' has-error' : ''); ?>">
            <input type="text" class="form-control " name="forgot_mobile" id="forgot_mobile" placeholder="<?php echo app('translator')->getFromJson('Your Mobile'); ?>"/>
           
          </div>
           
          
           
          <div class="form-group">
            <button id="forgot_button" type="button"  class="btn btn-primary"><?php echo app('translator')->getFromJson('Forgot Password'); ?></button>
          </div>
          <div class="forgot-link">
            <!-- <a href="<?php echo e(route('password.request')); ?>"><?php echo app('translator')->getFromJson('Forgot Your Password?'); ?></a> -->
            <p>Already have an account? <a href="javascript:void(0)" data-dismiss="modal" data-toggle="modal" data-target="#login-modal">Login</a></p>
            <p>Don't have an account with us? <a href="javascript:void(0)" data-dismiss="modal" data-toggle="modal" data-target="#register-modal"><?php echo app('translator')->getFromJson('Sign Up'); ?></a></p>
          </div>
        </form>
        
      </div>
     
    </div>
  </div>
</div>
<!-- forgot modal -->
<!-- forgot otp -->
<div class="modal fade login-register verify_forgot_otp" id="verify_forgot_otp" tabindex="-1" role="dialog" aria-labelledby="verify_otpTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="login-modalTitle">Verify OTP</h5>
        <button type="button" class="close closeLogin" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="VerifyForgotMsg" style="color:#D3A012;"></div>
        <form method="POST" name="" id="" action="">
          <div class="form-group input-group<?php echo e($errors->has('otp') ? ' has-error' : ''); ?>">
            <input type="number" class="form-control" name="forgot_otp" id="forgot_otp" placeholder="<?php echo app('translator')->getFromJson('Ente Your OTP'); ?>"/>
            <?php if($errors->has('otp')): ?>
                <span class="help-block">
                    <strong class="text-danger">
                        <?php echo e($errors->first('otp')); ?>

                    </strong>
                </span>
            <?php endif; ?>
          </div>
          <div class="form-group">
            <button id="verify_forgot_otp_Btn" type="button"  class="btn btn-primary"><?php echo app('translator')->getFromJson('Verify'); ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- forgot otp -->
<!-- new password-->
<div class="modal fade login-register" id="reset_password" tabindex="-1" role="dialog" aria-labelledby="reset_passwordTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="login-modalTitle">Reset Password</h5>
        <button type="button" class="close closeLogin" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="resetPwdMsg" style="color:#D3A012;"></div>
        <form method="POST" name="" id="" action="">
          <div class="form-group input-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
              <input type="password" id="new_password" name="new_password" class="form-control"
                     placeholder="<?php echo app('translator')->getFromJson('Enter New Password*'); ?>" >
          </div>
          
          <div class="form-group input-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
              <input placeholder="<?php echo app('translator')->getFromJson('Retype New Password*'); ?>" type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
          </div>
          <div class="form-group">
            <button id="change_password_Btn" type="button"  class="btn btn-primary"><?php echo app('translator')->getFromJson('Change Password'); ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- new password-->
<script type="text/javascript">
  jQuery(document).ready(function() {

    $("#verify_OTP_Btn").on("click",function() {

      $.ajax({
        type:"POST",
        url:"<?php echo e(route('front.verify_otp')); ?>",
        dataType: "JSON",
        data:{otp:$("#otp").val()},
        success:function(data) {
          if(data.status =='0') {
            $("#VerifyMsg").html(data.msg);
          } else if(data.status =='1') { 
            $("#termconditionmsg").hide();
            $("#VerifyMsg").show();
            $("#VerifyMsg").html(data.msg);
            /* setTimeout(function() {
              $("#VerifyMsg").slideUp();
			  location.reload();
              }, 3000); */
			  $(".verify_otp").modal('hide');
               $(".congrats").modal('show');
          }
        }
      });

    });
	$('#forgot_mobile').keypress(function(e) {
           
           if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                $("#forgoterrmsg").html("Digits Only").show().fadeOut("slow");
               return false;
           }
          if($(e.target).prop('value').length>=10) {
            if(e.keyCode!=32) {
              $("#forgoterrmsg").html("Allow 10 Digits only").show().fadeOut("slow");
              return false;
            } 
          }
             
         });
	$("#forgot_button").on("click",function() {

      $.ajax({
        type:"POST",
        url:"<?php echo e(route('front.check_user')); ?>",
        dataType: "JSON",
        data:{forgot_mobile:$("#forgot_mobile").val()},
        success:function(data) {
          if(data.status =='0') {
            $("#forgotMsg").html(data.msg);
          } else if(data.status =='1') { 
            $(".verify_forgot_otp").modal('show');
            $("#forgot-modal").modal('hide');
			
          }
        }
      });

    });
	$("#close_congrats, .closeCongrats").on("click",function() {

     location.reload();
            
    });
	$("#verify_forgot_otp_Btn").on("click",function() {

      $.ajax({
        type:"POST",
        url:"<?php echo e(route('front.verify_forgot_otp')); ?>",
        dataType: "JSON",
        data:{otp:$("#forgot_otp").val()},
        success:function(data) {
          if(data.status =='0') {
            $("#VerifyForgotMsg").html(data.msg);
          } else if(data.status =='1') { 
			$(".verify_forgot_otp").modal('hide');
			$("#reset_password").modal('show');
          }
        }
      });

    });
	$("#change_password_Btn").on("click",function() {

      $.ajax({
        type:"POST",
        url:"<?php echo e(route('front.change_password')); ?>",
        dataType: "JSON",
        data:{new_password:$("#new_password").val(),new_password_confirmation:$("#new_password_confirmation").val()},
        success:function(data) {
          if(data.status =='0') {
            $("#resetPwdMsg").show();
            $("#resetPwdMsg").html(data.msg);
          } else if(data.status =='1') { 
			$("#resetPwdMsg").show();
            $("#resetPwdMsg").html(data.msg);
            setTimeout(function() {
              $("#resetPwdMsg").slideUp();
			  $("#reset_password").modal('hide');
              }, 3000);
          }
        }
      });

    });
	
	
  });
</script>

    <!-- hero slider area start -->