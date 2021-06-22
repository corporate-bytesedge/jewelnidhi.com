

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('View Roles'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('View Roles'); ?>
    <!-- <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Role::class)): ?>
        <a class="btn btn-sm btn-info" href="<?php echo e(route('manage.roles.create')); ?>"><?php echo app('translator')->getFromJson('Add Role'); ?></a>
    <?php endif; ?> -->
    <!-- <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', App\User::class)): ?>
        <a class="text-info pull-right" href="<?php echo e(route('manage.users.index')); ?>"><h5 class="text-primary"><?php echo app('translator')->getFromJson('Assign Role to User'); ?></h5></a>
    <?php endif; ?> -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('View and Manage Roles'); ?>
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
            var condition = <?php echo e(Auth::user()->can('delete', App\Role::class) ? 1 : 0); ?>;
            var defaultSearch = (condition ? 2 : 1);
            $('#select-column').on('change', function() {
                defaultSearch = this.value;
            });
            $('#search-by-column').on('change', function(){
                table.search( '' ).columns().search( '' ).draw();
                if(defaultSearch == (condition ? 2 : 1)) {
                    table.column(defaultSearch).search( '^' + this.value, true, false).draw();
                } else {
                    table.column(defaultSearch).search(this.value).draw();
                }
            });
        }
        function searchByColumn2(table) {
            var defaultSearch = 1;
            $('#select-column2').on('change', function() {
                defaultSearch = this.value;
            });
            $('#search-by-column2').on('change', function(){
                table.search( '' ).columns().search( '' ).draw();
                if(defaultSearch == 1) {
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
            <?php if(Auth::user()->can('delete', App\Role::class)): ?>
                var arrayColumns = [1,2];
            <?php else: ?>
                var arrayColumns = [0,1];
            <?php endif; ?>
            var table = $('#roles-table').DataTable({
                responsive: true,
                dom: 'lBfrtip',
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

            var arrayColumns2 = [0,1,2];
            var table2 = $('#roles-permissions-table').DataTable({
                responsive: true,
                dom: 'lBfrtip',
                buttons: [
               {
                   extend: 'pdf',
                   exportOptions: {
                        columns: arrayColumns2
                    }
               },
               {
                   extend: 'csv',
                   exportOptions: {
                        columns: arrayColumns2
                    }
               },
               {
                   extend: 'excel',
                   exportOptions: {
                        columns: arrayColumns2
                    }
               },
               {
                   extend: 'print',
                   exportOptions: {
                        columns: arrayColumns2
                    }
                }]
            });
            new $.fn.dataTable.FixedHeader( table2 );
            searchByColumn2( table2 );
        });
    </script>
    <?php else: ?>
    <script>
        $(document).ready(function () {
            var table = $('#roles-table').DataTable({
                responsive: true,
            });
            new $.fn.dataTable.FixedHeader( table );
            searchByColumn(table);

            var table2 = $('#roles-permissions-table').DataTable({
                responsive: true,
            });
            new $.fn.dataTable.FixedHeader( table2 );
            searchByColumn2(table2);
        });
    </script>
    <?php endif; ?>
    <?php echo $__env->make('includes.form_delete_all_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php if(config('settings.toast_notifications_enable')): ?>
    <script>
        toastr.options.closeButton = true;
        <?php if(session()->has('role_created')): ?>
            toastr.success("<?php echo e(session('role_created')); ?>");
        <?php endif; ?>

        <?php if(session()->has('role_deleted')): ?>
            toastr.success("<?php echo e(session('role_deleted')); ?>");
        <?php endif; ?>

        <?php if(session()->has('role_not_deleted')): ?>
            toastr.error("<?php echo e(session('role_not_deleted')); ?>");
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <?php if(session()->has('role_created')): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><?php echo e(session('role_created')); ?></strong>
                </div>
            <?php endif; ?>
            <?php if(session()->has('role_deleted')): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><?php echo e(session('role_deleted')); ?></strong>
                </div>
            <?php endif; ?>
            <?php if(session()->has('role_not_deleted')): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong><?php echo e(session('role_not_deleted')); ?></strong>
                </div>
            <?php endif; ?>
            <?php echo $__env->make('partials.manage.roles.index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.manage', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>