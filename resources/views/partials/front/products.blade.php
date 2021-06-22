@if(count($products) > 0)
    <div class="row">
        <div class="col-md-3">
            <hr>
            @include('partials.front.filter-sidebar')
            @if(isset($banners_below_filters) || isset($sections_above_side_banners) || isset($sections_below_side_banners))
            <hr>
                @if(isset($sections_above_side_banners))
                    @include('partials.front.sections.brand-category.above-side-banners')
                @endif
                @if(isset($banners_below_filters))
                    @include('partials.front.side-banners', ['banners' => $banners_below_filters])
                @endif
                @if(isset($sections_below_side_banners))
                    @include('partials.front.sections.brand-category.below-side-banners')
                @endif
            @endif
        </div>
        <div class="col-md-9">
            <div class="text-right col-md-12">
                <div class="btn-group form-inline">
                    <div class="form-group">
                        <label for="sort_products">@lang('Sort Products:')</label>
                        <select class="form-control" id="sort_products">
                            <option value="0">@lang('Select Option')</option>
                            <option value="1">@lang('By Price Low to High')</option>
                            <option value="2">@lang('By Price High to Low')</option>
                            <option value="3">@lang('By Popularity')</option>
                            <option value="4">@lang('By Avg. Ratings')</option>
                            <option value="5">@lang('By Highest Reviews')</option>
                        </select>
                    </div>
                </div>
            </div>
            @include('includes.products')
        </div>
    </div>
@else
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h2 class="mb-5 text-center">Oops! We are sorry, <br/>we could not find what you are looking for</h2>
            <a href="{{ url('/') }}" class="btn btn-primary ShoppingBtn ">CONTINUE SHOPPING</a>

        </div>
@endif