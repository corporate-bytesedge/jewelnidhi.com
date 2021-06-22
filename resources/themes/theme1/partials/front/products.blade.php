@if(count($products) > 0)
    <div class="row">

        <div class="col-md-3">
            <!-- @include('partials.front.filter-sidebar') -->
            <!-- @if(isset($banners_below_filters) || isset($sections_above_side_banners) || isset($sections_below_side_banners)) -->

                <!-- @if(isset($sections_above_side_banners))
                    @include('partials.front.sections.brand-category.above-side-banners')
                @endif -->
                <!-- @if(isset($banners_below_filters))
                    @include('partials.front.side-banners', ['banners' => $banners_below_filters])
                @endif -->
                <!-- @if(isset($sections_below_side_banners))
                    @include('partials.front.sections.brand-category.below-side-banners')
                @endif -->
            <!-- @endif -->
        </div>
        <div class="custom_product_data">
            @include('includes.products_pagination')
        </div>
    </div>
@else
    <div class="col-lg-12 col-md-12 col-sm-12">
                <center><img src="{{ URL::asset('img/no-product.png') }}">
                <br/><a href="{{url('/')}}" class="btn btn-primary">@lang('Go to Shop')</a></center>
    </div>
@endif
