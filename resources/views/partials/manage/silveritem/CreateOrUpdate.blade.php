<div class="row">

    <div class="col-xs-12 col-sm-8">
 
        @include('includes.form_errors')
        @if(isset($silveritem))
        {!!  Form::model($silveritem, ['route' => ['manage.silveritem.update', $silveritem->id],'method'=>'PUT' ] )!!} 
         
        
        @else

            {!! Form::open(['method'=>'post', 'action'=>'ManageSilverItemController@store', 'files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
        @endif
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', __('Name:')) !!}
            {!! Form::text('name', isset($silveritem->name) ? $silveritem->name : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Name"), 'required']) !!}
        </div>

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('rate', __('Gram Rate:')) !!}
            {!! Form::text('rate', isset($silveritem->rate) ? $silveritem->rate : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Gram Rate"), 'required']) !!}
        </div>

         

 
        <div class="form-group">
            @if(isset($silveritem))
                {!! Form::submit(__('Update Silver Item'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @else 
                {!! Form::submit(__('Add Silver Item'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @endif
            
        </div>

        {!! Form::close() !!}

    </div>
</div>