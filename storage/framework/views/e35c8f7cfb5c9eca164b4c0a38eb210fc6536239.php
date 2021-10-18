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
              <?php if(!empty(config('settings.site_logo'))): ?>
                <img src="<?php echo e(asset('img/logo_new.gif')); ?>" alt="" style="max-width: 100px; max-height: 60px; margin:0 auto; display: block;"/>
              <?php endif; ?>
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
                    <td style="border:1px solid #ddd;"><?php echo e($order->getOrderId()); ?></td>
                    <td style="border:1px solid #ddd;"></td>
                    <td style="border:1px solid #ddd;"><b>Date:</b></td>
                    <td style="border:1px solid #ddd;"><?php echo e($order->created_at->toFormattedDateString()); ?></td>
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
                    <td style="border:1px solid #ddd; text-align: center;" colspan="4"> <?php echo e(ucwords($order->address->state)); ?>,India</td>
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
                  <?php if(!empty($order->address)): ?>
                    <?php echo e($order->address->first_name); ?> <?php echo e($order->address->last_name); ?>,</br>
                    <?php echo e($order->address->address); ?>,</br>
                    <?php echo e($order->address->city); ?>,</br>
                    <?php echo e($order->address->state); ?>,</br>
                    <?php echo e($order->address->zip); ?>

                  <?php endif; ?>
                </p>
              </td>
              <td style="border:1px solid #ddd; vertical-align: top;">
                <p style="margin:0;">
                  <b>Contact</b></br>
                  email:<?php echo e(isset($order->address->email) ? $order->address->email : ''); ?></br>
                  M:<?php echo e(isset($order->address->phone) ? $order->address->phone : ''); ?>

                </p>
              </td>
              <td style="border:1px solid #ddd;">
                <p style="margin:0;">
                  <b>Ship to</b></br>
                  Address: </br>
                  <?php if(!empty($order->address)): ?>
                  <?php echo e($order->address->first_name); ?> <?php echo e($order->address->last_name); ?>,</br>
                  <?php echo e($order->address->address); ?>,</br>
                  <?php echo e($order->address->city); ?>,</br>
                  <?php echo e($order->address->state); ?></br>
                  <?php echo e($order->address->zip); ?>

                  <?php endif; ?>
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
                <?php if($order->coupon_code): ?>
                  <td style="border:1px solid #ddd;"><b>Promo code</b></td>
                <?php endif; ?>
                 
                <td style="border:1px solid #ddd;"><b>Total</b></td>
              
            </tr>

            <?php
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
			      ?>
          
            <?php if(!empty($order->products)): ?>
         
              <?php $__currentLoopData = $order->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
             
                  <?php if(!empty($value->specificationTypes)): ?> 
                      
                        <?php $__currentLoopData = $value->specificationTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ke=> $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php 
                              if($val->id == '9') {
                                
                                 $purity = $val->pivot->value .' '.$val->pivot->unit;
                              }
                                
                            ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
                  <?php endif; ?>
              
              <?php 
                  
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
                    
				      ?>
                  <tr>
                      <td style="border:1px solid #ddd;"><?php echo e($i++); ?></td>
                      <td style="border:1px solid #ddd;"><?php echo e($value->jn_web_id); ?></td>
                      <td style="border:1px solid #ddd;"><?php echo e(ucwords($value->name)); ?></td>
                      <td style="border:1px solid #ddd;"><?php echo e(strip_tags(ucwords($value->description))); ?></td>
                      
                      <td style="border:1px solid #ddd;"><?php echo e($purity); ?> </td>
                      <td style="border:1px solid #ddd;"><?php echo e($value->total_weight ? $value->total_weight : $value->metal_weight); ?></td>
                      <!-- <td style="border:1px solid #ddd;">N/A</td> -->
                      <!-- <td style="border:1px solid #ddd;">10</td> -->
                      <?php if($order->coupon_code): ?>
                        <td style="border:1px solid #ddd;"><?php echo e($order->coupon_code ? $order->coupon_code : ''); ?> </td>
                      <?php endif; ?>

                     
                      <td style="border:1px solid #ddd;">
                       
                      <i class="fa fa-rupee"></i> <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($Amount)); ?>

                      </td>
                  </tr>
            
                  <tr>
                    <td colspan="9" style="padding:0">
                        <table Cellpadding="0" cellspacing="0" style="border-collapse:collapse; width: 100%;">
                            <tr>
                             
                              <td style="border:1px solid #ddd;"><b><?php echo e((  isset($value->metal->name) &&  $value->metal->name =='Gold' ||  $value->metal->name =='Diamond' ? "Gold" : "Metal")); ?> weight(gm)</b></td>
                              <td style="border:1px solid #ddd;"><?php echo e($goldWeight); ?></td>
                            </tr>
                            <tr>
                              <td style="border:1px solid #ddd;"><b>Diamond weight(Ct))</b></td>
                              <td style="border:1px solid #ddd;"> <?php echo e($diamondWeight); ?></td>
                            </tr>
                            <tr>
                              <td style="border:1px solid #ddd;"><b>Stone weight(gm)</b></td>
                              <td style="border:1px solid #ddd;"><?php echo e($stoneWeight); ?></td>
                            </tr>
                            <tr>
                              <td style="border:1px solid #ddd;"><b><?php echo e((  isset($value->metal->name) &&  $value->metal->name =='Gold' ||  $value->metal->name =='Diamond' ? "Gold" : "Metal")); ?>  cost(INR)</b></td>
                              <td style="border:1px solid #ddd;"> <i class="fa fa-rupee"></i> <?php echo e($goldCost); ?></td>
                            </tr>
                            <tr>
                              <td style="border:1px solid #ddd;"><b>Diamond cost(INR)</b></td>
                              <td style="border:1px solid #ddd;"> <i class="fa fa-rupee"></i> <?php echo e($diamondCost); ?> </td>
                            </tr>
                            <tr>
                              <td style="border:1px solid #ddd;"><b>Stone(Cost)</b></td>
                              <td style="border:1px solid #ddd;"> <i class="fa fa-rupee"></i> <?php echo e($stoneCost); ?>  </td>
                            </tr>
                            <tr>
                              <td style="border:1px solid #ddd;"><b>VA charges</b></td>
                              <td style="border:1px solid #ddd;"> <i class="fa fa-rupee"></i> <?php echo e($VA); ?>   </td>
                            </tr>
                             
                      </table>
                    </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>

            

          </table>
        </td>
      </tr>
	 
      <?php 
          $CGST = $GST / 2 ;
          $SGST = $GST / 2 ;
           
           
      ?>
      <tr>
        <td>
          <table Cellpadding="0" cellspacing="0" style="border-collapse:collapse; width: 100%; ">
            <tr>
              <td colspan="2" style="border:1px solid #ddd;">
                <table Cellpadding="0" cellspacing="0" style="border-collapse:collapse; width: 100%; ">
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Payment mode:</b></td>
                    <td style="border:1px solid #ddd;"><?php echo e($order->payment_method); ?></td>
                  </tr>
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Paid Date:</b></td>
                    <td style="border:1px solid #ddd;"><?php echo e($order->payment_date !='' ? date('d-m-Y', strtotime($order->payment_date)) : ''); ?></td>
                  </tr>
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Paid amount:</b></td>
                    <td style="border:1px solid #ddd;"><i class="fa fa-rupee"></i> <?php echo e($totalInvoiceValue ? \App\Helpers\IndianCurrencyHelper::IND_money_format(number_format($totalInvoiceValue)) : '0'); ?></td>
                  </tr>
                  <tr>
                    <td colspan="2" style="border:1px solid #ddd;">
                     <?php 
                     $totalInvoiceValueText = round($totalInvoiceValue);
                      
                      
                     ?>
                    <strong>Total Invoice value: </strong>  <?php 
                                                $inWords = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
                                                echo strtoupper(str_replace('-',' ',$inWords->format($totalInvoiceValueText)));
                                            ?>
                    </td>
                  </tr>
                </table>
              </td>
              <td style="border:1px solid #ddd;">
                <table Cellpadding="0" cellspacing="0" style="border-collapse:collapse; width: 100%; ">
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Total Value(INR):</b></td>
                    <td style="border:1px solid #ddd;"><i class="fa fa-rupee"></i> <?php echo e($subTotal ? \App\Helpers\IndianCurrencyHelper::IND_money_format($subTotal) : '0'); ?> </td>
                  </tr>
                  <?php if($order->coupon_amount !='0'): ?>           
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Coupon Amount:</b></td>
                    <td style="border:1px solid #ddd;"><i class="fa fa-rupee"></i>  <?php echo e($order->coupon_amount ? \App\Helpers\IndianCurrencyHelper::IND_money_format($order->coupon_amount) : ''); ?>   </td>
                  </tr>
                  <?php endif; ?>
                  
                  <tr>
                    <td style="border:1px solid #ddd;"><b>IGST(3%):</b></td>
                    <td style="border:1px solid #ddd;">
                    <?php if($order->address->state != 'ANDHRA PRADESH'): ?>
                    <i class="fa fa-rupee"></i> <?php echo e(\App\Helpers\IndianCurrencyHelper::IND_money_format($GST)); ?>

                    <?php else: ?>
                      <?php echo '-' ;?>
                    <?php endif; ?>
                       </td>
                  </tr>
                   
                  
                   
                  <tr>
                    <td style="border:1px solid #ddd;"><b>CGST(1.5%):</b></td>
                    <td style="border:1px solid #ddd;">
                    <?php if($order->address->state == 'ANDHRA PRADESH'): ?>
                      <i class="fa fa-rupee"></i> <?php echo e(number_format($CGST,2)); ?>

                    <?php else: ?>
                      <?php echo '-' ;?>
                    <?php endif; ?>
                    </td>
                  </tr>
                  <tr>
                    <td style="border:1px solid #ddd;"><b>SGST(1.5%):</b></td>
                    <td style="border:1px solid #ddd;">
                        <?php if($order->address->state == 'ANDHRA PRADESH'): ?>
                          <i class="fa fa-rupee"></i> <?php echo e(number_format($SGST,2)); ?>

                        <?php else: ?>
                          <?php echo '-' ;?>
                        <?php endif; ?>
                    </td>
                  </tr>
				  
				  <?php if($order->shipping_cost !='0'): ?>           
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Shipping Cost:</b></td>
                    <td style="border:1px solid #ddd;"><i class="fa fa-rupee"></i>  <?php echo e($order->shipping_cost ? \App\Helpers\IndianCurrencyHelper::IND_money_format($order->shipping_cost) : '0'); ?>   </td>
                  </tr>
                  <?php endif; ?>
				  
				  <?php if($order->wallet_amount !='0'): ?>           
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Wallet Amount:</b></td>
                    <td style="border:1px solid #ddd;"><i class="fa fa-rupee"></i>  <?php echo e($order->wallet_amount ? \App\Helpers\IndianCurrencyHelper::IND_money_format($order->wallet_amount) : '0'); ?>   </td>
                  </tr>
                  <?php endif; ?>
				  
                  <tr>
                    <td style="border:1px solid #ddd;"><b>Total invoice value:</b></td>
                    <td style="border:1px solid #ddd;"><i class="fa fa-rupee"></i> <?php echo e($totalInvoiceValue ? \App\Helpers\IndianCurrencyHelper::IND_money_format(number_format($totalInvoiceValue)) : '0'); ?></td>
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
            Thank you for making your shopping memorable by shopping with us. Indulge in designs @ Jewelnidhi.com </br> We always love to hear from you,please contact us for any queries <?php if(config('settings.contact_email')): ?> @ <?php echo e(config('settings.contact_email')); ?> <?php endif; ?>, <?php if(config('settings.contact_number')): ?><?php echo e(config('settings.contact_number')); ?><?php endif; ?>
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
