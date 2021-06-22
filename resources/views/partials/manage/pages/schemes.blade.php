 
<div class="row">
 
    <div class="col-xs-12 col-sm-8">

        @include('includes.form_errors')
        @if(isset($scheme))
        {!! Form::model($scheme, ['method'=>'patch', 'action'=>['ManagePagesController@updateScheme', $scheme->id], 'files'=>true,'id'=>'productForm', 'onsubmit'=>'submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
            
        @else
            {!! Form::open(['method'=>'post', 'action'=>'ManagePagesController@saveScheme', 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
        @endif
        
        
        
        
        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            {!! Form::label('title', __('Page Title:')) !!}
            {!! Form::text('title', $scheme->title ? $scheme->title : null, ['class'=>'form-control', 'placeholder'=>__('Enter page title'), 'required'])!!}
        </div>

        <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
            {!! Form::label('content', __('Content:')) !!}
            {!! Form::textarea('content', $scheme->content ? $scheme->content : null, ['class'=>'form-control', 'placeholder'=>__('Enter page content')]) !!}
        </div>

       

        <div class="form-group">
            {!! Form::label('is_active', __('Status:')) !!}
            {!! Form::select('is_active', [0=>__('inactive'), 1=>__('active')], $scheme->is_active, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
        </div>

        @include('partials.manage.meta-fields')

        <div class="form-group">
        @if(isset($scheme))
            {!! Form::submit(__('Update'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
        @else
        {!! Form::submit(__('Add'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
        @endif
            
        </div>

        {!! Form::close() !!}

    </div>
</div>