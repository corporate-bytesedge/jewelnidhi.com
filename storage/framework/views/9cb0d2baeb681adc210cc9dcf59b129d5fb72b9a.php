

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('View Testimonials'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('View Testimonials'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Testimonial::class)): ?>
        <a class="btn btn-sm btn-info" href="<?php echo e(route('manage.testimonials.create')); ?>"><?php echo app('translator')->getFromJson('Add Testimonial'); ?></a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('View and Manage Testimonials'); ?>
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
            var condition = <?php echo e(Auth::user()->can('delete', App\Testimonial::class) ? 1 : 0); ?>;
            var defaultSearch = (condition ? 3 : 2);
            $('#select-column').on('change', function() {
                defaultSearch = this.value;
                if(defaultSearch == (condition ? 7 : 6)) {
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
                if(defaultSearch == (condition ? 7 : 6)) {
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
            <?php if(Auth::user()->can('delete', App\Testimonial::class)): ?>
                var arrayColumns = [1,3,4,5,6,7,8];
            <?php else: ?>
                var arrayColumns = [0,2,3,4,5,6,7];
            <?php endif; ?>
            var table = $('#testimonials-table').DataTable({
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
            var table = $('#testimonials-table').DataTable({
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
        <?php if(session()->has('testimonial_created')): ?>
            toastr.success("<?php echo e(session('testimonial_created')); ?>");
        <?php endif; ?>

        <?php if(session()->has('testimonial_deleted')): ?>
            toastr.success("<?php echo e(session('testimonial_deleted')); ?>");
        <?php endif; ?>

        <?php if(session()->has('testimonial_not_deleted')): ?>
            toastr.error("<?php echo e(session('testimonial_not_deleted')); ?>");
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
            <?php if(session()->has('testimonial_created')): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><?php echo e(session('testimonial_created')); ?></strong>
                </div>
            <?php endif; ?>
            <?php if(session()->has('testimonial_deleted')): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><?php echo e(session('testimonial_deleted')); ?></strong>
                </div>
            <?php endif; ?>
            <?php if(session()->has('testimonial_not_deleted')): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><?php echo e(session('testimonial_not_deleted')); ?></strong>
                </div>
            <?php endif; ?>
            <?php echo $__env->make('partials.manage.testimonials.index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>