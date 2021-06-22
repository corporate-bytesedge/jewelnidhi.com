@extends('layouts.manage')

@section('title')
    @lang('Price Settings')
@endsection

@section('page-header-title')
    @lang('Price Settings')
@endsection

@section('page-header-description')
    @lang('View or Change Price Settings')
@endsection

@section('scripts')
    @if(config('settings.toast_notifications_enable'))
    <script>
        toastr.options.closeButton = true;
        @if(session()->has('profile_updated'))
            toastr.success("{{session('profile_updated')}}");
        @endif
    </script>
    @endif
@endsection

@section('content')
    @include('partials.manage.settings.pricesetting')
@endsection