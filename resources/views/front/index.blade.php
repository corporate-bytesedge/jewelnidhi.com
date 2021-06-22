@extends('layouts.front')

@section('come')
    <?php
        die('Starting dying HERE');
    ?>
@endsection('come')

@section('title'){{config('custom.meta_title') ? config('custom.meta_title') : config('app.name')}}@endsection
@section('meta-tags')<meta name="description" content="{{config('custom.meta_description')}}">
@if(config('custom.meta_keywords'))
    <meta name="keywords" content="{{config('custom.meta_keywords')}}">
@endif
@endsection
@section('meta-tags-og')<meta property="og:url" content="{{url()->current()}}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{config('custom.meta_title') ? config('custom.meta_title') : config('app.name')}}" />
    <meta property="og:description" content="{{config('custom.meta_description')}}" />
    <meta property="og:image" content="{{url('/img/'.config('settings.site_logo'))}}" />
@endsection

@section('scripts')
    <script>
        toastr.options.closeButton = true;
        @if(session()->has('payment_success'))
            toastr.success("{{session('payment_success')}}");
        @endif
        @if(session()->has('payment_fail'))
            toastr.error("{{session('payment_fail')}}");
        @endif
        @if(session()->has('success'))
            toastr.success("{{ session('success') }}");
        @endif
        @if(session()->has('email_activation'))
            toastr.success("{{ session('email_activation') }}");
        @endif
    </script>

    @foreach($deals as $key => $deal)
        @include('partials.front.deal-slider-script', ['index'=>$key, 'slides_preview'=>config('settings.banners_right_side_enable') ? 4 : 6])
    @endforeach

    @include('partials.front.full-width-slider-script')
    @include('partials.front.product-slider-script', ['slides_preview'=>config('settings.banners_right_side_enable') ? 4 : 6])
    @include('partials.front.category-slider-script', ['slides_preview'=>config('settings.banners_right_side_enable') ? 4 : 5])
    @include('partials.front.brand-slider-script', ['slides_preview'=>config('settings.banners_right_side_enable') ? 5 : 6])

    @include('includes.cart-submit-script')
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
    @include('partials.front.vendor-profile-completion')

    @if(session()->has('success'))
        <div class="text-center alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span>
            &nbsp;{{ session('success') }}
        </div>
    @endif
    @if(session()->has('email_activation'))
        <div class="text-center alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{session('email_activation')}}
        </div>
    @endif
    @if(session()->has('payment_success'))
        <div class="text-center alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="glyphicon glyphicon glyphicon-ok" aria-hidden="true"></span>
            <strong>&nbsp;@lang('Success!')</strong> {{session('payment_success')}}
        </div>
    @endif
    @if(session()->has('payment_fail'))
        <div class="text-center alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="glyphicon glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <strong>&nbsp;@lang('Error:')</strong> {{session('payment_fail')}}
        </div>
    @endif
    @if(Auth::check() && config('settings.phone_otp_verification'))
        @if(!auth()->user()->mobile)
        <a href="{{route('front.settings.profile')}}">
            <div class="text-center alert alert-info" role="alert">
                <span class="glyphicon glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                @lang('Please add and verify your phone number')
            </div>
        </a>
        @elseif(!auth()->user()->mobile->verified)
        <a href="{{route('front.settings.profile')}}">
            <div class="text-center alert alert-info" role="alert">
                <span class="glyphicon glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                @lang('Please verify your phone number')
            </div>
        </a>
        @endif
    @endif

    @include('partials.front.sections.above-main-slider')

    
@endsection

@section('content')
<div class="{{!config('settings.full_width_layout') ? 'wrapper' : ''}}">
    @if(count($banners_below_main_slider) > 0)
        @include('partials.front.banners-below-main-slider', ['banners' => $banners_below_main_slider])
    @endif
    @if(count($banners_below_main_slider_3_images_layout) > 0)
        @include('partials.front.banners-below-main-slider-3-img-layout', ['secondary_banners' => $banners_below_main_slider_3_images_layout->slice(1)->take(2), 'main_banner'=>$banners_below_main_slider_3_images_layout->first()])
    @endif
    @if(count($banners_below_main_slider_2_images_layout) > 0)
        @include('partials.front.banners-below-main-slider-2-img-layout', ['banners' => $banners_below_main_slider_2_images_layout])
    @endif
    @if(config('settings.banners_right_side_enable'))
    <div class="col-md-9">
    @else
    <div class="{{!config('settings.full_width_layout') ? 'col-md-12' : ''}}">
    @endif
        @include('partials.front.sections.above-deal-slider')

        @foreach($deals as $key => $deal)
            @include('partials.front.deal-slider', ['titleSlider'=>$deal->name, 'products'=>$deal->products->where('is_active', 1), 'index'=>$key, 'dealSlug'=>$deal->slug])
        @endforeach

        @if(config('settings.products_slider_enable'))
            @include('partials.front.products-slider', ['titleSlider'=>__('Products')])
        @endif

        @if(config('settings.categories_slider_enable'))
            @include('partials.front.categories-slider', ['titleSlider'=>__('Shop By Categories')])
        @endif

        @if(config('settings.brands_slider_enable'))
            @include('partials.front.brands-slider', ['titleSlider'=>__('Shop By Brands')])
        @endif

        @include('partials.front.sections.below-deal-slider')
    </div>

    @if(config('settings.banners_right_side_enable'))
    <div class="col-md-3">
        @include('partials.front.sections.above-side-banners')
        @include('partials.front.side-banners', ['banners' => $banners_right_side])
        @include('partials.front.sections.below-side-banners')
    </div>
    @endif

    <div class="clearfix"></div>

    @if(count($testimonials) > 0)
        @include('partials.front.testimonials')
    @endif

    @include('partials.front.sections.above-footer')
</div>
@endsection
