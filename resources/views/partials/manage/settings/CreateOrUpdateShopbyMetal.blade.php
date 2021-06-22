<div class="row">

    <div class="col-xs-12 col-sm-8">

        @include('includes.form_errors')
        
        @if(isset($shop_by_metal_stone))

            {{ Form::model($shop_by_metal_stone, ['route' => ['manage.settings.update_shop_by_metal', $shop_by_metal_stone->id],'method' => 'PUT','files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) }}
            {!! Form::hidden('id', isset($shop_by_metal_stone->id) ? $shop_by_metal_stone->id : '' ) !!}
        
        @else
            {{ Form::open(array('route' => 'manage.settings.store_shop_by_metal','files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;')) }}
             
        @endif
        <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
            {!! Form::label('category_id', __('Category:')) !!}<em style="color:red;">*</em>
            {!! Form::select('category_id', $categories, null, ['class'=>'form-control','placeholder' => 'Select Category', 'required'] ); !!}
        </div>

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', __('Name:')) !!}<em style="color:red;">*</em>
            {!! Form::text('name', isset($shop_by_metal_stone->name) ? $shop_by_metal_stone->name : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Name"), 'required']) !!}
        </div>

        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
            {!! Form::label('image', __('Icon:')) !!}<em style="color:red;">*</em>
            {!! Form::file('image', null , ['class'=>'form-control']) !!}
            @if(isset($shop_by_metal_stone->image))
            {!! Form::hidden('old_image', isset($shop_by_metal_stone->image) ? $shop_by_metal_stone->image : '' ) !!}  
                <img src="{{ asset('storage/shopbymetal/'.$shop_by_metal_stone->image) }}" height="50" width="50" class="center" >
            @endif
        </div>

        <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
            {!! Form::label('is_active', __('Status:')) !!}<em style="color:red;">*</em>
            {!! Form::select('is_active', [ '1' => 'Active','2' => 'Inactive'], isset($shop_by_metal_stone->is_active) ? $shop_by_metal_stone->is_active : '', ['class'=>'form-control','placeholder' => 'Select Status']) !!}
        </div>
        

        <div class="form-group">
            @if(isset($certificates))
                {!! Form::submit(__('Update'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @else 
                {!! Form::submit(__('Submit'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
            @endif
            
        </div>

        {!! Form::close() !!}

    </div>
</div>