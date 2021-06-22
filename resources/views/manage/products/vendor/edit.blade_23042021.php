@extends('layouts.manage')

@section('title')
    @lang('Edit Vendor Product')
@endsection

@section('page-header-title')
    @lang('Edit Products Approval')
@endsection

@section('page-header-description')
    @lang('Edit Products Approval') <a href="{{route('manage.products.vendor_product')}}">@lang('Go Back')</a>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{asset('js/colorpick/colorPick.css')}}">
    <!-- The following line applies the dark theme -->
    <link rel="stylesheet" href="{{asset('js/colorpick/colorPick.dark.theme.css')}}">

   

    <link href="{{asset('css/jquery.dropdown.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/dropzone.min.css')}}">
   
    @include('partials.manage.categories-tree-style')
    <style>
        .bolden {
            font-family: "Arial Black";
        }
        .product-feature-image {
            max-height: 300px;
        }
        .variants {
            margin-bottom: 1.2rem;
        }
        .remove_variant {
            cursor: pointer;
        }
        .variant_name > strong {
            display: inline-block;
            margin-bottom: .5rem;
        }
        .custom_prod_var_span{
            display: flex;
        }
        .custom_prod_var_span>input{
            margin-right: 5px;
        }
        .error {
            color:red;
        }
    </style>
@endsection

