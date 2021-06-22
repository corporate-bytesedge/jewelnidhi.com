<div class="panel panel-default">
    <div class="panel-heading">
        @lang('List Silver Item')
    </div>
    <div class="panel-body">
        <form id="delete-form" action="bulk_silver_item_delete" method="post" class="form-inline">

        <div class="row">
                 
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
                                       if(confirm('@lang('Are you sure you want to delete selected silver item?')')) {
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
                 
                <div class="advanced-search col-md-{{Auth::user()->can('delete', App\Testimonial::class) ? '8' : '8 col-md-offset-4'}}">
                    
                    <div class="row">
                        
                        <div class="col-md-7">
                            <input class="form-control" type="text" id="search-by-column" placeholder="@lang('Search by Column')">
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <table class="display table table-striped table-bordered table-hover" id="certificate-table">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="options"></th>
                        
                        <th>@lang('Name')</th>
                        <th>@lang('Rate')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($silveritems->isNotEmpty())
                        @foreach($silveritems AS $k=> $value)
                        <tr>
                            <td><input class="checkboxes" type="checkbox" name="checkboxArray[]" value="{{$value->id}}"></td>
                            <td>{{ ucwords($value->name) }}</td>
                            <td>{{ number_format($value->rate,2) }}</td>
                            <td> 
                                <a class="btn btn-info btn-sm" href="{{route('manage.silveritem.edit', $value->id)}}">
                                        <i class="fa fa-pencil"></i>
                                </a>
                                <a class="btn btn-danger btn-sm" href="{{route('manage.silveritem.delete_silver_item', $value->id)}}" onclick="return confirm('Are you sure')">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                     @endif
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>