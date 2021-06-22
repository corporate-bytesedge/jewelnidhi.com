
<div class="panel panel-default">
    
    <div class="panel-body">
        @can('delete', App\Order::class)
        <form id="delete-form" action="delete/orders" method="post" class="form-inline">
        {{csrf_field()}}
        @endcan

                <div class="bulk-options">
                        <label for="checkboxArray">@lang('Bulk Options') <i class="fa fa-cog" aria-hidden="true"></i></label>
                        <div class="delete-option">
                                @if(Auth::user()->can('delete', App\Product::class) || isset($vendor))
                                

                                <div class="">
                                    
                                    <input type="hidden" name="_method" value="DELETE">

                                    
                          

                                    
                                        <button type="button" id="delete_all" name="delete_all" class="btn btn-warning" onclick="
                                            if(confirm('@lang('Are you sure you want to delete selected orders?')')) {
                                                $('#delete_all').attr('name', 'delete_all');
                                                event.preventDefault();
                                                    $('#delete-form').submit();
                                            } else {
                                                    event.preventDefault();
                                            }
                                            ">Delete</button>
                                    
                                        
                                    
                                </div>
                                
                                @endif
                        </div>
                </div>
                <div class="search-section">
                <label for="search">@lang('Search')</label>
                @if(!\Auth::user()->isApprovedVendor())
                    <div class="form-group">
                            <select class="form-control" id="Vendors">
                                <option value="">@lang('Select vendor')</option>
                                @if(count($vendor) > 0)
                                       @foreach($vendor AS $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                       @endforeach
                                @endif
                                
                                
                            </select>
                    </div>
                @endif
                <div class="form-group">
                            <select class="form-control" id="orderStatus">
                                <option value="">@lang('Select status')</option>
                                <option value="0">Pending</option>
                                <option value="1">Refunded</option>
                                <option value="2">Cancelled</option>
                                <option value="3">Delivered</option>
                                
                            </select>
                    </div>
                    <div class="form-group">
                    
                        <input class="form-control" type="text" id="search-by-column" placeholder="@lang('Search by Order No')">
                    </div>
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm Search"><span class="advanced-search-toggle" style="color:#fff;">@lang('Search')</span></a>
            </div>

            
            <div class="table-responsive">
                
                
               

              
                <table class="display table table-striped table-bordered table-hover" id="orders-table">
                    <thead>
                        <tr>
                            @can('delete', App\Order::class)
                                <th><input type="checkbox" id="options"></th>
                            @endcan
                            <th>Order No</th>
                            <th>Order Date</th>
                            <th>Customer Name</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>Total</th>
                            <th>Invoice</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" >Total:</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @can('delete', App\Order::class)
        </form>
        @endcan
       
    </div>
</div>