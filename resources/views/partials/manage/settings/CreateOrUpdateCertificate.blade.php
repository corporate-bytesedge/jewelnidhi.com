<div class="row">

    <div class="col-xs-12 col-sm-8">

        @include('includes.form_errors')
        @if(isset($certificates))

        {!! Form::open(['method'=>'post', 'action'=>'ManageSettingsController@updateCertificate',  'method' => 'PUT','files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
        {!! Form::hidden('id', isset($certificates->id) ? $certificates->id : '' ) !!} 
        
        @else

            {!! Form::open(['method'=>'post', 'action'=>'ManageSettingsController@storeCertificate', 'files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
        @endif
        <div class="form-group{{ $errors->has('author') ? ' has-error' : '' }}">
            {!! Form::label('name', __('Name:')) !!}
            {!! Form::text('name', isset($certificates->name) ? $certificates->name : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Name"), 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('image', __('Choose Certificate'), ['class'=>'btn btn-default btn-file']) !!}
            {!! Form::file('image',['class'=>'form-control', 'style'=>'display: none;','onchange'=>'$("#upload-file-info").html(files[0].name)']) !!}
            @if(isset($certificates->image) && $certificates->image!='')
            {!! Form::hidden('old_image', isset($certificates->image) ? $certificates->image : '' ) !!}  
                <img src="{{ asset('storage/certificate/'.$certificates->image) }}" height="50" width="50" class="center" >
            @endif
            
            <span class='label label-info' id="upload-file-info">@lang('No image chosen')</span>
        </div>

        <div class="form-group">
            @if(isset($certificates))
                {!! Form::submit(__('Update Certificate'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @else 
                {!! Form::submit(__('Add Certificate'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @endif
            
        </div>

        {!! Form::close() !!}

    </div>
</div>