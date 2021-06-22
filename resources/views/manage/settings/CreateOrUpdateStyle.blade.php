@extends('layouts.manage')
@if(isset($style))
        @section('title')
            @lang('Edit Style')
        @endsection
@else
    @section('title')
        @lang('Add Style')
    @endsection
@endif
@if(isset($style))
    @section('page-header-title')
        @lang('Edit Style') 
    @endsection
@else

    @section('page-header-title')
        @lang('Add Style') 
    @endsection

@endif

@section('page-header-description')
    @lang('Add New Style') <a href="{{url()->previous()}}">@lang('Go Back')</a>
@endsection
@section('scripts')
<script>
        jQuery(document).ready(function() {

              
            var _URL = window.URL || window.webkitURL;
            var _CATURL = window.URL || window.webkitURL;

            $('.styleImage').change(function () {
                
                $("#submit_button").attr('disabled',false);
                $("#styleimgError").html('');
                var file = $(this)[0].files[0];
                img = new Image();
                var imgwidth = 0;
                var imgheight = 0;
                var maxwidth = 640;
                var maxheight = 640;
                img.src = _CATURL.createObjectURL(file);
                img.onload = function() {
                    imgwidth = this.width;
                    imgheight = this.height;
                    if(imgwidth != '370' && imgheight != '300') {
                        $("#styleimgError").html('<span class="label label-danger">image diamonsion did not match</span>');
                        $("#submit_button").attr('disabled',true);
                    }
                };

            });

            $('.bannerImage').change(function () {
                console.log('here');
                $("#submit_button").attr('disabled',false);
                $("#imgError").html('');
                var file = $(this)[0].files[0];
                img = new Image();
                var imgwidth = 0;
                var imgheight = 0;
                var maxwidth = 640;
                var maxheight = 640;
                img.src = _URL.createObjectURL(file);
                img.onload = function() {
                    imgwidth = this.width;
                    imgheight = this.height;
                    if(imgwidth != '1600' && imgheight != '400') {
                        $("#imgError").html('<span class="label label-danger">image diamonsion did not match</span>');
                        $("#submit_button").attr('disabled',true);
                    }
                };
            
            });

         
            

             
        });
    </script>
@endsection

@section('content')
    @include('partials.manage.settings.CreateOrUpdateStyle')
@endsection