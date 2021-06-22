@extends('layouts.front')

@section('title')
    @lang('Pay with Razorpay')
@endsection


@section('scripts')
 

   
 
<script>
    jQuery(document).ready(function() {
      
        $("#razorpayment_form").submit();
    });
</script>
@endsection
@section('content')

  
 
<div class="container" style="display:none;">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                     
                <div class="panel-heading">@lang('Purchase with Razorpay')</div>

                <div class="panel-body text-center">
                    <form name="razorpayment_form" id="razorpayment_form" action="{!!route('razorpay.payment')!!}" method="POST" >
                        {{ csrf_field() }}
                        <!-- Note that the amount is in paise = 50 INR -->
                        <!--amount need to be in paisa-->
                        <script src="https://checkout.razorpay.com/v1/checkout.js"
                                data-key="{{ Config::get('razorpay.razor_key') }}"
                                data-amount="{{$amount}}"
                                data-buttontext="Pay {{$amount / 100}} INR"
                                data-name="Total"
                                data-description="Order Value: {{$amount / 100}} INR"
                                data-image="{{url('/img/'.config('settings.site_logo'))}}"
                                data-prefill.name="{{Auth::user()->name}}"
                                data-prefill.email="{{Auth::user()->email}}"
                                data-theme.color="#ff7529">
                        </script>
                        <input type="hidden" name="_token" value="{!!csrf_token()!!}">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br>
@endsection