@section('scripts')
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/additional-methods.js"></script>
    <script src="{{asset('js/jquery.dropdown.min.js')}}"></script>
    <script src="{{asset('js/dropzone.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/jquery_upload.js')}}"></script>
    @include('partials.manage.categories-tree-script');
    @include('includes.tinymce')
    @if(config('settings.toast_notifications_enable'))
    <script>
        toastr.options.closeButton = true;
        @if(session()->has('product_updated'))
            toastr.success("{{session('product_updated')}}");
        @endif
    </script>
    @endif
    <script>
        $('.brand_box').dropdown({
            // options here
        });
        $('.category_box').dropdown({
            

            choice:function (e) {
                
                if(e.target.dataset.value!='') {
                    $("#styleHtml").html('');
                    
                    $.ajax({
                        url : "{{ route('manage.styleajax') }}",
                        method : "GET",
                        data : { id:e.target.dataset.value },
                        success:function(response) {
                            
                            if(response!='') {
                                 
                                $("#styleHtml").html(response);
                                 $('.style_box').dropdown();
                            }  
                            
                        }
                    });
                    
                }
            }
        });
        $('.product_box').dropdown({
            // options here
        });
        var row_id  = $('.specification_types_rows tr:last').attr('id');
        
         
        $('#add-more-specification').click(function(e) {
            var row_index = $('.specification_types_rows tr:last').attr('id');
            row_index++;
            
            size = $('#specification_types_box >tbody >tr').length + 1,
            content = '<tr id="'+row_index+'">';
            content += '<td><select class="form-control selectpicker specification_type" id="specification_type" data-style="btn-default" name="specification_type[]">';
            content += '<option value="">Select</option>';
            @php
                foreach($specification_types AS $k => $value) {
            @endphp
                content +='<option value="@php echo $k @endphp">@php echo $value @endphp</option>';
            @php 
                }
            @endphp
            content += '</select></td>';
            content +='<td><input type="text" name="specification_type_value[]" class="form-control specification_type_value'+row_index+'" id="specification_type_value" value="" placeholder = "Example: 14, 3.5, red"></td>';
            content +='<td><input type="text" name="specification_type_unit[]" class="form-control" id="specification_type_unit" value="" placeholder = "kg, GHz (Leave blank if no unit)"></td>';
            content += '<td><button class="remove_row btn btn-danger btn-xs removeRow" type="button"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>';
            content +='</tr>';
            $(".removeRow").on("click",function() {
                $(this).parent().parent().remove();
            });
             
            
             $(".specification_types_rows").append(content);

             
              
             
            $(".specification_type").on("change",function() {
                
                var metal_id = $("#metal_id").val();
                var metal = $("#metal_id option:selected").text();
                
                if(metal!='') {
                    
                    $("#specification_type_value").attr('readonly',false);
                    $("#specification_type_unit").attr('readonly',false);
                    $("#add-more-specification").prop('disabled', false);
                    var type = $(this).children("option:selected").val();
                    $(".specification_type_value"+row_index).attr("id",type);

                    
                    
                    
                    if(metal=='Gemstone') {

                        $("#27,#33").on("change",function() {
                            var carat_weight = $("#27").val() ? $("#27").val() : 0;
                            var per_carate_cost = $("#33").val() ? $("#33").val() : 0;
                            var totalCost = parseFloat(carat_weight) * parseInt(per_carate_cost);
                            $("#carat_weight_val").val(carat_weight);
                            $("#per_carate_cost_val").val(per_carate_cost);
                            $("#old_price").val(totalCost);
                        });

                    } else if(metal=='Silver') { 

                        var selectedSilver = $("#silver_item_id").children("option:selected").val(); 
                        if(selectedSilver =='1' ) {
                            $("#9,#14,#17,#43,#64").on("change",function() {
                                        
                                var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                                var purity =   $("#9").val() !=undefined ? $("#9").val() : 0;
                                

                                var metalCost =  parseFloat(metalWeight) * parseFloat($("#current_silver_item_price").val());
                                var valueAdded = (parseFloat($("#17").val()) * parseFloat($("#current_silver_item_price").val()) * parseFloat($("#14").val()))/100;
                                
                                var subTotal =  parseFloat(metalCost) + parseFloat(Math.round(valueAdded));
                                var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(subTotal))/100;
                                var totalPrice = parseFloat(metalCost) + parseFloat(Math.round(valueAdded)) + parseFloat(Math.round(GSTPercentage));

                                var metail_weight = $("#14").val() ? $("#14").val() : 0;
                                $("#metal_weight").val(metail_weight);
                                
                                
                                metalCost = parseFloat(metalCost) || 0.00;
                                $("#price").val(metalCost.toFixed(2));
                                valueAdded = parseFloat(valueAdded) || 0.00;
                                $("#va").val(Math.round(valueAdded).toFixed(2));

                                totalPrice = totalPrice ? totalPrice : 0.00;
                                subTotal = subTotal ? subTotal : 0.00;
                                GSTPercentage = GSTPercentage ? GSTPercentage : 0.00;
                                
                                $("#gst_three_percent").val(Math.round(GSTPercentage).toFixed(2));
                                $("#subtotal").val(Math.round(subTotal).toFixed(2));
                                $("#old_price").val(Math.round(totalPrice).toFixed(2));
                                $("#total_price").val(Math.round(totalPrice).toFixed(2));
                            });
                        } else if(selectedSilver =='2' ) {
                                    
                            $("#9,#14,#43,#65,#16").on("change",function() {
                                    
                                var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                                var purity =   $("#9").val() !=undefined ? $("#9").val() : 0;
                                var perGramRate = $("#65").val() !=undefined ? $("#65").val() : 0;
                                var stonePrice = $("#16").val() !=undefined ? Math.round($("#16").val()) : 0;
                                $("#16").val(stonePrice);

                                var metalPrice =  parseFloat(perGramRate) * parseFloat(metalWeight);
                                
                                
                                
                                
                                
                                
                                
                                metalPrice = parseFloat(metalPrice) || 0.00;
                                $("#price").val(metalPrice.toFixed(2));
                                

                                totalPrice = totalPrice ? totalPrice : 0.00;
                                
                                GSTPercentage = GSTPercentage ? GSTPercentage : 0.00;

                                if(stonePrice!='') {
                                    var subTotal = parseFloat(metalPrice) + parseFloat(stonePrice);
                                } else {
                                    var subTotal = parseFloat(metalPrice) ;
                                }
                                    
                                subTotal = parseFloat(subTotal) || 0.00;
                                var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(subTotal))/100;
                                GSTPercentage = parseFloat(GSTPercentage) || 0.00;
                                var totalPrice = parseFloat(Math.round(subTotal)) + parseFloat(Math.round(GSTPercentage));
                            
                                $("#subtotal").val(Math.round(subTotal).toFixed(2));
                                $("#gst_three_percent").val(GSTPercentage.toFixed(2));
                                $("#old_price").val(Math.round(totalPrice).toFixed(2));
                                $("#total_price").val(Math.round(totalPrice).toFixed(2));
                            });
                        }

                    } else if(metal=='Diamond') {
                        
                        $("#9").on("change",function() {
                            if($(this).val()!='') {
                                
                                    $.ajax({
                                        url:'/manage/getProrityRate/'+$(this).val()+'_CRT',
                                        type:"GET",
                                        dataType : "json",
                                        success:function(data) {
                                            $("#current_gold_price").val(data.value);
                                            puiryDiamondSpecification();
                                        }
                                    });
                            }  else {
                                $("#current_gold_price").val('');
                            }
                        });

                        function puiryDiamondSpecification() {
                            var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                            var diamondWeight =   $("#38").val() !=undefined ? $("#38").val() : 0;
                            var DiamondPrice = $("#73").val() !=undefined ? $("#73").val() : 0;
                            
                            var diamondEarringCaratPrice = $("#63").val() !=undefined ? $("#63").val() : 0;
                            diamondEarringCaratPrice = parseFloat(Math.round(diamondEarringCaratPrice)) || 0.00;
                            
                            $("#diamond_wtcarats_earrings_price").val(Math.round(diamondEarringCaratPrice).toFixed(2));

                            /* diamond neckless */

                            var diamondWtNacklessCarat = parseFloat($("#55").val());
                            diamondWtNacklessCarat = parseFloat(Math.round(diamondWtNacklessCarat)) || 0.00;
                            $("#diamond_wtcarats_nackless").val(Math.round(diamondWtNacklessCarat).toFixed(2));

                            var diamondEarringNacklessPrice =  $("#62").val() !=undefined ? $("#62").val().replace(/,/g, "")  : 0;
                            diamondEarringNacklessPrice = parseFloat(Math.round(diamondEarringNacklessPrice)) || 0.00;
                            $("#diamond_wtcarats_nackless_price").val(Math.round(diamondEarringNacklessPrice).toFixed(2));

                            /* diamond neckless */

                            /* diamond pendant set */

                                var diamondWtPendantCarat = parseFloat($("#74").val());
                                diamondWtPendantCarat = parseFloat(Math.round(diamondWtPendantCarat)) || 0.00;
                                $("#diamond_wtcarats_pendant").val(Math.round(diamondWtPendantCarat).toFixed(2));

                                var diamondEarringPendantPrice =  $("#75").val() !=undefined ? $("#75").val().replace(/,/g, "") : 0;
                                diamondEarringPendantPrice = parseFloat(Math.round(diamondEarringPendantPrice)) || 0.00;
                                $("#diamond_wtcarats_pendant_price").val(Math.round(diamondEarringPendantPrice).toFixed(2));
                            /* diamond pendant set */

                            var totalStonePrice =  $("#46").val() !=undefined ? Math.round($("#46").val()) : 0;
                            totalStonePrice = parseFloat(Math.round(totalStonePrice)) || 0;
                            $("#46").val(totalStonePrice);
                            
                            var caratPrice =  $("#45").val() !=undefined ? $("#45").val() : 0;
                            caratPrice = parseFloat(Math.round(caratPrice)) || 0.00;
                            $("#carat_price_val").val(Math.round(caratPrice));
                            $("#total_stone_price").val(Math.round(totalStonePrice));
                            metalWeight = parseFloat(metalWeight) || 0.00;
                            $("#metal_weight").val(metalWeight);
                            diamondWeight = parseFloat(diamondWeight) || 0.00;
                            $("#diamond_weight").val(diamondWeight);
                            var caratWtPrDiamond = parseFloat(diamondWeight) / parseFloat($("#39").val());
                            caratWtPrDiamond = parseFloat(caratWtPrDiamond) || 0.00;
                            $("#carat_wt_per_diamond").val(caratWtPrDiamond.toFixed(2));
                            var diamondWtEarringCarat = parseFloat($("#52").val());
                            diamondWtEarringCarat = parseFloat(diamondWtEarringCarat) || 0.00;
                            $("#diamond_wtcarats_earrings").val(diamondWtEarringCarat.toFixed(2));
                            


                            var diamondNecklaceCaratPrice = parseFloat($("#54").val()) * parseFloat(75500);
                            diamondNecklaceCaratPrice = parseFloat(Math.round(diamondNecklaceCaratPrice)) || 0.00;
                            $("#diamond_necklace_carat_price").val(Math.round(diamondNecklaceCaratPrice).toFixed(2));
                            
                            
                            if(diamondEarringCaratPrice!='' && diamondEarringNacklessPrice == '' &&  diamondEarringPendantPrice == '') {
                               
                                var diamondEarringPrice = parseFloat(diamondEarringCaratPrice);
                               
                            }
                            
                            else if(diamondEarringNacklessPrice!='' && diamondEarringCaratPrice!='') {
                                
                                var diamondEarringPrice = parseFloat(diamondEarringCaratPrice) + parseFloat(diamondEarringNacklessPrice);
                                
                            } else if(diamondEarringPendantPrice!='' && diamondEarringCaratPrice!='') {

                                
                                var diamondEarringPrice = parseFloat(diamondEarringCaratPrice) + parseFloat(diamondEarringPendantPrice);
                            }
                            
                            
                            var goldPrice = parseFloat($("#14").val()) * parseFloat($("#current_gold_price").val());
                            goldPrice = parseFloat(Math.round(goldPrice)) || 0;
                             
                            $("#price").val(Math.round(goldPrice).toFixed(2));

                            /* diamond pendant set */

                            /* diamond pendant set */
                            console.log('diamondEarringPrice:- '+diamondEarringPrice);
                            if(diamondEarringPrice!=undefined) {
                                var diamondPrice = parseFloat(Math.round(diamondEarringPrice));
                            } else {
                                var diamondPrice = parseFloat(Math.round(DiamondPrice));
                            }
                            diamondPrice = parseFloat(Math.round(diamondPrice)) || 0;
                            
                            $("#diamond_price").val(Math.round(diamondPrice).toFixed(2));
                            var valueAdded = (parseFloat($("#17").val()) * parseFloat(goldPrice) )/100;
                            valueAdded = parseFloat(Math.round(valueAdded)) || 0;
                            $("#va").val(Math.round(valueAdded).toFixed(2));
                            var subTotal = parseFloat(goldPrice) + parseFloat(diamondPrice) + parseFloat(totalStonePrice) + parseFloat(valueAdded);
                            subTotal = parseFloat(Math.round(subTotal)) || 0;
                            $("#subtotal").val(Math.round(subTotal).toFixed(2));
                            var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(subTotal))/100;
                            GSTPercentage = parseFloat(Math.round(GSTPercentage)) || 0;
                            $("#gst_three_percent").val(GSTPercentage.toFixed(2));
                            var totalPrice = parseFloat(goldPrice) + parseFloat(diamondPrice) + parseFloat(totalStonePrice) + parseFloat(valueAdded) + parseFloat(GSTPercentage);
                            totalPrice = parseFloat(totalPrice) || 0;
                            $("#old_price").val(Math.round(totalPrice).toFixed(2));
                            $("#total_price").val(Math.round(totalPrice).toFixed(2));
                        }

                        $("#38,#39,#45,#14,#52,#55,#46,#17,#43,#62,#63,#73,#75,#74").on("change",function() {
                            
                            var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                            var diamondWeight =   $("#38").val() !=undefined ? $("#38").val() : 0;
                            var DiamondPrice = $("#73").val() !=undefined ? $("#73").val() : 0;
                            
                            var diamondEarringCaratPrice = $("#63").val() !=undefined ? $("#63").val() : 0;
                            diamondEarringCaratPrice = parseFloat(Math.round(diamondEarringCaratPrice)) || 0.00;
                            
                            $("#diamond_wtcarats_earrings_price").val(Math.round(diamondEarringCaratPrice).toFixed(2));

                            /* diamond neckless */

                            var diamondWtNacklessCarat = parseFloat($("#55").val());
                            diamondWtNacklessCarat = parseFloat(Math.round(diamondWtNacklessCarat)) || 0.00;
                            $("#diamond_wtcarats_nackless").val(Math.round(diamondWtNacklessCarat).toFixed(2));

                            var diamondEarringNacklessPrice =  $("#62").val() !=undefined ? $("#62").val().replace(/,/g, "")  : 0;
                            diamondEarringNacklessPrice = parseFloat(Math.round(diamondEarringNacklessPrice)) || 0.00;
                            $("#diamond_wtcarats_nackless_price").val(Math.round(diamondEarringNacklessPrice).toFixed(2));

                            /* diamond neckless */

                            /* diamond pendant set */

                                var diamondWtPendantCarat = parseFloat($("#74").val());
                                diamondWtPendantCarat = parseFloat(Math.round(diamondWtPendantCarat)) || 0.00;
                                $("#diamond_wtcarats_pendant").val(Math.round(diamondWtPendantCarat).toFixed(2));

                                var diamondEarringPendantPrice =  $("#75").val() !=undefined ? $("#75").val().replace(/,/g, "") : 0;
                                diamondEarringPendantPrice = parseFloat(Math.round(diamondEarringPendantPrice)) || 0.00;
                                $("#diamond_wtcarats_pendant_price").val(Math.round(diamondEarringPendantPrice).toFixed(2));
                            /* diamond pendant set */

                            var totalStonePrice =  $("#46").val() !=undefined ? Math.round($("#46").val()) : 0;
                            totalStonePrice = parseFloat(Math.round(totalStonePrice)) || 0;
                            $("#46").val(totalStonePrice);
                            
                            var caratPrice =  $("#45").val() !=undefined ? $("#45").val() : 0;
                            caratPrice = parseFloat(Math.round(caratPrice)) || 0.00;
                            $("#carat_price_val").val(Math.round(caratPrice));
                            $("#total_stone_price").val(Math.round(totalStonePrice));
                            metalWeight = parseFloat(metalWeight) || 0.00;
                            $("#metal_weight").val(metalWeight);
                            diamondWeight = parseFloat(diamondWeight) || 0.00;
                            $("#diamond_weight").val(diamondWeight);
                            var caratWtPrDiamond = parseFloat(diamondWeight) / parseFloat($("#39").val());
                            caratWtPrDiamond = parseFloat(caratWtPrDiamond) || 0.00;
                            $("#carat_wt_per_diamond").val(caratWtPrDiamond.toFixed(2));
                            var diamondWtEarringCarat = parseFloat($("#52").val());
                            diamondWtEarringCarat = parseFloat(diamondWtEarringCarat) || 0.00;
                            $("#diamond_wtcarats_earrings").val(diamondWtEarringCarat.toFixed(2));
                            


                            var diamondNecklaceCaratPrice = parseFloat($("#54").val()) * parseFloat(75500);
                            diamondNecklaceCaratPrice = parseFloat(Math.round(diamondNecklaceCaratPrice)) || 0.00;
                            $("#diamond_necklace_carat_price").val(Math.round(diamondNecklaceCaratPrice).toFixed(2));
                            
                            
                            if(diamondEarringCaratPrice!='' && diamondEarringNacklessPrice == '' &&  diamondEarringPendantPrice == '') {
                               
                                var diamondEarringPrice = parseFloat(diamondEarringCaratPrice);
                               
                            }
                            
                            else if(diamondEarringNacklessPrice!='' && diamondEarringCaratPrice!='') {
                                
                                var diamondEarringPrice = parseFloat(diamondEarringCaratPrice) + parseFloat(diamondEarringNacklessPrice);
                                
                            } else if(diamondEarringPendantPrice!='' && diamondEarringCaratPrice!='') {

                                
                                var diamondEarringPrice = parseFloat(diamondEarringCaratPrice) + parseFloat(diamondEarringPendantPrice);
                            }
                            
                            
                            var goldPrice = parseFloat($("#14").val()) * parseFloat($("#current_gold_price").val());
                            goldPrice = parseFloat(Math.round(goldPrice)) || 0;
                            
                            $("#price").val(Math.round(goldPrice).toFixed(2));

                            /* diamond pendant set */

                            /* diamond pendant set */
                            console.log('diamondEarringPrice:- '+diamondEarringPrice);
                            if(diamondEarringPrice!=undefined) {
                                var diamondPrice = parseFloat(Math.round(diamondEarringPrice));
                            } else {
                                var diamondPrice = parseFloat(Math.round(DiamondPrice));
                            }
                            diamondPrice = parseFloat(Math.round(diamondPrice)) || 0;
                            
                            $("#diamond_price").val(Math.round(diamondPrice).toFixed(2));
                            var valueAdded = (parseFloat($("#17").val()) * parseFloat(goldPrice) )/100;
                            valueAdded = parseFloat(Math.round(valueAdded)) || 0;
                            $("#va").val(Math.round(valueAdded).toFixed(2));
                            var subTotal = parseFloat(goldPrice) + parseFloat(diamondPrice) + parseFloat(totalStonePrice) + parseFloat(valueAdded);
                            subTotal = parseFloat(Math.round(subTotal)) || 0;
                            $("#subtotal").val(Math.round(subTotal).toFixed(2));
                            var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(subTotal))/100;
                            GSTPercentage = parseFloat(Math.round(GSTPercentage)) || 0;
                            $("#gst_three_percent").val(GSTPercentage.toFixed(2));
                            var totalPrice = parseFloat(goldPrice) + parseFloat(diamondPrice) + parseFloat(totalStonePrice) + parseFloat(valueAdded) + parseFloat(GSTPercentage);
                            totalPrice = parseFloat(totalPrice) || 0;
                            $("#old_price").val(Math.round(totalPrice).toFixed(2));
                            $("#total_price").val(Math.round(totalPrice).toFixed(2));
                        
                        });
                        
                        

                    } else {
                        
                        /*++++++ */
                        $("#9").on("change",function() {
                            if($(this).val()!='') {
                                
                                    $.ajax({
                                        url:'/manage/getProrityRate/'+$(this).val()+'_CRT',
                                        type:"GET",
                                        dataType : "json",
                                        success:function(data) {
                                            $("#current_gold_price").val(data.value);
                                            puiryGoldSpecification();
                                        }
                                    });
                            }  else {
                                $("#current_gold_price").val('');
                            }
                        });
                        
                         /*++++++ */
                        $("#14,#15,#23,#24,#16,#17,#43,#33,#16,#36").on("change",function() {

                            console.log('metal weight:- '+$("#14").val());
                            var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                            var stoneWeight =   $("#15").val() !=undefined ? $("#15").val() : 0;
                            var pearlsWeight =   $("#23").val() !=undefined ? $("#23").val() : 0;
                            var totalWeight = parseFloat(metalWeight) + parseFloat(stoneWeight) + parseFloat(pearlsWeight);
                            var stonePrice =   $("#16").val() !=undefined ? $("#16").val() : 0;
                            var pearlsPrice =   $("#24").val() !=undefined ? $("#24").val() : 0;
                            var watchcPrice =   $("#36").val() !=undefined ? $("#36").val() : 0;
                            
                            stonePrice = parseFloat(Math.round(stonePrice)) || 0;
                            
                            pearlsPrice = parseFloat(Math.round(pearlsPrice)) || 0;
                            watchcPrice = parseFloat(Math.round(watchcPrice)) || 0;
                            $("#16").val(stonePrice);
                            $("#stone_price").val(stonePrice);
                            $("#pearls_price").val(pearlsPrice);
                            $("#watch_price").val(watchcPrice);
                            var goldPrice = parseFloat($("#14").val()) * parseFloat($("#current_gold_price").val());
                            var valueAdded = (parseFloat($("#17").val()) * parseFloat($("#current_gold_price").val()) * parseFloat($("#14").val()))/100;
                            var subTotal =  parseFloat(Math.round(goldPrice)) + parseFloat(Math.round(stonePrice)) + parseFloat(Math.round(pearlsPrice)) +  parseFloat(Math.round(watchcPrice)) + parseFloat(Math.round(valueAdded));
                            var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(Math.round(subTotal))/100);
                            
                            var totalPrice = parseFloat(Math.round(goldPrice)) + parseFloat(Math.round(stonePrice)) + parseFloat(Math.round(pearlsPrice)) + parseFloat(Math.round(watchcPrice)) + parseFloat(Math.round(valueAdded)) + parseFloat(Math.round(GSTPercentage));
                            var metail_weight = $("#14").val() ? $("#14").val() : 0;
                            $("#metal_weight").val(metail_weight);
                            var stone_weight = $("#15").val() ? $("#15").val() : 0;
                            $("#stone_weight").val(stone_weight);
                            var pearls_weight = $("#23").val() ? $("#23").val() : 0; 
                            $("#pearls_weight").val(pearls_weight);
                            $("#total_weight").val(totalWeight);
                            goldPrice = parseFloat(Math.round(goldPrice)) || 0;
                            $("#price").val(Math.round(goldPrice).toFixed(2));
                            valueAdded = parseFloat(Math.round(valueAdded)) || 0;
                            $("#va").val(Math.round(valueAdded).toFixed(2));
                            totalPrice = totalPrice ? totalPrice : 0.00;
                            subTotal = subTotal ? subTotal : 0.00;
                            GSTPercentage = GSTPercentage ? Math.round(GSTPercentage) : 0;
                            $("#gst_three_percent").val(Math.round(GSTPercentage).toFixed(2));
                            $("#subtotal").val(Math.round(subTotal).toFixed(2));
                            $("#old_price").val(Math.round(totalPrice).toFixed(2));
                            $("#total_price").val(Math.round(totalPrice).toFixed(2));
                        }); 
                
                    
                        function puiryGoldSpecification() {

                            var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                            var stoneWeight =   $("#15").val() !=undefined ? $("#15").val() : 0;
                            var pearlsWeight =   $("#23").val() !=undefined ? $("#23").val() : 0;
                            var totalWeight = parseFloat(metalWeight) + parseFloat(stoneWeight) + parseFloat(pearlsWeight);
                            var stonePrice =   $("#16").val() !=undefined ? $("#16").val() : 0;
                            var pearlsPrice =   $("#24").val() !=undefined ? $("#24").val() : 0;
                            var watchcPrice =   $("#36").val() !=undefined ? $("#36").val() : 0;

                            stonePrice = parseFloat(Math.round(stonePrice)) || 0;

                            pearlsPrice = parseFloat(Math.round(pearlsPrice)) || 0;
                            watchcPrice = parseFloat(Math.round(watchcPrice)) || 0;
                            $("#16").val(stonePrice);
                            $("#stone_price").val(stonePrice);
                            $("#pearls_price").val(pearlsPrice);
                            $("#watch_price").val(watchcPrice);
                            var goldPrice = parseFloat($("#14").val()) * parseFloat($("#current_gold_price").val());

                            var valueAdded = (parseFloat($("#17").val()) * parseFloat($("#current_gold_price").val()) * parseFloat($("#14").val()))/100;
                            var subTotal =  parseFloat(Math.round(goldPrice)) + parseFloat(Math.round(stonePrice)) + parseFloat(Math.round(pearlsPrice)) +  parseFloat(Math.round(watchcPrice)) + parseFloat(Math.round(valueAdded));
                            var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(Math.round(subTotal))/100);

                            var totalPrice = parseFloat(Math.round(goldPrice)) + parseFloat(Math.round(stonePrice)) + parseFloat(Math.round(pearlsPrice)) + parseFloat(Math.round(watchcPrice)) + parseFloat(Math.round(valueAdded)) + parseFloat(Math.round(GSTPercentage));
                            var metail_weight = $("#14").val() ? $("#14").val() : 0;
                            $("#metal_weight").val(metail_weight);
                            var stone_weight = $("#15").val() ? $("#15").val() : 0;
                            $("#stone_weight").val(stone_weight);
                            var pearls_weight = $("#23").val() ? $("#23").val() : 0; 
                            $("#pearls_weight").val(pearls_weight);
                            $("#total_weight").val(totalWeight);
                            goldPrice = parseFloat(Math.round(goldPrice)) || 0;
                            $("#price").val(Math.round(goldPrice).toFixed(2));
                            valueAdded = parseFloat(Math.round(valueAdded)) || 0;
                            $("#va").val(Math.round(valueAdded).toFixed(2));
                            totalPrice = totalPrice ? totalPrice : 0.00;
                            subTotal = subTotal ? subTotal : 0.00;
                            GSTPercentage = GSTPercentage ? Math.round(GSTPercentage) : 0;
                            $("#gst_three_percent").val(Math.round(GSTPercentage).toFixed(2));
                            $("#subtotal").val(Math.round(subTotal).toFixed(2));
                            $("#old_price").val(Math.round(totalPrice).toFixed(2));
                            $("#total_price").val(Math.round(totalPrice).toFixed(2));

                        }
                        /*++++++ */
                    
                        /* ===== */
                    }

                } else {
                    $("#specification_type_value").attr('readonly',true);
                    $("#specification_type_unit").attr('readonly',true);
                    $("#add-more-specification").prop('disabled', true);
                    
                    confirm('Please Select Metal');
                }

            });
            
             
            
            
           
        });

        

        $('#specification_types_box').on('click', '.remove_row', function() {

                 
                 if($('.specification_types_rows tr').length > 1 ) {

                 
                    
                    $('.specification_types_rows tr').each(function(i) {
                      
                      var j = i+1;
                      
                      $(this).find('input').each(function() {
                          $(this).removeClass("specification_type_value"+j);
                          $(this).addClass("specification_type_value"+i);
                      });
                      $(this).attr("id",i);
                       
                      
                  });
  
                  $(this).closest('tr').remove();

                }
                
                
                
                
                
            });
        

        

        $('#add-more-field').click(function() {
            $('.custom_fields_rows > tr:first').clone().appendTo('.custom_fields_rows');
        });
        /* 
        $('#custom_fields_box').on('click', '.remove_row', function(){
            var rowCount =  $('.custom_fields_rows tr').length;
            if(rowCount > 1) {
                $(this).closest('tr').remove();
            }
        });
        */

        var virtualProduct = $('#virtual');
        var productQuantity = $('.product-quantity');

        var downloadableProduct = $('#downloadable');
        var downloadableFile = $('#downloadable-file');

        var removeFile = $('#remove_file');

        downloadableFile.hide();

        if(virtualProduct.is(':checked')) {
            productQuantity.hide();
        }

        $(document).ready(function() {

            virtualProduct.on('change', function() {
                if(virtualProduct.is(':checked')) {
                    productQuantity.fadeOut();
                } else {
                    productQuantity.fadeIn();
                }  
            });

            downloadableProduct.on('change', function() {
                if(downloadableProduct.is(':checked')) {
                    downloadableFile.fadeIn();
                    downloadableFile.find('input[type=file]').filter(':first').attr('name', 'file');
                } else {
                    downloadableFile.fadeOut();
                    downloadableFile.find('input[type=file]').filter(':first').removeAttr('name');
                }
            });

            removeFile.on('change', function() {
                if(removeFile.is(':checked')) {
                    downloadableFile.fadeIn();
                    downloadableFile.find('input[type=file]').filter(':first').attr('name', 'file');
                } else {
                    downloadableFile.fadeOut();
                    downloadableFile.find('input[type=file]').filter(':first').removeAttr('name');
                }
            });
        });
        
        jQuery(document).ready(function() {
            $(".progress").hide();
            $.validator.addMethod("checkWebID", 
                function(value, element) {
                    var result = false;
                    console.log('id:- '+id);
                    $.ajax({
                        type:"GET",
                        async: false,
                        url: "{{ route('manage.checkvendorproductwebid') }}", // script to validate in server side
                        data: {sku: value,id:id},
                        dataType:'JSON',
                        cache: true,
                        success: function(data) {
                            console.log('data:- '+data );
                            result = (data == true) ? true : false;
                            
                        }
                    });
                    // return true if username is exist in database
                    return result; 
                }, 
                "JN Web ID already exists."
            );

            $("#productVendorForm").validate({
                rules: {
                     
                     "category": "required",
                     "style_id[]": "required",
                     "jn_web_id": {
                         required: true,
                         checkWebID: true
                     },
                     "name": "required"
                     
                     
                 },
                 ignore: ":hidden:not(.ignore)",
                 
                 errorClass: 'error',
                 messages: {
                     
                     "category": "Please select category",
                     "style_id[]": "Please select style",
                     "jn_web_id":{
                         required: "Please enter JN Web ID"
                         
                     },
                     
                     "name": "Please enter name",
                     "metal_id" : "Product metal is required",
                     "old_price" : "Selling price is required"
                     
                     
                 },
                submitHandler: function (form) { // for demo	
                    form.submit();
                }
            });

            $('#save_draft').click(function () {
                console.log('save draft');
                $("#save_draft_btn").val('save_draft');
                $('[name="metal_id"], [name="old_price"]').each(function () {
                    $(this).rules('remove');
                });
                $("#productVendorForm").submit();  // validation test only
            });

            $('#submit_button').click(function () {
                console.log('submit button');
                $("#submit_button_btn").val('submit_button');
                $('[name="name"], [name="category"],[name="style_id"],[name="jn_web_id"], [name="metal_id"], [name="old_price"]').each(function () {
                    $(this).rules('add', {
                        required: true
                    });
                });
                $("#productVendorForm").submit();  // validate and submit
            });
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

            var metal = $("#metal_id option:selected").text();
            if(metal=='Gemstone') {

                $("#27,#33").on("change",function() {
                    var carat_weight = $("#27").val() ? $("#27").val() : 0;
                    var per_carate_cost = $("#33").val() ? $("#33").val() : 0;
                    var totalCost = parseFloat(carat_weight) * parseInt(per_carate_cost);
                    $("#carat_weight_val").val(carat_weight);
                    $("#per_carate_cost_val").val(per_carate_cost);
                    $("#old_price").val(totalCost);
                });

            }  else if(metal=='Silver') { 
                var selectedSilver = $("#silver_item_id").children("option:selected").val(); 
                 
                if(selectedSilver =='1' ) {
                    $("#9,#14,#17,#43,#64").on("change",function() {
                                
                        var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                        var purity =   $("#9").val() !=undefined ? $("#9").val() : 0;
                        

                        var metalCost =  parseFloat(metalWeight) * parseFloat($("#current_silver_item_price").val());
                        var valueAdded = (parseFloat($("#17").val()) * parseFloat($("#current_silver_item_price").val()) * parseFloat($("#14").val()))/100;
                         
                        var subTotal =  parseFloat(metalCost) + parseFloat(Math.round(valueAdded));
                        var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(subTotal))/100;
                        var totalPrice = parseFloat(metalCost) + parseFloat(Math.round(valueAdded)) + parseFloat(Math.round(GSTPercentage));

                        var metail_weight = $("#14").val() ? $("#14").val() : 0;
                        $("#metal_weight").val(metail_weight);
                        
                        
                        metalCost = parseFloat(metalCost) || 0.00;
                        $("#price").val(Math.round(metalCost).toFixed(2));
                        valueAdded = parseFloat(valueAdded) || 0.00;
                        $("#va").val(Math.round(valueAdded).toFixed(2));

                        totalPrice = totalPrice ? totalPrice : 0.00;
                        subTotal = subTotal ? subTotal : 0.00;
                        GSTPercentage = GSTPercentage ? GSTPercentage : 0.00;
                        
                        $("#gst_three_percent").val(Math.round(GSTPercentage).toFixed(2));
                        $("#subtotal").val(Math.round(subTotal).toFixed(2));
                        $("#old_price").val(Math.round(totalPrice).toFixed(2));
                        $("#total_price").val(Math.round(totalPrice).toFixed(2));
                    });

                    silverItemSpecificationOnLoad();

                } else if(selectedSilver =='2' ) {
                            
                    $("#9,#14,#43,#65,#16").on("change",function() {
                                
                        var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                        var purity =   $("#9").val() !=undefined ? $("#9").val() : 0;
                        var perGramRate = $("#65").val() !=undefined ? $("#65").val() : 0;
                        var stonePrice = $("#16").val() !=undefined ? Math.round($("#16").val()) : 0;
                        $("#16").val(stonePrice);

                        var metalPrice =  parseFloat(perGramRate) * parseFloat(metalWeight);
                        metalPrice = parseFloat(metalPrice) || 0.00;
                        $("#price").val(Math.round(metalPrice).toFixed(2));
                        

                        totalPrice = totalPrice ? totalPrice : 0.00;
                        
                        GSTPercentage = GSTPercentage ? GSTPercentage : 0.00;

                        if(stonePrice!='') {
                            var subTotal = parseFloat(metalPrice) + parseFloat(stonePrice);
                        } else {
                            var subTotal = parseFloat(metalPrice) ;
                        }
                            
                        subTotal = parseFloat(subTotal) || 0.00;
                        var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(subTotal))/100;
                        GSTPercentage = parseFloat(GSTPercentage) || 0.00;
                        var totalPrice = parseFloat(subTotal) + parseFloat(GSTPercentage);
                    
                        $("#subtotal").val(Math.round(subTotal).toFixed(2));
                        $("#gst_three_percent").val(Math.round(GSTPercentage).toFixed(2));
                        $("#old_price").val(Math.round(totalPrice).toFixed(2));
                        $("#total_price").val(Math.round(totalPrice).toFixed(2));
                    });

                    silverJewellerySpecificationOnLoad();
                }

                //  $("#goldPriceHtml").hide();
                //  
                $("#pricelabel").html('Metal cost');
                $("#dimondPriceHtml").hide();
            } else if(metal=='Diamond') {
                     
                diamondSpecification();

                    

                             

            } else {
                
                goldSpecification();
                         
            }


             $("#metal_id").on("change",function() {
                 
                 if($("#metal_id").val() == '13') {
                    $("#silverHtml").show();
                 } else {
                    $("#silverHtml").hide();
                 }
                
                var content = $('#specification_types_box tbody tr:first'),
                
                content = '<tr>';
                content += '<td><select class="form-control selectpicker specification_type" id="specification_type" data-style="btn-default" name="specification_type[]">';
                content += '<option value="">Select</option>';
                @php
                    foreach($specification_types AS $k => $value) {
                @endphp
                    content +='<option value="@php echo $k @endphp">@php echo $value @endphp</option>';
                @php 
                }
                @endphp
                content += '</select></td>';
                content +='<td><input type="text" name="specification_type_value[]" class="form-control specification_type_value" id="specification_type_value" value="" placeholder = "Example: 14, 3.5, red"></td>';
                content +='<td><input type="text" name="specification_type_unit[]" class="form-control" id="specification_type_unit" value="" placeholder = "kg, GHz (Leave blank if no unit)"></td>';
                content += '<td><button class="remove_row btn btn-danger btn-xs removeRow" type="button"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>';
                content +='</tr>';
                $(".removeRow").on("click",function() {
                    $(this).parent().parent().remove();
                });
                $("#specification_types_box .specification_types_rows").html(content);

                $(".specification_type").on("change",function() {   
                    var type = $(this).children("option:selected").val();
            
                    $(".specification_type_value").attr("id",type);
                    var metal = $("#metal_id").children("option:selected").text(); 
                    if(metal == 'Gold') {
                        goldSpecification();
                    }
                        
                    
                    
                });
                $("#price").val('');
                $("#diamond_price").val('');
                $("#va").val('');
                $("#gst_three_percent").val('');
                $("#old_price").val('');
                $("#new_price").val('');
                $("#total_weight").val('');
                $("#metal_weight").val('');
                $("#stone_weight").val('');
                $("#pearls_weight").val('');
                $("#diamond_weight").val('');
                $("#diamond_price_one").val('');
                $("#diamond_price_two").val('');
                $("#stone_price").val('');
                $("#pearls_price").val('');
                $("#watch_price").val('');
                $("#total_stone_price").val('');
                $("#carat_wt_per_diamond").val('');
                $("#diamond_wtcarats_earrings").val('');
                $("#diamond_wtcarats_nackless").val('');
                $("#diamond_wtcarats_nackless_price").val('');
             });

            $("#silver_item_id").on("change",function() {
                
                if($(this).val()!='') {
                     
                    $("#price").val('');
                    $("#va").val('');
                    $("#gst_three_percent").val('');
                    $("#total_price").val('');
                    
                    var content = $('#specification_types_box tbody tr:first'),

                    content = '<tr>';
                    content += '<td><select class="form-control selectpicker specification_type" id="specification_type" data-style="btn-default" name="specification_type[]">';
                    content += '<option value="">Select</option>';
                    @php
                    foreach($specification_types AS $k => $value) {
                    @endphp
                    content +='<option value="@php echo $k @endphp">@php echo $value @endphp</option>';
                    @php 
                    }
                    @endphp
                    content += '</select></td>';
                    content +='<td><input type="text" name="specification_type_value[]" class="form-control specification_type_value" id="specification_type_value" value="" placeholder = "Example: 14, 3.5, red"></td>';
                    content +='<td><input type="text" name="specification_type_unit[]" class="form-control" id="specification_type_unit" value="" placeholder = "kg, GHz (Leave blank if no unit)"></td>';
                    content += '<td><button class="remove_row btn btn-danger btn-xs removeRow" type="button"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></td>';
                    content +='</tr>';
                    $(".removeRow").on("click",function() {
                    $(this).parent().parent().remove();
                    });
                    $("#specification_types_box .specification_types_rows").html(content);
                    $(".specification_type").on("change",function() {
                        var type = $(this).children("option:selected").val();
                 
                        $(".specification_type_value").attr("id",type);
                        var selectedSilver = $("#silver_item_id").children("option:selected").val(); 

                        if(selectedSilver =='1' ) {
                            $("#9,#14,#17,#43,#64").on("change",function() {
                                
                                var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                                var purity =   $("#9").val() !=undefined ? $("#9").val() : 0;
                                

                                var metalCost =  parseFloat(metalWeight) * parseFloat($("#current_silver_item_price").val());
                                var valueAdded = (parseFloat($("#17").val()) * parseFloat($("#current_silver_item_price").val()) * parseFloat($("#14").val()))/100;
                                 
                                var subTotal =  parseFloat(metalCost) + parseFloat(valueAdded);
                                var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(subTotal))/100;
                                var totalPrice = parseFloat(metalCost) + parseFloat(Math.round(valueAdded)) + parseFloat(Math.round(GSTPercentage));

                                var metail_weight = $("#14").val() ? $("#14").val() : 0;
                                $("#metal_weight").val(metail_weight);
                                
                                
                                metalCost = parseFloat(metalCost) || 0.00;
                                $("#price").val(metalCost.toFixed(2));
                                valueAdded = parseFloat(valueAdded) || 0.00;
                                $("#va").val(Math.round(valueAdded).toFixed(2));

                                totalPrice = totalPrice ? totalPrice : 0.00;
                                subTotal = subTotal ? subTotal : 0.00;
                                GSTPercentage = GSTPercentage ? GSTPercentage : 0.00;
                                
                                $("#gst_three_percent").val(Math.round(GSTPercentage).toFixed(2));
                                $("#subtotal").val(Math.round(subTotal).toFixed(2));
                                $("#old_price").val(Math.round(totalPrice).toFixed(2));
                                $("#total_price").val(Math.round(totalPrice).toFixed(2));
                            });


                            
                            
                           

                        } else if(selectedSilver =='2' ) {
                            
                            $("#9,#14,#43,#65,#16").on("change",function() {
                                
                                var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                                var purity =   $("#9").val() !=undefined ? $("#9").val() : 0;
                                var perGramRate = $("#65").val() !=undefined ? $("#65").val() : 0;
                                var stonePrice = $("#16").val() !=undefined ? Math.round($("#16").val()) : 0;
                                $("#16").val(stonePrice);

                                var metalPrice =  parseFloat(perGramRate) * parseFloat(metalWeight);
                                
                                
                                
                                
                                
                                
                                
                                metalPrice = parseFloat(metalPrice) || 0.00;
                                $("#price").val(metalPrice.toFixed(2));
                                

                                totalPrice = totalPrice ? totalPrice : 0.00;
                                
                                GSTPercentage = GSTPercentage ? GSTPercentage : 0.00;

                                if(stonePrice!='') {
                                    var subTotal = parseFloat(metalPrice) + parseFloat(stonePrice);
                                } else {
                                    var subTotal = parseFloat(metalPrice) ;
                                }
                                    
                                subTotal = parseFloat(subTotal) || 0.00;
                                var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(subTotal))/100;
                                GSTPercentage = parseFloat(GSTPercentage) || 0.00;
                                var totalPrice = parseFloat(subTotal) + parseFloat(Math.round(GSTPercentage));
                            
                                $("#subtotal").val(Math.round(subTotal).toFixed(2));
                                $("#gst_three_percent").val(Math.round(GSTPercentage).toFixed(2));
                                $("#old_price").val(Math.round(totalPrice).toFixed(2));
                                $("#total_price").val(Math.round(totalPrice).toFixed(2));
                             });
                        } 
                         
                    });
                    $(".specification_type").prop("disabled", false);
                    $("#specification_type_value").attr('readonly',false);
                    $("#specification_type_unit").attr('readonly',false);
                    $("#add-more-specification").prop('disabled', false);
                    
                } else {
                    $(".specification_type").prop("disabled", true);
                    $("#specification_type_value").attr('readonly',true);
                    $("#specification_type_unit").attr('readonly',true);
                    $("#add-more-specification").prop('disabled', true);
                }
                
            });
            
            function goldSpecification() {
                console.log('=====');
                    $("#9").on("change",function() {
                       
                        if($(this).val()!='') {
                            $.ajax({
                                url:'/manage/getProrityRate/'+$(this).val()+'_CRT',
                                type:"GET",
                                dataType : "json",
                                success:function(data) {
                                    $("#current_gold_price").val(data.value);
                                    puiryGoldSpecification();
                                }
                            });
                        }  else {
                            $("#current_gold_price").val('');
                        }
                    });

                    /*++++++ */
                    $(".specification_type").on("change",function() {
                         
                                var metal_id = $("#metal_id").val();
                                var metal = $("#metal_id option:selected").text();
                                
                                if(metal_id!='') {

                                    var type = $(this).children("option:selected").val();
                                    var rowid = $(this).closest('tr').attr('id'); 
                                     
                                    $(".specification_type_value"+rowid).attr("id",type);
                                    $(".specification_type_value"+rowid).val('');
                                    if(type=='va-') {
                                        type = 'va_percent';
                                    }
                                    
                                    $("#9").on("change",function() {
                                        
                                        if($(this).val()!='') {
                                            
                                            $.ajax({
                                                url:'/manage/getProrityRate/'+$(this).val()+'_CRT',
                                                type:"GET",
                                                dataType : "json",
                                                success:function(data) {
                                                    $("#current_gold_price").val(data.value);
                                                }
                                            });
                                        }  else {
                                            $("#current_gold_price").val('');
                                        }
                                    });
                                    
                                    $("#14,#15,#23,#24,#16,#17,#43,#33,#16,#36").on("change",function() {
                        
                                        var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                                        var stoneWeight =   $("#15").val() !=undefined ? $("#15").val() : 0;
                                        var pearlsWeight =   $("#23").val() !=undefined ? $("#23").val() : 0;
                                        var totalWeight = parseFloat(metalWeight) + parseFloat(stoneWeight) + parseFloat(pearlsWeight);
                                        var stonePrice =   $("#16").val() !=undefined ? $("#16").val() : 0;
                                        var pearlsPrice =   $("#24").val() !=undefined ? $("#24").val() : 0;
                                        var watchcPrice =   $("#36").val() !=undefined ? $("#36").val() : 0;
                                        
                                        stonePrice = parseFloat(Math.round(stonePrice)) || 0;
                                        
                                        pearlsPrice = parseFloat(Math.round(pearlsPrice)) || 0;
                                        watchcPrice = parseFloat(Math.round(watchcPrice)) || 0;
                                        $("#16").val(stonePrice);
                                        $("#stone_price").val(stonePrice);
                                        $("#pearls_price").val(pearlsPrice);
                                        $("#watch_price").val(watchcPrice);
                                        var goldPrice = parseFloat($("#14").val()) * parseFloat($("#current_gold_price").val());
                                        var valueAdded = (parseFloat($("#17").val()) * parseFloat($("#current_gold_price").val()) * parseFloat($("#14").val()))/100;
                                        var subTotal =  parseFloat(Math.round(goldPrice)) + parseFloat(Math.round(stonePrice)) + parseFloat(Math.round(pearlsPrice)) +  parseFloat(Math.round(watchcPrice)) + parseFloat(Math.round(valueAdded));
                                        var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(Math.round(subTotal))/100);
                                        
                                        var totalPrice = parseFloat(Math.round(goldPrice)) + parseFloat(Math.round(stonePrice)) + parseFloat(Math.round(pearlsPrice)) + parseFloat(Math.round(watchcPrice)) + parseFloat(Math.round(valueAdded)) + parseFloat(Math.round(GSTPercentage));
                                        var metail_weight = $("#14").val() ? $("#14").val() : 0;
                                        $("#metal_weight").val(metail_weight);
                                        var stone_weight = $("#15").val() ? $("#15").val() : 0;
                                        $("#stone_weight").val(stone_weight);
                                        var pearls_weight = $("#23").val() ? $("#23").val() : 0; 
                                        $("#pearls_weight").val(pearls_weight);
                                        $("#total_weight").val(totalWeight);
                                        goldPrice = parseFloat(Math.round(goldPrice)) || 0;
                                        $("#price").val(Math.round(goldPrice).toFixed(2));
                                        valueAdded = parseFloat(Math.round(valueAdded)) || 0;
                                        $("#va").val(Math.round(valueAdded).toFixed(2));
                                        totalPrice = totalPrice ? totalPrice : 0.00;
                                        subTotal = subTotal ? subTotal : 0.00;
                                        GSTPercentage = GSTPercentage ? Math.round(GSTPercentage) : 0;
                                        $("#gst_three_percent").val(Math.round(GSTPercentage).toFixed(2));
                                        $("#subtotal").val(Math.round(subTotal).toFixed(2));
                                        $("#old_price").val(Math.round(totalPrice).toFixed(2));
                                        $("#total_price").val(Math.round(totalPrice).toFixed(2));
                                    });

                                    
                                
                                } else {
                                    $("#specification_type_value").attr('readonly',true);
                                    $("#specification_type_unit").attr('readonly',true);
                                    $("#add-more-specification").prop('disabled', true);
                                    
                                    confirm('Please Select Metal');
                                }
                    });
                    /* ===== */
                    function puiryGoldSpecification() {

                        var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                        var stoneWeight =   $("#15").val() !=undefined ? $("#15").val() : 0;
                        var pearlsWeight =   $("#23").val() !=undefined ? $("#23").val() : 0;
                        var totalWeight = parseFloat(metalWeight) + parseFloat(stoneWeight) + parseFloat(pearlsWeight);
                        var stonePrice =   $("#16").val() !=undefined ? $("#16").val() : 0;
                        var pearlsPrice =   $("#24").val() !=undefined ? $("#24").val() : 0;
                        var watchcPrice =   $("#36").val() !=undefined ? $("#36").val() : 0;

                        stonePrice = parseFloat(Math.round(stonePrice)) || 0;

                        pearlsPrice = parseFloat(Math.round(pearlsPrice)) || 0;
                        watchcPrice = parseFloat(Math.round(watchcPrice)) || 0;
                        $("#16").val(stonePrice);
                        $("#stone_price").val(stonePrice);
                        $("#pearls_price").val(pearlsPrice);
                        $("#watch_price").val(watchcPrice);
                        var goldPrice = parseFloat($("#14").val()) * parseFloat($("#current_gold_price").val());

                        var valueAdded = (parseFloat($("#17").val()) * parseFloat($("#current_gold_price").val()) * parseFloat($("#14").val()))/100;
                        var subTotal =  parseFloat(Math.round(goldPrice)) + parseFloat(Math.round(stonePrice)) + parseFloat(Math.round(pearlsPrice)) +  parseFloat(Math.round(watchcPrice)) + parseFloat(Math.round(valueAdded));
                        var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(Math.round(subTotal))/100);

                        var totalPrice = parseFloat(Math.round(goldPrice)) + parseFloat(Math.round(stonePrice)) + parseFloat(Math.round(pearlsPrice)) + parseFloat(Math.round(watchcPrice)) + parseFloat(Math.round(valueAdded)) + parseFloat(Math.round(GSTPercentage));
                        var metail_weight = $("#14").val() ? $("#14").val() : 0;
                        $("#metal_weight").val(metail_weight);
                        var stone_weight = $("#15").val() ? $("#15").val() : 0;
                        $("#stone_weight").val(stone_weight);
                        var pearls_weight = $("#23").val() ? $("#23").val() : 0; 
                        $("#pearls_weight").val(pearls_weight);
                        $("#total_weight").val(totalWeight);
                        goldPrice = parseFloat(Math.round(goldPrice)) || 0;
                        $("#price").val(Math.round(goldPrice).toFixed(2));
                        valueAdded = parseFloat(Math.round(valueAdded)) || 0;
                        $("#va").val(Math.round(valueAdded).toFixed(2));
                        totalPrice = totalPrice ? totalPrice : 0.00;
                        subTotal = subTotal ? subTotal : 0.00;
                        GSTPercentage = GSTPercentage ? Math.round(GSTPercentage) : 0;
                        $("#gst_three_percent").val(Math.round(GSTPercentage).toFixed(2));
                        $("#subtotal").val(Math.round(subTotal).toFixed(2));
                        $("#old_price").val(Math.round(totalPrice).toFixed(2));
                        $("#total_price").val(Math.round(totalPrice).toFixed(2));

                    }
                    

                    $("#14,#15,#23,#24,#16,#17,#43,#33,#16,#36").on("change",function() {
                        
                        var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                        var stoneWeight =   $("#15").val() !=undefined ? $("#15").val() : 0;
                        var pearlsWeight =   $("#23").val() !=undefined ? $("#23").val() : 0;
                        var totalWeight = parseFloat(metalWeight) + parseFloat(stoneWeight) + parseFloat(pearlsWeight);
                        var stonePrice =   $("#16").val() !=undefined ? $("#16").val() : 0;
                        var pearlsPrice =   $("#24").val() !=undefined ? $("#24").val() : 0;
                        var watchcPrice =   $("#36").val() !=undefined ? $("#36").val() : 0;
                        
                        stonePrice = parseFloat(Math.round(stonePrice)) || 0;
                        
                        pearlsPrice = parseFloat(Math.round(pearlsPrice)) || 0;
                        watchcPrice = parseFloat(Math.round(watchcPrice)) || 0;
                        $("#16").val(stonePrice);
                        $("#stone_price").val(stonePrice);
                        $("#pearls_price").val(pearlsPrice);
                        $("#watch_price").val(watchcPrice);
                        var goldPrice = parseFloat($("#14").val()) * parseFloat($("#current_gold_price").val());
                        var valueAdded = (parseFloat($("#17").val()) * parseFloat($("#current_gold_price").val()) * parseFloat($("#14").val()))/100;
                        var subTotal =  parseFloat(Math.round(goldPrice)) + parseFloat(Math.round(stonePrice)) + parseFloat(Math.round(pearlsPrice)) +  parseFloat(Math.round(watchcPrice)) + parseFloat(Math.round(valueAdded));
                        var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(Math.round(subTotal))/100);
                        
                        var totalPrice = parseFloat(Math.round(goldPrice)) + parseFloat(Math.round(stonePrice)) + parseFloat(Math.round(pearlsPrice)) + parseFloat(Math.round(watchcPrice)) + parseFloat(Math.round(valueAdded)) + parseFloat(Math.round(GSTPercentage));
                        var metail_weight = $("#14").val() ? $("#14").val() : 0;
                        $("#metal_weight").val(metail_weight);
                        var stone_weight = $("#15").val() ? $("#15").val() : 0;
                        $("#stone_weight").val(stone_weight);
                        var pearls_weight = $("#23").val() ? $("#23").val() : 0; 
                        $("#pearls_weight").val(pearls_weight);
                        $("#total_weight").val(totalWeight);
                        goldPrice = parseFloat(Math.round(goldPrice)) || 0;
                        $("#price").val(Math.round(goldPrice).toFixed(2));
                        valueAdded = parseFloat(Math.round(valueAdded)) || 0;
                        $("#va").val(Math.round(valueAdded).toFixed(2));
                        totalPrice = totalPrice ? totalPrice : 0.00;
                        subTotal = subTotal ? subTotal : 0.00;
                        GSTPercentage = GSTPercentage ? Math.round(GSTPercentage) : 0;
                        $("#gst_three_percent").val(Math.round(GSTPercentage).toFixed(2));
                        $("#subtotal").val(Math.round(subTotal).toFixed(2));
                        $("#old_price").val(Math.round(totalPrice).toFixed(2));
                        $("#total_price").val(Math.round(totalPrice).toFixed(2));
                    });
                    
                    var oldPrice = $("#old_price").val();
                    
                    if(oldPrice == '0.00') {
                        $("#product_discount").val('');
                        $("#new_price").val('0');
                    }

                    
                    

                        
            }
            /* on body load load silver item specification value */
                function silverItemSpecificationOnLoad() {
                        var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                        var purity =   $("#9").val() !=undefined ? $("#9").val() : 0;
                        

                        var metalCost =  parseFloat(metalWeight) * parseFloat($("#current_silver_item_price").val());
                        var valueAdded = (parseFloat($("#17").val()) * parseFloat($("#current_silver_item_price").val()) * parseFloat($("#14").val()))/100;
                         
                        var subTotal =  parseFloat(metalCost) + parseFloat(Math.round(valueAdded));
                        var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(subTotal))/100;
                        var totalPrice = parseFloat(metalCost) + parseFloat(Math.round(valueAdded)) + parseFloat(Math.round(GSTPercentage));

                        var metail_weight = $("#14").val() ? $("#14").val() : 0;
                        $("#metal_weight").val(metail_weight);
                        
                        
                        metalCost = parseFloat(metalCost) || 0.00;
                        $("#price").val(Math.round(metalCost).toFixed(2));
                        valueAdded = parseFloat(valueAdded) || 0.00;
                        $("#va").val(Math.round(valueAdded).toFixed(2));

                        totalPrice = totalPrice ? totalPrice : 0.00;
                        subTotal = subTotal ? subTotal : 0.00;
                        GSTPercentage = GSTPercentage ? GSTPercentage : 0.00;
                        
                        $("#gst_three_percent").val(Math.round(GSTPercentage).toFixed(2));
                        $("#subtotal").val(Math.round(subTotal).toFixed(2));
                        $("#old_price").val(Math.round(totalPrice).toFixed(2));
                        $("#total_price").val(Math.round(totalPrice).toFixed(2));

                        if($("#product_discount").val()!='') {
                        
                            var totalPrice = $("#old_price").val();
                            var discount = parseFloat(totalPrice) * parseFloat($("#product_discount").val())/100;
                            console.log('discount:- '+discount);
                            var discountPrice = parseFloat(totalPrice) - parseFloat(discount);
                            
                            $("#new_price").val( Math.round(discountPrice).toFixed(2));
                        } else {
                            $("#new_price").val('');
                        }
                }
            /* on body load load silver item specification value */

            /* on body load load silver jewellery specification value */

                function silverJewellerySpecificationOnLoad() {
                    
                    var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                    var purity =   $("#9").val() !=undefined ? $("#9").val() : 0;
                    var perGramRate = $("#65").val() !=undefined ? $("#65").val() : 0;
                    var stonePrice = $("#16").val() !=undefined ? Math.round($("#16").val()) : 0;
                    $("#16").val(stonePrice);

                    var metalPrice =  parseFloat(perGramRate) * parseFloat(metalWeight);
                    metalPrice = parseFloat(metalPrice) || 0.00;
                    $("#price").val(Math.round(metalPrice).toFixed(2));
                    

                    totalPrice = totalPrice ? totalPrice : 0.00;
                    
                    GSTPercentage = GSTPercentage ? GSTPercentage : 0.00;

                    if(stonePrice!='') {
                        var subTotal = parseFloat(metalPrice) + parseFloat(stonePrice);
                    } else {
                        var subTotal = parseFloat(metalPrice) ;
                    }
                        
                    subTotal = parseFloat(subTotal) || 0.00;
                    var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(subTotal))/100;
                    GSTPercentage = parseFloat(GSTPercentage) || 0.00;
                    var totalPrice = parseFloat(subTotal) + parseFloat(GSTPercentage);
                
                    $("#subtotal").val(Math.round(subTotal).toFixed(2));
                    $("#gst_three_percent").val(Math.round(GSTPercentage).toFixed(2));
                    $("#old_price").val(Math.round(totalPrice).toFixed(2));
                    $("#total_price").val(Math.round(totalPrice).toFixed(2));

                    if($("#product_discount").val()!='') {
                        
                        var totalPrice = $("#old_price").val();
                        var discount = parseFloat(totalPrice) * parseFloat($("#product_discount").val())/100;
                        console.log('discount:- '+discount);
                        var discountPrice = parseFloat(totalPrice) - parseFloat(discount);
                        
                        $("#new_price").val( Math.round(discountPrice).toFixed(2));
                    } else {
                        $("#new_price").val('');
                    }

                }
            /* on body load load silver jewellery specification value */
            

            function diamondSpecification() {
                 console.log('diamondSpecification');
                $("#9").on("change",function() {
                    
                    if($(this).val()!='') {
                        $.ajax({
                            url:'/manage/getProrityRate/'+$(this).val()+'_CRT',
                            type:"GET",
                            dataType : "json",
                            success:function(data) {
                                $("#current_gold_price").val(data.value);
                                puiryDiamondSpecification();
                            }
                        });
                    }  else {
                        $("#current_gold_price").val('');
                    }
                });

                function puiryDiamondSpecification() {
                    var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                    var diamondWeight =   $("#38").val() !=undefined ? $("#38").val() : 0;
                    var DiamondPrice = $("#73").val() !=undefined ? $("#73").val() : 0;
                    
                    var diamondEarringCaratPrice = $("#63").val() !=undefined ? $("#63").val() : 0;
                    diamondEarringCaratPrice = parseFloat(Math.round(diamondEarringCaratPrice)) || 0.00;
                    
                    $("#diamond_wtcarats_earrings_price").val(Math.round(diamondEarringCaratPrice).toFixed(2));

                    /* diamond neckless */

                    var diamondWtNacklessCarat = parseFloat($("#55").val());
                    diamondWtNacklessCarat = parseFloat(Math.round(diamondWtNacklessCarat)) || 0.00;
                    $("#diamond_wtcarats_nackless").val(Math.round(diamondWtNacklessCarat).toFixed(2));

                    var diamondEarringNacklessPrice =  $("#62").val() !=undefined ? $("#62").val().replace(/,/g, "")  : 0;
                    diamondEarringNacklessPrice = parseFloat(Math.round(diamondEarringNacklessPrice)) || 0.00;
                    $("#diamond_wtcarats_nackless_price").val(Math.round(diamondEarringNacklessPrice).toFixed(2));

                    /* diamond neckless */

                    /* diamond pendant set */

                        var diamondWtPendantCarat = parseFloat($("#74").val());
                        diamondWtPendantCarat = parseFloat(Math.round(diamondWtPendantCarat)) || 0.00;
                        $("#diamond_wtcarats_pendant").val(Math.round(diamondWtPendantCarat).toFixed(2));

                        var diamondEarringPendantPrice =  $("#75").val() !=undefined ? $("#75").val().replace(/,/g, "") : 0;
                        diamondEarringPendantPrice = parseFloat(Math.round(diamondEarringPendantPrice)) || 0.00;
                        $("#diamond_wtcarats_pendant_price").val(Math.round(diamondEarringPendantPrice).toFixed(2));
                    /* diamond pendant set */

                    var totalStonePrice =  $("#46").val() !=undefined ? Math.round($("#46").val()) : 0;
                    totalStonePrice = parseFloat(Math.round(totalStonePrice)) || 0;
                    $("#46").val(totalStonePrice);
                    
                    var caratPrice =  $("#45").val() !=undefined ? $("#45").val() : 0;
                    caratPrice = parseFloat(Math.round(caratPrice)) || 0.00;
                    $("#carat_price_val").val(Math.round(caratPrice));
                    $("#total_stone_price").val(Math.round(totalStonePrice));
                    metalWeight = parseFloat(metalWeight) || 0.00;
                    $("#metal_weight").val(metalWeight);
                    diamondWeight = parseFloat(diamondWeight) || 0.00;
                    $("#diamond_weight").val(diamondWeight);
                    var caratWtPrDiamond = parseFloat(diamondWeight) / parseFloat($("#39").val());
                    caratWtPrDiamond = parseFloat(caratWtPrDiamond) || 0.00;
                    $("#carat_wt_per_diamond").val(caratWtPrDiamond.toFixed(2));
                    var diamondWtEarringCarat = parseFloat($("#52").val());
                    diamondWtEarringCarat = parseFloat(diamondWtEarringCarat) || 0.00;
                    $("#diamond_wtcarats_earrings").val(diamondWtEarringCarat.toFixed(2));
                    


                    var diamondNecklaceCaratPrice = parseFloat($("#54").val()) * parseFloat(75500);
                    diamondNecklaceCaratPrice = parseFloat(Math.round(diamondNecklaceCaratPrice)) || 0.00;
                    $("#diamond_necklace_carat_price").val(Math.round(diamondNecklaceCaratPrice).toFixed(2));
                    
                    
                    if(diamondEarringCaratPrice!='' && diamondEarringNacklessPrice == '' &&  diamondEarringPendantPrice == '') {
                        
                        var diamondEarringPrice = parseFloat(diamondEarringCaratPrice);
                        
                    }
                    
                    else if(diamondEarringNacklessPrice!='' && diamondEarringCaratPrice!='') {
                        
                        var diamondEarringPrice = parseFloat(diamondEarringCaratPrice) + parseFloat(diamondEarringNacklessPrice);
                        
                    } else if(diamondEarringPendantPrice!='' && diamondEarringCaratPrice!='') {

                        
                        var diamondEarringPrice = parseFloat(diamondEarringCaratPrice) + parseFloat(diamondEarringPendantPrice);
                    }
                    
                    
                    var goldPrice = parseFloat($("#14").val()) * parseFloat($("#current_gold_price").val());
                    goldPrice = parseFloat(Math.round(goldPrice)) || 0;
                    $("#price").val(Math.round(goldPrice).toFixed(2));

                    /* diamond pendant set */

                    /* diamond pendant set */
                    console.log('diamondEarringPrice:- '+diamondEarringPrice);
                    if(diamondEarringPrice!=undefined) {
                        var diamondPrice = parseFloat(Math.round(diamondEarringPrice));
                    } else {
                        var diamondPrice = parseFloat(Math.round(DiamondPrice));
                    }
                    diamondPrice = parseFloat(Math.round(diamondPrice)) || 0;
                    
                    $("#diamond_price").val(Math.round(diamondPrice).toFixed(2));
                    var valueAdded = (parseFloat($("#17").val()) * parseFloat(goldPrice) )/100;
                    valueAdded = parseFloat(Math.round(valueAdded)) || 0;
                    $("#va").val(Math.round(valueAdded).toFixed(2));
                    var subTotal = parseFloat(goldPrice) + parseFloat(diamondPrice) + parseFloat(totalStonePrice) + parseFloat(valueAdded);
                    subTotal = parseFloat(Math.round(subTotal)) || 0;
                    $("#subtotal").val(Math.round(subTotal).toFixed(2));
                    var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(subTotal))/100;
                    GSTPercentage = parseFloat(Math.round(GSTPercentage)) || 0;
                    $("#gst_three_percent").val(GSTPercentage.toFixed(2));
                    var totalPrice = parseFloat(goldPrice) + parseFloat(diamondPrice) + parseFloat(totalStonePrice) + parseFloat(valueAdded) + parseFloat(GSTPercentage);
                    totalPrice = parseFloat(totalPrice) || 0;
                    $("#old_price").val(Math.round(totalPrice).toFixed(2));
                    $("#total_price").val(Math.round(totalPrice).toFixed(2));
                }
                

                /*++++++ */
                $(".specification_type").on("change",function() {

                    var metal_id = $("#metal_id").val();
                    var metal = $("#metal_id option:selected").text();

                    if(metal_id!='') {

                        var type = $(this).children("option:selected").val();
                        var rowid = $(this).closest('tr').attr('id'); 
                        
                        $(".specification_type_value"+rowid).attr("id",type);
                        $(".specification_type_value"+rowid).val('');
                        if(type=='va-') {
                            type = 'va_percent';
                        }
                        
                        $("#9").on("change",function() {
                    
                            if($(this).val()!='') {
                                $.ajax({
                                    url:'/manage/getProrityRate/'+$(this).val()+'_CRT',
                                    type:"GET",
                                    dataType : "json",
                                    success:function(data) {
                                        $("#current_gold_price").val(data.value);
                                    }
                                });
                            }  else {
                                $("#current_gold_price").val('');
                            }
                        });
                        
                        $("#38,#39,#45,#14,#52,#55,#46,#17,#43,#62,#63,#73,#75,#74").on("change",function() {
                            
                            var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                            var diamondWeight =   $("#38").val() !=undefined ? $("#38").val() : 0;
                            var DiamondPrice = $("#73").val() !=undefined ? $("#73").val() : 0;
                            
                            var diamondEarringCaratPrice = $("#63").val() !=undefined ? $("#63").val() : 0;
                            diamondEarringCaratPrice = parseFloat(Math.round(diamondEarringCaratPrice)) || 0.00;
                            
                            $("#diamond_wtcarats_earrings_price").val(Math.round(diamondEarringCaratPrice).toFixed(2));

                            /* diamond neckless */

                            var diamondWtNacklessCarat = parseFloat($("#55").val());
                            diamondWtNacklessCarat = parseFloat(Math.round(diamondWtNacklessCarat)) || 0.00;
                            $("#diamond_wtcarats_nackless").val(Math.round(diamondWtNacklessCarat).toFixed(2));

                            var diamondEarringNacklessPrice =  $("#62").val() !=undefined ? $("#62").val().replace(/,/g, "")  : 0;
                            diamondEarringNacklessPrice = parseFloat(Math.round(diamondEarringNacklessPrice)) || 0.00;
                            $("#diamond_wtcarats_nackless_price").val(Math.round(diamondEarringNacklessPrice).toFixed(2));

                            /* diamond neckless */

                            /* diamond pendant set */

                                var diamondWtPendantCarat = parseFloat($("#74").val());
                                diamondWtPendantCarat = parseFloat(Math.round(diamondWtPendantCarat)) || 0.00;
                                $("#diamond_wtcarats_pendant").val(Math.round(diamondWtPendantCarat).toFixed(2));

                                var diamondEarringPendantPrice =  $("#75").val() !=undefined ? $("#75").val().replace(/,/g, "") : 0;
                                diamondEarringPendantPrice = parseFloat(Math.round(diamondEarringPendantPrice)) || 0.00;
                                $("#diamond_wtcarats_pendant_price").val(Math.round(diamondEarringPendantPrice).toFixed(2));
                            /* diamond pendant set */

                            var totalStonePrice =  $("#46").val() !=undefined ? Math.round($("#46").val()) : 0;
                            totalStonePrice = parseFloat(Math.round(totalStonePrice)) || 0;
                            $("#46").val(totalStonePrice);
                            
                            var caratPrice =  $("#45").val() !=undefined ? $("#45").val() : 0;
                            caratPrice = parseFloat(Math.round(caratPrice)) || 0.00;
                            $("#carat_price_val").val(Math.round(caratPrice));
                            $("#total_stone_price").val(Math.round(totalStonePrice));
                            metalWeight = parseFloat(metalWeight) || 0.00;
                            $("#metal_weight").val(metalWeight);
                            diamondWeight = parseFloat(diamondWeight) || 0.00;
                            $("#diamond_weight").val(diamondWeight);
                            var caratWtPrDiamond = parseFloat(diamondWeight) / parseFloat($("#39").val());
                            caratWtPrDiamond = parseFloat(caratWtPrDiamond) || 0.00;
                            $("#carat_wt_per_diamond").val(caratWtPrDiamond.toFixed(2));
                            var diamondWtEarringCarat = parseFloat($("#52").val());
                            diamondWtEarringCarat = parseFloat(diamondWtEarringCarat) || 0.00;
                            $("#diamond_wtcarats_earrings").val(diamondWtEarringCarat.toFixed(2));
                            


                            var diamondNecklaceCaratPrice = parseFloat($("#54").val()) * parseFloat(75500);
                            diamondNecklaceCaratPrice = parseFloat(Math.round(diamondNecklaceCaratPrice)) || 0.00;
                            $("#diamond_necklace_carat_price").val(Math.round(diamondNecklaceCaratPrice).toFixed(2));
                            
                            
                            if(diamondEarringCaratPrice!='' && diamondEarringNacklessPrice == '' &&  diamondEarringPendantPrice == '') {
                               
                                var diamondEarringPrice = parseFloat(diamondEarringCaratPrice);
                               
                            }
                            
                            else if(diamondEarringNacklessPrice!='' && diamondEarringCaratPrice!='') {
                                
                                var diamondEarringPrice = parseFloat(diamondEarringCaratPrice) + parseFloat(diamondEarringNacklessPrice);
                                
                            } else if(diamondEarringPendantPrice!='' && diamondEarringCaratPrice!='') {

                                
                                var diamondEarringPrice = parseFloat(diamondEarringCaratPrice) + parseFloat(diamondEarringPendantPrice);
                            }
                            
                            
                            var goldPrice = parseFloat($("#14").val()) * parseFloat($("#current_gold_price").val());
                            goldPrice = parseFloat(Math.round(goldPrice)) || 0;
                            $("#price").val(Math.round(goldPrice).toFixed(2));

                            /* diamond pendant set */

                            /* diamond pendant set */
                            console.log('diamondEarringPrice:- '+diamondEarringPrice);
                            if(diamondEarringPrice!=undefined) {
                                var diamondPrice = parseFloat(Math.round(diamondEarringPrice));
                            } else {
                                var diamondPrice = parseFloat(Math.round(DiamondPrice));
                            }
                            diamondPrice = parseFloat(Math.round(diamondPrice)) || 0;
                            
                            $("#diamond_price").val(Math.round(diamondPrice).toFixed(2));
                            var valueAdded = (parseFloat($("#17").val()) * parseFloat(goldPrice) )/100;
                            valueAdded = parseFloat(Math.round(valueAdded)) || 0;
                            $("#va").val(Math.round(valueAdded).toFixed(2));
                            var subTotal = parseFloat(goldPrice) + parseFloat(diamondPrice) + parseFloat(totalStonePrice) + parseFloat(valueAdded);
                            subTotal = parseFloat(Math.round(subTotal)) || 0;
                            $("#subtotal").val(Math.round(subTotal).toFixed(2));
                            var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(subTotal))/100;
                            GSTPercentage = parseFloat(Math.round(GSTPercentage)) || 0;
                            $("#gst_three_percent").val(GSTPercentage.toFixed(2));
                            var totalPrice = parseFloat(goldPrice) + parseFloat(diamondPrice) + parseFloat(totalStonePrice) + parseFloat(valueAdded) + parseFloat(GSTPercentage);
                            totalPrice = parseFloat(totalPrice) || 0;
                            $("#old_price").val(Math.round(totalPrice).toFixed(2));
                            $("#total_price").val(Math.round(totalPrice).toFixed(2));
                        
                        });
                        
                        

                        

                    } else {
                        $("#specification_type_value").attr('readonly',true);
                        $("#specification_type_unit").attr('readonly',true);
                        $("#add-more-specification").prop('disabled', true);
                        
                        confirm('Please Select Metal');
                    }
                });
                    /* ===== */
                        $("#38,#39,#45,#14,#52,#55,#46,#17,#43,#62,#63,#73,#75,#74").on("change",function() {
                            
                            var metalWeight =   $("#14").val() !=undefined ? $("#14").val() : 0;
                            var diamondWeight =   $("#38").val() !=undefined ? $("#38").val() : 0;
                            var DiamondPrice = $("#73").val() !=undefined ? $("#73").val() : 0;
                            
                            var diamondEarringCaratPrice = $("#63").val() !=undefined ? $("#63").val() : 0;
                            diamondEarringCaratPrice = parseFloat(Math.round(diamondEarringCaratPrice)) || 0.00;
                            
                            $("#diamond_wtcarats_earrings_price").val(Math.round(diamondEarringCaratPrice).toFixed(2));

                            /* diamond neckless */

                            var diamondWtNacklessCarat = parseFloat($("#55").val());
                            diamondWtNacklessCarat = parseFloat(Math.round(diamondWtNacklessCarat)) || 0.00;
                            $("#diamond_wtcarats_nackless").val(Math.round(diamondWtNacklessCarat).toFixed(2));

                            var diamondEarringNacklessPrice =  $("#62").val() !=undefined ? $("#62").val().replace(/,/g, "")  : 0;
                            diamondEarringNacklessPrice = parseFloat(Math.round(diamondEarringNacklessPrice)) || 0.00;
                            $("#diamond_wtcarats_nackless_price").val(Math.round(diamondEarringNacklessPrice).toFixed(2));

                            /* diamond neckless */

                            /* diamond pendant set */

                                var diamondWtPendantCarat = parseFloat($("#74").val());
                                diamondWtPendantCarat = parseFloat(Math.round(diamondWtPendantCarat)) || 0.00;
                                $("#diamond_wtcarats_pendant").val(Math.round(diamondWtPendantCarat).toFixed(2));

                                var diamondEarringPendantPrice =  $("#75").val() !=undefined ? $("#75").val().replace(/,/g, "") : 0;
                                diamondEarringPendantPrice = parseFloat(Math.round(diamondEarringPendantPrice)) || 0.00;
                                $("#diamond_wtcarats_pendant_price").val(Math.round(diamondEarringPendantPrice).toFixed(2));
                            /* diamond pendant set */

                            var totalStonePrice =  $("#46").val() !=undefined ? Math.round($("#46").val()) : 0;
                            totalStonePrice = parseFloat(Math.round(totalStonePrice)) || 0;
                            $("#46").val(totalStonePrice);
                            
                            var caratPrice =  $("#45").val() !=undefined ? $("#45").val() : 0;
                            caratPrice = parseFloat(Math.round(caratPrice)) || 0.00;
                            $("#carat_price_val").val(Math.round(caratPrice));
                            $("#total_stone_price").val(Math.round(totalStonePrice));
                            metalWeight = parseFloat(metalWeight) || 0.00;
                            $("#metal_weight").val(metalWeight);
                            diamondWeight = parseFloat(diamondWeight) || 0.00;
                            $("#diamond_weight").val(diamondWeight);
                            var caratWtPrDiamond = parseFloat(diamondWeight) / parseFloat($("#39").val());
                            caratWtPrDiamond = parseFloat(caratWtPrDiamond) || 0.00;
                            $("#carat_wt_per_diamond").val(caratWtPrDiamond.toFixed(2));
                            var diamondWtEarringCarat = parseFloat($("#52").val());
                            diamondWtEarringCarat = parseFloat(diamondWtEarringCarat) || 0.00;
                            $("#diamond_wtcarats_earrings").val(diamondWtEarringCarat.toFixed(2));
                            


                            var diamondNecklaceCaratPrice = parseFloat($("#54").val()) * parseFloat(75500);
                            diamondNecklaceCaratPrice = parseFloat(Math.round(diamondNecklaceCaratPrice)) || 0.00;
                            $("#diamond_necklace_carat_price").val(Math.round(diamondNecklaceCaratPrice).toFixed(2));
                            
                            
                            if(diamondEarringCaratPrice!='' && diamondEarringNacklessPrice == '' &&  diamondEarringPendantPrice == '') {
                               
                                var diamondEarringPrice = parseFloat(diamondEarringCaratPrice);
                               
                            }
                            
                            else if(diamondEarringNacklessPrice!='' && diamondEarringCaratPrice!='') {
                                
                                var diamondEarringPrice = parseFloat(diamondEarringCaratPrice) + parseFloat(diamondEarringNacklessPrice);
                                
                            } else if(diamondEarringPendantPrice!='' && diamondEarringCaratPrice!='') {

                                
                                var diamondEarringPrice = parseFloat(diamondEarringCaratPrice) + parseFloat(diamondEarringPendantPrice);
                            }
                            
                            
                            var goldPrice = parseFloat($("#14").val()) * parseFloat($("#current_gold_price").val());
                            goldPrice = parseFloat(Math.round(goldPrice)) || 0;
                            $("#price").val(Math.round(goldPrice).toFixed(2));

                            /* diamond pendant set */

                            /* diamond pendant set */
                            console.log('diamondEarringPrice:- '+diamondEarringPrice);
                            if(diamondEarringPrice!=undefined) {
                                var diamondPrice = parseFloat(Math.round(diamondEarringPrice));
                            } else {
                                var diamondPrice = parseFloat(Math.round(DiamondPrice));
                            }
                            diamondPrice = parseFloat(Math.round(diamondPrice)) || 0;
                            
                            $("#diamond_price").val(Math.round(diamondPrice).toFixed(2));
                            var valueAdded = (parseFloat($("#17").val()) * parseFloat(goldPrice) )/100;
                            valueAdded = parseFloat(Math.round(valueAdded)) || 0;
                            $("#va").val(Math.round(valueAdded).toFixed(2));
                            var subTotal = parseFloat(goldPrice) + parseFloat(diamondPrice) + parseFloat(totalStonePrice) + parseFloat(valueAdded);
                            subTotal = parseFloat(Math.round(subTotal)) || 0;
                            $("#subtotal").val(Math.round(subTotal).toFixed(2));
                            var GSTPercentage = (parseFloat($("#43").val()) * parseFloat(subTotal))/100;
                            GSTPercentage = parseFloat(Math.round(GSTPercentage)) || 0;
                            $("#gst_three_percent").val(GSTPercentage.toFixed(2));
                            var totalPrice = parseFloat(goldPrice) + parseFloat(diamondPrice) + parseFloat(totalStonePrice) + parseFloat(valueAdded) + parseFloat(GSTPercentage);
                            totalPrice = parseFloat(totalPrice) || 0;
                            $("#old_price").val(Math.round(totalPrice).toFixed(2));
                            $("#total_price").val(Math.round(totalPrice).toFixed(2));
                        
                        });

                        

                /* remove row */
                $(".removeRow").on("click",function() {
                     
                    var value = $("#"+$(this).attr('id')).val();
                    
                });
                /* remove row */

            }

             

            $("#product_discount").on("change",function() {
                if($(this).val()!='') {
                    var totalPrice = $("#old_price").val();
                    var discount = parseFloat(totalPrice) * parseFloat($(this).val())/100;
                    var discountPrice = parseFloat(totalPrice) - parseFloat(discount);
                    $("#new_price").val( Math.round(discountPrice).toFixed(2));
                } else {
                    $("#new_price").val('');
                }
                
                
            });
            var url = $(location).attr('href').split("/").splice(0, 7).join("/");
            var segments = url.split( '/' );
            var id = segments[6];
            if(id!='') {
            
                 
                
            }

            $(".upload-files-info2").on('change', function(e) {
                     
                     var formData = new FormData();
                     var totalImage = e.originalEvent.target.files.length; 
                     var images = $('.upload-files-info2')[0];
                     var imageArray = [];  
                     for (let i = 0; i < totalImage; i++) {
                         formData.append('file[]', images.files[i]);
                         imageArray.push(images.files[i]);
                     }
                     formData.append('_token', '{{ csrf_token() }}');
                     $.ajaxSetup ({
                         processData: false,
                         contentType: false
                     });
                     
                     $.ajax({
                         xhr: function() {
                             var xhr = new window.XMLHttpRequest();
                             xhr.upload.addEventListener("progress", function(evt) {
                                 if (evt.lengthComputable) {
                                     var percentComplete = ((evt.loaded / evt.total) * 100);
                                     $(".progress-bar").width(percentComplete + '%');
                                     $(".progress-bar").html(percentComplete+'%');
                                 }
                             }, false);
                             return xhr;
                         },
                         type: "POST",
                         url: "{{ route('manage.products.upload_multiple_image') }}",
                         data: formData,
                         contentType: false,
                         cache: false,
                         processData:false,
                         beforeSend: function() {
                             $("#submit_button").attr('disabled',true);
                             $(".progress").show();
                             $(".progress-bar").width('0%');
                             // $('#uploadStatus').html('<img src="images/loading.gif"/>');
                         },
                         error:function(){
                             $("#submit_button").attr('disabled',true);
                             $(".progress").hide();
                             $('#success').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
                         },
                         success: function(resp){
                             console.log('response:- '+resp.success);
                             if(resp.success == '1'){
                                 $("#submit_button").attr('disabled',false);
                                 $(".progress").hide();
                                 // $('#uploadForm')[0].reset();
                                 //$('#success').html('<p style="color:#28A74B;">File has uploaded successfully!</p>');
                             }
                         }
                     });
                 });
            
        });

</script>
    @include('partials.manage.includes.colorPickJs')
@endsection

@section('content')
    @include('partials.manage.products.vendor.edit')
@endsection