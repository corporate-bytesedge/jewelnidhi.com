@extends('layouts.manage')

@section('title')
    @lang('Add Category')
@endsection

@section('page-header-title')
    @lang('Add Category') 
@endsection

@section('page-header-description')
    @lang('Add Category') <a href="{{route('manage.categories.index')}}">@lang('Go Back')</a>
@endsection

@section('styles')

<link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
<link href="{{asset('css/dataTables-responsive/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
<link href="{{asset('css/dataTables-responsive/responsive.bootstrap.min.css')}}" rel="stylesheet">
@if(config('settings.export_table_enable'))
<link href="{{asset('css/dataTables-export/buttons.dataTables.min.css')}}" rel="stylesheet">
@endif
@include('partials.manage.categories-tree-style')
<style>
    .bolden {
        font-family: "Arial Black";
    }
</style>
<link href="{{asset('css/jquery.dropdown.min.css')}}" rel="stylesheet">
@include('includes.datatable_style')


     
         
@endsection

@section('scripts')
    <script src="{{asset('js/jquery.dropdown.min.js')}}"></script>
    <script>
        $('.category_box').dropdown({
                // options here
        });
        $('.specification_type_box').dropdown({
                // options here
        });
    </script>
    @if(config('settings.toast_notifications_enable'))
    <script>
        toastr.options.closeButton = true;
        @if(session()->has('category_updated'))
            toastr.success("{{session('category_updated')}}");
        @endif
    </script>
    @endif
    <script>
        jQuery(document).ready(function() {

             
            var _URL = window.URL || window.webkitURL;
            var _CATURL = window.URL || window.webkitURL;
            $('.bannerImage').change(function () {

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
                    if(imgwidth != '1600' && imgheight != '350') {
                        $("#imgError").html('<span class="label label-danger">image diamonsion did not match</span>');
                        $("#submit_button").attr('disabled',true);
                    }
                };

            });

            $('.categoryImage').change(function () {
                
                $("#submit_button").attr('disabled',false);
                $("#CatimgError").html('');
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
                        $("#CatimgError").html('<span class="label label-danger">image diamonsion did not match</span>');
                        $("#submit_button").attr('disabled',true);
                    }
                };

            });


 
        });
    </script>

@include('partials.manage.categories-tree-script');
@endsection

@section('content')
    @include('partials.manage.categories.create')
    @if(count($root_categories) > 0)
            <div class="col-md-6">
                <h4 class="text-info">@lang('Categories Tree:')</h4>
                <ul id="tree1">
                    @foreach($root_categories as $category)
                    @php 
                        

                    $product =  \App\Product::whereHas('product_category_styles', function ($query) use($category)  {
                                    $query->where('category_id', $category->id);
                                })->where(function ($query) {
                                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                                })->where('is_active','1')->where('category_id', $category->id)->count();
                    @endphp
                        <li>
                            

                            {{ $category->name . ' ('.$product.')' }}
                            @if(count($category->categories))
                                @include('partials.manage.subcategories', ['childs' => $category->categories])
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
@endsection