 
<div class="two-banner-sec" data-aos="fade-up" data-aos-once="true">
            <div class="container">
            <div class="row">
            @if(!empty($middleBanner))
            <a href="{{ $middleBanner->link_two }}">
                <div class="col-md-6">
                    <div class="img-block">
                     
                    @if(isset($middleBanner->image_two))
                        @php
                            $leftbanner = public_path().'/storage/left-banner/'.$middleBanner->image_two;
                        @endphp

                        @if(file_exists($leftbanner))
                            <img src="{{ asset('storage/left-banner/'.$middleBanner->image_two) }}" >
                        @else
                            <img src="{{ asset('img/noimage.png') }} " >
                        @endif

                        <div class="content">
                            <h3>{{ $middleBanner->title_two }}</h3>
                            <a href="{{ $middleBanner->link_two ? $middleBanner->link_two : url()->current()}}">Shop Now</a>
                        </div>
                    @endif
                    </div>
                </div>
            </a>
            @endif

            @if(!empty($middleBanner))
                <a href="{{ $middleBanner->link_three }}">
                    <div class="col-md-6">
                        <div class="img-block">

                        @if(isset($middleBanner->image_three))

                            @php
                                $rightbanner = public_path().'/storage/right-banner/'.$middleBanner->image_three;
                            @endphp

                            @if(file_exists($rightbanner))
                                <img src="{{ asset('storage/right-banner/'.$middleBanner->image_three) }}" >
                            @else
                                <img src="{{ asset('img/noimage.png') }} " >
                            @endif
                             
                            
                            <div class="content">
                                <h3>{{ $middleBanner->title_three }}</h3>
                                <a href="{{ $middleBanner->link_three ? $middleBanner->link_three : url()->current()}}">Shop Now</a>
                            </div>
                        @endif

                             
                        </div>
                    </div>
                </a>
                @endif
                
                </div>
            
            </div>
        </div>