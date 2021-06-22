<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6">

        @if(session()->has('profile_updated'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>{{session('profile_updated')}}</strong>
            </div>
        @endif

        @include('includes.form_errors')

        {!! Form::model($user, ['method'=>'patch', 'action'=>['ManageSettingsController@updateProfile'], 'files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}

        <!-- @if($user->photo)
            <div class="form-group">
                <div class="has-error">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('remove_photo') !!} <strong>@lang('Remove Photo')</strong>
                        </label>
                    </div>
                </div>
            </div>
        @endif -->

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', __('Name:')) !!}
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>__('Enter name'), 'required'])!!}
        </div>

        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            {!! Form::label('username', __('Username:')) !!}
            {!! Form::text('username', null, ['class'=>'form-control', 'placeholder'=>__('Enter username'), 'required'])!!}
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            {!! Form::label('email', __('Email:')) !!}
            {!! Form::email('email', null, ['class'=>'form-control', 'placeholder'=>__('Enter email'), 'required']) !!}
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            {!! Form::label('password', __('Password:')) !!}
            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>__('Enter password')]) !!}
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            {!! Form::label('password_confirmation', __('Confirm Password:')) !!}
            {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>__('Enter password again')]) !!}
        </div>

        @if(\Auth::user()->isApprovedVendor()) 
                         
        <hr>
        <h4 class="profile_vendor_details">Vendor Details</h4>
        <br/>
        <div class="form-group{{ $errors->has('shop_name') ? ' has-error' : '' }}">
            {!! Form::label('shop_name', __('Shop Name:')) !!}
            {!! Form::text('shop_name',isset(\Auth::user()->vendor->shop_name) ? \Auth::user()->vendor->shop_name : null, ['class'=>'form-control', 'placeholder'=>__('Enter shop name')]) !!}
        </div>

        <div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
            {!! Form::label('company_name', __('Company Name:')) !!}
            {!! Form::text('company_name',isset(\Auth::user()->vendor->name) ? \Auth::user()->vendor->name : null, ['class'=>'form-control', 'placeholder'=>__('Enter company name')]) !!}
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            {!! Form::label('description', __('Description:')) !!}
            {!! Form::textarea('description',isset(\Auth::user()->vendor->description) ? \Auth::user()->vendor->description : null, ['class'=>'form-control', 'placeholder'=>__('Enter description')]) !!}
        </div>

        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
            {!! Form::label('address', __('Address:')) !!}
            {!! Form::text('address',isset(\Auth::user()->vendor->address) ? \Auth::user()->vendor->address : null, ['class'=>'form-control', 'placeholder'=>__('Enter address')]) !!}
        </div>

        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
            {!! Form::label('city', __('City:')) !!}
            {!! Form::text('city', isset(\Auth::user()->vendor->city) ? \Auth::user()->vendor->city : null, ['class'=>'form-control', 'placeholder'=>__('Enter city')]) !!}
        </div>

        <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
            {!! Form::label('state', __('State:')) !!}
            {!! Form::text('state', isset(\Auth::user()->vendor->state) ? \Auth::user()->vendor->state : null, ['class'=>'form-control', 'placeholder'=>__('Enter state')]) !!}
        </div>

    @endif

        <div class="row col-md-6">
            {!! Form::submit(__('Update Profile'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
        </div>

        {!! Form::close() !!}

    </div>
</div>