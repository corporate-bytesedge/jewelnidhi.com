@extends('layouts.manage')

@section('title')
    @lang('View Orders') {{(isset($id)) ? __("for Customer ID: :id", ['id'=> $id]) : ""}}
@endsection

@section('page-header-title')
    @lang('View Orders') {{(isset($id)) ? __("for Customer ID: :id", ['id'=> $id]) : ""}}
@endsection

@section('page-header-description')
    @lang('View and Manage Orders')
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap.min.js')}}"></script>

    
    @include('partials.manage.dataTables-responsive')
    
    
    @include('partials.manage.dataTables-export')
    
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
                    var order_status = $('#orderStatus').val();
                    var jnWEBId = $('#search-by-column').val();
                    ordertables.draw();
                    e.preventDefault();
                }
            });
            $(".Search").on("click",function() {
                var vendorId = $('#Vendors').val();
                var order_status = $('#orderStatus').val();
                var jnWEBId = $('#search-by-column').val();
                ordertables.draw();
            });
        
            var ordertables = $('#orders-table').DataTable({
                 
                searchable: true,
                processing: true,
                serverSide: true,
                
                'columnDefs': [ 
                    {
                        'targets': [0,7], // column index (start from 0)
                        'orderable': false, // set orderable false for selected columns
                    }
                ],
                language: {
                    'processing': '<div class="spinner"></div>'
                },
                "order": [[ 1, "desc" ]],
                ajax: {
                    url: "{{ route('manage.orders.index') }}",
                    data: function (d) {
                            d.search = $('#Vendors').val(),
                            d.web_id = $('#search-by-column').val(),
                            d.order_status = $('#orderStatus').val()
                                    
                        }
                },
                // "fnDrawCallback": function() {
                //     var api = this.api()
                //     var json = api.ajax.json();
                //     console.log(json.totals.totals);
                //     $(api.column(6).footer()).html(json.totals.totals);
                // },
                columns: [
                    {data: 'Ids', name: 'id'},
                    {data: 'order_no', name: 'id'},
                    {data: 'order_date', name: 'processed_date'},
                    {data: 'customer_name', name: 'customer_id'},
                    {data: 'payment_status', name: 'status'},
                    {data: 'order_status', name: 'paid'},
                    {data: 'total', name: 'total'},
                    {data: 'invoice', name: 'invoice'},
                    
                    {data: 'Action', name: 'Action',
                        
                            mRender: function (data, type, row) {
                               
                               
                                
                                
                                var actionBtn = '';
                                <?php if(\Auth::user()->can('update', App\Order::class) ) { ?>
                                    if(row.is_processed !='3' && row.paid != '0') {
                                        var tempurl = "{{ route('manage.orders.edit', ["+row.id+"]) }}";
                                        var url = tempurl.replace("+row.id+",row.id);
                                        actionBtn += '<a  title = "Edit Invoice" class="btn btn-info btn-sm" href="'+url+'">  <i class="fa fa-pencil"></i></a>&nbsp;';
                                    }   
                                <?php } ?>

                                  
                                return actionBtn;
                               
                            
                            }

                            
                        
                    }
                    
                ],
                "footerCallback": function ( row, data, start, end, display ) {
                        var api = this.api(), data;
                        
                     var json = api.ajax.json();
                        // Remove the formatting to get integer data for summation
                        var intVal = function ( i ) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '')*1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };
            
                        // Total over all pages
                        total = json.totals.totals;
            
                        // Total over this page
                        pageTotal = api
                            .column( 6, { page: 'current'} )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
            
                        // Update footer
                        $( api.column( 6 ).footer() ).html(
                            total
                        );
                    }
                
                
            });
            
            
    });
        
    </script>
    
    
     
    @if(config('settings.toast_notifications_enable'))
    <script>
        toastr.options.closeButton = true;
        @if(session()->has('order_deleted'))
            toastr.success("{{session('order_deleted')}}");
        @endif
        @if(session()->has('order_not_deleted'))
            toastr.error("{{session('order_not_deleted')}}");
        @endif
        @if(session()->has('order_updated'))
            toastr.success("{{session('order_updated')}}");
        @endif
        @if(session()->has('order_not_updated'))
            toastr.error("{{session('order_not_updated')}}");
        @endif
    </script>
    @endif
    @include('includes.form_delete_all_script');
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
            if(perPage) {
                perPage = parseInt(perPage)
            } else {
                perPage = 15;
            }

            var requestUrl = url + '?s=' + search;

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
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(session()->has('order_deleted'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{session('order_deleted')}}</strong>
                </div>
            @endif
            @if(session()->has('order_not_deleted'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{session('order_not_deleted')}}</strong>
                </div>
            @endif
            @if(session()->has('order_updated'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{session('order_updated')}}</strong>
                </div>
            @endif
            @if(session()->has('order_not_updated'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="glyphicon glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <strong>{{session('order_not_updated')}}</strong>
                </div>
            @endif
            @if(session()->has('order_passed_to_shipment'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{session('order_passed_to_shipment')}}</strong>
                </div>
            @endif
            @include('partials.manage.orders.index')
        </div>
    </div>
@endsection