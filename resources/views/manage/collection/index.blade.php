@extends('layouts.manage')

@section('title')
    @lang('View Collection')
@endsection

@section('page-header-title')
    @lang('View Collection')
    
@endsection

@section('page-header-description')
    @lang('View Collection')
@endsection

 

@section('scripts')
     
   
   
    
    @if(config('settings.toast_notifications_enable'))
    <script>
        toastr.options.closeButton = true;
        @if(session()->has('discount_created'))
            toastr.success("{{session('discount_created')}}");
        @endif

        @if(session()->has('discount_deleted'))
            toastr.success("{{session('discount_deleted')}}");
        @endif

        @if(session()->has('discount_not_deleted'))
            toastr.error("{{session('discount_not_deleted')}}");
        @endif
    </script>
    @endif
    @include('includes.form_delete_all_script')
    <script>
        var advancedSearch = $('.advanced-search');
        advancedSearch.hide();
        $(document).on('click', '.advanced-search-toggle', function() {
            advancedSearch.fadeToggle();
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(session()->has('discount_created'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{session('discount_created')}}</strong>
                </div>
            @endif
            @if(session()->has('discount_deleted'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{session('discount_deleted')}}</strong>
                </div>
            @endif
            @if(session()->has('discount_not_deleted'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{session('discount_not_deleted')}}</strong>
                </div>
            @endif
            @include('partials.manage.collection.index')
        </div>
    </div>
@endsection