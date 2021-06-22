<div class="row">
    <div class="col-xs-12 col-sm-10 col-md-8">

        @if(session()->has('vendor_created'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>{{session('vendor_created')}}</strong>
            </div>
        @endif

        @if(session()->has('vendor_not_created'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>{{session('vendor_not_created')}}</strong>
            </div>
        @endif

        @include('includes.form_errors')

        {!! Form::open(['method'=>'post', 'action'=>'ManageVendorsController@store', 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}

        <!-- <h4>@lang('User Details')</h4> -->
        <br>
        <!-- <div class="form-group">
            <div class="user_box">
                <div class="checkbox">
                    <label>
                        <input id="existing_user" name="existing_user" type="checkbox">
                        @lang('Select From Existing Users To Promote as Vendor:')
                    </label>
                </div>
            </div>
        </div> -->
        <!-- <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            {!! Form::label('username', __('Username:')) !!}
            {!! Form::text('username', null, ['class'=>'form-control', 'placeholder'=>__('Enter username'), 'required'])!!}
            <span class="text-danger" id="no_username" style="display: none;">@lang('Entered username not found or is already a vendor')</span>
        </div> -->
        <div id="existing_form">
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                {!! Form::label('name', __('Name:')) !!} <em style="color:red;">*</em>
                {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=>__('Enter name'), 'required'])!!}
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                {!! Form::label('email', __('Email:')) !!} <em style="color:red;">*</em>
                {!! Form::email('email', old('email'), ['class'=>'form-control', 'placeholder'=>__('Enter email')]) !!}
            </div>

            <div class="form-group">
                {!! Form::label('verified', __('Email Status:')) !!}
                {!! Form::select('verified', [true=>__('verified'), false=>__('unverified')], null, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                {!! Form::label('password', __('Password:')) !!} <em style="color:red;">*</em>
                {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>__('Enter password'), 'required']) !!}
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                {!! Form::label('password_confirmation', __('Confirm Password:')) !!} <em style="color:red;">*</em>
                {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>__('Enter confirm password'), 'required']) !!}
            </div>

            
        </div>
        <hr>
        <h4>@lang('Vendor Details')</h4>
        <br>

        <div class="form-group{{ $errors->has('shop_name') ? ' has-error' : '' }}">
            {!! Form::label('shop_name', __('Shop Name:')) !!} <em style="color:red;">*</em>
            {!! Form::text('shop_name', old('shop_name'), ['class'=>'form-control', 'placeholder'=>__('Enter shop name'), 'required'])!!}
        </div>

        <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
            {!! Form::label('company_name', __('Company Name:')) !!} <em style="color:red;">*</em>
            {!! Form::text('company_name', old('company_name'), ['class'=>'form-control', 'placeholder'=>__('Enter company name'), 'required'])!!}
        </div>

        <div class="form-group{{ $errors->has('phone_number') ? ' has-error' : '' }}">
            {!! Form::label('phone_number', __('Phone:')) !!} <em style="color:red;">*</em>
            {!! Form::text('phone_number', old('phone_number'), ['class'=>'form-control phone_number','id'=>'phone_number', 'maxlength'=>"10", 'placeholder'=>__('Enter phone number')])!!}
        </div>

        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            {!! Form::label('description', __('Description:')) !!}
            {!! Form::textarea('description', old('description'), ['class'=>'form-control', 'placeholder'=>__('Enter description'), 'rows'=>'6'])!!}
        </div>

        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
            {!! Form::label('address', __('Address:')) !!}
            {!! Form::text('address', old('address'), ['class'=>'form-control', 'placeholder'=>__('Enter address')])!!}
        </div>

        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
            {!! Form::label('city', __('City:')) !!}
            {!! Form::text('city', old('city'), ['class'=>'form-control', 'placeholder'=>__('Enter city')])!!}
        </div>

        <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
            {!! Form::label('state', __('State:')) !!}
            {!! Form::text('state', old('state'), ['class'=>'form-control', 'placeholder'=>__('Enter state')])!!}
        </div>

        <!-- <div class="form-group{{ $errors->has('amount_percentage_per_sale') ? ' has-error' : '' }}">
            {!! Form::label('amount_percentage_per_sale', __('Percentage of amount per sale that this vendor gets:')) !!}
            {!! Form::number('amount_percentage_per_sale', null, ['class'=>'form-control', 'step'=>'any', 'placeholder'=>__('Enter percentage of amount per sale that this vendor gets'), 'required']) !!}
        </div> -->

        <!-- <div class="form-group">
            {!! Form::label('profile_status', __('Profile Status:')) !!}
            {!! Form::select('profile_status', [0=>__('Incomplete'), 1=>__('Completed')], null, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
        </div> -->
        <div class="form-group">
                {!! Form::label('status', __('Status:')) !!}
                {!! Form::select('status', [0=>__('inactive'), 1=>__('active')], 1, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
            </div>
        <!-- <div class="form-group">
            {!! Form::label('account_status', __('Account Status:')) !!}
            {!! Form::select('account_status', [0=>__('Pending'), 1=>__('Approved')], null, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
        </div> -->

        <div class="form-group">
            {!! Form::submit(__('Add Vendor'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
        </div>

        {!! Form::close() !!}
    </div>
</div>
