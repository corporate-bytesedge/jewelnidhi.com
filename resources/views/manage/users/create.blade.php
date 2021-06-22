@extends('layouts.manage')

@section('title')
    @lang('Add User')
@endsection

@section('page-header-title')
    @lang('Add User') <a class="btn btn-info btn-sm" href="{{route('manage.users.index')}}">@lang('View User')</a>
@endsection

@section('page-header-description')
    @lang('Add New User') <a href="{{url()->previous()}}">@lang('Go Back')</a>
@endsection

@section('content')
    @include('partials.manage.users.create')
@endsection