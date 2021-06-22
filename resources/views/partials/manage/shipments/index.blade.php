<div class="panel panel-default">
    <div class="panel-heading">
        @lang('View Shipments')
    </div>
    <div class="panel-body">
        <form id="delete-form" action="delete/shipments" method="post" class="form-inline">
            <div class="row">
                @can('delete', App\Shipment::class)
                {{csrf_field()}}
                <div class="col-md-4">
                    <div class="text-muted">
                        <label for="checkboxArray">@lang('Bulk Options') <i class="fa fa-cog" aria-hidden="true"></i></label>
                    </div>
                    <input type="hidden" name="_method" value="DELETE">

                    <div class="form-group">
                        <select name="checkboxArray" class="form-control">
                            <option value="">@lang('Delete')</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input id="delete_all" name="" class="btn fa btn-warning" value="&#xf1d8;"
                               onclick="
                                       if(confirm('@lang('Are you sure you want to delete selected shipments?')')) {
                                           $('#delete_all').attr('name', 'delete_all');
                                           event.preventDefault();
                                            $('#delete-form').submit();
                                       } else {
                                            event.preventDefault();
                                       }
                                       "
                        > 
                    </div>
                </div>
                @endcan
                <div class="advanced-search col-md-{{Auth::user()->can('delete', App\Shipment::class) ? '8' : '8 col-md-offset-4'}}">
                    <div class="text-muted">
                        <label for="checkboxArray">@lang('Advanced Search') <i class="fa fa-search" aria-hidden="true"></i></label>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <select class="form-control" id="select-column">
                                <option value={{Auth::user()->can('delete', App\Shipment::class) ? 2 : 1}}>@lang('Name')</option>
                                <option value={{Auth::user()->can('delete', App\Shipment::class) ? 3 : 2}}>@lang('Address')</option>
                                <option value={{Auth::user()->can('delete', App\Shipment::class) ? 4 : 3}}>@lang('City')</option>
                                <option value={{Auth::user()->can('delete', App\Shipment::class) ? 5 : 4}}>@lang('State')</option>
                                <option value={{Auth::user()->can('delete', App\Shipment::class) ? 6 : 5}}>@lang('Zip Code')</option>
                                <option value={{Auth::user()->can('delete', App\Shipment::class) ? 7 : 6}}>@lang('Country')</option>
                                <option value={{Auth::user()->can('delete', App\Shipment::class) ? 8 : 7}}>@lang('Number of Shippers')</option>
                                <option value={{Auth::user()->can('delete', App\Shipment::class) ? 9 : 8}}>@lang('Date Created')</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <input class="form-control" type="text" id="search-by-column" placeholder="@lang('Search by Column')">
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <div class="col-md-12 text-right">
                    <span class="advanced-search-toggle">@lang('Advanced Search')</span>
                </div>
                <table class="display table table-striped table-bordered table-hover" id="shipments-table">
                    <thead>
                    <tr>
                        @can('delete', App\Shipment::class)
                        <th><input type="checkbox" id="options"></th>
                        @endcan
                        <th>@lang('ID')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Address')</th>
                        <th>@lang('City')</th>
                        <th>@lang('State')</th>
                        <th>@lang('Zip')</th>
                        <th>@lang('Country')</th>
                        <th>@lang('Shippers')</th>
                        <th>@lang('Created')</th>
                        @if((Auth::user()->can('update', App\Shipment::class)) || (Auth::user()->can('delete', App\Shipment::class)))
                        <th>@lang('Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($shipments) > 0)
                        @foreach($shipments as $shipment)
                            <tr>
                                @can('delete', App\Shipment::class)
                                <td><input class="checkboxes" type="checkbox" name="checkboxArray[]" value="{{$shipment->id}}"></td>
                                @endcan
                                <td>{{$shipment->id}}</td>
                                <td>{{$shipment->name}}</td>
                                <td>{{$shipment->address}}</td>
                                <td>{{$shipment->city}}</td>
                                <td>{{$shipment->state}}</td>
                                <td>{{$shipment->zip}}</td>
                                <td>{{$shipment->country}}</td>
                                <td>{{count($shipment->users)}}</td>
                                <td>{{$shipment->created_at}}</td>
                                @if((Auth::user()->can('update', App\Shipment::class)) || (Auth::user()->can('delete', App\Shipment::class)))
                                <td>
                                @can('update', App\Shipment::class)
                                    <a href="{{route('manage.shipments.edit', $shipment->id)}}">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                @endcan
                                &nbsp;
                                @can('delete', App\Shipment::class)
                                    <input type="hidden" id="delete_single" name="">
                                    <a href=""
                                       onclick="
                                               if(confirm('@lang('Are you sure you want to delete this?')')) {
                                                   $('#delete_single').attr('name', 'delete_single').val({{$shipment->id}});
                                                   event.preventDefault();
                                                   $('#delete-form').submit();
                                               } else {
                                                    event.preventDefault();
                                               }
                                               "
                                    ><span class="glyphicon glyphicon-trash text-danger"></span></a>
                                @endcan
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>