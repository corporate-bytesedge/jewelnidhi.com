<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6">
        @if(session()->has('user_created_error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>{{session('user_created_error')}}</strong>
            </div>
        @endif
        @include('includes.form_errors')

        {!! Form::open(['method'=>'post', 'action'=>'ManageUsersController@store', 'files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', __('Name:')) !!} <em style="color:red;">*</em>
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>__('Enter name'), 'required'])!!}
        </div>
        @if(config('settings.phone_otp_verification'))
        <div class="form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
            {!! Form::label('phone-number',__('Phone:')) !!}<em style="color:red;">*</em>
            @if(isset($user->mobile))
                @if(isset($user->mobile->verified))
                <strong class="text-success">@lang('verified')</strong>
                @else
                <strong class="text-danger">@lang('unverified')</strong>
                @endif
            @endif
            {!! Form::text('phone_number', isset($user->mobile) ? $user->mobile->number : null, ['class'=>'form-control phone_number','pattern'=>"[789][0-9]{9}", 'maxlength'=>"10", 'placeholder'=>__('Enter phone number')]) !!}
        </div>
        @endif
        <!-- <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            {!! Form::label('username', __('Username:')) !!}
            {!! Form::text('username', null, ['class'=>'form-control', 'placeholder'=>__('Enter username'), 'required'])!!}
        </div> -->

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            {!! Form::label('email', __('Email:')) !!}<em style="color:red;">*</em>
            {!! Form::email('email', null, ['class'=>'form-control', 'placeholder'=>__('Enter email')]) !!}
        </div>

        <div class="form-group">
            {!! Form::label('role', __('Role:')) !!}
            {!! Form::select('role',  $roles, null, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('verified', __('Email/Phone Status:')) !!}
            {!! Form::select('verified', [true=>__('verified'), false=>__('unverified')], null, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('status', __('Status:')) !!}
            {!! Form::select('status', [0=>__('inactive'), 1=>__('active')], 1, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            {!! Form::label('password', __('Password:')) !!}<em style="color:red;">*</em>
            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>__('Enter password'), 'required']) !!}
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            {!! Form::label('password_confirmation', __('Confirm Password:')) !!}<em style="color:red;">*</em>
            {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>__('Enter password again'), 'required']) !!}
        </div>

        <!-- <div class="form-group">
            {!! Form::label('photo', __('Choose photo'), ['class'=>'btn btn-default btn-file']) !!}
            {!! Form::file('photo',['class'=>'form-control', 'style'=>'display: none;','onchange'=>'$("#upload-file-info").html(files[0].name)']) !!}
            <span class='label label-info' id="upload-file-info">@lang('No photo chosen')</span>
        </div> -->

        <div class="form-group">
            {!! Form::submit(__('Add User'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
        </div>

        {!! Form::close() !!}

    </div>
</div>