<div class="panel panel-default">
    <div class="panel-heading">
        @lang('View Home Banner')
    </div>
    <div class="panel-body">
          
          @if(isset($banner))
          {{ Form::model($banner, ['route' => ['manage.home_banner.update', $banner->id],'files'=>true, 'method' => 'PUT']) }}
          {!! Form::hidden('id', isset($banner->id) ? $banner->id : '' ) !!}
            @else
          {{ Form::open(array('route' => 'manage.home_banner.store','files'=>true)) }}
          @endif
     
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif

            <div class="col-12 col-sm-12 col-lg-12">
            <div class="card card-primary card-outline card-tabs">
              <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                  <li class="nav-item active">
                    <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Middle Banner</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Left Bottom Banner</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill" href="#custom-tabs-two-messages" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false">Right Bottom Banner</a>
                  </li>
                   
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-two-tabContent">
                  <div class="tab-pane fade active in" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                     
                  <table class="display table table-striped table-bordered table-hover" id="discounts-products-table">
                    <thead>
                    <tr>
                       <th>Title</th>
                       <th>Image <span style="color:red;font-size:10px;">Dimension(1600 x 535)</span></th>
                       <th>Link <span style="color:red; font-size:10px;"><br/>(ex:- http://www.example.com)</span></th>
                       <!-- <th>Description</th> -->
                        
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                     
                    <tr>
                       <td> {!! Form::text('title_one', isset($banner->title_one) ? $banner->title_one : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Title")]) !!}</td>
                       <td> {!! Form::file('image_one', null , ['class'=>'form-control']) !!}

                        @if(isset($banner->image_one))
                            @php 
                                $imagetwopath = public_path().'/storage/middle-banner/'.$banner->image_one;
                            @endphp
                            @if(file_exists($imagetwopath))
                                <img src="{{ asset('storage/middle-banner/'.$banner->image_one) }}" height="50" width="50"  >
                            @endif
                        @endif

                       </td>
                       <td> {!! Form::text('link_one', isset($banner->link_one) ? $banner->link_one : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Link")]) !!}</td>
                       <!-- <td> {!! Form::textarea('description_one', isset($banner->description_one) ? $banner->description_one : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Description")]) !!}</td> -->
                    </tr>
                    </table>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                    <table class="display table table-striped table-bordered table-hover" id="discounts-products-table">
                        <thead>
                        <tr>
                        <th>Title</th>
                        <th>Image <span style="color:red;font-size:10px;">Dimension(570 x 350)</span></th>
                        <th>Link <span style="color:red; font-size:10px;"><br/>(ex:- http://www.example.com)</span></th>
                        <!-- <th>Description</th> -->
                            
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tr>
                        <td> {!! Form::text('title_two',  isset($banner->title_two) ? $banner->title_two : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Title")]) !!}</td>
                        <td>{!! Form::file('image_two', null , ['class'=>'form-control']) !!}
                        
                         
                        @if(isset($banner->image_two))
                            @php 
                                $imagetwopath = public_path().'/storage/left-banner/'.$banner->image_two;
                            @endphp
                            @if(file_exists($imagetwopath))
                                <img src="{{ asset('storage/left-banner/'.$banner->image_two) }}" height="50" width="50"  >
                            @endif
                        @endif
                        </td>
                        <td> {!! Form::text('link_two', isset($banner->link_two) ? $banner->link_two : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Link")]) !!}</td>
                        <!-- <td> {!! Form::textarea('description_two', isset($banner->description_two) ? $banner->description_two : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Description")]) !!}</td> -->
                        </tr>
                        </table>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-two-messages" role="tabpanel" aria-labelledby="custom-tabs-two-messages-tab">
                  <table class="display table table-striped table-bordered table-hover" id="discounts-products-table">
                        <thead>
                        <tr>
                        <th>Title</th>
                        <th>Image <span style="color:red;font-size:10px;">Dimension(570 x 350)</span></th>
                        <th>Link <span style="color:red; font-size:10px;"><br/>(ex:- http://www.example.com)</span></th>
                        <!-- <th>Description</th> -->
                            
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                            <tr>
                                <td> {!! Form::text('title_three', isset($banner->title_three) ? $banner->title_three : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Title")]) !!}</td>
                                <td>{!! Form::file('image_three', null , ['class'=>'form-control']) !!}
                                @if(isset($banner->image_two))
                                    @php 
                                        $imagetwopath = public_path().'/storage/right-banner/'.$banner->image_three;
                                    @endphp
                                    @if(file_exists($imagetwopath))
                                        <img src="{{ asset('storage/right-banner/'.$banner->image_three) }}" height="50" width="50"  >
                                    @endif
                                @endif
                                
                                </td>
                                <td> {!! Form::text('link_three', isset($banner->link_three) ? $banner->link_three : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Link")]) !!}</td>
                                <!-- <td> {!! Form::textarea('description_three', isset($banner->description_three) ? $banner->description_three : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Description")]) !!}</td> -->
                            </tr>
                        </table>
                        
                  </div>
                   
                </div>
              </div>
              <!-- /.card -->
                      <div class="col-sm-2">
                        {!! Form::submit(__('Save'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
                    </div>
            </div>
          </div>  
          
            
            {{ Form::close() }}

         
    </div>
</div>