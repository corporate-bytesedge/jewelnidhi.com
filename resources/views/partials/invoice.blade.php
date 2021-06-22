<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>html</title>
    <meta name="viewport"content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
   
    <style>
      td {
        padding:5px;
      }
    </style>

   </head>

  <body style="background-color:#fff; color:#000;">

    <div style="max-width:1000px;margin:0 auto; text-align:right;padding:10px 0;font-size:18px;">
      <a href="javascript:void();" class="printDiv"  style="color:#000;"><i class="fa fa-print"></i></a>
    </div>
	<div id="printarea"  style="background-color:#fff; color:#000;">

    <table Cellpadding="0" cellspacing="0" style="border-collapse:collapse; border:1px solid #ddd; width: 100%; max-width:1000px; margin:0 auto; padding: 0; vertical-align: top; font-size: 12px; font-family: Arial, Helvetica, sans-serif;">

      <tr>
        <td style="border:1px solid #ddd;">
          <table  Cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <tr>
              <td style="border:1px solid #ddd; text-align: center;">
              @if(!empty(config('settings.site_logo')))
                <img src="{{ asset('img/logo_new.gif') }}" alt="" style="max-width: 100px; max-height: 60px; margin:0 auto; display: block;"/>
              @endif
              </td>
             
              <td style="border:1px solid #ddd;">
                <p style="margin:0;">
                JEWELNIDHI, </br>D.No: 9-3-126,127,</br>Opp. Sri Balaji Jewellery Mart, J P Towers,<br/> Tiruapti,AndhraPradesh,India-517501
                </p>
              </td>
              <td style="border:1px solid #ddd;">
                <table Cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%;">
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Order#</b></td>
                    <td style="border:1px solid #ddd;">{{$order->getOrderId()}}</td>
                    <td style="border:1px solid #ddd;"></td>
                    <td style="border:1px solid #ddd;"><b>Date:</b></td>
                    <td style="border:1px solid #ddd;">{{$order->created_at->toFormattedDateString()}}</td>
                  </tr>
                  <tr>
                    <td style="border:1px solid #ddd;"><b>GST#</b></td>
                    <td style="border:1px solid #ddd;">37AAQFJ1902N1C9</td>
                    <td style="border:1px solid #ddd;"></td>
                    <td style="border:1px solid #ddd;"><b>PAN#</b></td>
                    <td style="border:1px solid #ddd;">AAQFJ1902N</td>
                  </tr>
                  <tr>
                    <td style="border:1px solid #ddd;"><b>State#</b></td>
                    <td style="border:1px solid #ddd; text-align: center;" colspan="4"> {{ ucwords($order->address->state) }},India</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
     
      <tr>
        <td>
          <table Cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <tr>
              <td style="border:1px solid #ddd;">
                <p style="margin:0;">
                  <b>Bill to</b></br>
                  Address: </br>
                  @if(!empty($order->address))
                    {{$order->address->first_name}} {{$order->address->last_name}},</br>
                    {{$order->address->address}},</br>
                    {{$order->address->city}},</br>
                    {{$order->address->state}},</br>
                    {{$order->address->zip}}
                  @endif
                </p>
              </td>
              <td style="border:1px solid #ddd; vertical-align: top;">
                <p style="margin:0;">
                  <b>Contact</b></br>
                  email:{{isset($order->address->email) ? $order->address->email : ''}}</br>
                  M:{{ isset($order->address->phone) ? $order->address->phone : '' }}
                </p>
              </td>
              <td style="border:1px solid #ddd;">
                <p style="margin:0;">
                  <b>Ship to</b></br>
                  Address: </br>
                  @if(!empty($order->address))
                  {{$order->address->first_name}} {{$order->address->last_name}},</br>
                  {{$order->address->address}},</br>
                  {{$order->address->city}},</br>
                  {{$order->address->state}}</br>
                  {{$order->address->zip}}
                  @endif
                </p>
              </td>
            </tr>
          </table>
        </td>
      </tr>

	<tr>
        <td>
          <table Cellpadding="0" cellspacing="0" style="border-collapse:collapse; width: 100%; ">
            <tr>
                <td style="border:1px solid #ddd;"><b>S.No</b></td>
                <td style="border:1px solid #ddd;"><b>JEN Web ID</b></td>
                <td style="border:1px solid #ddd;"><b>Product</b></td>
                <td style="border:1px solid #ddd;"><b>Description:</b></td>
                
                <td style="border:1px solid #ddd;"><b>Purity(KT)</b></td>
                <td style="border:1px solid #ddd;"><b>TotalWeight(gm)</b></td>
                <!-- <td style="border:1px solid #ddd;"><b>HSNcode</b></td> -->
                <!-- <td style="border:1px solid #ddd;"><b>Special discount</b></td> -->
                @if($order->coupon_code)
                  <td style="border:1px solid #ddd;"><b>Promo code</b></td>
                @endif
                 
                <td style="border:1px solid #ddd;"><b>Total</b></td>
              
            </tr>

            @php
                $subTotal = 0;
                $GST = 0;
                $totalValue = 0;
                $i = 1;
                $purity = '';
                $goldWeight = 0;
                $diamondWeight = 0;
                $stoneWeight = 0;
                $goldCost = 0;
                $diamondCost = 0;
                $stoneCost =0;
                $VA =0;
                $totalAmount =0;
                $totalAmount = 0;
                $totalInvoiceValue = 0 ;
                $Amount =0;
			      @endphp
          
            @if(!empty($order->products))
         
              @foreach($order->products AS $k => $value)
             
                  @if(!empty($value->specificationTypes)) 
                      
                        @foreach($value->specificationTypes AS  $ke=> $val )
                            @php 
                              if($val->id == '9') {
                                
                                 $purity = $val->pivot->value .' '.$val->pivot->unit;
                              }
                                
                            @endphp
                        @endforeach   
                  @endif
              
              @php 
                  
                  $GST+=$value->gst_three_percent;
                  $goldWeight = $value->metal_weight  !='' ? $value->metal_weight : '';
                  $diamondWeight = $value->diamond_weight !='' ? $value->diamond_weight : '-';
                  $stoneWeight = $value->stone_weight !='' ? $value->stone_weight : '-';
                  $goldCost = $value->price !='' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($value->price) : '0';
                  $diamondCost = $value->diamond_price !='' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($value->diamond_price) : '0';
                  $stoneCost = $value->stone_price !='' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($value->stone_price)  : '0';
                  $VA = $value->vat_rate !='' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($value->vat_rate) : '0';
                  $Amount = $value->pivot->total ;
                  
                  $totalAmount += $Amount;
                   
                  $subTotal = $totalAmount -  $GST;
                  $totalInvoiceValue = $subTotal - $order->coupon_amount + $GST + $order->shipping_cost - $order->wallet_amount;
                    
				      @endphp
                  <tr>
                      <td style="border:1px solid #ddd;">{{ $i++ }}</td>
                      <td style="border:1px solid #ddd;">{{ $value->jn_web_id }}</td>
                      <td style="border:1px solid #ddd;">{{ ucwords($value->name) }}</td>
                      <td style="border:1px solid #ddd;">{{ strip_tags(ucwords($value->description)) }}</td>
                      
                      <td style="border:1px solid #ddd;">{{ $purity }} </td>
                      <td style="border:1px solid #ddd;">{{ $value->total_weight ? $value->total_weight : $value->metal_weight }}</td>
                      <!-- <td style="border:1px solid #ddd;">N/A</td> -->
                      <!-- <td style="border:1px solid #ddd;">10</td> -->
                      @if($order->coupon_code)
                        <td style="border:1px solid #ddd;">{{ $order->coupon_code ? $order->coupon_code : '' }} </td>
                      @endif

                     
                      <td style="border:1px solid #ddd;">
                       
                      <i class="fa fa-rupee"></i> {{ \App\Helpers\IndianCurrencyHelper::IND_money_format($Amount) }}
                      </td>
                  </tr>
            
                  <tr>
                    <td colspan="9" style="padding:0">
                        <table Cellpadding="0" cellspacing="0" style="border-collapse:collapse; width: 100%;">
                            <tr>
                             
                              <td style="border:1px solid #ddd;"><b>{{ (  isset($value->metal->name) &&  $value->metal->name =='Gold' ||  $value->metal->name =='Diamond' ? "Gold" : "Metal") }} weight(gm)</b></td>
                              <td style="border:1px solid #ddd;">{{ $goldWeight }}</td>
                            </tr>
                            <tr>
                              <td style="border:1px solid #ddd;"><b>Diamond weight(Ct))</b></td>
                              <td style="border:1px solid #ddd;"> {{ $diamondWeight }}</td>
                            </tr>
                            <tr>
                              <td style="border:1px solid #ddd;"><b>Stone weight(gm)</b></td>
                              <td style="border:1px solid #ddd;">{{ $stoneWeight }}</td>
                            </tr>
                            <tr>
                              <td style="border:1px solid #ddd;"><b>{{ (  isset($value->metal->name) &&  $value->metal->name =='Gold' ||  $value->metal->name =='Diamond' ? "Gold" : "Metal") }}  cost(INR)</b></td>
                              <td style="border:1px solid #ddd;"> <i class="fa fa-rupee"></i> {{ $goldCost  }}</td>
                            </tr>
                            <tr>
                              <td style="border:1px solid #ddd;"><b>Diamond cost(INR)</b></td>
                              <td style="border:1px solid #ddd;"> <i class="fa fa-rupee"></i> {{ $diamondCost  }} </td>
                            </tr>
                            <tr>
                              <td style="border:1px solid #ddd;"><b>Stone(Cost)</b></td>
                              <td style="border:1px solid #ddd;"> <i class="fa fa-rupee"></i> {{ $stoneCost  }}  </td>
                            </tr>
                            <tr>
                              <td style="border:1px solid #ddd;"><b>VA charges</b></td>
                              <td style="border:1px solid #ddd;"> <i class="fa fa-rupee"></i> {{ $VA  }}   </td>
                            </tr>
                             
                      </table>
                    </td>
                  </tr>
                @endforeach
          @endif

            

          </table>
        </td>
      </tr>
	 
      @php 
          $CGST = $GST / 2 ;
          $SGST = $GST / 2 ;
           
           
      @endphp
      <tr>
        <td>
          <table Cellpadding="0" cellspacing="0" style="border-collapse:collapse; width: 100%; ">
            <tr>
              <td colspan="2" style="border:1px solid #ddd;">
                <table Cellpadding="0" cellspacing="0" style="border-collapse:collapse; width: 100%; ">
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Payment mode:</b></td>
                    <td style="border:1px solid #ddd;">{{$order->payment_method }}</td>
                  </tr>
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Paid Date:</b></td>
                    <td style="border:1px solid #ddd;">{{ $order->payment_date !='' ? date('d-m-Y', strtotime($order->payment_date)) : ''  }}</td>
                  </tr>
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Paid amount:</b></td>
                    <td style="border:1px solid #ddd;"><i class="fa fa-rupee"></i> {{ $totalInvoiceValue ? \App\Helpers\IndianCurrencyHelper::IND_money_format(number_format($totalInvoiceValue)) : '0' }}</td>
                  </tr>
                  <tr>
                    <td colspan="2" style="border:1px solid #ddd;">
                     @php 
                     $totalInvoiceValueText = round($totalInvoiceValue);
                      
                      
                     @endphp
                    <strong>Total Invoice value: </strong>  @php 
                                                $inWords = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
                                                echo strtoupper(str_replace('-',' ',$inWords->format($totalInvoiceValueText)));
                                            @endphp
                    </td>
                  </tr>
                </table>
              </td>
              <td style="border:1px solid #ddd;">
                <table Cellpadding="0" cellspacing="0" style="border-collapse:collapse; width: 100%; ">
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Total Value(INR):</b></td>
                    <td style="border:1px solid #ddd;"><i class="fa fa-rupee"></i> {{ $subTotal ? \App\Helpers\IndianCurrencyHelper::IND_money_format($subTotal) : '0' }} </td>
                  </tr>
                  @if($order->coupon_amount !='0')           
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Coupon Amount:</b></td>
                    <td style="border:1px solid #ddd;"><i class="fa fa-rupee"></i>  {{ $order->coupon_amount ? \App\Helpers\IndianCurrencyHelper::IND_money_format($order->coupon_amount) : ''}}   </td>
                  </tr>
                  @endif
                  
                  <tr>
                    <td style="border:1px solid #ddd;"><b>IGST(3%):</b></td>
                    <td style="border:1px solid #ddd;">
                    @if($order->address->state != 'ANDHRA PRADESH')
                    <i class="fa fa-rupee"></i> {{ \App\Helpers\IndianCurrencyHelper::IND_money_format($GST) }}
                    @else
                      @php echo '-' ;@endphp
                    @endif
                       </td>
                  </tr>
                   
                  
                   
                  <tr>
                    <td style="border:1px solid #ddd;"><b>CGST(1.5%):</b></td>
                    <td style="border:1px solid #ddd;">
                    @if($order->address->state == 'ANDHRA PRADESH')
                      <i class="fa fa-rupee"></i> {{ number_format($CGST,2) }}
                    @else
                      @php echo '-' ;@endphp
                    @endif
                    </td>
                  </tr>
                  <tr>
                    <td style="border:1px solid #ddd;"><b>SGST(1.5%):</b></td>
                    <td style="border:1px solid #ddd;">
                        @if($order->address->state == 'ANDHRA PRADESH')
                          <i class="fa fa-rupee"></i> {{ number_format($SGST,2) }}
                        @else
                          @php echo '-' ;@endphp
                        @endif
                    </td>
                  </tr>
				  
				  @if($order->shipping_cost !='0')           
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Shipping Cost:</b></td>
                    <td style="border:1px solid #ddd;"><i class="fa fa-rupee"></i>  {{ $order->shipping_cost ? \App\Helpers\IndianCurrencyHelper::IND_money_format($order->shipping_cost) : '0'}}   </td>
                  </tr>
                  @endif
				  
				  @if($order->wallet_amount !='0')           
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Wallet Amount:</b></td>
                    <td style="border:1px solid #ddd;"><i class="fa fa-rupee"></i>  {{ $order->wallet_amount ? \App\Helpers\IndianCurrencyHelper::IND_money_format($order->wallet_amount) : '0'}}   </td>
                  </tr>
                  @endif
				  
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Total invoice value:</b></td>
                    <td style="border:1px solid #ddd;"><i class="fa fa-rupee"></i> {{ $totalInvoiceValue ? \App\Helpers\IndianCurrencyHelper::IND_money_format(number_format($totalInvoiceValue)) : '0' }}</td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="border:1px solid #ddd;">
          <p style="margin:0;">
            We hereby certify that our registration provisional certificate under the Central Goods and Services Tax Act,2017 is in. We hereby certify that our registration provisional certificate under the Central Goods and Services Tax Act,2017 is in.
          </p>
        </td>
      </tr>
      
      <tr>
        <td>
          <table Cellpadding="0" cellspacing="0" style="border-collapse:collapse; width: 100%; ">
              <tr>
                <td style="border:1px solid #ddd;">
                  <p>
                    <b>Registered office:</b> </br>
                    JEWELNIDHI, </br>
                    D.No: 9-3-126,127, Opp. Sri Balaji Jewellery Mart, J P Towers</br>
                    Tiruapti,Andhra Pradesh,India- 517501
                    
                  </p>
                </td>
                <td style="border:1px solid #ddd;">
                  <table Cellpadding="0" cellspacing="0" style="border-collapse:collapse; width: 100%; ">
                    <tr>
                      <td><b>Customer Name:</b> M.Kavitha</td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                      <td><b>Customer Signature:</b></td>
                    </tr>
                  </table>
                </td>
                <td style="border:1px solid #ddd; text-align: center;">
                  <table Cellpadding="0" cellspacing="0" style="border-collapse:collapse; width: 100%; ">
                    <tr>
                      <td><b>For JewelNidhi PVT LTD.</b></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr>
                      <td><b>(Authorised Signatory)</b></td>
                    </tr>
                  </table>
                </td>
              </tr>
          </table>
        </td>
      </tr>

      <tr>
        <td style="text-align: center; border:1px solid #ddd;">
          <p style="margin:0;">
            Thank you for making your shopping memorable by shopping with us. Indulge in designs @ Jewelnidhi.com </br> We always love to hear from you,please contact us for any queries @if(config('settings.contact_email')) @ {{ config('settings.contact_email') }} @endif, @if(config('settings.contact_number')){{ config('settings.contact_number') }}@endif
          </p>
        </td>
      </tr>

    </table>

  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js" ></script> 

<script type="text/JavaScript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.js"></script>
  <script>
  $(".printDiv").on('click',function () {
    $("#printarea").print();
});

function printDiv1(){
        var printContents = document.getElementById("printarea").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
}
  </script>


  </body>
</html>
