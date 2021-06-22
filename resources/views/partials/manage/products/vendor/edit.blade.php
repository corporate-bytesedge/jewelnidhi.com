
<div class="row">
    <div class="col-xs-12 col-sm-8">

        @if(session()->has('product_updated'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>{{session('product_updated')}}</strong> <a target="_blank" href="{{route('front.product.show', $product->slug)}}">@lang('View')</a>
            </div>
        @endif

        @include('includes.form_errors')

        
        {!! Form::model($product, ['method'=>'patch', 'action'=>['ManageProductsController@updateVendorProduct', $product->id], 'files'=>true,'id'=>'productVendorForm', 'onsubmit'=>'submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
        <input name="save_draft_btn" id="save_draft_btn" value="" type="hidden">
        <input name="submit_button_btn" id="submit_button_btn" value="" type="hidden">
        @if($product->photo)
                @if($product->photo->name)
                    @php
                        $image_url = \App\Helpers\Helper::check_image_avatar($product->photo->name, 150);
                    @endphp
                    <img src="{{$image_url}}" class="img-responsive product-feature-image" alt="{{$product->name}}"  />
                @endif
            <div class="form-group">
                <div class="has-error">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('remove_photo') !!} <strong>@lang('Remove Featured Image')</strong>
                        </label>
                    </div>
                </div>
            </div>
        @endif
       
        @if($product->overlayphoto)
                @if($product->overlayphoto->name)
                    @php
                        $image_url = \App\Helpers\Helper::check_overlayimage_avatar($product->overlayphoto->name, 150);
                    @endphp
                    <img src="{{$image_url}}" class="img-responsive product-feature-image" alt="{{$product->name}}"  />
                @endif
            <div class="form-group">
                <div class="has-error">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('remove_overlay_photo') !!} <strong>@lang('Remove Overlay  Image')</strong>
                        </label>
                    </div>
                </div>
            </div>
        @endif
          
        @if(!empty($product->photos) && count($product->photos) > 0)
            <div class="row">
            <hr>
            
            @foreach($product->photos as $photo)
             
                <div class="col-md-3">
                 
                    @if($photo->name!='')
                       
                        @php
                            $image_url = \App\Helpers\Helper::check_image_avatar($photo->name, 80);
                            
                        @endphp
                        <img src="{{$image_url}}" class="img-responsive" alt="{{$product->name}}" width=80 height=80  />
                    @else
                        <img src="https://via.placeholder.com/80x80?text=No+Image" class="img-responsive" width=80 height=80 alt="{{$product->name}}" />
                    @endif
                    <div class="checkbox">
                        <label>
                            <input name="remove_images[]" type="checkbox" value="{{$photo->id}}"> <strong>@lang('Remove')</strong>
                        </label>
                    </div>
                </div>
            @endforeach
            </div>
        @endif
        
        <div class="form-group">
            <div class="category_box form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                <label for="category">@lang('Category:') <em style="color:red;">*</em></label>
                <select id="category" name="category" class="form-control selectpicker" data-style='btn-default'>
                    <option value="">@lang('Choose Option')</option>
                    @foreach($root_categories as $category)
                     
                        @if(count($category->categories) > 0)
                        
                            <option {{ isset($product->category_id) && $product->category_id==$category->id ? 'selected="selected"' : '' }} class="bolden" value="{{$category->id}}">{{$category->name}}  </option>
                             <!-- @include('partials.manage.subcategories-select', ['childs' => $category->categories, 'space'=>1])  -->
                        @else
                            <option  value="{{$category->id}}">{{$category->name}} </option>
                        @endif
                    @endforeach
                </select>
            </div>
            
        </div>
        <div id="styleHtml"> 
            <div class="form-group">
                <div class="product_box">
                    <label for="pincode">@lang('Style:') <em style="color:red;">*</em></label>
                    
                    <select name="style_id[]" class="form-control ignore"  id="style_id" multiple  data-style = "btn-default" placholder="Choose Style"  >
                            
                                
                        <option value="">@lang('Choose Style')</option>
                        @if(!empty($styles))
                                
                                    @foreach($styles AS $k => $value)
                                    @php $selectedStyleOption = ''; @endphp
                                        @foreach($selectedStyle AS $ke => $val)
                                            @php $selectedStyleOption .= ($val['product_style_id'] == $value->id ?  'selected="selected"' : '' )  @endphp
                                        @endforeach
                                    
                                    
                                        <option {{ $selectedStyleOption }} value="{{ $value->id }}"  >{{ $value->name }} </option>
                                    @endforeach
                            @endif
                    </select>
                </div>
            </div>
        </div>
             
        {!! Form::hidden('current_price',isset($current_gold_price->value) ? $current_gold_price->value : '', ['class'=>'form-control','id'=>'current_gold_price'])!!}
        {!! Form::hidden('current_silver_item_price',isset($current_silver_item_price->value) ? $current_silver_item_price->value : '', ['class'=>'form-control','id'=>'current_silver_item_price'])!!}
        {!! Form::hidden('current_silver_jewellery_price',isset($current_silver_jewellery_price->value) ? $current_silver_jewellery_price->value : '', ['class'=>'form-control','id'=>'current_silver_jewellery_price'])!!}
        
        <div class="form-group {{ $errors->has('jn_web_id') ? ' has-error' : '' }}">
            {!! Form::label('jn_web_id', __('JN WEB ID:')) !!} <em style="color:red;">*</em>
            {!! Form::text('jn_web_id', isset($product->jn_web_id) && $product->jn_web_id!='' ? $product->jn_web_id : '', ['class'=>'form-control','id'=>'sku', 'placeholder'=>__('Enter JN WEB ID')])!!}
        </div>

       
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            {!! Form::label('name', __('Name:')) !!} <em style="color:red;">*</em>
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>__('Enter name')])!!}
        </div>

        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            {!! Form::label('description', __('Description:')) !!}
            {!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>__('Enter description')]) !!}
        </div>

        <div class="form-group{{ $errors->has('metal_id') ? ' has-error' : '' }}">
                {!! Form::label('metal_id', __('Metal:')) !!}
                {!! Form::select('metal_id', $metals, null, ['class'=>'form-control','placeholder' => 'Select Metal']); !!}
        </div>
        @if(isset($product->silver_item_id))
        <div class="form-group{{ $errors->has('metal_id') ? ' has-error' : '' }}" id="silverHtml" >
                {!! Form::label('silver_item_id', __('Silver Item:')) !!}
                <select name="silver_item_id" id="silver_item_id" class="form-control">
                    <option value="">Select Silver Item</option>
                    
                        <option  @php echo $product->silver_item_id == '1' ? 'selected="selected"' : ''  @endphp  value="1">Silver Items</option>
                        <option  @php echo $product->silver_item_id == '2' ? 'selected="selected"' : ''  @endphp value="2">Silver Jewellery</option>
                        
                     
                </select>
                 
            </div>
        @endif
            
        
        <div class="specification_types_box">
            <table id="specification_types_box" class="table table-responsive table-bordered">
                <thead>
                    <tr>
                        <th>@lang('Specification Type')</th>
                        <th>@lang('Value')</th>
                        <th>@lang('Unit')</th>
                        <th></th>
                    </tr>
                </thead>
                
                <tbody class="specification_types_rows">
                    @if(count($product->specificationTypes) > 0)
                    @foreach($product->specificationTypes as $k=> $product_specification_type)
                      
                    
                    <tr id="{{ $k }}">
                        <td>
                            {!! Form::select('specification_type[]', $specification_types, $product_specification_type->id, ['class'=>'form-control selectpicker specification_type','id'=>'specification_type', 'data-style'=>'btn-default']) !!}
                        </td>
                        <td>
                            {!! Form::text('specification_type_value[]', $product_specification_type->pivot->value, ['class'=>'form-control specification_type_value'.$k,'id'=>$product_specification_type->id, 'placeholder'=>__('Example: 14, 3.5, red')])!!}
                        </td>
                        <td>
                            {!! Form::text('specification_type_unit[]', $product_specification_type->pivot->unit, ['class'=>'form-control', 'placeholder'=>__('kg, GHz (Leave blank if no unit)')])!!}
                        </td>
                        <td>
                            <button class="remove_row btn btn-danger btn-xs" type="button">
                                <span class="glyphicon glyphicon-remove removeRow" id="{{ $product_specification_type->id }}"></span>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td>
                            {!! Form::select('specification_type[]', $specification_types, null, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default','disabled' =>' {{ $product->silver_item_id ? true : false  }}' ]) !!}
                        </td>
                        <td>
                            {!! Form::text('specification_type_value[]', null, ['class'=>'form-control', 'placeholder'=>__('Example: 14, 3.5'),'readonly' =>' {{ $product->silver_item_id ? true : false  }}'])!!}
                        </td>
                        <td>
                            {!! Form::text('specification_type_unit[]', null, ['class'=>'form-control', 'placeholder'=>__('Example: inch, kg'),'readonly' =>' {{ $product->silver_item_id ? true : false  }}'])!!}
                        </td>
                        <td>
                            <button class="remove_row btn btn-danger btn-xs" type="button">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </button>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            
            <div class="text-right">
                <button type="button" id="add-more-specification" class="btn btn-success btn-sm">@lang('Add More')</button>
            </div>
            
        </div>
       

        
          
        <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                {!! Form::label('price', __('Gold Price:'),['id'=>'pricelabel']) !!}
                {!! Form::text('price', old('price'), ['id'=>'price', 'class'=>'form-control','placeholder'=>__('Gold Price'),'readonly'=>true])!!}
            </div>
            <div class="form-group{{ $errors->has('diamond_price') ? ' has-error' : '' }}" id="dimondPriceHtml">
                {!! Form::label('diamond_price', __('Diamond Price:')) !!}
                
                {!! Form::text('diamond_price', old('diamond_price'), ['id'=>'diamond_price','class'=>'form-control', 'placeholder'=>__('Dimond Price'),'readonly'=>true])!!}
            </div>

            <div class="form-group{{ $errors->has('vat_rate') ? ' has-error' : '' }}">
                {!! Form::label('vat_rate', __('VA:')) !!}
                {!! Form::text('vat_rate', isset($product->vat_rate) ? $product->vat_rate : null, ['id'=>'va','class'=>'form-control','placeholder'=>__('VA(Value added)'),'readonly'=>true])!!}
            </div>

            <div class="form-group{{ $errors->has('gst_three_percent') ? ' has-error' : '' }}">
                {!! Form::label('gst_three_percent', __('GST:')) !!}
                {!! Form::text('gst_three_percent', isset($product->gst_three_percent) ? $product->gst_three_percent : null, ['id'=>'gst_three_percent','class'=>'form-control', 'placeholder'=>__('GST(3%)'),'readonly'=>true])!!}
            </div>

                             
            {!! Form::hidden('total_weight', isset($product->total_weight) ? $product->total_weight : null, ['id'=>'total_weight', 'placeholder'=>__('Total weight(g)')])!!}
            {!! Form::hidden('metal_weight',  isset($product->metal_weight) ? $product->metal_weight : null, ['id'=>'metal_weight', 'placeholder'=>__('Metal weight(g)')])!!}
            {!! Form::hidden('stone_weight', isset($product->stone_weight) ? $product->stone_weight : null, ['id'=>'stone_weight', 'placeholder'=>__('Stone weight(g)')])!!}
            {!! Form::hidden('pearls_weight', isset($product->pearls_weight) ? $product->pearls_weight : null, ['id'=>'pearls_weight', 'placeholder'=>__('Stone weight(g)')])!!}
            {!! Form::hidden('diamond_weight', isset($product->diamond_weight) ? $product->diamond_weight : null, ['id'=>'diamond_weight', 'placeholder'=>__('Diamond weight(g)')])!!}
            
            
            {!! Form::hidden('diamond_price_one', isset($product->diamond_price_one) ? $product->diamond_price_one : null, ['id'=>'diamond_price_one', 'placeholder'=>__('Dimond Price One')])!!}
            {!! Form::hidden('diamond_price_two',  isset($product->diamond_price_two) ? $product->diamond_price_two : null, ['id'=>'diamond_price_two', 'placeholder'=>__('Dimond Price Two')])!!}
            
            {!! Form::hidden('stone_price', old('stone_price'), ['id'=>'stone_price', 'placeholder'=>__('Stone Price')])!!}
            {!! Form::hidden('pearls_price', old('pearls_price'), ['id'=>'pearls_price', 'placeholder'=>__('Pearls Price')])!!}
            {!! Form::hidden('watch_price', old('watch_price'), ['id'=>'watch_price', 'placeholder'=>__('Watch Price')])!!}
            
            {!! Form::hidden('total_stone_price', isset($product->total_stone_price) ? $product->total_stone_price : null , ['id'=>'total_stone_price', 'placeholder'=>__('Total Stone Price')])!!}
            
            {!! Form::hidden('carat_wt_per_diamond', isset($product->carat_wt_per_diamond) ? $product->carat_wt_per_diamond : null, ['id'=>'carat_wt_per_diamond', 'placeholder'=>__('carat_wt_per_diamond')])!!}
            {!! Form::hidden('diamond_wtcarats_earrings', isset($product->diamond_wtcarats_earrings) ? $product->diamond_wtcarats_earrings : null, ['id'=>'diamond_wtcarats_earrings', 'placeholder'=>__('diamond_wtcarats_earrings')])!!}
            {!! Form::hidden('diamond_wtcarats_nackless', isset($product->diamond_wtcarats_nackless) ? $product->diamond_wtcarats_nackless : null, ['id'=>'diamond_wtcarats_nackless', 'placeholder'=>__('diamond_wtcarats_nackless')])!!}

            {!! Form::hidden('diamond_wtcarats_nackless_price', isset($product->diamond_wtcarats_nackless_price) ? $product->diamond_wtcarats_nackless_price : null, ['id'=>'diamond_wtcarats_nackless_price', 'placeholder'=>__('diamond_wtcarats_nackless_price')])!!}
            {!! Form::hidden('diamond_necklace_carat_price', isset($product->diamond_necklace_carat_price) ? $product->diamond_necklace_carat_price : null, ['id'=>'diamond_necklace_carat_price', 'placeholder'=>__('diamond_necklace_carat_price')])!!}

            
            
            {!! Form::hidden('diamond_wtcarats_earrings_price', isset($product->diamond_wtcarats_earrings_price) ? $product->diamond_wtcarats_earrings_price : null, ['id'=>'diamond_wtcarats_earrings_price', 'placeholder'=>__('diamond_wtcarats_earrings_price')])!!}
            
             
            {!! Form::hidden('subtotal', isset($product->subtotal) ? $product->subtotal : null, ['id'=>'subtotal', 'placeholder'=>__('Enter SubTotal')])!!}
            
            {!! Form::hidden('carat_weight', isset($product->carat_weight) ? $product->carat_weight : null, ['id'=>'carat_weight_val', 'placeholder'=>__('Carat Weight')])!!}
            {!! Form::hidden('carat_price', isset($product->carat_price) ? $product->carat_price : null, ['id'=>'carat_price_val', 'placeholder'=>__('Carat Price')])!!}

            {!! Form::hidden('per_carate_cost', isset($product->per_carate_cost) ? $product->per_carate_cost : null, ['id'=>'per_carate_cost_val', 'placeholder'=>__('Per Carate Cost')])!!}
            {!! Form::hidden('total_price', isset($product->total_price) ? $product->total_price : null, ['id'=>'total_price', 'placeholder'=>__('Total Price')])!!}

        <div class="form-group{{ $errors->has('old_price') ? ' has-error' : '' }}">
            {!! Form::label('old_price', __('Selling Price:')) !!} <em style="color:red;">*</em>
            {!! Form::number('old_price', $product->old_price, ['class'=>'form-control', 'step'=>'any','readonly'=>true, 'placeholder'=>__('Enter regular price')]) !!}
        </div>

        <div class="form-group{{ $errors->has('new_price') ? ' has-error' : '' }}">
            {!! Form::label('new_price', __(' Discount Price:')) !!}
            {!! Form::text('new_price', isset($product->new_price) ? $product->new_price : null, ['class'=>'form-control', 'placeholder'=>__('Enter selling price'),'readonly'=>true]) !!}
        </div>

        <div class="form-group product_box">
            <label for="certificates[]">@lang('Certification:') <span style="color:#DAA520;">(Select/Deselect from the list)</span></label>
                <select  name="certificates[]" id="certificates[]" placholder="Select Certification" multiple>
                    @if(!empty($certificates)) 
                        @foreach($certificates as $k=> $certificate)
                            @if($product->certificate_products->contains($k))
                                <option selected value="{{$k}}">{{$certificate}}</option>
                            @else
                                <option value="{{$k}}">{{$certificate}} </option>
                            @endif
                        @endforeach
                    @endif
                </select>
        </div>
        <div class="form-group{{ $errors->has('product_discount') ? ' has-error' : '' }}">
            {!! Form::label('product_discount', __('Product Discount: (%)'))  !!}
            {!! Form::text('product_discount', old('product_discount'), ['class'=>'form-control','id'=>'product_discount',  'placeholder'=>__('Enter Product Discount')]) !!}
        </div>

        <div class="form-group{{ $errors->has('product_discount') ? ' has-error' : '' }}">
            {!! Form::label('product_discount_on', __('Product Discount Text:'))  !!}
            {!! Form::text('product_discount_on', old('product_discount_on'), ['class'=>'form-control','id'=>'product_discount_on',  'placeholder'=>__('Enter Product Discount Text')]) !!}
        </div>

       

        <div class="checkbox row">
            <div class="col-md-4 col-xs-6">
                <label>
                    <input type="checkbox" name="is_new" id="is_new"
                    @if($product->is_new)
                        checked
                    @endif
                    > <strong>@lang('Is New')</strong>
                </label>
            </div>
            <div class="col-md-4 col-xs-6">
                <label>
                    <input type="checkbox" name="best_seller" id="best_seller"
                    @if($product->best_seller)
                        checked
                    @endif
                    > <strong>@lang('Best Seller')</strong>
                </label>
            </div>
        </div>

        @if($product->file)
            <div>
                <a role="link" class="btn btn-link" href="{{route('manage.download', [$product->file->filename])}}">{{$product->file->original_filename}}</a>
            </div>
            <div class="form-group">
                <div class="has-error">
                    <div class="checkbox">
                        <label>
                            {!! Form::checkbox('remove_file', null, null, ['id'=>'remove_file']) !!} <strong>@lang('Remove Current File / Choose New File')</strong>
                        </label>
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group" id="downloadable-file">
            {!! Form::label('file', __('Choose File In Zip'), ['class'=>'btn btn-default btn-file']) !!}
            {!! Form::file('file',['class'=>'form-control', 'style'=>'display: none;','onchange'=>'$("#upload-file-name").html(files[0].name)']) !!}
            <span class='label label-info' id="upload-file-name">@lang('No file chosen')</span>
        </div>

        <div class="product-quantity">
            {!! Form::hidden('in_stock', 1000) !!}

            {!! Form::hidden('qty_per_order', 1000, []) !!}

           
        </div>

        

        <div class="form-group">
            {!! Form::label('status', __('Status:')) !!}
            {!! Form::select('status', [0=>__('inactive'), 1=>__('active')], $product->is_active, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
        </div>
         
        <div class="form-group">
            {!! Form::label('is_approved', __('Approved:')) !!}
            {!! Form::select('is_approved', [1=>__('Yes'), 0=>__('No')], $product->is_approved, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
        </div>
    

        

        
        <div class="form-group">
            {!! Form::label('photo', __('Choose Featured Image'), ['class'=>'btn btn-default btn-file']) !!}
            {!! Form::file('photo',['class'=>'form-control', 'style'=>'display: none;','onchange'=>'$("#upload-file-info").html(files[0].name)']) !!}
            <span class='label label-info' id="upload-file-info">@lang('No image chosen')</span>
        </div>

        <div class="form-group">
            {!! Form::label('overlay_photo', __('Choose Overlay Image'), ['class'=>'btn btn-default btn-file']) !!}
            {!! Form::file('overlay_photo',['class'=>'form-control', 'style'=>'display: none;','onchange'=>'$("#upload-file-info1").html(files[0].name)']) !!}
            <span class='label label-info' id="upload-file-info1">@lang('No image chosen')</span>
        </div>
        <div class="form-group">
            {!! Form::label('photos[]', __('Choose More Images'), ['class'=>'btn btn-default btn-file']) !!}
            {!! Form::file('photos[]',['class'=>'form-control upload-files-info2', 'multiple', 'style'=>'display: none;','onchange'=>'$("#upload-files-info").html(moreImagesNames(files))']) !!}
            <span class='label label-info' id="upload-files-info">@lang('No image chosen')</span>
            <strong>(@lang('Hold Ctrl to select multiple images'))</strong>
        </div>

        <div class="progress">
                <div class="progress-bar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    0%
                </div>
            </div>
            <br />
            <div id="success" class="row">

            </div>

        @include('partials.manage.meta-fields')

        <!-- <div class="form-group">
            {!! Form::submit(__('Update'), ['class'=>'btn btn-primary col-xs-6 col-sm-6 pull-left', 'name'=>'submit_button','id'=>'submit_button']) !!}
        </div> -->

        <div class="form-group col-sm-4">
            {!! Form::button(__('Update'), ['class'=>'btn btn-success btn-block btnDisable', 'name'=>'submit_button','id'=>'submit_button']) !!}
        </div>

        <div class="form-group col-sm-4">
            {!! Form::button(__('Save Draft'), ['class'=>'btn btn-primary btn-block btnDisable', 'name'=>'save_draft','id'=>'save_draft']) !!}
        </div>

        <div class="form-group col-sm-4">
            {!! Form::reset(__('Reset'), ['class'=>'btn btn-danger btn-block', 'name'=>'reset_button','id'=>'reset_button']) !!}
        </div>

        {!! Form::close() !!}

    </div>
    <!-- <div class="col-xs-12 col-sm-2">
        <div class="form-group">
        @if(count($root_categories) > 0)
            <hr>
            <div>@lang('Category:') @if(!$product->category) <span class="label label-lg label-primary">@lang('None')</span> @else <strong>{{ $product->category->name }} (@lang('ID:') {{ $product->category->id }})@endif</strong></div>
            <ul id="tree1">
                @foreach($root_categories as $category)
                    <li>
                        @if($product->category_id == $category->id)
                            {{ $category->name }}  <span class="glyphicon glyphicon-ok"></span><small><span class="text-muted"> (@lang('Root'))</span></small>
                        @else
                            {{ $category->name }} 
                        @endif
                         
                    </li>
                @endforeach
            </ul>
        @endif

        @if($product->category)
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remove_category"> <strong class="text-danger">@lang('Remove Category')</strong>
                </label>
            </div>
        @endif
        </div>
    </div> -->
</div>

<!-- <hr>

<div class="row">
    <div class="col-xs-12 col-sm-8">
        <label>@lang('Upload more images for this product')</label>
        {!! Form::model($product, ['method'=>'patch', 'action'=>['ManageProductsController@storeMoreImages', $product->id], 'class'=>'dropzone']) !!}
        {!! Form::close() !!}
    </div>
</div> -->
