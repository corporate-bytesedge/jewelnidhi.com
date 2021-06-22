

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('Products Approval'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('Products Approval'); ?>
     
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('View and Manage Products Approval'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <!-- DATA TABLE STYLE -->
     
    <link href="<?php echo e(asset('css/dataTables.bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dataTables-responsive/fixedHeader.bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/dataTables-responsive/responsive.bootstrap.min.css')); ?>" rel="stylesheet">
    <?php if(config('settings.export_table_enable')): ?>
    <link href="<?php echo e(asset('css/dataTables-export/buttons.dataTables.min.css')); ?>" rel="stylesheet">
    <?php endif; ?>
    <?php echo $__env->make('includes.datatable_style', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <style>
        .modal-backdrop.in {
            opacity:0 !important;
        }
        .modal a.close-modal {
            position: absolute;
            top: -1.5px !important;
            right: -4.9px !important;
            display: block;
            width: 30px;
            height: 30px;
            text-indent: -9999px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center center;
            background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAYAAAA6/NlyAAAAAXNSR0IArs4c6QAAA3hJREFUaAXlm8+K00Acx7MiCIJH/yw+gA9g25O49SL4AO3Bp1jw5NvktC+wF88qevK4BU97EmzxUBCEolK/n5gp3W6TTJPfpNPNF37MNsl85/vN/DaTmU6PknC4K+pniqeKJ3k8UnkvDxXJzzy+q/yaxxeVHxW/FNHjgRSeKt4rFoplzaAuHHDBGR2eS9G54reirsmienDCTRt7xwsp+KAoEmt9nLaGitZxrBbPFNaGfPloGw2t4JVamSt8xYW6Dg1oCYo3Yv+rCGViV160oMkcd8SYKnYV1Nb1aEOjCe6L5ZOiLfF120EjWhuBu3YIZt1NQmujnk5F4MgOpURzLfAwOBSTmzp3fpDxuI/pabxpqOoz2r2HLAb0GMbZKlNV5/Hg9XJypguryA7lPF5KMdTZQzHjqxNPhWhzIuAruOl1eNqKEx1tSh5rfbxdw7mOxCq4qS68ZTjKS1YVvilu559vWvFHhh4rZrdyZ69Vmpgdj8fJbDZLJpNJ0uv1cnr/gjrUhQMuI+ANjyuwftQ0bbL6Erp0mM/ny8Fg4M3LtdRxgMtKl3jwmIHVxYXChFy94/Rmpa/pTbNUhstKV+4Rr8lLQ9KlUvJKLyG8yvQ2s9SBy1Jb7jV5a0yapfF6apaZLjLLcWtd4sNrmJUMHyM+1xibTjH82Zh01TNlhsrOhdKTe00uAzZQmN6+KW+sDa/JD2PSVQ873m29yf+1Q9VDzfEYlHi1G5LKBBWZbtEsHbFwb1oYDwr1ZiF/2bnCSg1OBE/pfr9/bWx26UxJL3ONPISOLKUvQza0LZUxSKyjpdTGa/vDEr25rddbMM0Q3O6Lx3rqFvU+x6UrRKQY7tyrZecmD9FODy8uLizTmilwNj0kraNcAJhOp5aGVwsAGD5VmJBrWWbJSgWT9zrzWepQF47RaGSiKfeGx6Szi3gzmX/HHbihwBser4B9UJYpFBNX4R6vTn3VQnez0SymnrHQMsRYGTr1dSk34ljRqS/EMd2pLQ8YBp3a1PLfcqCpo8gtHkZFHKkTX6fs3MY0blKnth66rKCnU0VRGu37ONrQaA4eZDFtWAu2fXj9zjFkxTBOo8F7t926gTp/83Kyzzcy2kZD6xiqxTYnHLRFm3vHiRSwNSjkz3hoIzo8lCKWUlg/YtGs7tObunDAZfpDLbfEI15zsEIY3U/x/gHHc/G1zltnAgAAAABJRU5ErkJggg==);
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <!-- DATA TABLE SCRIPTS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    
    <script src="<?php echo e(asset('js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/dataTables.bootstrap.min.js')); ?>"></script>
    <?php echo $__env->make('partials.manage.dataTables-responsive', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
        $(document).ready(function () {
            
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#search-by-column").on("keypress",function(e) {
                if(e.which==13) {
                    var vendorId = $('#Vendors').val();
                    var jnWEBId = $('#search-by-column').val();
                    productstables.draw();
                    e.preventDefault();
                }
            });
            
            $(".Search").on("click",function(e) {
               
                
                var vendorId = $('#Vendors').val();
               
                var jnWEBId = $('#search-by-column').val();
                productstables.draw();

            });

            
           
            
            
            var productstables = $('#products-table').DataTable({
                searchable: true,
                processing: true,
                serverSide: true,
                searching: false,
                
                'columnDefs': [ {
                    'targets': [0,7], // column index (start from 0)
                    'orderable': false, // set orderable false for selected columns
                   
                }],
                language: {
                    'processing': '<div class="spinner"></div>'
                },
                "order": [[ 1, "desc" ]],
                ajax: {
                url: "<?php echo e(route('manage.products.vendor_product')); ?>",
                data: function (d) {
                        
                    d.search = $('#Vendors').val(),
                    d.search_column = $('#search-by-column').val()         
                    }
                },
                columns: [
                    
                    
                    {data: 'Ids', name: 'id'},
                    {data: 'vendor', name: 'vendor'},
                    {data: 'category', name: 'category'},
                    {data: 'style', name: 'style'},
                    {data: 'photo', name: 'photo'},
                    {data: 'name', name: 'name'},
                    {data: 'price', name: 'price'},
                    {data: 'status', name: 'status'},
                    
                        {data: 'approved', name: 'approved'},
                    
                    {data: 'Action', name: 'Action',
                        
                            mRender: function (data, type, row) {
                               
                                var tempurl = "<?php echo e(route('manage.products.vendor_product_edit', ["+row.id+"])); ?>";
                                var url = tempurl.replace("+row.id+",row.id);
                                var deletetempurl = "<?php echo e(route('manage.products.delete_vendor_product', ["+row.id+"])); ?>";
                                var deleteurl = deletetempurl.replace("+row.id+",row.id);
                                
                                    var actionBtn = '';
                                    <?php  if (Auth::user()->can('update', App\Product::class)) { ?>
 actionBtn += '<a title = "Edit Product" class="btn btn-info btn-sm" href="'+url+'"> <i class="fa fa-pencil"></i></a>&nbsp;'; <?php } if (Auth::user()->can('delete', App\Product::class)) { ?>
 actionBtn += '<a class="btn btn-danger btn-sm" href="'+deleteurl+'" onclick="return confirm(\'Are you sure you want to delete this product?\')"><i class="fa fa-trash-o"></i></a>'; <?php } ?>
                                    return actionBtn;
                                 
                            }

                            
                        
                    }
                    
                ]
            });


             
            
        });
    </script>
     
    <?php echo $__env->make('partials.manage.dataTables-export', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
 
    
   
    
     
    <?php if(config('settings.toast_notifications_enable')): ?>
    <script>
        toastr.options.closeButton = true;
        <?php if(session()->has('product_created')): ?>
            toastr.success("<?php echo e(session('product_created')); ?>");
        <?php endif; ?>

        <?php if(session()->has('product_deleted')): ?>
            toastr.success("<?php echo e(session('product_deleted')); ?>");
        <?php endif; ?>

        <?php if(session()->has('product_not_deleted')): ?>
            toastr.error("<?php echo e(session('product_not_deleted')); ?>");
        <?php endif; ?>
    </script>
    <?php endif; ?>
    <?php echo $__env->make('includes.form_delete_all_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
        var advancedSearch = $('.advanced-search');
        advancedSearch.hide();
        $(document).on('click', '.advanced-search-toggle', function() {
            advancedSearch.fadeToggle();
        });
        $(document).on('click', '#search-btn', function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            var search = $('#keyword').val();
            var perPage = $('#per_page').val();
            var vendor = $('#vendor').val();
            if(perPage) {
                perPage = parseInt(perPage)
            } else {
                perPage = 15;
            }

            var requestUrl = url + '?s=' + search;


            if(vendor) {
                requestUrl += '&vendor=' + parseInt(vendor);
            }

            var urlParams = new URLSearchParams(window.location.search);
            var all = urlParams.get('all');

            if(parseInt(all) > 0) {
                requestUrl += '&all=' + all;
            } else {
                requestUrl += '&per_page=' + perPage;
            }

            location.href = requestUrl;
        });
    </script>
     
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-12">
            <?php if(session()->has('product_created')): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><?php echo e(session('product_created')); ?></strong> <a target="_blank" href="<?php echo e(route('front.product.show', session('product_view'))); ?>">View</a>
                </div>
            <?php endif; ?>
            <?php if(session()->has('product_deleted')): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><?php echo e(session('product_deleted')); ?></strong>
                </div>
            <?php endif; ?>
            <?php if(session()->has('product_not_deleted')): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><?php echo e(session('product_not_deleted')); ?></strong>
                </div>
            <?php endif; ?>
            <?php echo $__env->make('partials.manage.products.vendor.index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    </div>

    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>