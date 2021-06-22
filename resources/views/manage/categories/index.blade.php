@extends('layouts.manage')

@section('title')
    @lang('Manage Categories')
@endsection

@section('page-header-title')
    @lang('Manage Categories') <a class="btn btn-sm btn-info" href="{{route('manage.categories.create')}}">@lang('Add Category')</a>
@endsection

@section('page-header-description')
    @lang('View and Manage Categories')
@endsection

@section('styles')
    <!-- DATA TABLE STYLE -->
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
    <!-- DATA TABLE SCRIPTS -->
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
    @include('partials.manage.dataTables-responsive')
    <script>
        function searchByColumn(table) {
            var condition = {{Auth::user()->can('delete', App\Category::class) ? 1 : 0}};
            var defaultSearch = (condition ? 2 : 1);
            $('#select-column').on('change', function() {
                defaultSearch = this.value;
                if(defaultSearch == (condition ? 4 : 3)) {
                    $("#search-by-column")
                        .replaceWith('<select class="form-control" id="search-by-column">' +
                            '<option value="">@lang('Choose Option')</option>' +
                            '<option value="active">@lang('active')</option>' +
                            '<option value="inactive">@lang('inactive')</option>' +
                            '</select>');
                } else {
                    $("#search-by-column")
                        .replaceWith('<input class="form-control" type="text" id="search-by-column" placeholder="@lang('Search by Column')">');
                }
            });
            $(document).on('change','#search-by-column', function() {
                table.search( '' ).columns().search( '' ).draw();
                if(defaultSearch == (condition ? 4 : 3)) {
                    table.column(defaultSearch).search( '^' + this.value, true, false).draw();
                } else {
                    table.column(defaultSearch).search(this.value).draw();
                }
            });
        }
    </script>
    @if(config('settings.export_table_enable'))
    @include('partials.manage.dataTables-export')
    <script>
        $(document).ready(function () {
            @if(Auth::user()->can('delete', App\Category::class))
                var arrayColumns = [1,2,3,4];
            @else
                var arrayColumns = [0,1,2,3];
            @endif
            var table = $('#categories-table').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
               {
                   extend: 'pdf',
                   exportOptions: {
                        columns: arrayColumns
                    }
               },
               {
                   extend: 'csv',
                   exportOptions: {
                        columns: arrayColumns
                    }
               },
               {
                   extend: 'excel',
                   exportOptions: {
                        columns: arrayColumns
                    }
               },
               {
                   extend: 'print',
                   exportOptions: {
                        columns: arrayColumns
                    }
               }]
            });
            new $.fn.dataTable.FixedHeader( table );
            searchByColumn( table );
        });
    </script>
    @else
    <script>
        $(document).ready(function () {
            var table = $('#categories-table').DataTable({
                responsive: true,
            });
            new $.fn.dataTable.FixedHeader( table );
            searchByColumn( table );
        });
    </script>
    @endif
    @include('partials.manage.categories-tree-script');
    @include('includes.form_delete_all_script');

    @if(config('settings.toast_notifications_enable'))
    <script>
        toastr.options.closeButton = true;
        @if(session()->has('category_created'))
            toastr.success("{{session('category_created')}}");
        @endif

        @if(session()->has('category_deleted'))
            toastr.success("{{session('category_deleted')}}");
        @endif

        @if(session()->has('category_not_deleted'))
            toastr.error("{{session('category_not_deleted')}}");
        @endif
    </script>
    @endif
    <script>
        var advancedSearch = $('.advanced-search');
        advancedSearch.hide();
        $(document).on('click', '.advanced-search-toggle', function() {
            advancedSearch.fadeToggle();
        });
    </script>

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
                    if(imgwidth != '1024' && imgheight != '1024') {
                        $("#CatimgError").html('<span class="label label-danger">image diamonsion did not match</span>');
                        $("#submit_button").attr('disabled',true);
                    }
                };
            
            });
            

             
        });
    </script>
@endsection

<?php /* @section('content')

    <div class="row">

        @can('create', App\Category::class)
            @include('partials.manage.categories.create')
        @endcan

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

    </div>

    <hr>
    <div class="row">
        <div class="col-md-12">
            @if(session()->has('category_deleted'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{session('category_deleted')}}</strong>
                </div>
            @endif

            @if(session()->has('category_not_deleted'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{session('category_not_deleted')}}</strong>
                </div>
            @endif

            @include('partials.manage.categories.index')
        </div>
    </div>
@endsection
*/
?>
@section('content')
    @include('partials.manage.categories.index')
@endsection
