
 
<div class="row">

    <div class="col-xs-12 col-sm-8">

        @include('includes.form_errors')
        @if(isset($style))
            {{ Form::model($style, ['route' => ['manage.settings.update_style', $style->id],'method' => 'PUT','files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) }}
         
        
        @else
            {{ Form::open(array('route' => 'manage.settings.store_style','files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;')) }}
             
        @endif
         
        @if(isset($style->image) && $style->image!='')
                <img src="{{ asset('storage/style/'.$style->image) }}" height="200" width="200" class="img-responsive" alt="Category">
                <div class="form-group">
                    <div class="has-error">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('remove_photo') !!} <strong>@lang('Remove Icon Image')</strong>
                            </label>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($style->category_img) && $style->category_img!='')
                <img src="{{ asset('storage/style/topcategory/'.$style->category_img) }}" height="200" width="200" class="img-responsive" alt="Category">
                <div class="form-group">
                    <div class="has-error">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('remove_category_photo') !!} <strong>@lang('Remove Style Image')</strong>
                            </label>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($style->banner) && $style->banner!='')
                <img src="{{ asset('storage/style/banner/'.$style->banner) }}"  height="200" width="200" class="img-responsive" alt="Category">
                <div class="form-group">
                    <div class="has-error">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('remove_banner') !!} <strong>@lang('Remove Banner Image')</strong>
                            </label>
                        </div>
                    </div>
                </div>
            @endif
        <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
            {!! Form::label('name', __('Category :')) !!} <em style="color:red;">*</em>
            {!! Form::select('category_id', $category, null, ['class'=>'form-control','placeholder' => 'Select Category', 'required'] ); !!}
        </div>
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', __('Name:')) !!}<em style="color:red;">*</em>
            {!! Form::text('name', isset($style->name) ? $style->name : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Name"), 'required']) !!}
        </div>

        <div class="form-group{{ $errors->has('hsn_code') ? ' has-error' : '' }}">
            {!! Form::label('hsn_code', __('HSN Code:')) !!}<em style="color:red;">*</em>
            {!! Form::text('hsn_code', isset($style->hsn_code) ? $style->hsn_code : '' , ['class'=>'form-control', 'placeholder'=>__("Enter HSN Code"), 'required']) !!}
        </div>
        
        <!-- <div class="checkbox">
            <label>
                <input type="checkbox" name="show_in_slider" {{ isset($style->show_in_slider) && $style->show_in_slider=='1' ? 'checked="checked"' : '' }} value="1"> <strong>@lang('Show in Top Categories')</strong>
            </label>
        </div> -->
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_in_slider" value="1" @if(isset($style->show_in_slider) && $style->show_in_slider) checked @endif> <strong>@lang('Show in Top Categories')</strong>
                </label>
                
            </div>

             <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_price" value="1" @if(isset($style->show_filter_price) && $style->show_filter_price) checked @endif> <strong>@lang('Show Filter Price')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_metal" value="1" @if(isset($style->show_filter_metal) && $style->show_filter_metal) checked @endif> <strong>@lang('Show Filter Metal')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_purity" value="1" @if(isset($style->show_filter_purity) && $style->show_filter_purity) checked @endif> <strong>@lang('Show Filter Purity')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_gender" value="1" @if(isset($style->show_filter_gender) && $style->show_filter_gender) checked @endif> <strong>@lang('Show Filter Gender')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_offers" value="1" @if(isset($style->show_filter_offers) && $style->show_filter_offers) checked @endif> <strong>@lang('Show Filter Offers')</strong>
                </label>
                
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="show_filter_short_by" value="1" @if(isset($style->show_filter_short_by) && $style->show_filter_short_by) checked @endif> <strong>@lang('Show Filter Sort By')</strong>
                </label>
                
            </div>
         
        <div class="form-group{{ $errors->has('priority') ? ' has-error' : '' }}">
            {!! Form::label('priority', __('Order No: (to display under Menus)'))  !!}
            {!! Form::text('priority', isset($style->priority) && $style->priority!='0'  ? $style->priority : '1', ['class'=>'form-control', 'placeholder'=>__("Enter Order By")]) !!}
        </div>
        <div class="form-group{{ $errors->has('top_category_priority') ? ' has-error' : '' }}">
            {!! Form::label('top_category_priority', __('Order No: (to display under Top Categories Home Page)'))  !!}
            {!! Form::text('top_category_priority', isset($style->top_category_priority) && $style->top_category_priority!= null ? $style->top_category_priority : 1, ['class'=>'form-control', 'placeholder'=>__("Enter order by top category")]) !!}
        </div>

        <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
            {!! Form::label('is_active', __('Status:')) !!}<em style="color:red;">*</em>
            {!! Form::select('is_active', [ '1' => 'Active','2' => 'Inactive'], isset($style->is_active) ? $style->is_active : '', ['class'=>'form-control','placeholder' => 'Select Status','required'=>true]) !!}
        </div>

        <div class="form-group">
            {!! Form::label('icon', __('Choose Style Icon'), ['class'=>'btn btn-default btn-file']) !!}
            {!! Form::file('icon',['class'=>'form-control', 'style'=>'display: none;','onchange'=>'$("#upload-file-info").html(files[0].name)']) !!}
            @if(isset($style->image) && $style->image!='')
            {!! Form::hidden('old_icon', isset($style->image) ? $style->image : '' ) !!}  
                <img src="{{ asset('storage/style/'.$style->image) }}" height="50" width="50" class="center" >
            @endif
            
            <span class='label label-info' id="upload-file-info">@lang('No style icon chosen')</span>
        </div>

        <div class="form-group">
            {!! Form::label('style_image', __('Choose Image'), ['class'=>'btn btn-default btn-file']) !!}
            {!! Form::file('style_image',['class'=>'form-control styleImage', 'style'=>'display: none;','onchange'=>'$("#upload-file-info2").html(files[0].name)']) !!}
            @if(isset($style->category_img) && $style->category_img!='')
            {!! Form::hidden('old_style_image', isset($style->category_img) ? $style->category_img : '' ) !!}  
                <img src="{{ asset('storage/style/topcategory/'.$style->category_img) }}" height="50" width="50" class="center" >
            @endif
            <span id="styleimgError"></span>
            <span class='label label-info' id="upload-file-info">@lang('No image chosen')</span><em style="color:red;">(Dimensions:- 370*300)</em>
        </div>

        <div class="form-group">
            {!! Form::label('banner', __('Choose Banner'), ['class'=>'btn btn-default btn-file']) !!}
            {!! Form::file('banner',['class'=>'form-control bannerImage', 'style'=>'display: none;','onchange'=>'$("#upload-file-info1").html(files[0].name)']) !!}
            @if(isset($style->banner) && $style->banner!='')
            {!! Form::hidden('old_banner', isset($style->banner) ? $style->banner : '' ) !!}  
                <img src="{{ asset('storage/style/banner/'.$style->banner) }}" height="50" width="50" class="center" >
            @endif
            <span id="imgError"></span>
            <span class='label label-info' id="upload-file-info1">@lang('No image chosen')</span><em style="color:red;">(Dimensions:- 1600*400)</em>
        </div>

        <div class="form-group">
            @if(isset($style))
                {!! Form::submit(__('Update Style'), ['class'=>'btn btn-primary btn-block', 'id'=>'submit_button', 'name'=>'submit_button']) !!}
            @else 
                {!! Form::submit(__('Add Style'), ['class'=>'btn btn-primary btn-block','id'=>'submit_button', 'name'=>'submit_button']) !!}
            @endif
            
        </div>

        {!! Form::close() !!}

    </div>
</div>