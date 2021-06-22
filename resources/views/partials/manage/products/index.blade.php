 
<div class="panel panel-default">
    
    
    <div class="panel-body">
        <form id="delete-form" action="delete/products" method="post" class="form-inline">
            <div class="bulk-options">
                <label for="checkboxArray">@lang('Bulk Options') <i class="fa fa-cog" aria-hidden="true"></i></label>
                <div class="delete-option">
                        @if(Auth::user()->can('delete', App\Product::class) || isset($vendor))
                        {{csrf_field()}}

                        <div class="">
                            
                            <input type="hidden" name="_method" value="DELETE">

                            
                                
                            
                                <button type="button" id="delete_all" name="" class="btn btn-warning" onclick="
                                                if(confirm('@lang('Are you sure you want to delete selected products?')')) {
                                                    $('#delete_all').attr('name', 'delete_all');
                                                    event.preventDefault();
                                                    $('#delete-form').submit();
                                                } else {
                                                    event.preventDefault();
                                                }
                                                ">Delete</button>
                            
                                
                            
                        </div>
                        
                        @endif
                </div>
                <a href="#ex1" id="manageGroup" class="btn btn-primary btn-sm" rel="modal:open">Create Group</a>

            </div>
            
            <div class="search-section">
                <label for="search">@lang('Search')</label>
                @if(!\Auth::user()->isApprovedVendor())
                    <div class="form-group">
                        <select class="form-control" id="Vendors">
                            <option value="">@lang('Select vendor')</option>
                            @if(count($vendor) > 0)
                                    @foreach($vendor AS $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                    @endforeach
                            @endif
                            
                            
                        </select>
                    </div>
                @endif
                    <div class="form-group searchKeyword">
                    
                        <input class="form-control" type="text" id="search-by-column" placeholder="@lang('Keywords')">
                    </div>

                    
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm Search"><span class="advanced-search-toggle" style="color:#fff;">@lang('Search')</span></a>
            </div>
            
            <div class="table-responsive">
                 
                
                
                
                <table class="display table table-striped table-bordered table-hover" id="products-table">
                
                    <thead>
                    <tr>
                        @if(Auth::user()->can('delete', App\Product::class) || isset($vendor))
                        <th><input type="checkbox" id="options"></th>
                        @endif
                        
                        <th>@lang('Group')</th>
                        <th>@lang('Category')</th>
                        
                        <th>@lang('Style')</th>
                        <th>@lang('Photo')</th>
                        <th>@lang('Name')</th>
                        <th>@lang('Price')</th>
                        <th>@lang('Status')</th>
                        @if(\Auth::user()->isApprovedVendor())
                         <th>@lang('Approved')</th>
                        @endif
                        
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