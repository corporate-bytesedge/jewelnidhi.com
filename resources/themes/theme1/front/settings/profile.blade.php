@extends('layouts.front')

@section('title')@lang('Profile Settings') - {{config('app.name')}}@endsection

@section('page-header-title')
    @lang('Profile Settings')
@endsection

@section('page-header-description')
    @lang('View or Change Profile Settings')
@endsection

@section('styles')
    @include('partials.phone_style')
    <link href="{{asset('css/front-sidebar-content.css')}}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
    <div class="row dashboard-page">
        @include('partials.front.sidebar')
        <div class="col-md-9">
            <div class="content">
                <div class="page-title">
                    <h2>@lang('Profile Settings')</h2>
                </div>
                <div class="card">
                       
                            @if(session()->has('profile_updated'))
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    {{session('profile_updated')}}
                                </div>
                            @endif
                            @include('includes.form_errors')
                            
                    @if(config('settings.phone_otp_verification'))
                        @if(!$user->mobile)
                        <!-- <div class="col-sm-8 col-sm-offset-2">
                            <div class="text-danger">
                                <strong>
                                @lang('Please add your phone number.')
                                </strong>
                            </div>
                        </div> -->
                       
                        @elseif(!$user->mobile->verified && $user->mobile->number !='')
                        <div class="col-sm-8 col-sm-offset-2 mt-4">
                            <div class="verify-phone-block">
                                <span class="help-block">
                                    <strong class="text-danger">
                                        @lang('Please verify your phone number.')
                                    </strong>
                                </span>
                                {!! Form::open(['method'=>'post', 'action'=>'SendVerificationSMS@sendOtp', 'id'=>'send-otp-form']) !!}
                                <div class="form-group">
                                    {!! Form::submit(__('Click here to verify your phone no.'), ['class'=>'btn-primary btn-link', 'name'=>'submit_button', 'id'=>'send-otp-btn']) !!}
                                </div>
                                {{ Form::close() }}
                                <div class="feedback-alert">
                                    <br>
                                    <div class="alert alert-info" role="alert">
                                        <span class="feedback"></span>
                                    </div>
                                </div>
                            </div>
                            <div id="verify-phone"></div>
                        </div>
                        @endif
                    @endif
                    
                    @if( (!empty($user->mobile) && $user->mobile->verified ) ||  empty($user->mobile) )
                    <div class="card-body  profile-setting-form">
                       
                        {!! Form::model($user, ['method'=>'patch', 'action'=>['FrontSettingsController@updateProfile'], 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;', 'id'=>'update-profile-form']) !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            {!! Form::label('name', __('Name:')) !!} <em style="color:red;">*</em>
                            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>__('Enter name'), 'required'])!!}
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            {!! Form::label('username', __('Username:')) !!} <em style="color:red;">*</em>
                            {!! Form::text('username', null, ['class'=>'form-control', 'placeholder'=>__('Enter username'), 'required'])!!}
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            {!! Form::label('email', __('Email:')) !!} <em style="color:red;">*</em>
                            {!! Form::email('email', null, ['class'=>'form-control', 'placeholder'=>__('Enter email'), 'required']) !!}
                        </div>

                        @if(config('settings.phone_otp_verification'))
                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            {!! Form::label('phone','Phone Number:') !!}
                            <input type="text" name="phone" id="phone-number1" class="form-control"
                                value="{{old('phone') ? old('phone') : ($user->phone ? $user->phone : null) }}" 
                                placeholder="Enter your phone number">
                                <span id="phnerrormsg"></span>
                        </div>
                        @endif

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            {!! Form::label('password', __('Password:')) !!}
                            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=>__('Enter password')]) !!}
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            {!! Form::label('password_confirmation', __('Confirm Password:')) !!}
                            {!! Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>__('Enter confirm password')]) !!}
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

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block" name="submit_button" id="update_btn"> @lang('Update Profile')
                            </button>

                        </div>

                        {!! Form::close() !!}
                    </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/tinymce/tinymce.min.js') }} "></script>
<script>
  tinymce.init({
  selector: 'textarea#description',
  height: 200,
  menubar: false,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table paste code help wordcount'
  ],
  toolbar: 'undo redo | formatselect | ' +
  'bold italic backcolor | alignleft aligncenter ' +
  'alignright alignjustify | bullist numlist outdent indent | ' +
  'removeformat | help',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});
 
$('#phone-number1').keypress(function(e) {

if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
  $("#phnerrormsg").css('color','red');
  $("#phnerrormsg").html("Digits only").show().fadeOut("slow");
  return false;
}
if($(e.target).prop('value').length>=10) {
    if(e.keyCode!=32) {
    $("#phnerrormsg").css('color','red');
      $("#phnerrormsg").html("Allow 10 digits only").show().fadeOut("slow");
      return false;
    } 
}

});
        var updateProfileForm = $('#update-profile-form');
        var oldEmail = updateProfileForm.find('input[name="email"]').val();
        $(document).on('click', '#update_btn', function (event) {
            event.preventDefault();
            var newEmail = updateProfileForm.find('input[name="email"]').val();
            if (oldEmail != newEmail && newEmail!='') {
                if (confirm('@lang('You have requested to change your email. An activation link will be sent to') "' + newEmail + '". @lang('You are going to logout. Click Ok to confirm.')')) {
                    updateProfileForm.submit();
                }
            } else {
                updateProfileForm.submit();
            }
        });
    </script>
    @include('partials.phone_script')
    <script>
    // Off Canvas Open close
        $(".mobile-menu-btn").on('click', function () {
            $("body").addClass('fix');
            $(".off-canvas-wrapper").addClass('open');
        });

      $(".btn-close-off-canvas,.off-canvas-overlay").on('click', function () {
        $("body").removeClass('fix');
        $(".off-canvas-wrapper").removeClass('open');
      });

    
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var sendOtpBtn = $('#send-otp-btn');
        var feedback = $('.feedback');
        var feedbackAlert = $('.feedback-alert');
        feedbackAlert.hide();
        $(document).on('submit', '#send-otp-form', function (e) {

            e.preventDefault();
            var data = $(this).serialize();
            var url = $(this).attr('action');

            sendOtpBtn.attr("disabled", "disabled");

            $.post(url, data, function (receivedData) {
                if (!receivedData.error) {
                    if (receivedData.sent) {
                        feedback.html('Verification code was sent to ' + receivedData.number);
                        var ajaxurl = '{{route('auth.sms.verify.form')}}';
                        $.ajax({
                            url: ajaxurl,
                            type: "GET",
                            success: function (data) {
                                $data = $(data);
                                $('#verify-phone').hide().html($data).fadeIn();
                            }
                        });
                    } else {
                        if (receivedData.message) {
                            feedback.html(receivedData.message);
                        } else {
                            feedback.html('Error occurred while sending verification code. Please try again after some time.');
                        }
                    }
                    feedbackAlert.show();
                } else {
                    feedbackAlert.show();
                    feedback.html('Something went wrong.');
                }

                sendOtpBtn.removeAttr("disabled");
            });

        });
    </script>
@endsection
