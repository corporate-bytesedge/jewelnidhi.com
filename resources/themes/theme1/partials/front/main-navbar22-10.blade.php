<header>
  <div class="top-scroll-sec">
    <div class="scroll-rate-sec">
      <p>Today’s Gold rate 22K per gm:  {{ isset($gold_rate->value) ? currency_format(number_format($gold_rate->value)) : currency_format('0.00') }}</p>
       
      <p class="inactive"> Today’s Silver rate per gm: {{ isset($silver_rate->value) ? currency_format(number_format($silver_rate->value)) : currency_format('0.00') }}</p>
    </div>
   </div>
  <div class="desktop-header">
    <div class="top-header">
      <div class="container">
        <div class="t-header-sec">
        
          <div class="logo">
            @if(config('settings.site_logo_enable') == 1)
              <a href="{{url('/')}}"><img src="{{ asset('img/logo_new.gif') }}" alt="{{ config('settings.site_logo_name') }}" height="{{ config('settings.site_logo_height') }}" width="{{ config('settings.site_logo_width') }}"/></a>
            @endif
          </div>
       
        
          <div class="top-right">
            
            
            <div class="top-right-link-search">
             <ul class="link-search">
                <li class="search">
                    {!! Form::open(['method'=>'get', 'action'=>['FrontController@search'], 'role'=>'search', 'class'=>'navbar-form', 'autocomplete'=>'off' ,'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
                      <div class="search-box">
                        {!! Form::text('keyword', null, ['class'=>'search-keyword' ,'placeholder'=>__('Enter Search...'), 'required', 'autofocus']) !!}
                        
                        <button type="submit" name="submit_button" class="search-icon"><img src="{{ URL::asset('img/search-icon.png') }}" alt=""/></button>
                      </div>
                    {!! Form::close() !!}
                </li>
              
              </ul>
              
              <ul class="top-icon">
                <li>
                  @if(\Auth::user()) 
                    <a href="{{url('/products/wishlist')}}"><img src="{{ URL::asset('img/heart.png') }}" alt=""/></a>
                  @else
                    <a href="javascript:void(0)"  data-toggle="modal" data-target="#login-modal"><img src="{{ URL::asset('img/heart.png') }}" alt=""/></a> 
                  @endif
                </li>
                <li class="cart-count">
                  <a href="{{ route('front.cart.index') }}"> <img src="{{ URL::asset('img/bascket.png') }}" alt=""/>
                    <span id="cartCount">{{Cart::content()->count()}}</span>
                  </a>
                </li>
                <li><a href="#"></a><img src="{{ URL::asset('img/india-flag.png') }}" alt=""/></a></li>
                  
                @if(\Auth::check())
                  <li class="dropdown">
                    <ul class="">
                      
                      <li>
                        <a href="#"><img src="{{ URL::asset('img/user-icon.png') }}" alt=""/> {{Auth::user()->name}}</a>
                        <ul class="dropdown-list">
                        @if(!Auth::user()->isSuperAdmin()  && !Auth::user()->vendor)
                          <li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('front.account')}}"><i class="fa fa-user"></i> @lang('Account Overview')</a></li>
                        @endif
                          @if(Auth::user()->vendor && Auth::user()->can('read', App\Customer::class))
                            <li>
                                <a  href="{{route('front.customers.customer')}}"><i class="fa fa-user"></i> @lang('Customer')</a>
                            </li>
                          @endif
                          @if(!Auth::user()->isSuperAdmin() && !Auth::user()->vendor)
                               
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('front.orders.index')}}"><i class="fa fa-shopping-cart"></i> @lang('Orders')</a></li>
                               

                              @if (Auth::user()->can('read', App\Address::class) )
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('front.addresses.index')}}"><i class="fa fa-truck"></i> @lang('Addresses')</a></li>
                              @endif
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('front.settings.profile')}}"><i class="fa fa-wrench"></i> @lang('Edit Profile')</a></li>
                           @endif
                          <!-- @if(Auth::user()->vendor && !Auth::user()->isSuperAdmin())
                          <li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('front.vendor.profile')}}"><i class="fa fa-user"></i> @lang('Vendor Profile')</a></li>
                          @endif -->
                          @if(Auth::user()->role)
                              <li role="presentation" class="divider"></li>
                              @if(Auth::user()->vendor && isset(Auth::user()->vendor->approved) && Auth::user()->isApprovedVendor())
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('/manage/vendor/dashboard')}}"><i class="fa fa-wrench"></i> @lang('Manage')</a></li>
                              @elseif(Auth::user()->isSuperAdmin() || Auth::user()->can('view-dashboard', \App\Other::class) )
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="{{url('/manage')}}"><i class="fa fa-wrench"></i> @lang('Manage')</a></li>
                              @endif
                          @endif
                          <li role="presentation"><a role="menuitem" tabindex="-1" onclick="logout();" href="#"><i class="fa fa-sign-out"></i> @lang('Logout')</a></li>
                          <form id="logout_form" action="{{ route('logout') }}" method="POST">
                              {{ csrf_field() }}
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
                  
                @else

                  <!-- <li><a href="javascript:void(0)" data-toggle="modal" data-target="#login-modal">@lang('Login')</a></li>
                  <li><a href="javascript:void(0)" data-toggle="modal" data-target="#register-modal">@lang('Signup')</a></li> -->
                    <ul class="top-icon">
                      
                      <li><a href="#"><img src="{{ URL::asset('img/user-icon.png') }}" alt=""/></i></a>
                        <ul class="dropdown-list">
                          <div class="login-dropdown">
                            <h4>Your Account</h4>
                            <p>Access account & manage your orders.</p>
                            <div class="btn-sec">
                              <a href="javascript:void(0)" data-toggle="modal" data-target="#login-modal">@lang('Login')</a>
                              <a href="javascript:void(0)" data-toggle="modal" data-target="#register-modal">@lang('Signup')</a>
                            </div>
                          </div>
                          
                        </ul>
                      </li>
                    </ul>
                    
                @endif
                 
              </ul>
            </div>
          </div>

        </div>

      </div>

    </div>
 
     <div class="container">
        <div class="menu-bar main-menu">
          <ul class="menu">
         
            @if(!empty($categories))
            @php $priceRange = 0;@endphp
            
              @foreach($categories AS $k=> $value)
               
              @php $priceRange = range($value->min_price,$value->max_price);
             
              @endphp
             
               
              @if(!$value->categories->isEmpty())
              <li><a href="/category/{{ $value->slug }}">{{ $value->name }} <i class="fa fa-angle-down"></i></a>
              
                    <div class="sub-menu">
                      <div class="inner-menu">
                        <div class="menu-box first">
                          <h5>Shop by style</h5>
                          <ul>
                            
                            @foreach($value->categories->sortBy('priority') AS $k=> $val)
                              @php
                                  $file = public_path().'/storage/style/'.$val->image;
                                  
                              @endphp
                                  <li>
                                    <a href="/category/{{ $val->slug }}">
                                      @if(file_exists($file))
                                        <img src="{{ asset('storage/style/'.$val->image) }} " style="max-width:20px!important;" >
                                      @endif
                                      <span>{{ $val->name }}</span>
                                    </a>
                                  </li>
                              @endforeach
                             
                          </ul>
                        </div>
                         @php 
                              $shopByMetal = \App\ShopByMetalStone::where('is_active',true)->where('category_id',$value->id)->get();
                              
                         @endphp
                         @if($value->name != 'Silver Articles'  && $value->name != 'Gems' )
                            @if(count($shopByMetal) > 0)
                              <div class="menu-box second">
                                <h5>SHOP BY METAL & STONE </h5>
                                <ul>
                                  @foreach($shopByMetal AS $metals)
                                      
                                        <li> 
                                          <a  href="/category/{{ $value->slug }}/{{ $metals->id  }}"><img src="{{ URL::asset('img/'.$metals->image) }}" alt=""/> <span> {{ strtoupper($metals->name) }}</span></a>
                                        </li>
                                      
                                  @endforeach
                                  
                                </ul>
                              </div>
                            @endif
                          @endif
                        @if($value->min_price!='0' && $value->max_price!='0')
                        <div class="menu-box third">
                          <h5>By Price Range</h5>
                          <ul>
                             
                             
                            @php  
                                
                                $start = $value->min_price;
                                
                                $end = $value->max_price;
                                $step = 10000;
                                $minVal = '';
                             @endphp
                            <li><a href="/filter/{{ $value->slug }}/0/{{ $start }}">Below  {{ isset($start) ? number_format($start): '' }} </a></li>
                             
                            
                            @for($i=$start; $i<$end; $i)

                            @php
                              $firstNum = $i;
                              $minPrice = str_split($firstNum);
                              $lastNum = $i + $step;
                              $MaxPrice = str_split($lastNum);

                              if($i < $step) {
                                $minVal = $minPrice[0];
                              } elseif($i==$step) {
                                $minVal = $minPrice[0].''.$MaxPrice[1];
                              }else {
                                $minVal = $minPrice[0].''.$MaxPrice[1];
                              } 
                            @endphp
                              <li><a href="/filter/{{ $value->slug }}/{{ $firstNum }}/{{ $lastNum }}"> Between {{ $minVal }}K -  {{ $MaxPrice[0].$MaxPrice[1] }}K </a></li>
                              @php 
                                $i=$i+$step
                              @endphp 
                            @endfor
                            
                            <li><a href="/filter/{{ $value->slug }}/{{ $end }}">  {{ isset($end) ? number_format($end): '' }} and above </a></li>
                             
                          </ul>
                        </div>
                        @endif
                        @if($value->name != 'Silver Articles' )
                          <div class="menu-box third">
                            <h5>Popular Type</h5>
                            <ul>
                                <li><a href="/filter/{{ $value->slug }}/men">For Men </a></li>
                                <li><a href="/filter/{{ $value->slug }}/women"> For Women </a></li>
                                <li><a href="/filter/{{ $value->slug }}/kids"> For Kids </a></li>
                            </ul>
                          </div>
                        @endif
                      </div>
                    </div>
              
                  </li>
              @else
                <li><a href="/category/{{ $value->slug }}">{{ $value->name }}</a></li>
              @endif
               
                @endforeach
            @endif
              @if(!empty($scheme))
                <li><a href="{{ route('front.schemes') }}">Schemes</a></li>
              @endif
                <li><a href="{{ route('front.catalog') }}">Catalog Order</a></li>
          </ul>
            @if(config('settings.contact_number'))
              <span class="call-no"><i class="fa fa-phone"></i> {{ config('settings.contact_number') }}</span>
            @endif
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
                          
                              <a href="{{url('/')}}">
                                  <img src="{{ asset('img/logo_new.gif') }}" alt="{{ config('settings.site_logo_name') }}">
                              </a>
                          </div>
                          <div class="mobile-menu-toggler">
                              <div class="mini-cart-wrap">
                              @if(\Auth::user()) 
                                    <a href="{{url('/products/wishlist')}}"><img src="{{ URL::asset('img/heart.png') }}" alt=""/></a>
                                @else
                                    <a href="javascript:void(0)"  data-toggle="modal" data-target="#login-modal"><img src="{{ URL::asset('img/heart.png') }}" alt=""/></a> 
                                @endif
                              
                                <a href="{{ route('front.cart.index') }}">
                                      <img src="{{ URL::asset('img/bascket.png') }}" alt=""/>
                                      <span id="cartCount">{{Cart::content()->count()}}</span>
                                  </a>

                              <a href="#"></a><img src="{{ URL::asset('img/india-flag.png') }}" alt=""/></a>
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
                  <div class="search-box-offcanvas">
                  {!! Form::open(['method'=>'get', 'action'=>['FrontController@search'], 'role'=>'search', 'class'=>'navbar-form', 'autocomplete'=>'off' ,'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
                    {!! Form::text('keyword', null, ['class'=>'search-keyword' ,'placeholder'=>__('Enter Search...'), 'required', 'autofocus']) !!}
                    <button type="submit" name="submit_button" class="search-btn"><img src="{{ URL::asset('img/search-icon.png') }}" alt=""/></button>
                  {!! Form::close() !!}
                  </div>
                  <!-- search box end -->

                  <!-- mobile menu start -->
                  <div class="mobile-navigation">

                      <!-- mobile menu navigation start -->
                      <nav>
                          <ul class="mobile-menu">
                            @if(!empty($categories))
                              @php $priceRange = 0;@endphp
                                  @foreach($categories AS $k=> $value)
                                    @php $priceRange = range($value->min_price,$value->max_price);
                                     
                                    @endphp
                                    @if(!$value->categories->isEmpty())
                                      <li class="menu-item-has-children"><a href="/category/{{ $value->slug }}">{{ $value->name }}</a>
                                        <ul class="dropdown">
                                          <li class="menu-item-has-children"><a href="#">Shop by style</a>
                                              <ul class="dropdown">
                                              @foreach($value->categories AS $k=> $val)
                                              @php 
                                                $file = public_path().'/storage/style/'.$val->image;
                                              @endphp
                                                  <li>
                                                    <a href="/category/{{ $val->slug }}">
                                                    @if(file_exists($file))
                                                        <img src="{{ asset('storage/style/'.$val->image) }} " style="max-width:20px!important;" >
                                                      @endif
                                                      {{ $val->name }}
                                                    </a>
                                                  </li>
                                                @endforeach
                                              </ul>
                                          </li>
                                                @php 
                                                  $shopByMetal = \App\ShopByMetalStone::where('is_active',true)->where('category_id',$value->id)->get();
                                    
                                                @endphp
                                                @if(count($shopByMetal) > 0)
                                                  <li class="menu-item-has-children"><a href="#">Shop By Metal & Stone</a>
                                                      <ul class="dropdown">
                                                      @foreach($shopByMetal AS $metals)
                                              
                                                        <li> 
                                                          <a  href="/category/{{ $value->slug }}/{{ $metals->id  }}"><img src="{{ URL::asset('img/'.$metals->image) }}" alt=""/> <span> {{ strtoupper($metals->name) }}</span></a>
                                                        </li>
                                            
                                                      @endforeach
                                                      </ul>
                                                  </li>
                                                @endif
                                          @if($value->min_price!='0' && $value->max_price!='0')
                                          @php  
                                
                                              $start = $value->min_price;
                                              $end = $value->max_price;
                                              $step = 10000;
                                          @endphp
                                          <li class="menu-item-has-children"><a href="#">By Price Range</a>
                                              <ul class="dropdown">
                                                <li><a href="/filter/{{ $value->slug }}/0/{{ $start }}">Below  {{ isset($start) ? number_format($start): '' }} </a></li>
                                                @for($i=$start; $i<$end; $i=$i+$step)

                                                @php
                                                  $firstNum = $i;
                                                  $minPrice = str_split($firstNum);
                                                  $lastNum = $i + $step;
                                                  $MaxPrice = str_split($lastNum);
                                                @endphp
                                                  <li><a href="/filter/{{ $value->slug }}/{{ $firstNum }}/{{ $lastNum }}"> Between {{ $minPrice[0].$minPrice[1] }}K -  {{ $MaxPrice[0].$MaxPrice[1] }}K </a></li>
                                                    
                                                @endfor
                                                
                                                <li><a href="/filter/{{ $value->slug }}/{{ $end }}">  {{ isset($end) ? number_format($end): '' }} and above </a></li>

                                              </ul>
                                          </li>
                                            @if($value->name != 'Silver Articles'   )
                                                <li class="menu-item-has-children"><a href="#">Popular Type</a>
                                                    <ul class="dropdown">
                                                        <li><a href="/filter/{{ $value->slug }}/men">For Men </a></li>
                                                        <li><a href="/filter/{{ $value->slug }}/women"> For Women </a></li>
                                                        <li><a href="/filter/{{ $value->slug }}/kids"> For Kids </a></li>

                                                      
                                                      
                                                    </ul>
                                                </li>
                                            @endif
                                          @endif
                                        </ul>
                                      </li>
                                    @else
                                    <li><a href="/category/{{ $value->slug }}">{{ $value->name }}</a></li>
                                  @endif
                                @endforeach
                            @endif
                            @if(!empty($scheme))
                              <li><a href="{{ route('front.schemes') }}">Schemes</a></li>
                            @endif
                              <li><a href="{{ route('front.catalog') }}">Catalog Order</a></li>
                          </ul>
                      </nav>
                      <!-- mobile menu navigation end -->
                  </div>
                  <!-- mobile menu end -->

                  <div class="mobile-settings">

                  
                      <ul class="nav">

                      @if(Auth::check() )

                       

                      <li>
                              <div class="dropdown mobile-top-dropdown">

                                  <a href="#" class="dropdown-toggle" id="myaccount" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{Auth::user()->name}}
                                      <i class="fa fa-angle-down"></i>
                                  </a>
                                  
                                  <div class="dropdown-menu" aria-labelledby="myaccount">

                                    @if(!Auth::user()->isSuperAdmin())
                                      <a class="dropdown-item" href="{{route('front.account')}}"><i class="fa fa-user"></i> @lang('Account Overview')</a>
                                    @endif

                                    @if(Auth::user()->vendor && Auth::user()->can('read', App\Customer::class))
                                      <a class="dropdown-item"  href="{{route('front.customers.customer')}}"><i class="fa fa-user"></i> @lang('Customer')</a>
                                    @endif

                                    @if(!Auth::user()->isSuperAdmin())
                                      @if (Auth::user()->can('read', App\Order::class) )
                                        <a class="dropdown-item" href="{{route('front.orders.index')}}"><i class="fa fa-shopping-cart"></i> @lang('Orders')</a>
                                      @endif

                                      @if (Auth::user()->can('read', App\Address::class) )
                                        <a class="dropdown-item" tabindex="-1" href="{{route('front.addresses.index')}}"><i class="fa fa-truck"></i> @lang('Addresses')</a>
                                      @endif
                                       <a class="dropdown-item" tabindex="-1" href="{{route('front.settings.profile')}}"><i class="fa fa-wrench"></i> @lang('Edit Profile')</a>
                                    @endif

                                    <!-- @if(Auth::user()->vendor && !Auth::user()->isSuperAdmin())
                                        <a class="dropdown-item" tabindex="-1" href="{{route('front.vendor.profile')}}"><i class="fa fa-user"></i> @lang('Vendor Profile')</a>
                                    @endif -->
                                    @if(Auth::user()->role)
                                        
                                        @if(Auth::user()->vendor && isset(Auth::user()->vendor->approved) && Auth::user()->isApprovedVendor())
                                            <a  class="dropdown-item" href="{{url('/manage/vendor/dashboard')}}"><i class="fa fa-wrench"></i> @lang('Manage')</a>
                                        @elseif(Auth::user()->isSuperAdmin() || Auth::user()->can('view-dashboard', \App\Other::class) )
                                            <a class="dropdown-item" href="{{url('/manage')}}"><i class="fa fa-wrench"></i> @lang('Manage')</a>
                                        @endif
                                    @endif

                                    <a class="dropdown-item" onclick="logout();" href="#"><i class="fa fa-sign-out"></i> @lang('Logout')</a>
                                      <form id="logout_form" action="{{ route('logout') }}" method="POST">
                                          {{ csrf_field() }}
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
                        
                          

                      
                      @else
                      <li>
                              <div class="dropdown mobile-top-dropdown">
                                  <a href="#" class="dropdown-toggle" id="myaccount" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      My Account
                                      <i class="fa fa-angle-down"></i>
                                  </a>
                                  <div class="dropdown-menu" aria-labelledby="myaccount">
                                  
                                       
                                      <a class="dropdown-item loginBtn" href="javascript:void(0)" data-toggle="modal" data-target="#login-modal">@lang('Login')</a>
                                      <a class="dropdown-item registerBtn" href="javascript:void(0)" data-toggle="modal" data-target="#register-modal">@lang('Signup')</a>
                                  </div>
                              </div>
                          </li>

                      @endif
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
                                @if(config('settings.contact_number'))
                                  {{ config('settings.contact_number') }}
                                @endif
                              </li>
                              <li><i class="fa fa-envelope"></i>
                                @if(config('settings.contact_email'))
                                  {{ config('settings.contact_email') }}
                                @endif
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
        <form method="POST" name="loginform" id="loginform" action="{{ route('login') }}">
        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
        
          <div class="form-group  input-group{{ $errors->has('username') || $errors->has('email') ? ' has-error' : '' }}">
            <input type="text" class="form-control " name="username" id="username" placeholder="@lang('Your Email/Mobile')"/>
            @if ($errors->has('username'))
                  <span class="help-block">
                      <strong class="text-danger">
                          {{ $errors->first('username') }}
                      </strong>
                  </span>
              @endif
              @if ($errors->has('email'))
                  <span class="help-block">
                      <strong class="text-danger">
                          {{ $errors->first('email') }}
                      </strong>
                  </span>
              @endif
          </div>
           
          <div class="form-group input-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <input type="password" class="form-control " name="password" id="password" placeholder="@lang('Your Password')"/>
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong class="text-danger">
                        {{ $errors->first('password') }}
                    </strong>
                </span>
            @endif
          </div>
           
          <div class="form-group">
            <button id="login-button" type="submit"  class="btn btn-primary login-button">@lang('Login')</button>
          </div>
          <div class="forgot-link">
            <!-- <a href="{{ route('password.request') }}">@lang('Forgot Your Password?')</a> -->
            <a href="javascript:void(0)" data-toggle="modal" data-dismiss="modal" data-target="#forgot-modal">@lang('Forgot Password?')</a>
            <p>Don't have an account with us? <a href="javascript:void(0)" data-dismiss="modal" data-toggle="modal" data-target="#register-modal">@lang('Sign Up')</a></p>
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
                             $(".login-button").text('{{__('Login')}}').prop('disabled', false);
                             $("#LoginMsg").slideUp();
                           }, 2000); 
                           window.location.reload();
                       } else if(data.role == 'super_admin') {
                           
                            
                         window.open('{{ URL::to("/manage") }}','_blank');
                         setTimeout(function() {
                             $(".login-button").text('{{__('Login')}}').prop('disabled', false);
                             $("#LoginMsg").slideUp();
                           }, 2000); 
                           window.location.reload();
                            
                       } else if(data.role == 'vendor') {
                           
                            
                           window.open('{{ URL::to("/manage/vendor/dashboard") }} ','_blank');
                           setTimeout(function() {
                               $(".login-button").text('{{__('Login')}}').prop('disabled', false);
                               $("#LoginMsg").slideUp();
                             }, 2000); 
                             window.location.reload();
                              
                         } else {
                           $("#LoginMsg").show();
                           $("#LoginMsg").html(data.msg);
                           setTimeout(function() {
                             $(".login-button").text('{{__('Login')}}').prop('disabled', false);
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
           
          <div class="form-group input-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required placeholder="@lang('Your Name')"/>
            @if ($errors->has('name'))
                <span class="help-block">
                    <strong class="text-danger">
                        {{ $errors->first('name') }}
                    </strong>
                </span>
            @endif
          </div>
          <span id="errmsg" style="color:#D3A012;"></span>
          
          <div class="form-group input-group{{ $errors->has('phone') ? ' has-error' : '' }}">
          
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="@lang('Your Mobile No.')"/>
            @if ($errors->has('phone'))
                <span class="help-block">
                    <strong class="text-danger">
                        {{ $errors->first('phone') }}
                    </strong>
                </span>
            @endif
          </div>
           
          
          <span id="emailerrmsg" style="color:#D3A012;"></span>
          
          <div class="form-group input-group{{ $errors->has('email') ? ' has-error' : '' }}">
             <input placeholder="@lang('Your Email')" type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" required>
             @if ($errors->has('email'))
                  <span class="help-block">
                      <strong class="text-danger">
                          {{ $errors->first('email') }}
                      </strong>
                  </span>
              @endif
          </div>
           
           
          <div class="form-group input-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <input required type="password" id="password" name="password" class="form-control"
                     placeholder="@lang('Enter Password')" required>
                     @if ($errors->has('password'))
                  <span class="help-block">
                      <strong class="text-danger">
                          {{ $errors->first('password') }}
                      </strong>
                  </span>
              @endif
              
          </div>
          
          <div class="form-group input-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <input placeholder="@lang('Retype Password')" type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
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
              url:"{{ route('register') }}",
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
          
          
           
          <div class="form-group input-group{{ $errors->has('otp') ? ' has-error' : '' }}">
            <input type="number" class="form-control" name="otp" id="otp" placeholder="@lang('Ente Your OTP')"/>
            @if ($errors->has('otp'))
                <span class="help-block">
                    <strong class="text-danger">
                        {{ $errors->first('otp') }}
                    </strong>
                </span>
            @endif
          </div>
           
          <div class="form-group">
            <button id="verify_OTP_Btn" type="submit"  class="btn btn-primary login-button">@lang('Verify')</button>
          </div>
          
        </form>
          
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
        <div id="LoginMsg"></div>
        <form method="POST" name="loginform" id="loginform" action="">
        
        
          <div class="form-group  input-group{{ $errors->has('username') || $errors->has('email') ? ' has-error' : '' }}">
            <input type="text" class="form-control " name="username" id="username" placeholder="@lang('Your Email/Mobile')"/>
            @if ($errors->has('username'))
                  <span class="help-block">
                      <strong class="text-danger">
                          {{ $errors->first('username') }}
                      </strong>
                  </span>
              @endif
              @if ($errors->has('email'))
                  <span class="help-block">
                      <strong class="text-danger">
                          {{ $errors->first('email') }}
                      </strong>
                  </span>
              @endif
          </div>
           
          
           
          <div class="form-group">
            <button id="login-button" type="button"  class="btn btn-primary login-button">@lang('Reset Password')</button>
          </div>
          
        </form>
        
      </div>
     
    </div>
  </div>
</div>
<!-- forgot modal -->
<script type="text/javascript">
  jQuery(document).ready(function() {

    $("#verify_OTP_Btn").on("click",function() {

      $.ajax({
        type:"POST",
        url:"{{ route('front.verify_otp') }}",
        dataType: "JSON",
        data:{otp:$("#otp").val()},
        success:function(data) {
          if(data.status =='0') {
            $("#VerifyMsg").html(data.msg);
          } else if(data.status =='1') { 
            $("#termconditionmsg").hide();
            $("#VerifyMsg").show();
            $("#VerifyMsg").html(data.msg);
            setTimeout(function() {
              $("#VerifyMsg").slideUp();
              }, 10000);
               
          }
        }
      });

    });
  });
</script>

    <!-- hero slider area start -->