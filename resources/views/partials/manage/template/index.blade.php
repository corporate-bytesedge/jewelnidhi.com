<div class="panel panel-default">
    
    <div class="panel-body">

    <!-- @if(isset($template))
            {{ Form::model($template, ['route' => ['manage.templates.update', 1],'method'=>'put'] ) }}
           
    @else
            
    @endif -->
    {{ Form::open(array('route' => 'manage.templates.store')) }}

            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif

            <div class="col-12 col-sm-12 col-lg-12">
            <div class="card card-primary card-outline card-tabs">
              <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                  <li class="nav-item active">
                    <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Registration</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Forget Password</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill" href="#custom-tabs-two-messages" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false">Order Placement</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-messages_one-tab" data-toggle="pill" href="#custom-tabs-two-messages_one" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false">Order Cancellation</a>
                  </li>
                   
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-two-tabContent">
                  <div class="tab-pane fade active in" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                    <div class="card-body">
                     
                        {!! Form::hidden('template_name[]', 'Registraion', ['class'=>'form-control','readonly'=>true]) !!}
                        <div class="form-group row mt-20">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Subject</strong></label>
                        <div class="col-sm-6">
                        {!! Form::text('template_subject[]',  !empty($registerTemplate) ? $registerTemplate['template_subject'] : null, ['class'=>'form-control']) !!}
                        </div>
                        </div>
                        <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Body</strong></label>
                        <div class="col-sm-10">
                        {!! Form::textarea('template_body[]', !empty($registerTemplate) ? $registerTemplate['template_body'] : null, ['class'=>'form-control']) !!}
                        </div>
                        </div>
                    
                        
                        
                 
                    </div>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                            <div class="card-body">

                                {!! Form::hidden('template_name[]', 'Forget Password', ['class'=>'form-control','readonly'=>true]) !!}
                                <div class="form-group row mt-20">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Subject</strong></label>
                                        <div class="col-sm-6">
                                        {!! Form::text('template_subject[]',  !empty($forgetTemplate) ? $forgetTemplate['template_subject'] : null, ['class'=>'form-control']) !!}
                                        </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Body</strong></label>
                                    <div class="col-sm-10">
                                    {!! Form::textarea('template_body[]', !empty($forgetTemplate) ? $forgetTemplate['template_body'] : null, ['class'=>'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                  </div>
                    <div class="tab-pane fade" id="custom-tabs-two-messages" role="tabpanel" aria-labelledby="custom-tabs-two-messages-tab">
                        <div class="card-body">

                            {!! Form::hidden('template_name[]', 'Order Placement', ['class'=>'form-control','readonly'=>true]) !!}
                            <div class="form-group row mt-20">
                                <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Subject</strong></label>
                                <div class="col-sm-6">
                                    {!! Form::text('template_subject[]',  !empty($orderPlaceTemplate) ? $orderPlaceTemplate['template_subject'] : null, ['class'=>'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Body</strong></label>
                                <div class="col-sm-10">
                                {!! Form::textarea('template_body[]', !empty($orderPlaceTemplate) ? $orderPlaceTemplate['template_subject'] : null, ['class'=>'form-control']) !!}
                                </div>
                            </div>

                            
                    
                        </div>
                    </div>

                    <div class="tab-pane fade" id="custom-tabs-two-messages_one" role="tabpanel" aria-labelledby="custom-tabs-two-messages_one-tab">
                        <div class="card-body">

                            {!! Form::hidden('template_name[]', 'Order Cancellation', ['class'=>'form-control','readonly'=>true]) !!}
                            <div class="form-group row mt-20">
                                <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Subject</strong></label>
                                <div class="col-sm-6">
                                    {!! Form::text('template_subject[]',  !empty($orderCancelTemplate) ? $orderCancelTemplate['template_subject'] : null, ['class'=>'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Body</strong></label>
                                <div class="col-sm-10">
                                {!! Form::textarea('template_body[]', !empty($orderCancelTemplate) ? $orderCancelTemplate['template_body'] : null, ['class'=>'form-control']) !!}
                                </div>
                            </div>

                            
                    
                        </div>
                    </div>

                   
                </div>
              </div>
              <!-- /.card -->
                
                    
                    <div class="col-sm-2">
                        {!! Form::submit(__('Submit'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
                    </div>
                
            </div>
          </div>  
          
            
            {{ Form::close() }}

         
    </div>
</div>