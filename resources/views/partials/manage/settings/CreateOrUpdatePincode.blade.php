<div class="row">

    <div class="col-xs-12 col-sm-8">

        @include('includes.form_errors')
        @if(isset($pincode))

            {{ Form::model($pincode, ['route' => ['manage.settings.update_pincode', $pincode->id],'method' => 'PUT','files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) }}
         
        
        @else
            {{ Form::open(array('route' => 'manage.settings.store_pincode','files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;')) }}
             
        @endif
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', __('Pincode:')) !!}
            {!! Form::text('name', isset($pincode->name) ? $pincode->name : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Pincode"), 'required']) !!}
        </div>

        <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
            {!! Form::label('is_active', __('Status:')) !!}
            {!! Form::select('is_active', [ '1' => 'Active','2' => 'Inactive'], isset($pincode->is_active) ? $pincode->is_active : '', ['class'=>'form-control','placeholder' => 'Select Status']) !!}
        </div>

 
        <div class="form-group">
            @if(isset($pincode))
                {!! Form::submit(__('Update Pincode'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @else 
                {!! Form::submit(__('Add Pincode'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @endif
            
        </div>

        {!! Form::close() !!}

    </div>
</div>