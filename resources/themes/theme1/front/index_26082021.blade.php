@extends('layouts.front')
 
@section('title')
 

{{ config('app.name') }}
@endsection

@section('meta-tags')
    <meta name="description" content="{{config('custom.meta_description')}}">
    @if(config('custom.meta_keywords'))
        <meta name="keywords" content="{{config('custom.meta_keywords')}}">
    @endif
@endsection

@section('meta-tags-og')
    <meta property="og:url" content="{{url()->current()}}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{config('custom.meta_title') ? config('custom.meta_title') : config('app.name')}}" />
    <meta property="og:description" content="{{config('custom.meta_description')}}" />
    <meta property="og:image" content="{{url('/img/'.config('settings.site_logo'))}}" />
@endsection

@section('styles')

    <style>
        body {
            background-color: #f1f3f6;
        }
        .side-banner {
            margin-top: 60px;
        }
        
    </style>


@endsection

@section('above_container')

    <!-- @include('includes.front.vendor-profile-completion-alert') -->

    <!-- @include('includes.front.phone-verification-alert') -->
    
    <!-- @include('partials.front.sections.above-main-slider') -->
    
    <div class="main-banner-box {{!config('customcss.front_header_full_width') ? 'wrapper' : ''}}">
        @include('partials.front.full-width-slider')
    </div>
    @include('partials.front.sections.below-main-slider')

    <!-- <div class="cart-message">
        @include('partials.front.cart-message')
    </div> -->

@endsection

@section('content')
    <div class="{{!config('settings.full_width_layout') ? 'wrapper fdfd' : ''}}">
        @if(!empty($banners_below_main_slider))
            @include('partials.front.banners-below-main-slider', ['banners' => $banners_below_main_slider])
        @endif

        @if(!empty($banners_below_main_slider_3_images_layout))
            @include('partials.front.banners-below-main-slider-3-img-layout', ['secondary_banners' => $banners_below_main_slider_3_images_layout->slice(1)->take(2), 'main_banner'=>$banners_below_main_slider_3_images_layout->first()])
        @endif

        @if(!empty($banners_below_main_slider_2_images_layout))
            @include('partials.front.banners-below-main-slider-2-img-layout', ['banners' => $banners_below_main_slider_2_images_layout])
        @endif

        @if(config('settings.banners_right_side_enable'))
            <div class="col-md-9">
        @else
            <div class="{{!config('settings.full_width_layout') ? 'col-md-12' : ''}}">
        @endif

        @if(config('settings.categories_slider_enable') == 1)
            @include('partials.front.sections.above-deal-slider')
        @endif
            <!-- @foreach($deals as $key => $deal)
                @include('partials.front.deal-slider', ['titleSlider'=>$deal->name, 'products'=>$deal->products->where('is_active', 1), 'index'=>$key, 'dealSlug'=>$deal->slug])
            @endforeach -->

            <!-- @if(config('settings.products_slider_enable'))
                @include('partials.front.products-slider', ['titleSlider'=>__('Products')])
            @endif -->

            <!-- @if(config('settings.categories_slider_enable'))
                @include('partials.front.categories-slider', ['titleSlider'=>__('Shop By Categories')])
            @endif -->

            <!-- @if(config('settings.brands_slider_enable'))
                @include('partials.front.brands-slider', ['titleSlider'=>__('Shop By Brands')])
            @endif -->

            <!-- @include('partials.front.sections.below-deal-slider') -->
        </div>

        <!-- @if(config('settings.banners_right_side_enable'))
            <div class="col-md-3">
                @include('partials.front.sections.above-side-banners')
                @include('partials.front.side-banners', ['banners' => $banners_right_side])
                @include('partials.front.sections.below-side-banners')
            </div>
        @endif -->
        @if(!empty($middleBanner))
            @include('partials.front.middle-banner', ['middleBanner' => $middleBanner])
        @endif

        @if(!empty($middleBanner))
            @include('partials.front.left-right-bottom-banner', ['middleBanner' => $middleBanner])
        @endif
        
        @include('partials.front.instagram-stories')
        
        @if(!empty($testimonials))
           @include('partials.front.testimonials')
        @endif

        @include('partials.front.certifications-section')

        <!-- @include('partials.front.sections.above-footer') -->
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/nice-select.min.js')}}"></script>
<script src="{{ asset('js/xzoom.js')}} "></script>
<script src="{{ asset('js/image-zoom.min.js')}}"></script>
<script src="{{ asset('js/script.js')}} "></script>
<script src="{{ asset('js/owl.carousel.js')}} "></script>
@endsection