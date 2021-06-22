 <!-- hero slider area start -->
 
 @if(!empty($banners_main_slider) && config('settings.main_slider_enable') == 1 )
 <section class="slider-area">
        <div class="hero-slider-active">
          <!-- single slider item start -->
          @foreach($banners_main_slider as $banner)
          @if($banner->photo)
                @php
                    $image_url = \App\Helpers\Helper::check_image_avatar($banner->photo->name, 1500, '', 500);
                @endphp
            @else
                @php
                    $image_url = "https://via.placeholder.com/1500x1500?text=No+Image";
                @endphp
            @endif
           
          <div class="hero-single-slide hero-overlay">
              <div class="hero-slider-item bg-img">
              <a href="{{$banner->link ? $banner->link : url()->current()}}" class=""><img src="{{ $image_url }}" alt="{{ $banner->title}}" /> </a>
                  <div class="container">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="hero-slider-content slide-1">
                                  <h2 class="slide-title">{{$banner->title ? $banner->title : __('')}}</span></h2>
                                  <!-- <h4 class="slide-desc">Hot Collection 2020</h4> -->
                                  <p>{{$banner->description ? $banner->description : __('')}} </p>
                                 
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
           
          @endforeach
        </div>
  </section>
  @endif
  <!-- hero slider area end -->