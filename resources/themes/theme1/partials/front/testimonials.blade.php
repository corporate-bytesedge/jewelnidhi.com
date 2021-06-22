<div class="section testimonial-sec"  data-aos="fade-up" data-aos-once="true">
    <div class="container">
      <div class="heading-sec">
        <h2>Testimonials</h2>
        <img src="{{ URL::asset('img/home_line1.png') }} " alt=""/>
        <span>@lang('What our customers say')</span>
      </div>
      
      @if(count($testimonials) > 0)
      @php 
        $testimonialArray = array();
        
      @endphp
        @foreach($testimonials as $key=>$testimonial)
            @php
              if(isset($testimonial->photo->name) && $testimonial->photo->name!='') {
                $testimonialArray[$testimonial->id]['image'] = $testimonial->photo->name;
              } else {
                $testimonialArray[$testimonial->id]['image'] = '';
              }
              $testimonialArray[$testimonial->id]['review'] = $testimonial->review;
              $testimonialArray[$testimonial->id]['author'] = $testimonial->author;
            @endphp
        @endforeach

        
      <div class="row">
        <div class="col-12">
         
            <div class="testimonial-thumb-wrapper">
                <div class="testimonial-thumb-carousel">
              
                @foreach($testimonialArray as $key=>$testimoniall)
                 
                    <div class="testimonial-thumb">
                      @if($testimoniall['image'])
                        @php
                          $image_url = \App\Helpers\Helper::check_image_avatar($testimoniall['image'], 150, $default_photo);
                        @endphp
                          <img src="{{$image_url}}" alt=""/>
                      @else
                          <img src="https://via.placeholder.com/150x150?text=No+Image" alt="" />
                      @endif
                    </div>

                @endforeach
                  
                </div>
            </div>
            
            <div class="testimonial-content-wrapper">
                <div class="testimonial-content-carousel">
                
                  @foreach($testimonialArray as $testimonialsss)
                  
                    
                    <div class="testimonial-content">
                    <p> {{$testimonialsss['review']}}</p>
                    <h5 class="testimonial-author">{{$testimonialsss['author']}}</h5>
                    </div>
                    
                  @endforeach  
                  
                    
                </div>
            </div>
         
        </div>
      </div>
    @endif
    </div>
  </div>