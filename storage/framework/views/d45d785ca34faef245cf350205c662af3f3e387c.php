

<?php $__env->startSection('title'); ?><?php echo app('translator')->getFromJson('Profile Settings'); ?> - <?php echo e(config('app.name')); ?><?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-title'); ?>
    <?php echo app('translator')->getFromJson('Profile Settings'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header-description'); ?>
    <?php echo app('translator')->getFromJson('View or Change Profile Settings'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <?php echo $__env->make('partials.phone_style', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <link href="<?php echo e(asset('css/front-sidebar-content.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
    <div class="row dashboard-page">
        <?php echo $__env->make('partials.front.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="col-md-9">
            <div class="content">
                <div class="page-title">
                    <h2><?php echo app('translator')->getFromJson('Profile Settings'); ?></h2>
                </div>
                <div class="card">
                       
                            <?php if(session()->has('profile_updated')): ?>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <?php echo e(session('profile_updated')); ?>

                                </div>
                            <?php endif; ?>
                            <?php echo $__env->make('includes.form_errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                            
                    <?php if(config('settings.phone_otp_verification')): ?>
                        <?php if(!$user->mobile): ?>
                        <!-- <div class="col-sm-8 col-sm-offset-2">
                            <div class="text-danger">
                                <strong>
                                <?php echo app('translator')->getFromJson('Please add your phone number.'); ?>
                                </strong>
                            </div>
                        </div> -->
                       
                        <?php elseif(!$user->mobile->verified && $user->mobile->number !=''): ?>
                        <div class="col-sm-8 col-sm-offset-2 mt-4">
                            <div class="verify-phone-block">
                                <span class="help-block">
                                    <strong class="text-danger">
                                        <?php echo app('translator')->getFromJson('Please verify your phone number.'); ?>
                                    </strong>
                                </span>
                                <?php echo Form::open(['method'=>'post', 'action'=>'SendVerificationSMS@sendOtp', 'id'=>'send-otp-form']); ?>

                                <div class="form-group">
                                    <?php echo Form::submit(__('Click here to verify your phone no.'), ['class'=>'btn-primary btn-link', 'name'=>'submit_button', 'id'=>'send-otp-btn']); ?>

                                </div>
                                <?php echo e(Form::close()); ?>

                                <div class="feedback-alert">
                                    <br>
                                    <div class="alert alert-info" role="alert">
                                        <span class="feedback"></span>
                                    </div>
                                </div>
                            </div>
                            <div id="verify-phone"></div>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if( (!empty($user->mobile) && $user->mobile->verified ) ||  empty($user->mobile) ): ?>
                    <div class="card-body  profile-setting-form">
                       
                        <?php echo Form::model($user, ['method'=>'patch', 'action'=>['FrontSettingsController@updateProfile'], 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;', 'id'=>'update-profile-form']); ?>


                        <div class="form-group<?php echo e($errors->has('name') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('name', __('Name:')); ?> <em style="color:red;">*</em>
                            <?php echo Form::text('name', null, ['class'=>'form-control', 'placeholder'=>__('Enter name'), 'required']); ?>

                        </div>

                        <div class="form-group<?php echo e($errors->has('username') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('username', __('Username:')); ?> <em style="color:red;">*</em>
                            <?php echo Form::text('username', null, ['class'=>'form-control', 'placeholder'=>__('Enter username'), 'required']); ?>

                        </div>

                        <div class="form-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('email', __('Email:')); ?> <em style="color:red;">*</em>
                            <?php echo Form::email('email', null, ['class'=>'form-control', 'placeholder'=>__('Enter email'), 'required']); ?>

                        </div>

                        <?php if(config('settings.phone_otp_verification')): ?>
                        <div class="form-group<?php echo e($errors->has('phone') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('phone','Phone Number:'); ?>

                            <input type="text" name="phone" id="phone-number1" class="form-control"
                                value="<?php echo e(old('phone') ? old('phone') : ($user->phone ? $user->phone : null)); ?>" 
                                placeholder="Enter your phone number">
                                <span id="phnerrormsg"></span>
                        </div>
                        <?php endif; ?>

                        <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('password', __('Password:')); ?>

                            <?php echo Form::password('password', ['class'=>'form-control', 'placeholder'=>__('Enter password')]); ?>

                        </div>

                        <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                            <?php echo Form::label('password_confirmation', __('Confirm Password:')); ?>

                            <?php echo Form::password('password_confirmation', ['class'=>'form-control', 'placeholder'=>__('Enter confirm password')]); ?>

                        </div>

                        <?php if(\Auth::user()->isApprovedVendor()): ?> 
                         
                            <hr>
                            <h4 class="profile_vendor_details">Vendor Details</h4>
                            <br/>
                            <div class="form-group<?php echo e($errors->has('shop_name') ? ' has-error' : ''); ?>">
                                <?php echo Form::label('shop_name', __('Shop Name:')); ?>

                                <?php echo Form::text('shop_name',isset(\Auth::user()->vendor->shop_name) ? \Auth::user()->vendor->shop_name : null, ['class'=>'form-control', 'placeholder'=>__('Enter shop name')]); ?>

                            </div>

                            <div class="form-group<?php echo e($errors->has('company_name') ? ' has-error' : ''); ?>">
                                <?php echo Form::label('company_name', __('Company Name:')); ?>

                                <?php echo Form::text('company_name',isset(\Auth::user()->vendor->name) ? \Auth::user()->vendor->name : null, ['class'=>'form-control', 'placeholder'=>__('Enter company name')]); ?>

                            </div>

                            <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                                <?php echo Form::label('description', __('Description:')); ?>

                                <?php echo Form::textarea('description',isset(\Auth::user()->vendor->description) ? \Auth::user()->vendor->description : null, ['class'=>'form-control', 'placeholder'=>__('Enter description')]); ?>

                            </div>

                            <div class="form-group<?php echo e($errors->has('address') ? ' has-error' : ''); ?>">
                                <?php echo Form::label('address', __('Address:')); ?>

                                <?php echo Form::text('address',isset(\Auth::user()->vendor->address) ? \Auth::user()->vendor->address : null, ['class'=>'form-control', 'placeholder'=>__('Enter address')]); ?>

                            </div>

                            <div class="form-group<?php echo e($errors->has('city') ? ' has-error' : ''); ?>">
                                <?php echo Form::label('city', __('City:')); ?>

                                <?php echo Form::text('city', isset(\Auth::user()->vendor->city) ? \Auth::user()->vendor->city : null, ['class'=>'form-control', 'placeholder'=>__('Enter city')]); ?>

                            </div>

                            <div class="form-group<?php echo e($errors->has('state') ? ' has-error' : ''); ?>">
                                <?php echo Form::label('state', __('State:')); ?>

                                <?php echo Form::text('state', isset(\Auth::user()->vendor->state) ? \Auth::user()->vendor->state : null, ['class'=>'form-control', 'placeholder'=>__('Enter state')]); ?>

                            </div>

                        <?php endif; ?>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block" name="submit_button" id="update_btn"> <?php echo app('translator')->getFromJson('Update Profile'); ?>
                            </button>

                        </div>

                        <?php echo Form::close(); ?>

                    </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('js/tinymce/tinymce.min.js')); ?> "></script>
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
                if (confirm('<?php echo app('translator')->getFromJson('You have requested to change your email. An activation link will be sent to'); ?> "' + newEmail + '". <?php echo app('translator')->getFromJson('You are going to logout. Click Ok to confirm.'); ?>')) {
                    updateProfileForm.submit();
                }
            } else {
                updateProfileForm.submit();
            }
        });
    </script>
    <?php echo $__env->make('partials.phone_script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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
                        var ajaxurl = '<?php echo e(route('auth.sms.verify.form')); ?>';
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>