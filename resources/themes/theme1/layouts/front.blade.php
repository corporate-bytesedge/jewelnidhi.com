<!DOCTYPE html>
<html lang="{{Config::get('app.locale')}}">
<head>
    @if(config('analytics.google_analytics_script') != "")
        @include('partials.front.google-analytics')
    @endif
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/> -->
   <meta name = "viewport" content = "width=device-width, minimum-scale=1.0, maximum-scale = 1.0, user-scalable = no">
    <meta name="csrf-token" content="{{csrf_token()}}">
    
    @yield('meta-tags')

    <title>@yield('title')</title>
     
    <!-- <title>{{ config('app.name') }}</title> -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico')}}" type="image/x-icon">
    @yield('meta-tags-og')

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
     
     
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/nice-select.css') }} " />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.css') }} ">
    <link href="{{asset('css/developer.css')}}" rel="stylesheet">
    @yield('styleg')
    <script src=" {{ asset('js/jquery.min.js')}} "></script>
    <script src="{{ asset('js/jquery.elevateZoom-3.0.8.min.js')}}"></script>
    <style>
    .item a {
            color:#fff !important;
        }
  
     
    </style>
    <link rel="stylesheet" href="{{ asset('css/aos.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/responsive-tables.css') }}" />

    </head>
<body>
<!-- <div id="cover-spin"></div> -->
        @if(\Route::currentRouteName() != 'front.orders.show' )
            @include('includes.theme-header')
        @endif
    <!-- <div class="main-wrapper wrapper-cont"> -->
        <!-- @yield('sidebar') -->

        @yield('above_container')

        @yield('content')
    <!-- </div> -->
    @if(\Route::currentRouteName() != 'front.orders.show' )
      @include('partials.front.footer')
    @endif
    <!-- @yield('footer')

<script src="{{asset('js/libs.js')}}"></script>
<script src="{{asset('js/front.js')}}"></script>

@include('includes.front.footer-toaster')

<script src="{{asset('js/jquery-ui.min.js')}}"></script>

@include('includes.front.live-chat-widget')

<script type="text/javascript" href="{{asset(theme_url('/js/animations.min.js'))}}"> </script>
<script src="{{asset(theme_url('/js/script.js'))}}?v=1.8"></script>
-->
<button   id="myBtn" data-scroll="up" type="button">
<i class="fa fa-chevron-up"></i>
</button>

@yield('scripts') 
<script type="text/javascript">
var mybutton = document.getElementById("myBtn");
window.onscroll = function() {scrollFunction()};
      
      function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            
          mybutton.style.display = "block";
        } else {
          mybutton.style.display = "none";
        }
      }
      
      
$("#myBtn").click(function() {
    
  $("html, body").animate({ scrollTop: 0 }, "slow");
  return false;
});
</script>
</body>
</html>