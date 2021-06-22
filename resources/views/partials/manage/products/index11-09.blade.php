 
<div class="panel panel-default">
    <div class="panel-heading">
        @if($products_vendor) @lang('Vendor') <a target="_blank" href="{{route('manage.vendors.edit', $products_vendor->id)}}">{{$products_vendor->name}}</a> @endif
        <br>
        @if(!empty($search = request()->s))
        @lang('Showing :total_products products with :per_page products per page for keyword: <strong>:keyword</strong>', ['total_products' => $products->total(), 'per_page' => $products->perPage(), 'keyword' => $search])&nbsp;&nbsp;<a class="text-primary" href="{{url('manage/products')}}">@lang('Show all')</a>
        @else
        @lang('Showing :total_products products with :per_page products per page', ['total_products' => $products->total(), 'per_page' => $products->perPage() ])
        @endif
        @if(empty(request()->all))
        <br>
        <a href="{{url('manage/products')}}?all=1">@lang('Show products in a single page')</a>
        @else
        <br>
        <a href="{{url('manage/products')}}">@lang('Show products with pagination')</a>
        @endif
    </div>
    
    <div class="panel-body">
        <form id="delete-form" action="delete/products" method="post" class="form-inline">

            <div class="row">
                @if(Auth::user()->can('delete', App\Product::class) || isset($vendor))
                {{csrf_field()}}
                <div class="col-md-4">
                    <div class="text-muted">
                        <label for="checkboxArray">@lang('Bulk Options') <i class="fa fa-cog" aria-hidden="true"></i></label>
                    </div>
                    <input type="hidden" name="_method" value="DELETE">

                    <div class="form-group">
                        <select name="checkboxArray" class="form-control">
                            <option value="">@lang('Delete')</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input id="delete_all" name="" class="btn fa btn-warning" value="&#xf1d8;"
                                onclick="
                                        if(confirm('@lang('Are you sure you want to delete selected products?')')) {
                                            $('#delete_all').attr('name', 'delete_all');
                                            event.preventDefault();
                                            $('#delete-form').submit();
                                        } else {
                                            event.preventDefault();
                                        }
                                        "
                        > 
                    </div>
                </div>
                
                @endif
                {{--
                <div class="advanced-search col-md-{{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? '6' : '6 col-md-offset-4'}}">
                    <div class="text-muted">
                        <label for="checkboxArray">@lang('Advanced Search') <i class="fa fa-search" aria-hidden="true"></i></label>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <select class="form-control" id="select-column">
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 3 : 2}}>@lang('Name')</option>
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 4 : 3}}>@lang('SKU')</option>
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 5 : 4}}>@lang('HSN')</option>
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 6 : 5}}>@lang('Stock')</option>
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 7 : 6}}>@lang('Price')</option>
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 8 : 7}}>@lang('Tax')</option>
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 9 : 8}}>@lang('Category')</option>
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 10 : 9}}>@lang('Brand')</option>
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 11 : 10}}>@lang('Model')</option>
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 12 : 11}}>@lang('Vendor')</option>
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 13 : 12}}>@lang('Custom Fields')</option>
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 14 : 13}}>@lang('Status')</option>
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 15 : 14}}>@lang('Created')</option>
                                <option value={{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? 16 : 15}}>@lang('Added By')</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <input class="form-control" type="text" id="search-by-column" placeholder="@lang('Search by Column')">
                        </div>
                    </div>
                </div>
                
                --}}
                <div class="col-md-2">
                    <div class="text-muted">
                     
                        <a href="#ex1" id="manageGroup" class="btn btn-primary btn-sm" rel="modal:open">Create Group</a>
                    </div>
                </div>
                <div class="search-box text-right col-md-{{(Auth::user()->can('delete', App\Product::class) || isset($vendor)) ? '6' : '6 col-md-offset-4'}}">
                    <div class="form-group search-field-box">
                        <input type="text" class="form-control" name="search" id="keyword" placeholder="@lang('Enter name, JN Web ID or category')" value="{{request()->s}}">
                        @if($products_vendor)
                        <input type="hidden" class="form-control" name="search" id="vendor" value="{{$products_vendor->id}}">
                        @endif
                    </div>
                    <button data-url="{{url('manage/products')}}" type="submit" class="btn btn-primary" id="search-btn">@lang('Search')</button>
                </div>
            </div>
            
            <div class="table-responsive">
            
                {{--
                <div class="col-md-12 text-right">
                    <span class="advanced-search-toggle">@lang('Advanced Search')</span>
                </div>
                --}}
                
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
                        <!-- <th>@lang('SKU')</th> -->
                        <!-- <th>@lang('HSN')</th> -->
                        <!-- <th>@lang('Stock')</th> -->
                        <th>@lang('Price')</th>
                        <!-- <th>@lang('Tax(%)')</th> -->
                        
                        
                        <!-- <th>@lang('Model')</th> -->
                        <!-- <th>@lang('Vendor')</th> -->
                        <!-- <th>@lang('Custom Fields')</th> -->
                        <th>@lang('Status')</th>
                        <th>@lang('Created')</th>
                        <!-- <th>@lang('Added By')</th> -->
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($products)
                        @php 
                            $ii = 0;
                            
                        @endphp
                        
                        @foreach($products as $k=> $product)
                         
                            @php 
                                 $style = ''; 
                                 $is_group = false;
                             $group = explode(',',$product->product_group_id);  
                             $count_product = COUNT($group);
                            @endphp
                            @if($product->product_group==1 && in_array($product->id, $group))
                                @php
                                    $is_group = true;
                                    $ii++;
                                @endphp
                                @if($ii == 1)
                                    @php
                                        $style = 'border-top: 1px solid red';
                                    @endphp
                                @elseif($ii == $count_product)
                                    @php
                                        $style = 'border-bottom: 1px solid red';
                                    @endphp
                                @endif
                            @else
                                @php
                                    $ii = 0;
                                    
                                   
                                     
                                
                                @endphp
                            @endif
                           
                            <tr>
                                @if(Auth::user()->can('delete', App\Product::class) || isset($vendor))
                                <td><input class="checkboxes" type="checkbox" name="checkboxArray[]" value="{{$product->id}}"></td>
                                @endif
                                <td  style= "{{ $style }} ">
                                <input id="groupCheckbox_{{ $product->id }}" class="groupCheckbox" {{ $product->product_group_id !='' ? 'disabled ' : ''}} type="checkbox" name="groupCheckbox[]" value="{{$product->id}}">
                                @if($product->product_group_id !='')
                                    <span> <a style="color:red; text-decoration:none;" href="javascript:void(0);" id="UnGrouped_{{ $product->id}}" title="Ungrouped" onclick="unGrouped({{ "$product->id" }});"><i class="fa fa-unlock-alt" ></i></a> </span> <span style="color:red;">{{ ($product->product_group_default == 1 ? 'Default' : '') }}</span> @endif
                                </td>
                                <td>{{$product->category ? $product->category->name  : '-'}}</td>
                                <td>
                                                                
                                     @php 
                                     $arr = array();
                                     $styleArr = array();
                                     $style = \App\ProductCategoryStyle::where('category_id',$product->category->id)->where('product_id',$product->id)->get();
                                     if(!empty($style)) {
                                         foreach($style AS $key => $val) {
                                            $styles = \App\Category::where('id',$val->product_style_id)->where('category_id',$product->category->id)->first();
                                            array_push($styleArr,$styles->name);
                                         }
                                          
                                          
                                     }

                                     if(!empty($styleArr)) {
                                       echo  implode(",",$styleArr);
                                     }
                                      
                                      
                                     @endphp
                                    
                                    


                                    
                                        
                                    
                                          
                                       
                                        
                                
                                        
                                     
                                     
                                 
                                
                                </td>
                                <td>
                                    @if($product->photo)
                                        @php
                                            $image_url = \App\Helpers\Helper::check_image_avatar($product->photo->name, 50);
                                        @endphp
                                        <img src="{{$image_url}}" height="50px" alt="{{$product->name}}"  />
                                    @else
                                        <img src="https://via.placeholder.com/50x50?text=No+Image" height="50px" alt="{{$product->name}}" />
                                    @endif
                                </td>
                                <td>{{$product->name}}</td>
                                <!-- @if($product->vendor && isset($vendor) && $vendor)
                                    @if($product->sku)
                                    <td>{{substr($product->sku, strpos($product->sku, '-') + 1)}}</td>
                                    @else
                                    <td>-</td>
                                    @endif
                                    @if($product->hsn)
                                    <td>{{substr($product->hsn, strpos($product->hsn, '-') + 1)}}</td>
                                    @else
                                    <td>-</td>
                                    @endif
                                @else
                                <td>{{$product->sku ? $product->sku : '-'}}</td>
                               
                                @endif -->
                                <!-- <td>{{$product->in_stock ? $product->in_stock : __('Out of Stock')}}</td> -->
                                <td>
                                
                                @if($product->product_discount!='')
                                    {{ number_format($product->new_price) }}
                                @else
                                    {{ number_format($product->old_price) }}
                                @endif
                                </td>
                                <!-- <td>{{$product->tax_rate}}</td> -->
                               
                                
                                <!-- <td>{{$product->model ? $product->model : '-'}}</td>
                                <td>{{$product->vendor ? $product->vendor->name : '-'}}</td> -->
                                <!-- <td>
                                    @if(count($custom_fields = unserialize($product->custom_fields)) > 0 && is_array($custom_fields))
                                        @php($i = 0)
                                        <ul>
                                        @foreach($custom_fields as $key => $field)
                                            @if(!empty($key) && !empty($field))
                                            @php($i++)
                                            <li><strong>{{$key}}: </strong>{{$field}}</li>
                                            @endif
                                        @endforeach
                                        </ul>
                                        @if($i < 1)
                                            -
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td> -->
                                <td>{{$product->is_active ? __('Active') : __('Inactive')}}</td>
                                <td>{{$product->created_at->diffForHumans()}}</td>
                                <!-- <td>{{$product->user ? $product->user->name .' @'.$product->user->username : '-'}}</td> -->
                                <td>
                                @if(Auth::user()->can('read', App\Product::class))
                                    <a target="_blank" href="{{route('front.product.show', $product->slug)}}">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    </a>&nbsp;
                                @endif
                                @if(Auth::user()->can('update', App\Product::class))
                                    <a href="{{route('manage.products.edit', $product->id)}}">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                @endif
                                &nbsp;
                                @if(Auth::user()->can('delete', App\Product::class))
                                    <input type="hidden" id="delete_single" name="">
                                    <a href=""
                                        onclick="
                                                if(confirm('@lang('Are you sure you want to delete this?')')) {
                                                    $('#delete_single').attr('name', 'delete_single').val({{$product->id}});
                                                    event.preventDefault();
                                                    $('#delete-form').submit();
                                                } else {
                                                    event.preventDefault();
                                                }
                                                "
                                    ><span class="glyphicon glyphicon-trash text-danger"></span></a>
                                @endif
                                </td>
                            </tr>
                           
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </form>
        @if(!request()->all)
        <div class="text-right">
            {{$products->appends($_GET)->links()}}
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