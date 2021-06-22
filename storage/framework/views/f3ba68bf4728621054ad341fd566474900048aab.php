

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('Manage Categories'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('Manage Categories'); ?> <a class="btn btn-sm btn-info" href="<?php echo e(route('manage.categories.create')); ?>"><?php echo app('translator')->getFromJson('Add Category'); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('View and Manage Categories'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <!-- DATA TABLE STYLE -->
    <link href="<?php echo e(asset('css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dataTables-responsive/fixedHeader.bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dataTables-responsive/responsive.bootstrap.min.css')); ?>" rel="stylesheet">
    <?php if(config('settings.export_table_enable')): ?>
    <link href="<?php echo e(asset('css/dataTables-export/buttons.dataTables.min.css')); ?>" rel="stylesheet">
    <?php endif; ?>
    <?php echo $__env->make('partials.manage.categories-tree-style', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <style>
        .bolden {
            font-family: "Arial Black";
        }
    </style>
    <link href="<?php echo e(asset('css/jquery.dropdown.min.css')); ?>" rel="stylesheet">
    <?php echo $__env->make('includes.datatable_style', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('js/jquery.dropdown.min.js')); ?>"></script>
    <script>
        $('.category_box').dropdown({
                // options here
        });
        $('.specification_type_box').dropdown({
                // options here
        });
    </script>
    <!-- DATA TABLE SCRIPTS -->
    <script src="<?php echo e(asset('js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/dataTables.bootstrap.min.js')); ?>"></script>
    <?php echo $__env->make('partials.manage.dataTables-responsive', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
        function searchByColumn(table) {
            var condition = <?php echo e(Auth::user()->can('delete', App\Category::class) ? 1 : 0); ?>;
            var defaultSearch = (condition ? 2 : 1);
            $('#select-column').on('change', function() {
                defaultSearch = this.value;
                if(defaultSearch == (condition ? 4 : 3)) {
                    $("#search-by-column")
                        .replaceWith('<select class="form-control" id="search-by-column">' +
                            '<option value=""><?php echo app('translator')->getFromJson('Choose Option'); ?></option>' +
                            '<option value="active"><?php echo app('translator')->getFromJson('active'); ?></option>' +
                            '<option value="inactive"><?php echo app('translator')->getFromJson('inactive'); ?></option>' +
                            '</select>');
                } else {
                    $("#search-by-column")
                        .replaceWith('<input class="form-control" type="text" id="search-by-column" placeholder="<?php echo app('translator')->getFromJson('Search by Column'); ?>">');
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
    <?php if(config('settings.export_table_enable')): ?>
    <?php echo $__env->make('partials.manage.dataTables-export', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
        $(document).ready(function () {
            <?php if(Auth::user()->can('delete', App\Category::class)): ?>
                var arrayColumns = [1,2,3,4];
            <?php else: ?>
                var arrayColumns = [0,1,2,3];
            <?php endif; ?>
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
    <?php else: ?>
    <script>
        $(document).ready(function () {
            var table = $('#categories-table').DataTable({
                responsive: true,
            });
            new $.fn.dataTable.FixedHeader( table );
            searchByColumn( table );
        });
    </script>
    <?php endif; ?>
    <?php echo $__env->make('partials.manage.categories-tree-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>;
    <?php echo $__env->make('includes.form_delete_all_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>;

    <?php if(config('settings.toast_notifications_enable')): ?>
    <script>
        toastr.options.closeButton = true;
        <?php if(session()->has('category_created')): ?>
            toastr.success("<?php echo e(session('category_created')); ?>");
        <?php endif; ?>

        <?php if(session()->has('category_deleted')): ?>
            toastr.success("<?php echo e(session('category_deleted')); ?>");
        <?php endif; ?>

        <?php if(session()->has('category_not_deleted')): ?>
            toastr.error("<?php echo e(session('category_not_deleted')); ?>");
        <?php endif; ?>
    </script>
    <?php endif; ?>
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
<?php $__env->stopSection(); ?>

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
                    <?php 
                        

                    $product =  \App\Product::whereHas('product_category_styles', function ($query) use($category)  {
                                    $query->where('category_id', $category->id);
                                })->where(function ($query) {
                                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                                })->where('is_active','1')->where('category_id', $category->id)->count();
                    ?>
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
<?php $__env->startSection('content'); ?> <?php echo $__env->make('partials.manage.categories.index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>