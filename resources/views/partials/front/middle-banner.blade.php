@if(!empty($middleBanner))
 
        <a href="{{ $middleBanner->link_one }}">
            <div class="img-banner-sec"  data-aos="fade-up" data-aos-once="true">
                    @if(isset($middleBanner->image_one))
                            @php 
                                $imagetwopath = public_path().'/storage/middle-banner/'.$middleBanner->image_one;
                            @endphp
                            @if(file_exists($imagetwopath))
                                <img src="{{ asset('storage/middle-banner/'.$middleBanner->image_one) }}"   >
                            @else
                                <img src="{{ asset('img/noimage.png') }} " >
                            @endif
                        @endif
            </div>
        </a>    
        @endif