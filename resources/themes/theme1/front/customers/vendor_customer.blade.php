
@extends('layouts.front')

 





@section('content')

    <div class="container">
        <div class="row">
            
                @include('partials.front.sidebar')
                <div class="col-md-9 content">
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="page-title">
                                    <h2>@lang('Customer')</h2>
                                </div>
                                <table class="table table-borderless ">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>@lang('Name')</th>
                                            <th>@lang('Mobile')</th>
                                             
                                            <th>@lang('City')</th>
                                            <th>@lang('State')</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     
                                     @if(count($customers) > 0 )
                                        @foreach($customers AS $k => $value) 
                                            <tr>
                                                <td> {{ ucwords($value->first_name).' '.ucwords($value->last_name) }} </td>
                                                <td> {{ $value->phone ? $value->phone : '-' }} </td>
                                                 
                                                <td> {{ $value->city ? ucwords($value->city) : '-' }} </td>
                                                <td> {{ $value->state ? ucwords($value->state) : '-' }} </td>
                                                <td> 
                                                @if (Auth::user()->can('read', App\Customer::class))
                                                 
                                                    <a title="view" href="{{route('front.customers.showcustomer', $value->id)}}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                                @endif
                                                @if (Auth::user()->can('delete', App\Customer::class))
                                                    {!! Form::model([], ['method'=>'patch', 'action'=>['FrontCustomersController@delete_customer', $value->id], 'id'=> 'hide-form-'.$value->id, 'style'=>'display: none;']) !!}
                                                    {!! Form::close() !!}
                                                    <a href="" class='btn btn-sm btn-danger'
                                                    onclick="
                                                            if(confirm('@lang('Are you sure you want to delete this?')')) {
                                                            event.preventDefault();
                                                            $('#hide-form-{{1}}').submit();
                                                            } else {
                                                            event.preventDefault();
                                                            }
                                                            "
                                                    ><i class="fa fa-trash"> </i></a>
                                                @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                     @else
                                        <tr>
                                            <td colspan="5"> Result not found </td>
                                        </tr>                       
                                     @endif
                                       
                                        
                                        
                                    
                                    </tbody>
                                </table>
                                
                                        
                            
                                
                                
                            
                            </div>
                        </div>
                </div>
             
        </div>
          
    </div>
     

@endsection
