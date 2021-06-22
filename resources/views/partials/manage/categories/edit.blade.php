<div class="row">
    <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6">

        @if(session()->has('category_updated'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>{{session('category_updated')}}</strong> <a target="_blank" href="{{route('front.category.show', $category->slug)}}">@lang('View')</a>
            </div>
        @endif

        @include('includes.form_errors')
        
        {!! Form::model($category, ['method'=>'patch', 'action'=>['ManageCategoriesController@update', $category->id], 'files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
        {!! Form::hidden('id', $category->id)!!}
            
        <div class="row">

        @if(isset($category->category_img) && $category->category_img!='')
            <div class="col-md-6 col-sm-8 col-xs-12">
                @lang('Category Image:') 
                
                @if(file_exists('storage/style/topcategory/'.$category->category_img))
                <input type="hidden" name="old_category_img" id="old_category_img" value="{{ $category->category_img}}" />
                    <img src="{{ asset('storage/style/topcategory/'.$category->category_img) }}" class="img-responsive" alt="Category">
                    <div class="form-group">
                        <div class="has-error">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('remove_category_photo') !!} <strong>@lang('Remove Image')</strong>
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
            
                <br><br>
            </div>
            @endif
        </div>

        <div class="row">

        @if(isset($category->banner) && $category->banner!='')
            <div class="col-md-6 col-sm-8 col-xs-12">
                @lang('Banner Image:') 
                @if(isset($category->banner) && $category->banner!='')
                @if(file_exists('storage/style/banner/'.$category->banner))
                <input type="hidden" name="old_banner" id="old_banner" value="{{ $category->banner}}" />
                    <img src="{{ asset('storage/style/banner/'.$category->banner) }}" class="img-responsive" alt="Category">
                    <div class="form-group">
                        <div class="has-error">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('remove_photo') !!} <strong>@lang('Remove Image')</strong>
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
            @endif 
            <br><br>
            </div>
        @endif
        </div>
         
     
       
            
            

            <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                {!! Form::label('name', __('Category Name:')) !!}
                {!! Form::text('name', $category->name, ['class'=>'form-control', 'placeholder'=>__('Enter category name'), 'required'])!!}
            </div>
            {!! Form::hidden('parent', 0, []) !!}
           

            <div class="form-group{{ $errors->has('priority') ? ' has-error' : '' }}">
                {!! Form::label('priority', __('Order No: (to display under Menus)'))  !!}
                {!! Form::number('priority', isset($category->priority) ? $category->priority : 1, ['class'=>'form-control', 'placeholder'=>__('Enter order by'), 'min'=>'1']) !!}
            </div>

            <div class="form-group{{ $errors->has('top_category_priority') ? ' has-error' : '' }}">
                    {!! Form::label('top_category_priority', __('Order No: (to display under Top Categories Home Page)'))  !!}
                    {!! Form::number('top_category_priority', isset($category->top_category_priority) ? $category->top_category_priority : 1, ['class'=>'form-control', 'placeholder'=>__('Enter order by top category'), 'min'=>'1']) !!}
            </div>

            <div class="form-group{{ $errors->has('min_price') ? ' has-error' : '' }}">
                {!! Form::label('min_price', __('Min Price:')) !!}
                {!! Form::number('min_price', old('min_price'), ['class'=>'form-control','step'=>'5000', 'placeholder'=>__('Enter Min Price')]) !!}
            </div>

            <div class="form-group{{ $errors->has('max_price') ? ' has-error' : '' }}">
                {!! Form::label('max_price', __('Max Price:')) !!}
                {!! Form::number('max_price', old('max_price'), ['class'=>'form-control','step'=>'5000', 'placeholder'=>__('Enter Max Price')]) !!}
            </div>

            

            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_in_slider" value="1" @if($category->show_in_slider) checked @endif> <strong>@lang('Show in Top Categories')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_price" value="1" @if($category->show_filter_price) checked @endif> <strong>@lang('Show Filter Price')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_metal" value="1" @if($category->show_filter_metal) checked @endif> <strong>@lang('Show Filter Metal')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_purity" value="1" @if($category->show_filter_purity) checked @endif> <strong>@lang('Show Filter Purity')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_gender" value="1" @if($category->show_filter_gender) checked @endif> <strong>@lang('Show Filter Gender')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_offers" value="1" @if($category->show_filter_offers) checked @endif> <strong>@lang('Show Filter Offers')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_short_by" value="1" @if($category->show_filter_short_by) checked @endif> <strong>@lang('Show Filter Sort By')</strong>
                </label>
                
            </div>


            
            
            <div class="form-group">
                {!! Form::label('category_img', __('Choose Category Image'), ['class'=>'btn btn-default btn-file']) !!}
                {!! Form::file('category_img',['class'=>'form-control categoryImage', 'style'=>'display: none;','onchange'=>'$("#upload-file-info1").html(files[0].name)']) !!}
                <span id="CatimgError"></span>
                <span class='label label-info' id="upload-file-info1">@lang('No image chosen')</span><em style="color:red;">(Dimensions:- 370*300)</em>
            </div>


            <div class="form-group">
            
                {!! Form::label('banner', __('Choose Banner Image'), ['class'=>'btn btn-default btn-file']) !!}
                {!! Form::file('banner',['class'=>'form-control bannerImage', 'style'=>'display: none;','onchange'=>'$("#upload-file-info").html(files[0].name)']) !!}
                <span id="imgError"></span>
                <span class='label label-info' id="upload-file-info">@lang('No image chosen')</span> <em style="color:red;">(Dimensions:- 1600*400)</em>
            </div> 

            <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                {!! Form::label('status', __('Status:')) !!}
                {!! Form::select('status', [0=>__('inactive'), 1=>__('active')], $category->is_active, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
            </div>

            @include('partials.manage.meta-fields')

            <div class="form-group">
                {!! Form::submit(__('Update'), ['class'=>'btn btn-primary col-xs-6 col-sm-6 pull-left', 'id'=>'submit_button','name'=>'submit_button']) !!}
            </div>

        {!! Form::close() !!}

        

    </div>
    @if(count($root_categories) > 0)
            <div class="col-md-6">
                <h4 class="text-info">@lang('Categories Tree:')</h4>
                <ul id="tree1">
                    @foreach($root_categories as $category)
                    @php 
                        

                    $product =  \App\Product::whereHas('product_category_styles', function ($query) use($category)  {
                                    $query->where('category_id', $category->id);
                                })->where(function ($query) {
                                    $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                                })->where('is_active','1')->where('category_id', $category->id)->count();
                    @endphp
                        <li>
                            

                            {{ $category->name . ' ('.$product.')' }}
                            @if(count($category->categories))
                                @include('partials.manage.subcategories', ['childs' => $category->categories])
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
</div>
 