 

 <div class="section our-bestselling" data-aos="fade-up" data-aos-once="true">
    <div class="container">
    <div id="CartMsg"></div>
      <div class="heading-sec">
        <h2>Our Bestselling items</h2>
        <img src="{{ URL::asset('img/home_line.png') }}" alt=""/>
      </div>
      <div class="row">
        <div class="col-lg-12 columns">
          <div class="owl-carousel owl-theme">
              @if(!empty($best_selling_products))
                @foreach($best_selling_products AS $k=> $value)
                  {!! Form::open(['method'=>'patch', 'route'=>['front.cart.add', $value->id], 'id'=>'frontcart-form'.$value->id]) !!}
                  @php 
                    if($value->photo->name) {
                      $file = public_path().'/img/'.basename($value->photo->name);
                    }
                  @endphp
                    <div class="item">
                      <a href="/product/{{ $value->slug }}">
                          @if(file_exists($file))
                              @php
                                $image_url = \App\Helpers\Helper::check_image_avatar($value->photo->name, 200);
                              @endphp
                                <img src="{{$image_url}}" alt=""  />
                            @else
                              <img src="https://via.placeholder.com/200x200?text=No+Image" alt=""   />
                          @endif
                          <!-- <p> {{ ucwords($value->name) }}</p> -->

                          <div class="product-caption">

                              @if(isset($value->product_discount) && $value->product_discount!='')
                                  <div class="price-box">
                                  @if($value->old_price!='0')
                                      <span class="price-old">
                                          <del> 
                                          <i class="fa fa-rupee"></i> {{ \App\Helpers\IndianCurrencyHelper::IND_money_format($value->old_price) }}
                                          </del>
                                        </span>
                                    @endif  
                                      <span class="price-regular"> 
                                      <i class="fa fa-rupee"></i> {{ isset($value->new_price) && $value->new_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($value->new_price) : '' }}</span>
                                  </div>
                                  @else
                                  <div class="price-box">
                                    <span class="price-regular"> 
                                    <i class="fa fa-rupee"></i> {{ \App\Helpers\IndianCurrencyHelper::IND_money_format($value->old_price) }}
                                    </span>
                                  </div>
                              @endif

                                        

                                @if(isset($value->old_price) && $value->old_price!=null && $value->product_discount!='')
                                  <div class="you-save">
                                    Save <span>
                                    <i class="fa fa-rupee"></i> {{ isset($value->old_price) && $value->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($value->old_price - $value->new_price) : '' }}
                                    </span>
                                  </div>
                                @endif
                              </div>
                          </a>
                    </div>
                  
                  <!-- <div class="add-to-cart">
                    <button class="btn btn-primary btn-xs" id="add_to_cart" data-product_id = "{{ $value->id }}" name="submit_button" type="button">@lang('Add to Cart')</button>
                  </div> -->
                  {!! Form::close() !!}
                @endforeach
              @endif
               
            </div>
            
              <script>
                $(document).ready(function() {
                  var owl = $('.owl-carousel');
                  owl.owlCarousel({
                    items: 4,
                    loop: true,
                    margin: 25,
                    autoplay: true,
                    autoplayTimeout: 4000,
                    autoplayHoverPause: true,
                    nav:true,
                    navText : ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
                    responsive:{
                        0:{
                            items:1,
                            nav:true
                        },
                        600:{
                            items:2
                            
                        },
                        1000:{
                            items:4
                            
                        }
                      }
                  });
                   
                });
              </script>
        </div>
      </div>
  </div>
</div>


     

  @include('includes.cart-submit-script')
 