@extends('layouts.front')

@section('content')
<div class="container">
        <div class="row">
            @include('partials.front.sidebar')
 
            <div class="col-md-9 card">
                <div class="card-body">
                    <div class="page-title">
                        <h2 style="color:#000!important;">@lang('View Customer')</h2>
                         
                    </div>

                    <div class="row m-20">
				        <table class="table table-bordered">
                        
                            <tr>
                                <th>First Name</th>
                                <td> {{ isset($customer->first_name) ? ucwords($customer->first_name) : '-' }} </td>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <td> {{ isset($customer->last_name) ? ucwords($customer->last_name) : '-' }} </td>
                            </tr>
                             
                            <tr>
                                <th>Phone</th>
                                <td> {{ isset($customer->phone) ? $customer->phone : '-' }} </td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td> {{ isset($customer->city) ? ucwords($customer->city) : '-' }} </td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td> {{ isset($customer->state) ? ucwords($customer->state) : '-' }} </td>
                            </tr>
                            <tr>
                                <th>Zip</th>
                                <td> {{ isset($customer->zip) ? $customer->zip : '-' }} </td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td> {{ isset($customer->country) ? $customer->country : '-' }} </td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td> {{ isset($customer->address) ? ucwords($customer->address) : '-' }} </td>
                            </tr>
                        </table>
                    </div>    
                
                     
                </div>
            </div>
        </div>
</div>
@endsection