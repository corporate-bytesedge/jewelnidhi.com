 
<div class="row">
 
 <div class="col-xs-12 col-sm-8">
        @if(Session::has('page_created'))
            <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('page_created') }}</p>
        @endif

        @if(Session::has('page_updated'))
            <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('page_updated') }}</p>
        @endif
     @include('includes.form_errors')
     @if(isset($certifications))
     {!! Form::model($certifications, ['method'=>'patch', 'action'=>['ManagePagesController@updateCertifications', $certifications->id], 'files'=>true,'id'=>'productForm', 'onsubmit'=>'submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
         
     @else
         {!! Form::open(['method'=>'post', 'action'=>'ManagePagesController@saveCertifications', 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
     @endif
     
     
     
     
     <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
         {!! Form::label('title', __('Page Title:')) !!}
         {!! Form::text('title', isset($certifications->title) ? $certifications->title : null, ['class'=>'form-control', 'placeholder'=>__('Enter page title')])!!}
     </div>

     <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
         {!! Form::label('content', __('Content:')) !!}
         {!! Form::textarea('content', isset($certifications->content) ? $certifications->content : null, ['class'=>'form-control', 'placeholder'=>__('Enter page content')]) !!}
     </div>

    

     <div class="form-group">
         {!! Form::label('is_active', __('Status:')) !!}
         {!! Form::select('is_active', [0=>__('inactive'), 1=>__('active')], isset($certifications->is_active) ? $certifications->is_active : '', ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
     </div>

    

     <div class="form-group">
     @if(isset($certifications))
         {!! Form::submit(__('Update'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
     @else
     {!! Form::submit(__('Add'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
     @endif
         
     </div>

     {!! Form::close() !!}

 </div>
</div>