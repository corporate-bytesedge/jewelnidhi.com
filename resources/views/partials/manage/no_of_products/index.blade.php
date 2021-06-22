 
<div class="panel panel-default">
    
    <div class="panel-body">
         

            <div class="row">
                
           
                <!-- <a href="{{route('manage.number_of_products.export_category_csv')}}" id="CategoryCSV" class="btn btn-primary">@lang('Export CSV')</a> -->
                <a style="margin-left:13px;" href="{{route('manage.number_of_products.export_style_csv')}}" id="StyleCSV" class="btn btn-primary">@lang('Export CSV')</a>
                
                <!-- <div class="pull-right">
                
                    <button  type="button" class="btn btn-primary" id="viewStyle">@lang('View Style')</button>
                    <button  type="button" class="btn btn-primary" id="viewCategory">@lang('View Category')</button>
                </div> -->
            </div>
            
            <div class="table-responsive mt-15">
            
                
                
                <!-- <table class="display table table-striped table-bordered table-hover" id="viewCategoryHtml">
                    <thead>
                    <tr>
                       
                        
                        <th>@lang('Category')</th>
                        <th>@lang('Total Product')</th>
                        
                        
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($root_categories))
                     
                        @foreach($root_categories AS $k => $value)

                        @php 
                        $product =  \App\Product::whereHas('product_category_styles', function ($query) use($value) {
                                                        $query->where('category_id',$value->id);
                                                        })->where(function ($query) {
                                                                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                                                        })->where('category_id',$value->id)->where('is_active','1')->count();
                        @endphp
                       
                        <tr>
                            
                            <td>{{ ucwords($value->name) }}</td>
                            <td> {{ $product  }}</td>
                        </tr>
                        @endforeach
                    @endif
                    </tbody>
                   
                </table> -->
                @if(!empty($root_categories))
                    @php 
                        $i = 1;
                        $totalProduct = 0;
                    @endphp
                    <table class="display table table-striped table-bordered table-hover viewStyleHtml" id="">
                            
                    @foreach($root_categories AS $k => $parent)

                        @if(count($parent->products) > 0)
                          
                            @if($parent->name)
                            @php 
                            $product =  \App\Product::whereHas('product_category_styles', function ($query) use($parent) {
                                                        $query->where('category_id',$parent->id);
                                                        })->where(function ($query) {
                                                                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                                                        })->where('category_id',$parent->id)->where('is_active','1')->count();
                            @endphp
                                <tr>
                                 
                                    <th>{{ ucwords($parent->name) }} ( {{ $product }} Products) </th>
                                
                                     
                                    
                                    
                                </tr>
                            @endif
                        
                            
                                @if(!empty($parent->categories))
                               
                                         @foreach($parent->categories AS $k => $st)
                                         
                                         
                                            @php
                                             
                                             
                                             
                                            $product =  \App\Product::whereHas('product_category_styles', function ($query) use($st,$k) {
                                                        $query->where('category_id',$st->category->categories[$k]->category_id)->where('product_style_id', $st->category->categories[$k]->id);
                                                        })->where(function ($query) {
                                                                        $query->where('product_group_default', 1)->where('product_group', 1)->orWhere('product_group',  null);
                                                        })->where('category_id',$st->category->categories[$k]->category_id)->where('is_active','1')->count();
                                            @endphp
                                            <tr>
                                                <td style="padding-left:45px;">{{ $st->name }} ({{ $product }})</td>
                                                
                                            
                                            </tr>
                                            
                                        @endforeach    
                                @endif
                        @endif
                    @endforeach
                     
                        
                        </table>
                @endif
            </div>
        
        @if(!request()->all)
        <div class="text-right">
             
        </div>
        @endif
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