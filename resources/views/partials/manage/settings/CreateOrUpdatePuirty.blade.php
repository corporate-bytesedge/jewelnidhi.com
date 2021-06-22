<div class="row">

    <div class="col-xs-12 col-sm-8">

        @include('includes.form_errors')
        @if(isset($puirty))

        {!! Form::open(['method'=>'post', 'action'=>'ManageSettingsController@updateMetalPuirty',  'method' => 'PUT','files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
        {!! Form::hidden('id', isset($puirty->id) ? $puirty->id : '' ) !!} 
        
        @else

            {!! Form::open(['method'=>'post', 'action'=>'ManageSettingsController@storeMetalPuirty', 'files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
        @endif

        <div class="form-group{{ $errors->has('metal_id') ? ' has-error' : '' }}">
            {!! Form::label('metal', __('Metal:')) !!}
            {!! Form::select('metal_id', $metals, null, ['class'=>'form-control','placeholder' => 'Select Metal', 'required'] ); !!}
        </div>

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', __('Name:')) !!}
            {!! Form::text('name', isset($puirty->name) ? $puirty->name : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Name"), 'required']) !!}
        </div>

        <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
            {!! Form::label('is_active', __('Status:')) !!}
            {!! Form::select('is_active', [ '1' => 'Active','2' => 'Inactive'], isset($puirty->is_active) ? $puirty->is_active : '', ['class'=>'form-control','placeholder' => 'Select Status']) !!}
        </div>

 
        <div class="col-sm-4">
            @if(isset($puirty))
                {!! Form::submit(__('Update'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @else 
                {!! Form::submit(__('Add'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @endif
            
        </div>

        {!! Form::close() !!}

    </div>
</div>