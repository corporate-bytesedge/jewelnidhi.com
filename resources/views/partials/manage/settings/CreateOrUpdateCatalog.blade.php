<div class="row">

    <div class="col-xs-12 col-sm-8">

        @include('includes.form_errors')
        @if(isset($catalog))

        {{ Form::model($catalog, ['route' => ['manage.settings.update_catalog', $catalog->id],'method' => 'PUT','files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) }}
       
         
        
        @else
            {{ Form::open(array('route' => 'manage.settings.store_catalog','files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;')) }}
             
        @endif

         <div class="form-group">
            {!! Form::label('image', __('Choose Catalog'), ['class'=>'btn btn-default btn-file']) !!}
            {!! Form::file('image',['class'=>'form-control', 'style'=>'display: none;','onchange'=>'$("#upload-file-info").html(files[0].name)']) !!}
            @if(isset($catalog->image))
            {!! Form::hidden('old_image', isset($catalog->image) ? $catalog->image : '' ) !!}  
                <img src="{{ asset('storage/catalog/'.$catalog->image) }}" height="50" width="50" class="center" >
            @endif
            
            <span class='label label-info' id="upload-file-info">@lang('No image chosen')</span>
        </div>
         
        <div class="form-group{{ $errors->has('weight') ? ' has-error' : '' }}">
            {!! Form::label('sku', __('Product ID:')) !!}
            {!! Form::text('sku', isset($catalog->sku) ? $catalog->sku : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Product ID"), 'required']) !!}
        </div>

        <div class="form-group{{ $errors->has('weight') ? ' has-error' : '' }}">
            {!! Form::label('weight', __('Weight:')) !!}
            {!! Form::text('weight', isset($catalog->weight) ? $catalog->weight : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Weight"), 'required']) !!}
        </div>

        <div class="form-group{{ $errors->has('diamond_weight') ? ' has-error' : '' }}">
            {!! Form::label('diamond_weight', __('Diamond Weight:')) !!}
            {!! Form::text('diamond_weight', isset($catalog->diamond_weight) ? $catalog->diamond_weight : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Diamond Weight"), 'required']) !!}
        </div>

        <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
            {!! Form::label('is_active', __('Status:')) !!}
            {!! Form::select('is_active', [ '1' => 'Active','2' => 'Inactive'], isset($catalog->is_active) ? $catalog->is_active : '', ['class'=>'form-control','placeholder' => 'Select Status']) !!}
        </div>

 
        <div class="col-sm-4">
            @if(isset($catalog))
                {!! Form::submit(__('Update'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @else 
                {!! Form::submit(__('Add'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @endif
            
        </div>

        {!! Form::close() !!}

    </div>
</div>