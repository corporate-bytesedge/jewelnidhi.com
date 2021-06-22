<div class="panel panel-default">
    <div class="panel-heading">
        @lang('View Collection')
    </div>
    <div class="panel-body">
    @if(isset($collections))
        {{ Form::model($collections, ['route' => ['manage.collection.update', $collections->id],'files'=>true, 'method' => 'PUT']) }}
        {!! Form::hidden('id', isset($collections->id) ? $collections->id : '' ) !!}  
    @else
    {{ Form::open(array('route' => 'manage.collection.store','files'=>true)) }}
    @endif
    
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif

            <div class="col-12 col-sm-12 col-lg-12">
            <div class="card card-primary card-outline card-tabs">
              <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                  <li class="nav-item active">
                    <a class="nav-link active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Collection One</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Collection Two</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill" href="#custom-tabs-two-messages" role="tab" aria-controls="custom-tabs-two-messages" aria-selected="false">Collection Three</a>
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
                       <th>Image</th>
                       <th>Link</th>
                       <th>Description</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tr>
                       <td> {!! Form::text('title_one', isset($collections->title_one) ? $collections->title_one : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Title")]) !!}</td>
                       <td> {!! Form::file('image_one', null , ['class'=>'form-control']) !!}
                        @php 
                            $imageonepath = public_path().'/storage/collection/'.$collections->image_one;
                        @endphp
                        @if(file_exists($imageonepath))
                            <img src="{{ asset('storage/collection/'.$collections->image_one) }}" height="50" width="50"  >
                        @endif
                       </td>
                       <td> {!! Form::text('link_one', isset($collections->link_one) ? $collections->link_one : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Link")]) !!}</td>
                       <td> {!! Form::textarea('description_one', isset($collections->description_one) ? $collections->description_one : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Description")]) !!}</td>
                    </tr>
                    </table>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                    <table class="display table table-striped table-bordered table-hover" id="discounts-products-table">
                        <thead>
                        <tr>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Link</th>
                        <th>Description</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tr>
                        <td> {!! Form::text('title_two',  isset($collections->title_two) ? $collections->title_two : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Title")]) !!}</td>
                        <td>{!! Form::file('image_two', null , ['class'=>'form-control']) !!}
                        
                        @php 
                                $imagetwopath = public_path().'/storage/collection/'.$collections->image_two;
                            @endphp
                            @if(file_exists($imagetwopath))
                                <img src="{{ asset('storage/collection/'.$collections->image_two) }}" height="50" width="50"  >
                            @endif
                        </td>
                        <td> {!! Form::text('link_two', isset($collections->link_two) ? $collections->link_two : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Link")]) !!}</td>
                        <td> {!! Form::textarea('description_two', isset($collections->description_two) ? $collections->description_two : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Description")]) !!}</td>
                        </tr>
                        </table>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-two-messages" role="tabpanel" aria-labelledby="custom-tabs-two-messages-tab">
                  <table class="display table table-striped table-bordered table-hover" id="discounts-products-table">
                        <thead>
                        <tr>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Link</th>
                        <th>Description</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                            <tr>
                                <td> {!! Form::text('title_three', isset($collections->title_three) ? $collections->title_three : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Title")]) !!}</td>
                                <td>{!! Form::file('image_three', null , ['class'=>'form-control']) !!}
                                @php 
                                        $imagethreepath = public_path().'/storage/collection/'.$collections->image_three;
                                    @endphp
                                    @if(file_exists($imagethreepath))
                                        <img src="{{ asset('storage/collection/'.$collections->image_three) }}" height="50" width="50"  >
                                    @endif
                                
                                </td>
                                <td> {!! Form::text('link_three', isset($collections->link_three) ? $collections->link_three : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Link")]) !!}</td>
                                <td> {!! Form::textarea('description_three', isset($collections->description_three) ? $collections->description_three : '' , ['class'=>'form-control', 'placeholder'=>__("Enter Description")]) !!}</td>
                            </tr>
                        </table>
                        
                  </div>
                   
                </div>
              </div>
              <!-- /.card -->
                @if(isset($collections))
                    <div class="col-sm-2">
                        {!! Form::submit(__('Update'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
                    </div> 
                    @else
                    <div class="col-sm-2">
                        {!! Form::submit(__('Add'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
                    </div>
                @endif
            </div>
          </div>  
          
            
            {{ Form::close() }}

         
    </div>
</div>