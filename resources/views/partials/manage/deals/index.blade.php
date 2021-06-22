<div class="panel panel-default">
    <div class="panel-heading">
        @lang('View Deals')
    </div>
    <div class="panel-body">
        <form id="delete-form" action="delete/deals" method="post" class="form-inline">
            <div class="row">
                @can('delete', App\Deal::class)
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
                                       if(confirm('@lang('Are you sure you want to delete selected deals?')')) {
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
                <div class="advanced-search col-md-{{Auth::user()->can('delete', App\Deal::class) ? '8' : '8 col-md-offset-4'}}">
                    <div class="text-muted">
                        <label for="checkboxArray">@lang('Advanced Search') <i class="fa fa-search" aria-hidden="true"></i></label>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <select class="form-control" id="select-column">
                                <option value={{Auth::user()->can('delete', App\Deal::class) ? 2 : 1}}>@lang('Name')</option>
                                <option value={{Auth::user()->can('delete', App\Deal::class) ? 3 : 2}}>@lang('Priority')</option>
                                <option value={{Auth::user()->can('delete', App\Deal::class) ? 4 : 3}}>@lang('Status')</option>
                                <option value={{Auth::user()->can('delete', App\Deal::class) ? 5 : 4}}>@lang('Created')</option>
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
                <table class="display table table-striped table-bordered table-hover" id="deals-table">
                    <thead>
                    <tr>
                        @can('delete', App\Deal::class)
                        <th><input type="checkbox" id="options"></th>
                        @endcan
                        <th>@lang('ID')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Priority')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Created')</th>
                        @if((Auth::user()->can('update', App\Deal::class)) || (Auth::user()->can('delete', App\Deal::class)))
                        <th>@lang('Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($deals) > 0)
                        @foreach($deals as $deal)
                            <tr>
                                @can('delete', App\Deal::class)
                                <td><input class="checkboxes" type="checkbox" name="checkboxArray[]" value="{{$deal->id}}"></td>
                                @endcan
                                <td>{{$deal->id}}</td>
                                <td>{{$deal->name}}</td>
                                <td>{{$deal->priority}}</td>
                                <td>{{$deal->is_active ? __('Active') : __('Inactive')}}</td>
                                <td>{{$deal->created_at}}</td>
                                @if((Auth::user()->can('update', App\Deal::class)) || (Auth::user()->can('delete', App\Deal::class)))
                                <td>
                                @can('update', App\Deal::class)
                                    <a href="{{route('manage.deals.edit', $deal->id)}}">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                @endcan
                                &nbsp;
                                @can('delete', App\Deal::class)
                                    <input type="hidden" id="delete_single" name="">
                                    <a href=""
                                       onclick="
                                               if(confirm('@lang('Are you sure you want to delete this?')')) {
                                                   $('#delete_single').attr('name', 'delete_single').val({{$deal->id}});
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