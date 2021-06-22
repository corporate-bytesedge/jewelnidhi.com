 
<div class="panel panel-default">
    
    @if(Session::has('product_updated'))
        <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('product_updated') }}</p>
    @endif
    <div class="panel-body">
        <form id="approved-form" action="approve/approved_products" method="post" class="form-inline">
            
            <div class="bulk-options">
                <label for="checkboxArray">@lang('Bulk Options') <i class="fa fa-cog" aria-hidden="true"></i></label>
                <div class="delete-option">
                        @if(Auth::user()->can('delete', App\Product::class) || isset($vendor))
                            {{csrf_field()}}

                            <div class="">
                                
                             
                                <button type="button" id="approve_all" name="" class="btn btn-warning" onclick="
                                    if(confirm('@lang('Are you sure you want to approved selected products?')')) {
                                                        $('#approve_all').attr('name', 'delete_all');
                                                        event.preventDefault();
                                                        $('#approved-form').submit();
                                                    } else {
                                                        event.preventDefault();
                                                    }
                                                    ">Approved
                                </button>
                                
                                    
                                
                            </div>
                        
                        @endif
                </div>
            </div>
            
            
            <div class="table-responsive">
                 
                
                
                
                <table class="display table table-striped table-bordered table-hover" id="products-table">
                
                    <thead>
                    <tr>
                        @if(Auth::user()->can('delete', App\Product::class) )
                        <th><input type="checkbox" id="options"></th>
                        @endif
                        
                        <th>@lang('Vendor')</th>
                        <th>@lang('Category')</th>
                        
                        <th>@lang('Style')</th>
                        <th>@lang('Photo')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Price')</th>
                        <th>@lang('Status')</th>
                         
                         <th>@lang('Approved')</th>
                         
                        
                        <th>@lang('Action')</th>
                         
                    </tr>
                    </thead>
                    <tbody>
                    
                    </tbody>
                </table>
            </div>
        </form>
       
    </div>
</div>

 
 <!-- Modal -->
 <div id="ex1" class="modal" style="max-width:800px;">
    <div id="GroupMsg"></div>
    <form role="form" name="groupform" id="groupform" >
    {!! Form::hidden('product_id', null, ['class'=>'form-control','id'=>'product_id'])!!}
    {!! Form::hidden('group_by', null, ['class'=>'form-control','id'=>'group_by'])!!}
    
     <!-- {!! Form::hidden('grouparray', null, ['class'=>'form-control','id'=>'grouparray'])!!} -->
    
        <div class="card-body">
            <div class="form-group">
                
                {{ Form::checkbox('size', '10', false,['id'=>'sizeGroup','class'=>'Group']) }}
                <label for="size">Size</label>
                {{ Form::checkbox('purity', '9', false,['id'=>'purityGroup','class'=>'Group']) }}
                <label for="purity">Purity</label>
                
                    <div class="row"> 
                            <div class="col-sm-12 col-md-12"> 
                                <div id="sizeGroupHtml">
                                    
                                
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th colspan="5"> <center>Product Detail</center> </th>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Purity</th>
                                            <th>Size</th>
                                            <th>Default</th>
                                        </tr>
                                        </thead>
                                        <tbody id="GroupValue">
                                            
                                        </tbody>
                                        
                                    </table>
                                                                                
                                </div>
                            </div>
                            
                        
                    
                    </div>
            
                 

            
             
        </div>
        <div class="card-footer">
            <button type="button" id="saveGroup" class="btn btn-primary">Submit</button>
        </div>
    </form>
   
</div>