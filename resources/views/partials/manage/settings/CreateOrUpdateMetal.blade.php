<div class="row">

    <div class="col-xs-12 col-sm-8">

        @include('includes.form_errors')
        @if(isset($metal))

        {!! Form::open(['method'=>'post', 'action'=>'ManageSettingsController@updateMetal',  'method' => 'PUT','files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
        {!! Form::hidden('id', isset($metal->id) ? $metal->id : '' ) !!} 
        
        @else

            {!! Form::open(['method'=>'post', 'action'=>'ManageSettingsController@storeMetal', 'files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
        @endif
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', __('Name:')) !!}
            {!! Form::text('name', isset($metal->name) ? $metal->name : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Name"), 'required']) !!}
        </div>

        <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
            {!! Form::label('is_active', __('Status:')) !!}
            {!! Form::select('is_active', [ '1' => 'Active','2' => 'Inactive'], isset($metal->is_active) ? $metal->is_active : '', ['class'=>'form-control','placeholder' => 'Select Status']) !!}
        </div>

 
        <div class="form-group">
            @if(isset($metal))
                {!! Form::submit(__('Update Metal'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @else 
                {!! Form::submit(__('Add Metal'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @endif
            
        </div>

        {!! Form::close() !!}

    </div>
</div>