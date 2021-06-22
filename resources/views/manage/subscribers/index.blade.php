@extends('layouts.manage')

@section('title')
    @lang('View Subscribers')
@endsection

@section('page-header-title')
    @lang('View Subscribers')
@endsection

@section('page-header-description')
    @lang('View Subscribers')
@endsection

@section('styles')
    <!-- DATA TABLE STYLE -->
    <link href="{{asset('css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/dataTables-responsive/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/dataTables-responsive/responsive.bootstrap.min.css')}}" rel="stylesheet">
    @if(config('settings.export_table_enable'))
    <link href="{{asset('css/dataTables-export/buttons.dataTables.min.css')}}" rel="stylesheet">
    @endif
    @include('includes.datatable_style')
@endsection

@section('scripts')
    <!-- DATA TABLE SCRIPTS -->
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>
    @include('partials.manage.dataTables-responsive')
    <script>
        @if(session()->has('success'))
            toastr.success("{{session('success')}}");
        @endif
        @if(session()->has('error'))
            toastr.error("{{session('error')}}");
        @endif
        function searchByColumn(table) {
            var condition = {{Auth::user()->can('import-delete-subscribers', App\Other::class) ? 1 : 0}};
            var defaultSearch = (condition ? 2 : 1);
            $('#select-column').on('change', function() {
                defaultSearch = this.value;
                if(defaultSearch == condition ? 3 : 2) {
                    $("#search-by-column")
                        .replaceWith('<select class="form-control" id="search-by-column">' +
                            '<option value="">@lang('All')</option>' +
                            '<option value="Confirmed">@lang('Confirmed')</option>' +
                            '<option value="Pending">@lang('Pending')</option>' +
                            '</select>');
                } else {
                    $("#search-by-column")
                        .replaceWith('<input class="form-control" type="text" id="search-by-column" placeholder="@lang('Search by Column')">');
                }
            });
            $(document).on('change','#search-by-column', function() {
                table.search( '' ).columns().search( '' ).draw();
                table.column(defaultSearch).search(this.value).draw();
            });
        }
    </script>
    @if(config('settings.export_table_enable'))
    @include('partials.manage.dataTables-export')
    <script>
        $(document).ready(function () {
            @if(Auth::user()->can('import-delete-subscribers', App\Other::class))
                var arrayColumns = [1,2,3,4];
            @else
                var arrayColumns = [0,1,2,3];
            @endif
            var table = $('#subscribers-table').DataTable({
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
        });
    </script>
    @else
    <script>
        $(document).ready(function () {
            var table = $('#subscribers-table').DataTable({
                responsive: true,
            });
            new $.fn.dataTable.FixedHeader( table );
            searchByColumn(table);
        });
    </script>
    @endif
    @if(config('settings.toast_notifications_enable'))
        <script>
            toastr.options.closeButton = true;
            @if(session()->has('subscriber_deleted'))
            toastr.success("{{session('subscriber_deleted')}}");
            @endif

            @if(session()->has('subscriber_not_deleted'))
            toastr.error("{{session('subscriber_not_deleted')}}");
            @endif
        </script>
    @endif
    @include('includes.form_delete_all_script')
    <script>
        var advancedSearch = $('.advanced-search');
        advancedSearch.hide();
        $(document).on('click', '.advanced-search-toggle', function() {
            advancedSearch.fadeToggle();
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(session()->has('subscriber_deleted'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{session('subscriber_deleted')}}</strong>
                </div>
            @endif
            @if(session()->has('subscriber_not_deleted'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{session('subscriber_not_deleted')}}</strong>
                </div>
            @endif
            @include('partials.manage.subscribers.index')
        </div>
    </div>
@endsection