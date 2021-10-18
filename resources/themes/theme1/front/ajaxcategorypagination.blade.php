    
   


        
          @foreach($products AS $key => $val)
           
            
            
          <div class="col-lg-3 col-md-4 col-6" id="itemList">
                <div class="cat-item">
                  @if(isset($val->product_discount) && $val->product_discount!='0')
                    <span class="offers-wrapper"> <span class="offer"> <span>{{ $val->product_discount .'% '.$val->product_discount_on }} </span> </span> </span>
                  @endif
                    <figure class="product-thumb">
                        <a href="/product/{{ $val->slug }}">
                        
                        @if(!empty($val->photo))

                            @php 
                             
                              if($val->photo->name) {
                                $featured = public_path().'/img/'.basename($val->photo->name);
                              } 
                               
                            @endphp

                              @if(file_exists($featured))
                                 
                                @php
                                 
                                    $image_url = \App\Helpers\Helper::check_image_avatar($val->photo->name, 150);
                                @endphp
                                  <img class="{{ $val->overlayphoto!='' ? 'pri-img' : ''}} " src="{{$image_url}}" alt="{{ $val->photo->name }}">
                              
                              @else
                              <img src="{{ asset('img/noimage.png') }}" class="img-responsive product-feature-image" alt="" />
                              
                              
                                  
                              @endif
                              
                              

                        @else
                        <img src="{{ asset('img/noimage.png') }}" class="img-responsive product-feature-image" alt="" />
                        @endif
                        
                        @if(!empty($val->overlayphoto))

                          @php 
                              if($val->overlayphoto->name) {
                                $overlay = public_path().'/img/'.basename($val->overlayphoto->name);
                              }
                            @endphp
                            @if(file_exists($overlay))
                                  @php
                                      $image_url = \App\Helpers\Helper::check_overlayimage_avatar($val->overlayphoto->name, 150);
                                  @endphp
                                  <img class="sec-img" src="{{ $image_url }}" alt="{{ $val->overlayphoto->name }}">
                           
                            @endif
                        @endif   
                        </a>
                        @if(isset($val->is_new) && $val->is_new=='1')
                          <div class="product-badge">
                              <div class="product-label new">
                                  <span>new</span>
                              </div>
                          </div>
                        @endif
                        <div class="button-group">

                                @if(\Auth::user()) 
                                  @php
                                    $selected = false;
                                    foreach(\Auth::user()->favouriteProducts as $taxonomy) {
                                      if($taxonomy->id == $val->id )
                                      {
                                      $selected = true;
                                      }
                                    }
                        
                            
                                  @endphp

                                    
                                        <a href="javascript:void(0);" @php echo $selected == true ? '' :'hidden' @endphp  id="removeBtn_{{ $val->id }}" data-toggle="tooltip" style="color:goldenrod " onclick="removeWishlist('{{ $val->id }}')"   data-placement="left" title="Remove from your wishlist">
                                          <i class="fa fa-heart"></i>
                                      </a>
                                   
                                        <a href="javascript:void(0);" @php echo $selected == false ? '' :'hidden' @endphp   id="addBtn_{{ $val->id }}" data-toggle="tooltip" data-placement="left" title="Add to wishlist" onclick="addWishlist('{{ $val->id }}')">
                                            <i class="fa fa-heart-o"></i>
                                        </a>
                                    
                                    <form id="product-favourite-form_{{ $val->id }}" class="hidden" action="{{ route('front.product.favourite.store', $val->id) }}" method="POST">
                                      {{ csrf_field() }}
                                    </form>

                                @else
                                <span data-toggle="modal" data-target="#login-modal">
                                  <a href="javascript:void(0);" data-toggle="tooltip" data-placement="left" title="Add to wishlist"  >
                                    <i class="fa fa-heart-o"></i>          
                                  </a>
                                </span>
                                     
                                @endif

                                  
                                
                             
                        </div>
                        <div class="cart-hover">
                          <a href="/product/{{ $val->slug }}"><button class="btn btn-cart">View Details</button></a>
                        </div>
                    </figure>
                      
                      <div class="product-caption">

                          @if(isset($val->product_discount) && $val->product_discount!='')
                              <div class="price-box">
                              @if($val->old_price!='0')
                                  <span class="price-old">
                                      <del> 
                                      <i class="fa fa-rupee"></i> {{ isset($val->old_price) && $val->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($val->old_price) : '' }}
                                      </del>
                                    </span>
                                @endif  
                                  <span class="price-regular"> 
                                  <i class="fa fa-rupee"></i> {{ isset($val->new_price) && $val->new_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($val->new_price) : '' }}</span>
                              </div>
                              @else
                              <div class="price-box">
                                <span class="price-regular"> 
                                <i class="fa fa-rupee"></i> {{ isset($val->old_price) && $val->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($val->old_price) : '' }}
                                </span>
                              </div>
                          @endif

                                     
                       
                            @if(isset($val->old_price) && $val->old_price!=null && $val->product_discount!='')
                              <div class="you-save">
                                Save <span>
                                <i class="fa fa-rupee"></i> {{ isset($val->old_price) && $val->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($val->old_price - $val->new_price) : '' }}
                                </span>
                              </div>
                            @endif
                      </div>
                    
                </div>
            </div>
              @endforeach
             
        