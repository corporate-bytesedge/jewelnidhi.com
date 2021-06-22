
<div class="col-md-6">
    @if(session()->has('category_created'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>{{session('category_created')}}</strong>
        </div>
    @endif

    @include('includes.form_errors')

    {!! Form::open(['method'=>'post', 'action'=>'ManageCategoriesController@store','id'=>'category_form', 'files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}

    <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
        {!! Form::label('name', __('Category Name:')) !!}
        {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>__('Enter category name'), 'required']) !!}
    </div>
    {!! Form::hidden('parent', 0, []) !!}
    

    <div class="form-group{{ $errors->has('priority') ? ' has-error' : '' }}">
    {!! Form::label('priority', __('Order No: (to display under Menus)'))  !!}
        {!! Form::number('priority', 1, ['class'=>'form-control', 'placeholder'=>__('Enter order by'), 'min'=>'1']) !!}
    </div>

    <div class="form-group{{ $errors->has('top_category_priority') ? ' has-error' : '' }}">
        {!! Form::label('top_category_priority', __('Order No: (to display under Top Categories Home Page)'))  !!}
        {!! Form::number('top_category_priority', 1, ['class'=>'form-control', 'placeholder'=>__('Enter order by top category'), 'min'=>'1']) !!}
    </div>

    <div class="form-group{{ $errors->has('min_price') ? ' has-error' : '' }}">
        {!! Form::label('min_price', __('Min Price:')) !!}
        {!! Form::number('min_price', null, ['class'=>'form-control','step'=>'5000', 'placeholder'=>__('Enter Min Price')]) !!}
    </div>

    <div class="form-group{{ $errors->has('max_price') ? ' has-error' : '' }}">
        {!! Form::label('max_price', __('Max Price:')) !!}
        {!! Form::number('max_price', null, ['class'=>'form-control','step'=>'5000', 'placeholder'=>__('Enter Max Price')]) !!}
    </div>

    <!-- <div class="form-group{{ $errors->has('above_price') ? ' has-error' : '' }}">
        {!! Form::label('above_price', __('Above Max Price:')) !!}
        {!! Form::number('above_price', null, ['class'=>'form-control', 'placeholder'=>__('Enter Above Price'), 'min'=>'1']) !!}
    </div> -->

    <!-- <div class="checkbox">
        <label>
            <input type="checkbox" name="show_in_menu"> <strong>@lang('Show in Main Menu')</strong>
        </label>
    </div> -->

    <!-- <div class="checkbox">
        <label>
            <input type="checkbox" name="show_in_footer"> <strong>@lang('Show in Footer Menu')</strong>
        </label>
    </div> -->

    <div class="checkbox">
        <label>
            <input type="checkbox" name="show_in_slider" value="1"> <strong>@lang('Show in Top Categories')</strong>
        </label>
    </div>

    <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_price" value="1">  <strong>@lang('Show Filter Price')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_metal" value="1"> <strong>@lang('Show Filter Metal')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_purity" value="1"> <strong>@lang('Show Filter Purity')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_gender" value="1"> <strong>@lang('Show Filter Gender')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_offers" value="1"> <strong>@lang('Show Filter Offers')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_short_by" value="1"> <strong>@lang('Show Filter Sort By')</strong>
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
                {!! Form::select('status', [0=>__('inactive'), 1=>__('active')], 1, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
            </div>


    


   

    @include('partials.manage.meta-fields')

    <div class="form-group">
        {!! Form::submit(__('Add Category'), ['class'=>'btn btn-primary col-xs-6 col-sm-6 pull-left', 'id'=>'submit_button','name'=>'submit_button']) !!}
    </div>

    {!! Form::close() !!}

</div>