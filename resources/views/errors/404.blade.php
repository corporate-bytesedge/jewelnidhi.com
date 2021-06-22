
@extends('layouts.front')
@section('content')

<div class="container">
    <div class="page-error">
    
        <h1><i class="fa fa-exclamation-circle"></i> @lang('Error 404')</h1>
        <p>@lang('The page you have requested is not found.')</p>
        
        <p><a href="{{ url('/') }}">@lang('Go back to previous page')</a></p>
    </div>
</div>


@endsection
