@extends('layouts.front')


<title>@lang('Wishlist') - {{config('app.name')}}</title>




@section('styleg')
<style>
.emptyBtn {
    color: #fff !important;
    background-color: #D3A012 !important;
    border-color: #D3A012 !important;
}
</style>
@endsection
@section('content')
<div class="breadcrumb-sec">
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
      </ol>
    </div>
  </div>
<!-- cart main wrapper start -->
@if(count($products) > 0)
<div class="cart-main-wrapper section">
    <div class="container">
        <div class="section-bg-color">
            <div class="row">
                <div class="col-lg-12">
                <div id="CartMsg"></div>
                    <!-- Cart Table Area -->
                    <div class="cart-table">
                        <table class="table table-bordered cartDetails">
                            <thead>
                                <tr>
                                    <!-- <th class="pro-thumbnail">Thumbnail</th> -->
                                    <th class="pro-title">Product</th>
                                    <th class="pro-price">Price</th>
                                    <!-- <th class="pro-quantity">Stock Status</th> -->
                                    <th class="pro-subtotal">Add to Cart</th>
                                    <th class="pro-remove">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                             
                            @foreach($products as $product)
                                {!! Form::open(['method'=>'patch', 'route'=>['front.cart.add', $product->id], 'id'=>'wishlistcart-form'.$product->id]) !!}
                                {{ Form::hidden('product_id',isset($product->id) ? $product->id : '',['id'=>'product_id']) }}
                                {{ Form::hidden('new_price',isset($product->new_price) ? $product->new_price : $product->old_price,['id'=>'new_price']) }}
                                <tr>
                                    <td>
                                        <div class="pro-title">
                                            <span class="imgsection">
                                            @if($product->photo!=null)
                                                @if($product->photo->name)
                                                    @php
                                                        $image_url = \App\Helpers\Helper::check_image_avatar($product->photo->name, 150);
                                                    @endphp
                                                        <img class="img-fluid " src="{{$image_url}}" width="140px" alt="{{$product->photo->name}}">
                                                @else
                                                    <img src="{{asset('img/no-img.jpg')}}" class="product-image img-responsive" width="150"  alt="{{$cartItem->name}}" />
                                                @endif
                                            @endif
                                            </span>
                                            <span class="protitle">
                                            <a href="{{route('front.product.show', [$product->slug])}}">{{$product->name}} </a>
                                            </span>
                                    
                            
                                        </div>
                                    </td>
                                    <td class="pro-price">
                                    
                                    
                                    <div class="product-caption">

                                        @if(isset($product->product_discount) && $product->product_discount!='')
                                            <div class="price-box">
                                            @if($product->old_price!='0')
                                                <span class="price-old">
                                                    <del> 
                                                    <i class="fa fa-rupee"></i> {{ isset($product->old_price) && $product->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price) : '' }}
                                                    </del>
                                                </span>
                                            @endif  
                                                <span class="price-regular"> 
                                                <i class="fa fa-rupee"></i> {{ isset($product->new_price) && $product->new_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($product->new_price) : '' }}</span>
                                            </div>
                                            @else
                                            <div class="price-box">
                                            <span class="price-regular"> 
                                            <i class="fa fa-rupee"></i> {{ isset($product->old_price) && $product->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price) : '' }}
                                            </span>
                                            </div>
                                        @endif

                                                

                                        @if(isset($product->old_price) && $product->old_price!=null && $product->product_discount!='')
                                            <div class="you-save">
                                            Save <span>
                                            <i class="fa fa-rupee"></i> {{ isset($product->old_price) && $product->old_price!='0' ? \App\Helpers\IndianCurrencyHelper::IND_money_format($product->old_price - $product->new_price) : '' }}
                                            </span>
                                            </div>
                                        @endif
                                        </div>


                                    </td>
                                    <!-- <td class="pro-quantity">
                                    @if($product->in_stock > 0 )
                                      <span class="text-success">In Stock</span>
                                    @else 
                                        <span class="text-danger">Stock Out</span>
                                    @endif
                                    </td> -->
                                    <td class="pro-subtotal pro-wishlist">
                                        @if(!empty(Cart::content()))
                                        @php
                                            $classname = '';
                                        @endphp
                                            @foreach(Cart::content() AS $key => $value)
                                                @if($value->id == $product->id )
                                                    @php 
                                                        $classname .= 'disableBtn';
                                                    @endphp
                                                
                                                @endif
                                                
                                            @endforeach
                                        @endif
                                        <button class="btn btn-primary btn-xs addCartWishlist @php echo $classname @endphp"  id="add_to_cart_{{ $product->id }}"  data-product_id = "{{ $product->id }}" name="submit_button" type="button">@lang('Add to Cart')</button>
                                        
                                        
                                    </td>
                                    {!! Form::close() !!}
                                    <td class="pro-remove remove-wishlist">
                                        <a class="text-danger removeProduct"  data-id = "{{ $product->id }}" style="color: #fff!important;" href="javascript:void(0);">
                                                    <i class="fa fa-trash-o"></i>
                                        </a>
                                        <form action="{{ route('front.product.favourite.destroy', $product) }}"
                                            method="POST" 
                                            id="product-favourite-destroy-{{ $product->id }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                    </td>
                                </tr>
                            
                             @endforeach   
                            </tbody>
                        </table>
                    </div>
                    <!-- Cart Update Option -->
                    
                </div>
            </div>
           
        </div>
    </div>
</div>
@else
<br>
<div class="cart-main-wrapper section">
        <h2 class="text-center text-muted">
        <img src="{{asset('img/EMPTY_WISHLIST.png')}}">
        <br/>
            <a href="{{url('/')}}" class="btn btn-primary emptyBtn">@lang('Go to Shop')</a>
        </h2>
    </div>
@endif
<!-- cart main wrapper end -->

@endsection

@section('scripts')
    @include('includes.cart-submit-script')
     
    <script>
        jQuery(document).ready(function() {
            $(".removeProduct").on("click",function() {
                if(confirm('Are you sure you want to delete product from wishlist?') ) {
                    $("#product-favourite-destroy-"+$(this).data('id')).submit();
                } else {
                    return false;
                }
            })

             
        });
    </script>
@endsection

