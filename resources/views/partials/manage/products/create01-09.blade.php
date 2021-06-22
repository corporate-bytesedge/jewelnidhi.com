<div class="row">
    <div class="col-xs-12 col-sm-8">

        @include('includes.form_errors')

        {!! Form::open(['method'=>'post', 'action'=>'ManageProductsController@store', 'files'=>true,'id'=>'productForm', 'onsubmit'=>' submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
         

                    <div class="form-group {{ $errors->has('category') ? ' has-error' : '' }}">
                        <div class="category_box ">
                            <label for="category">@lang('Category:') <em style="color:red;">*</em> </label>
                            <select id="category" name="category" class="form-control ignore" data-style='btn-default'>
                                <option value="">@lang('Choose Option')</option>
                                @foreach($root_categories as $category)
                                    @if(count($category->categories) > 0)
                                        <option class="bolden" value="{{$category->id}}">{{$category->name}}   </option>
                                        <!-- @include('partials.manage.subcategories-select', ['childs' => $category->categories, 'space'=>1]) -->
                                    @else
                                        <option value="{{$category->id}}">{{$category->name}} </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                     
                    </div>
                <div id="styleHtml">
                    <div class="form-group {{ $errors->has('style_id') ? ' has-error' : '' }}">
                        <div class="style_box">
                            <label for="style_id">@lang('Style:') <em style="color:red;">*</em></label>
                            <select name="style_id[]" class="form-control ignore"  id="style_id"  data-style = "btn-default" placholder="Choose Style"  >
                        
                                <option value="">@lang('Choose Style')</option>
                                 
                            </select>
                            
                        </div>
                    </div>
                </div>

                     

                    
                     
        <!-- <div class="form-group existing_product_box">
            {!! Form::label('existing_product', __('From Existing Product:')) !!}
            <select class="form-control selectpicker" data-style="btn-default" name="existing_product" id="existing_product">
                <option value="">@lang('Choose Product')</option>
                @foreach($products as $product)
                <option value="{{$product->id}}">{{$product->name}} ( @lang('ID:') {{$product->id}})</option>
                @endforeach
            </select>
        </div>
        <div id="existing_product_message"></div>
        <hr> -->

        

         <div id="fetch_existing_product">
            {!! Form::hidden('current_price',isset($current_gold_price->value) ? $current_gold_price->value : '', ['class'=>'form-control','id'=>'current_gold_price'])!!}
           
            <div class="form-group {{ $errors->has('sku') ? ' has-error' : '' }}">
                {!! Form::label('jn_web_id', __('JN WEB ID:')) !!} <em style="color:red;">*</em>
                {!! Form::text('jn_web_id', null, ['class'=>'form-control','id'=>'jn_web_id', 'placeholder'=>__('Enter JN WEB ID')])!!}
            </div>
            <div class="form-group {{ $errors->has('sku') ? ' has-error' : '' }}">
                {!! Form::label('sku', __('Product ID:')) !!}
                {!! Form::text('sku', null, ['class'=>'form-control','id'=>'sku', 'placeholder'=>__('Enter Product ID')])!!}
            </div>

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                {!! Form::label('name', __('Product Name:')) !!} <em style="color:red;">*</em>
                {!! Form::text('name', null, ['class'=>'form-control','id'=>'name', 'placeholder'=>__('Enter name')])!!}
            </div>
 

            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                {!! Form::label('description', __('Description:')) !!}
                {!! Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>__('Enter description')]) !!}
            </div>

            <div class="form-group{{ $errors->has('metal_id') ? ' has-error' : '' }}">
                {!! Form::label('metal_id', __('Metal:')) !!} <em style="color:red;">*</em>
                {!! Form::select('metal_id', $metals, null, ['class'=>'form-control','id'=>'metal_id','placeholder' => 'Select Metal'] ); !!}
            </div>

            <div class="form-group{{ $errors->has('silver_item_id') ? ' has-error' : '' }}" id="silverHtml">
                {!! Form::label('silver_item_id', __('Silver Item:')) !!}
                <select name="silver_item_id" id="silver_item_id" class="form-control">
                    <option value="">Select Silver Item</option>
                    @if($silveritems->isNotEmpty())
                        @foreach($silveritems AS $k=> $value)
                            <option data-rate = " {{ $value->rate }}" value="{{ $value->id }}">{{ $value->name }} ( {{ number_format($value->rate,2) }} ) </option>
                        @endforeach
                    @endif
                     
                </select>
                 
            </div>

            <!-- <div class="form-group{{ $errors->has('product_height') ? ' has-error' : '' }}">
                {!! Form::label('product_height', __('Product Height:')) !!}
                {!! Form::number('product_height', null, ['class'=>'form-control','id'=>'product_height',  'placeholder'=>__('Enter Product Height')]) !!}
            </div>

            <div class="form-group{{ $errors->has('product_width') ? ' has-error' : '' }}">
                {!! Form::label('product_width', __('Product Width:')) !!}
                {!! Form::number('product_width', null, ['class'=>'form-control','id'=>'product_width',  'placeholder'=>__('Enter Product Width')]) !!}
            </div> -->

            
            
           

            <!-- <div class="variants">
                <div class="variants-field"></div>
                <button type="button" id="add-variant" class="btn btn-sm btn-primary">@lang('Add Variant')</button>
            </div>   -->

            
            <!-- <div class="form-group{{ $errors->has('sku') ? ' has-error' : '' }}">
                {!! Form::label('sku', __('SKU:')) !!}
                {!! Form::text('sku', null, ['class'=>'form-control', 'placeholder'=>__('Enter sku')])!!}
            </div>
            
           

             

            <div class="form-group{{ $errors->has('hsn') ? ' has-error' : '' }}">
                {!! Form::label('hsn', __('HSN:')) !!}
                {!! Form::text('hsn', null, ['class'=>'form-control', 'placeholder'=>__('Enter hsn')])!!}
            </div> -->

            <!-- <div class="form-group{{ $errors->has('tax_rate') ? ' has-error' : '' }}">
                {!! Form::label('tax_rate', __('Tax Rate:')) !!}
                {!! Form::number('tax_rate', 0, ['class'=>'form-control', 'step'=>'any', 'min'=>0, 'max'=>100, 'placeholder'=>__('Enter tax rate'), 'required']) !!}
            </div> -->
            <input type="hidden" class="colorPickSelector" style="display: none;">
            <!-- <div class="brand_box form-group{{ $errors->has('brand') ? ' has-error' : '' }}">
                {!! Form::label('brand', __('Brand:')) !!}
                {!! Form::select('brand', [''=>__('Choose Option')] + $brands, null, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
            </div> -->

           

            <!-- <div class="category_box form-group{{ $errors->has('comp_group') ? ' has-error' : '' }}">
                <label for="comp_group">@lang('Comparision Group:')</label>
                <select id="comp_group" name="comp_group" class="form-control selectpicker" data-style='btn-default'>
                    <option value="">@lang('Choose Option')</option>
                    @foreach($comparision_groups as $item)
                            <option value="{{$item->cg_id}}">{{$item->title}} (@lang('ID:') {{$item->cg_id}})</option>
                    @endforeach
                </select>
            </div> -->

            <div id="get-specifications"></div>
            
             
            
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
                        <tr id="row_1">
                            <td>
                                {!! Form::select('specification_type[]', $specification_types, null, ['class'=>'form-control selectpicker specification_type', 'data-style'=>'btn-default']) !!}
                            </td>
                            <td>
                                {!! Form::text('specification_type_value[]', null, ['class'=>'form-control specification_type_value','id'=>'specification_type_value', 'placeholder'=>__('Example: 14, 3.5, red')])!!}
                            </td>
                            <td>
                                {!! Form::text('specification_type_unit[]', null, ['class'=>'form-control','id'=>'specification_type_unit', 'placeholder'=>__('kg, GHz (Leave blank if no unit)')])!!}
                            </td>
                            <td>
                                <button class="remove_row btn btn-danger btn-xs" type="button">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right">
                    <button type="button" id="add-more-specification" class="btn btn-success btn-sm">@lang('Add More')</button>
                </div>
            </div>

            <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                {!! Form::label('price', __('Gold Price:')) !!}
                {!! Form::text('price', null, ['id'=>'price', 'class'=>'form-control','placeholder'=>__('Gold Price'),'readonly'=>true])!!}
            </div>
            <div class="form-group{{ $errors->has('diamond_price') ? ' has-error' : '' }}">
                {!! Form::label('diamond_price', __('Dimond Price:')) !!}
                {!! Form::text('diamond_price', null, ['id'=>'diamond_price','class'=>'form-control', 'placeholder'=>__('Dimond Price'),'readonly'=>true])!!}
            </div>

            <div class="form-group{{ $errors->has('vat_rate') ? ' has-error' : '' }}">
                {!! Form::label('vat_rate', __('VA:')) !!}
                {!! Form::text('vat_rate', null, ['id'=>'va','class'=>'form-control','placeholder'=>__('VA(Value added)'),'readonly'=>true])!!}
            </div>

            <div class="form-group{{ $errors->has('gst_three_percent') ? ' has-error' : '' }}">
                {!! Form::label('gst_three_percent', __('GST:')) !!}
                {!! Form::text('gst_three_percent', null, ['id'=>'gst_three_percent','class'=>'form-control', 'placeholder'=>__('GST(3%)'),'readonly'=>true])!!}
            </div>

            {!! Form::hidden('total_weight', null, ['id'=>'total_weight', 'placeholder'=>__('Total weight(g)')])!!}
            {!! Form::hidden('metal_weight', null, ['id'=>'metal_weight', 'placeholder'=>__('Metal weight(g)')])!!}
            {!! Form::hidden('stone_weight', null, ['id'=>'stone_weight', 'placeholder'=>__('Stone weight(g)')])!!}
            {!! Form::hidden('pearls_weight', null, ['id'=>'pearls_weight', 'placeholder'=>__('Stone weight(g)')])!!}
            {!! Form::hidden('diamond_weight', null, ['id'=>'diamond_weight', 'placeholder'=>__('Diamond weight(g)')])!!}
           
            
            {!! Form::hidden('diamond_price_one', null, ['id'=>'diamond_price_one', 'placeholder'=>__('Dimond Price One')])!!}
            {!! Form::hidden('diamond_price_two', null, ['id'=>'diamond_price_two', 'placeholder'=>__('Dimond Price Two')])!!}
            {!! Form::hidden('total_stone_price', null, ['id'=>'total_stone_price', 'placeholder'=>__('Total Stone Price')])!!}
            
            {!! Form::hidden('carat_wt_per_diamond', null, ['id'=>'carat_wt_per_diamond', 'placeholder'=>__('carat_wt_per_diamond')])!!}
            
            {!! Form::hidden('diamond_wtcarats_earrings', null, ['id'=>'diamond_wtcarats_earrings', 'placeholder'=>__('diamond_wtcarats_earrings')])!!}
            {!! Form::hidden('diamond_wtcarats_nackless', null, ['id'=>'diamond_wtcarats_nackless', 'placeholder'=>__('diamond_wtcarats_nackless')])!!}

            {!! Form::hidden('diamond_wtcarats_nackless_price', null, ['id'=>'diamond_wtcarats_nackless_price', 'placeholder'=>__('diamond_wtcarats_nackless_price')])!!}
            {!! Form::hidden('diamond_necklace_carat_price', null, ['id'=>'diamond_necklace_carat_price', 'placeholder'=>__('diamond_necklace_carat_price')])!!}

            
            
            {!! Form::hidden('diamond_wtcarats_earrings_price', null, ['id'=>'diamond_wtcarats_earrings_price', 'placeholder'=>__('diamond_wtcarats_earrings_price')])!!}
            
            
            {!! Form::hidden('subtotal', null, ['id'=>'subtotal', 'placeholder'=>__('Enter SubTotal')])!!}
            
            {!! Form::hidden('carat_weight', null, ['id'=>'carat_weight_val', 'placeholder'=>__('Carat Weight')])!!}
            {!! Form::hidden('carat_price', null, ['id'=>'carat_price_val', 'placeholder'=>__('Carat Price')])!!}
            
            {!! Form::hidden('per_carate_cost', null, ['id'=>'per_carate_cost_val', 'placeholder'=>__('Per Carate Cost')])!!}
            {!! Form::hidden('total_price', null, ['id'=>'total_price', 'placeholder'=>__('Total Price')])!!}
            

            <!-- <div class="custom_fields_box">
                <label for="custom_fields_box">@lang('Custom Fields:')</label>
                <table id="custom_fields_box" class="table table-responsive table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Value')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="custom_fields_rows">
                        <tr>
                            <td>
                                {!! Form::text('custom_field_name[]', null, ['class'=>'form-control', 'placeholder'=>__('Name')])!!}
                            </td>
                            <td>
                                {!! Form::text('custom_field_value[]', null, ['class'=>'form-control', 'placeholder'=>__('Value')])!!}
                            </td>
                            <td>
                                <button class="remove_row btn btn-danger btn-xs" type="button">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-right">
                    <button type="button" id="add-more-field" class="btn btn-success btn-sm">@lang('Add More')</button>
                </div>
            </div> -->
             

            <!-- <div class="checkbox row">
                <div class="col-md-4 col-xs-6">
                    <label>
                        <input type="checkbox" name="downloadable" id="downloadable" 
                        @if(old('downloadable'))
                            checked
                        @endif
                        > <strong>@lang('Downloadable')</strong>
                    </label>
                </div>
                <div class="col-md-4 col-xs-6">
                    <label>
                        <input type="checkbox" name="virtual" id="virtual" 
                        @if(old('virtual'))
                            checked
                        @endif
                        > <strong>@lang('Virtual Product')</strong>
                    </label>
                </div>
            </div> -->
            <div class="form-group{{ $errors->has('old_price') ? ' has-error' : '' }}">
                {!! Form::label('old_price', __('Old Price:')) !!}
                {!! Form::number('old_price', null, ['class'=>'form-control','id'=>'old_price', 'step'=>'any','readonly'=>true, 'placeholder'=>__('Enter Old price')]) !!}
            </div>

            <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                {!! Form::label('price', __('Selling Price:')) !!} <em style="color:red;">*</em>
                {!! Form::number('new_price', null, ['class'=>'form-control','id'=>'new_price', 'step'=>'any', 'placeholder'=>__('Enter  price'),'readonly'=>true, 'required']) !!}
            </div>
            <div class="form-group" id="CertificationHtml">
                <div class="product_box">
                    <label for="certificates[]">@lang('Certification:')</label>
                    <select style="display:none" name="certificates[]" id="certificates[]" placholder="Select Certification" multiple>
                   
                        @foreach($certificates as $k=> $certificate)
                        
                            <option value="{{ $k }}">{{$certificate}} {{'(' . __('ID:') . ' '.$k.')'}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group{{ $errors->has('product_discount') ? ' has-error' : '' }}">
                {!! Form::label('product_discount', __('Product Discount:'))  !!}
                {!! Form::text('product_discount', old('product_discount'), ['class'=>'form-control','id'=>'product_discount',  'placeholder'=>__('Enter Product Discount')]) !!}
            </div>

            <div class="form-group{{ $errors->has('product_discount') ? ' has-error' : '' }}">
                {!! Form::label('product_discount_on', __('Product Discount Text:'))  !!}
                {!! Form::text('product_discount_on', old('product_discount_on'), ['class'=>'form-control','id'=>'product_discount_on',  'placeholder'=>__('Enter Product Discount Text')]) !!}
            </div>

            <!-- <div class="form-group">
                <div class="product_box">
                    <label for="pincode">@lang('Pincode:')</label>
                    <select style="display:none" name="pincode[]" id="pincode[]" placholder="Select Pincode" multiple>
                   
                        @foreach($pincodes as $k=> $pincode)
                        
                            <option value="{{ $k }}">{{$pincode}} {{'(' . __('ID:') . ' '.$k.')'}}</option>
                        @endforeach
                    </select>
                </div>
            </div> -->

            <div class="checkbox row">
                <div class="col-md-4 col-xs-6">
                    <label>
                        <input type="checkbox" name="is_new" id="is_new" 
                        @if(old('is_new'))
                            checked
                        @endif
                        > <strong>@lang('Is New')</strong>
                    </label>
                </div>
                <div class="col-md-4 col-xs-6">
                    <label>
                        <input type="checkbox" name="best_seller" id="best_seller" 
                        @if(old('best_seller'))
                            checked
                        @endif
                        > <strong>@lang('Best Seller')</strong>
                    </label>
                </div>
            </div>

            <div class="form-group" id="downloadable-file">
                {!! Form::label('file', __('Choose File In Zip'), ['class'=>'btn btn-default btn-file']) !!}
                {!! Form::file('file',['class'=>'form-control', 'style'=>'display: none;','onchange'=>'$("#upload-file-name").html(files[0].name)']) !!}
                <span class='label label-info' id="upload-file-name">@lang('No file chosen')</span>
            </div>

            <div class="product-quantity">
                <div class="form-group{{ $errors->has('in_stock') ? ' has-error' : '' }}">
                    {!! Form::label('in_stock', __('Number in Stock:')) !!} <em style="color:red;">*</em>
                    {!! Form::number('in_stock', null, ['class'=>'form-control','id'=>'in_stock', 'placeholder'=>__('Enter number in stock')]) !!}
                </div>
                {!! Form::hidden('qty_per_order', 100, []) !!}
                <!-- <div class="form-group{{ $errors->has('qty_per_order') ? ' has-error' : '' }}">
                    {!! Form::label('qty_per_order', __('Maximum allowed Quantity per Order:')) !!}
                    {!! Form::number('qty_per_order', null, ['class'=>'form-control', 'placeholder'=>__('Enter maximum allowed quantity per order')]) !!}
                </div> -->
            </div>

            <!-- <div class="form-group">
                <div class="product_box">
                    <label for="product[]">@lang('Related Products:')</label>
                    <select style="display:none" name="product[]" id="product[]" multiple>
                        @foreach($products as $related_product)
                            <option value="{{$related_product->id}}">{{$related_product->name}} {{'(' . __('ID:') . ' '.$related_product->id.')'}}</option>
                        @endforeach
                    </select>
                </div>
            </div> -->

            <div class="form-group">
                {!! Form::label('status', __('Status:')) !!}
                {!! Form::select('status', [0=>__('inactive'), 1=>__('active')], 1, ['class'=>'form-control selectpicker', 'data-style'=>'btn-default']) !!}
            </div>

            <div class="form-group{{ $errors->has('offer') ? ' has-error' : '' }}">
                {!! Form::label('offer', __('Offer:')) !!}
                {!! Form::text('offer', null, ['class'=>'form-control', 'placeholder'=>__('Enter offer text') ])!!}
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
                {!! Form::file('photos[]',['class'=>'form-control', 'multiple', 'style'=>'display: none;','onchange'=>'$("#upload-files-info").html(moreImagesNames(files))']) !!}
                <span class='label label-info' id="upload-files-info">@lang('No image chosen')</span>
                <strong>(@lang('Hold Ctrl to select multiple images'))</strong>
            </div>

            @include('partials.manage.meta-fields')

        </div>

        <div class="form-group">
            {!! Form::submit(__('Add Product'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
        </div>

        {!! Form::close() !!}

    </div>

    <div class="col-xs-12 col-sm-2">
        <div class="form-group">
            @if(count($root_categories) > 0)
                <strong>@lang('Categories Tree:')</strong>
                    <ul id="tree1">
                        @foreach($root_categories as $category)
                            <li>
                                {{ $category->name  }}
                                @if(count($category->categories))
                                    @include('partials.manage.subcategories', ['childs' => $category->categories])
                                @endif
                            </li>
                        @endforeach
                    </ul>
                <br>
            @endif
        </div>
    </div>
</div>

 
 
 