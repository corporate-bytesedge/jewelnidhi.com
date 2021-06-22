<div class="row">

    <div class="col-xs-12 col-sm-8">

        @if(session()->has('coupon_updated'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>{{session('coupon_updated')}}</strong>
            </div>
        @endif

        @include('includes.form_errors')

        <!-- @can('read', App\Location::class)
        <div class="row">
            <div class="col-md-6 col-sm-8 col-xs-12">
                @lang('Location:') 
                <strong>
                    @if($coupon->location)
                        {{$coupon->location->name}}
                    @else
                        @lang('None')
                    @endif
                </strong>
            <br><br>
            </div>
        </div>
        @endcan -->

        {!! Form::model($coupon, ['method'=>'patch', 'action'=>['ManageCouponsController@update', $coupon->id], 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', __('Coupon Name:')) !!}
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>__('Enter coupon name'), 'required'])!!}
        </div>

        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
            {!! Form::label('code', __('Coupon Code:')) !!}
            {!! Form::text('code', null, ['class'=>'form-control', 'placeholder'=>__('Enter coupon code'), 'required'])!!}
        </div>

        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            {!! Form::label('description', __('Description:')) !!}
            {!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>__('Enter description')]) !!}
        </div>
      
       
        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
            {!! Form::label('type', __('Discount:')) !!}&nbsp;
            Flat Rate :  <input type ="radio" name ="type" class="discountRadio" id="flat_rate"  <?php echo $coupon->type == 0 ? 'checked' : '' ?>  value="0" > &nbsp;&nbsp;
            Percentage : <input type ="radio" name ="type" class="discountRadio" id="percentage"  <?php echo $coupon->type == 1 ? 'checked' : '' ?>  value="1 " >
            
        </div>
          
        <div  class="form-group{{ $errors->has('discount_percentage') ? ' has-error' : '' }}" style="{{ isset($coupon->type) && $coupon->type == 1 ? 'display:block;' :'display:none;' }}" id="discount_percentage">
            {!! Form::label('discount_percentage', __('Discount Percent:')) !!}
            {!! Form::number('discount_percentage', null, ['class'=>'form-control', 'placeholder'=>__('Enter discount percent')]) !!}
        </div>

        <div  class="form-group{{ $errors->has('discount_amount') ? ' has-error' : '' }}" style=" {{ isset($coupon->type) && $coupon->type  == 0  ? 'display:block;' :'display:none;' }}" id="discount_flat_rate">
            {!! Form::label('discount_amount', __('Discount Amount:')) !!}
            {!! Form::number('discount_amount', null, ['class'=>'form-control', 'id'=>'discount_amount',  'placeholder'=>__('Enter discount amount')]) !!}
        </div>

        <div  class="form-group{{ $errors->has('use_coupon_one_time') ? ' has-error' : '' }}"  id="">
            {!! Form::label('use_coupon_one_time', __('One time use  :')) !!}
            {!! Form::checkbox('use_coupon_one_time', 1, $coupon->use_coupon_one_time !='' ?  true : false) !!}
        </div>


        <div class="form-group{{ $errors->has('starts_at') ? ' has-error' : '' }}">
            {!! Form::label('starts_at', __('Starting Date and Time:')) !!}
            {!! Form::text('starts_at', null, ['class'=>'form-control', 'placeholder'=>__('Select starting date and time'), 'required', 'id' =>'datetimepicker_start'])!!}
        </div>

        <div class="form-group{{ $errors->has('expires_at') ? ' has-error' : '' }}">
            {!! Form::label('expires_at', __('Ending Date and Time:')) !!}
            {!! Form::text('expires_at', null, ['class'=>'form-control', 'placeholder'=>__('Select ending date and time'), 'required', 'id' =>'datetimepicker_end'])!!}
        </div>

        <div class="form-group{{ $errors->has('max_uses_user') ? ' has-error' : '' }}">
            {!! Form::label('valid_above_amount', __('Amount above which Coupon is Valid:')) !!}
            {!! Form::number('valid_above_amount', null, ['class'=>'form-control', 'step'=>'any', 'placeholder'=>__('Enter amount above which coupon is valid'), 'min'=>0]) !!}
        </div>

        <div class="form-group">
            {!! Form::submit(__('Update Coupon'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
        </div>

        {!! Form::close() !!}

    </div>
</div>