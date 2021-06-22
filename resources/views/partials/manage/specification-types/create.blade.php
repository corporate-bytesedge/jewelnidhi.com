<div class="col-md-4">
    @if(session()->has('specification_type_created'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>{{session('specification_type_created')}}</strong>
        </div>
    @endif

    @include('includes.form_errors')

    {!! Form::open(['method'=>'post', 'action'=>'ManageSpecificationTypesController@store', 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}

    <!-- <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::label('metal_id', __('Metal:')) !!}
        {!! Form::select('metal_id', [''=>'Select Metal','2' => 'Gold', '12' => 'Gemstone','13' => 'Silver','14' => 'Diamond'],null, ['class'=>'form-control'], ['placeholder' => 'Select Metal']) !!}
         
    </div> -->
    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::label('name', __('Name:')) !!}
        {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>__('Example: Color, Size, Weight'), 'required']) !!}
    </div>

    <div class="form-group">
        {!! Form::submit(__('Add Specification Type'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
    </div>

    {!! Form::close() !!}

</div>