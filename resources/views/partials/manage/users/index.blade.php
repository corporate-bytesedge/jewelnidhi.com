<div class="panel panel-default">
    <div class="panel-heading">
        @lang('View User')
    </div>
    <div class="panel-body">
    
    
        <!-- <div class="row advanced-search">
            <div class="col-md-5">
                <select class="form-control" id="select-column">
                    <option value=2>@lang('Name')</option>
                    <option value=3>@lang('Username')</option>
                    <option value=4>@lang('Email')</option>
                    <option value=5>@lang('Role')</option>
                    <option value=6>@lang('Status')</option>
                    <option value=7>@lang('Created')</option>
                </select>
            </div>
            <div class="col-md-7">
                <input class="form-control" type="text" id="search-by-column" placeholder="@lang('Search by Column')">
            </div>
        </div> -->
        <div class="table-responsive">
            <!-- <div class="col-md-12 text-right">
                <span class="advanced-search-toggle">@lang('Advanced Search')</span>
            </div> -->
            <table class="display table table-striped table-bordered table-hover" id="users-table">
                <thead>
                    <tr>
                        <!-- <th><input type="checkbox" id="options"></th> -->
                        <th>@lang('S.No.')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Phone')</th>
                        <th>@lang('Email')</th>
                        <th>@lang('Role')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Created')</th>
                        @can('update', App\User::class)
                        <th>@lang('Edit')</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                @if($users)
                @php 
                    $i = 1;
                @endphp
                    @foreach($users as $user)
                        <tr>
                            <td> {{ $i++ }}
                            <!-- @if($user->id != 1)
                                <input class="checkboxes" type="checkbox" name="checkboxArray[]" value="{{$user->id}}">
                            @endif -->
                            </td>
                            
                            <td>{{$user->name}}</td>
                            
                           
                            <td>
                                @if($user->mobile)
                                    @if($user->mobile->verified)
                                    {{$user->mobile->number}} <strong class="text-success">(@lang('verified'))</strong>
                                    @else
                                    {{$user->mobile->number}} <strong class="text-danger">(@lang('unverified'))</strong>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            
                            <td>{{$user->email}}</td>
                            <td>
                                @if($user->role)
                                    @if($user->role->id == 1)
                                        <strong><span class="text-danger">{{$user->role->name}}</span></strong>
                                    @else
                                        {{$user->role->name}}
                                    @endif
                                @else
                                    @lang('None')
                                @endif
                            </td>
                            <td>
                                @if($user->is_active == '1')
                                        <span class="badge bg-success"><i class="fa fa-check"></i></span>
                                    @else
                                        <span class="badge bg-danger"><i class="fa fa-times"></i></span>
                                    @endif
                            
                            </td>
                            <td>{{$user->created_at ? $user->created_at : '-'}}</td>
                            @can('update', App\User::class)
                            <td>
                            <a href="{{route('manage.users.edit', $user->id)}}" class="btn btn-info btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    

                                    <a class="btn btn-danger btn-sm" href="{{route('manage.users.deleteuser', $user->id)}}" onclick="return confirm('Are you sure you want to delete this user? All data related to this user will also be deleted.')">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                               
                            </td>
                            @endcan
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
         
    </div>
</div>