

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('View Vendors'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('View Vendors'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Vendor::class)): ?>
        <a class="btn btn-sm btn-info" href="<?php echo e(route('manage.vendors.create')); ?>"><?php echo app('translator')->getFromJson('Add Vendor'); ?></a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('View and Manage Vendors'); ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <!-- DATA TABLE SCRIPTS -->
    <script src="<?php echo e(asset('js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/dataTables.bootstrap.min.js')); ?>"></script>
    <?php echo $__env->make('partials.manage.dataTables-responsive', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
        function searchByColumn(table) {
            var condition = <?php echo e(Auth::user()->can('delete', App\Vendor::class) ? 2 : 1); ?>;
            var defaultSearch = (condition ? 4 : 3);
            $('#select-column').on('change', function() {
                defaultSearch = this.value;
            });
            $(document).on('change','#search-by-column', function() {
                table.search( '' ).columns().search( '' ).draw();
                table.column(defaultSearch).search(this.value).draw();
            });
        }
    </script>
    <?php if(config('settings.export_table_enable')): ?>
    <?php echo $__env->make('partials.manage.dataTables-export', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <script>
        $(document).ready(function () {
            <?php if(Auth::user()->can('delete', App\Vendor::class)): ?>
                var arrayColumns = [1,2,3,4,5,6,7,8,9,10,11,12];
            <?php else: ?>
                var arrayColumns = [0,1,2,3,4,5,6,7,8,9,10,11];
            <?php endif; ?>
            var table = $('#vendors-table').DataTable({
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
            var table = $('#vendors-table').DataTable({
                responsive: true,
            });
            new $.fn.dataTable.FixedHeader( table );
            searchByColumn( table );
        });
    </script>
    <?php endif; ?>
    <?php if(config('settings.toast_notifications_enable')): ?>
    <script>
        toastr.options.closeButton = true;
        <?php if(session()->has('vendor_created')): ?>
            toastr.success("<?php echo e(session('vendor_created')); ?>");
        <?php endif; ?>

        <?php if(session()->has('vendor_deleted')): ?>
            toastr.success("<?php echo e(session('vendor_deleted')); ?>");
        <?php endif; ?>

        <?php if(session()->has('vendor_not_deleted')): ?>
            toastr.error("<?php echo e(session('vendor_not_deleted')); ?>");
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
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <?php if(session()->has('vendor_created')): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><?php echo e(session('vendor_created')); ?></strong>
                </div>
            <?php endif; ?>
            <?php if(session()->has('vendor_deleted')): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><?php echo e(session('vendor_deleted')); ?></strong>
                </div>
            <?php endif; ?>
            <?php if(session()->has('vendor_not_deleted')): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><?php echo e(session('vendor_not_deleted')); ?></strong>
                </div>
            <?php endif; ?>
            <?php echo $__env->make('partials.manage.vendors.index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>