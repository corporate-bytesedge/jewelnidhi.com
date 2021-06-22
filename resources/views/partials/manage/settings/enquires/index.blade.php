<div class="panel panel-default">
    <div class="panel-heading">
        @lang('List Enquiry')
    </div>
    <div class="panel-body">
        <form id="delete-form" action="bulk_enquiry_delete" method="post" class="form-inline">

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
                                       if(confirm('@lang('Are you sure you want to delete selected enquiry?')')) {
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
                        <th>@lang('Product Name')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Email')</th>
                        <th>@lang('Phone')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                       
                        @if(count($enquires) > 0)
                            @foreach($enquires AS $value)
                            
                            <tr>
                            <td><input class="checkboxes" type="checkbox" name="checkboxArray[]" value="{{ $value->id }}"></td>
                            <td>{{ isset($value->product->name) ? $value->product->name : '-' }}</td>
                            <td> {{ $value->name ? $value->name : '-' }}</td>
                            <td> {{ $value->email ? $value->email : '-' }}</td>
                            <td> {{ $value->phone ? $value->phone : '-' }}</td>
                            <td> {{ $value->created_at ? date('d-m-Y',strtotime($value->created_at)) : '-' }}</td>
                            <td>    <a class="btn btn-info btn-sm " title="View" href="{{route('manage.settings.view_enquiry', $value->id)}}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    
                                    <a class="btn btn-danger btn-sm" title="Delete" href="{{route('manage.settings.delete_enquiry', $value->id)}}" onclick="return confirm('Are you sure')">
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