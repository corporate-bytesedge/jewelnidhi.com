@extends('layouts.front')

@section('title')@lang('Track Orders') - {{config('app.name')}}@endsection

@section('styles')
    <link href="{{asset('css/front-sidebar-content.css')}}" rel="stylesheet">
    @include('includes.order_tracker_style')
    <style>
        .orders-details-inner > h3 {
            padding: 6px !important;
            margin: 0px !important;
            font-size: 18px;
            border-bottom: 1px solid #eee;
        }

        .orders-details-inner > ul > li {
            float: left;
            list-style: none;
            margin-bottom: 20px;
            padding: 10px !important;
            border-right: 1px solid #eee;
        }

        .orders-details-inner ul li a {
            color: #333;
            font-size: 16px;
        }

        .orders-details {
            background-color: #f9f9f9;
            padding: 15px;
            margin-top: 15px;
        }

        .bar1, .bar2, .bar3 {
            width: 22px;
            height: 3px;
            background-color: #333;
            margin: 4px 0;
            transition: 0.4s;
        }

        .change .bar1 {
            -webkit-transform: rotate(-45deg) translate(-7px, 3px);
            transform: rotate(-45deg) translate(-7px, 3px);
        }

        .change .bar2 {
            opacity: 0;
        }

        .change .bar3 {
            -webkit-transform: rotate(45deg) translate(-7px, -3px);
            transform: rotate(45deg) translate(-7px, -3px);
        }

        .btn-toggle-close a {
            margin-top: 20px !important;
            display: block;
        }

        .orders-details-inner-sub-2 {
            margin-top: 15px;
        }

        .orders-details-inner-sub-2 img {
            width: 60px;
            float: left;
            margin-right: 15px;
        }

        .orders-details-inner-sub-2 h4 a {
            color: #888;
        }

        .orders-details-inner-sub-2 p {
            padding-top: 0px;
            font-size: 14px;
            color: #333;
        }

        @media (max-width: 767px) {
            .orders-details-inner-sub-2 {
                height: 100%;
                display: block;
                margin: 40px auto;
            }
        }

        .panel-body-row {
            text-align: left;
        }

        .img-box {
            text-align: center;
        }

        .img-box .product-img {
            height: 80px;
            width: auto !important;
        }

        .product-img {
            width: 100% !important;
        }

        .grand-total p {
            text-align: left;
            padding: 0px;
        }

        .panel-heading, .panel-body {
            border: 1px solid #e5e5e5;
        }

        .panel-heading {
            border-bottom: none;
        }

        .orders-details {
            border: 1px solid #158cba;
            border-radius: 5px;
        }

        .media-body {
            text-align: center;
        }

        .order_detail_item_name {
            color: #444;
        }
    </style>
@endsection

@section('scripts')
    <script>
        function myFunction(x) {
            x.classList.toggle("change");
        }
    </script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        @include('partials.front.sidebar')
            <div class="content col-md-9">
                
                <div class="card">
                
                    <div class="card-body">
                        <div class="page-title">
                            <h2 class="wallet-heading">@lang('Wallet History')<button type="button" class="btn btn-primary pull-right">
                                Current Balance : {{ currency_format(\Auth::user()->wallet_balance, config('currency.default') )}}
                            </button></h2>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>@lang('Txn Id')</th>
                                        <th>@lang('Txn Amount')</th>
                                        <th>@lang('Description')</th>
                                        <th>@lang('Txn Date')</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(count($user_wallet_history) > 0)
                                    @foreach($user_wallet_history as $key => $wallet)
                                        @if($wallet->transaction_amt != '0')
                                        <tr>
                                            <td>
                                                <h4>
                                                    <a class="" onclick="myFunction(this)" class="btn-toggle-close"
                                                data-toggle="collapse" href="#multiCollapseExample{{$key+1}}"
                                                aria-expanded="false" aria-controls="multiCollapseExample{{$key+1}}">
                                                    #TXN-{{ StringHelper::preZero($wallet->wallet_id)}}
                                                    </a>
                                                </h4>
                                            </td>
                                            <td>  
                                                @if(!empty($wallet->credit_amt) && $wallet->debit_amt == 0)
                                                    <span class="text-success">{{currency_format($wallet->transaction_amt,$wallet->transaction_currency)}}</span>
                                                @else
                                                    <span class="text-danger">{{currency_format($wallet->transaction_amt,$wallet->transaction_currency)}}</span>
                                                @endif
                                            </td>
                                            <td>{{$wallet->transaction_description}}</td>
                                            <td>{{$wallet->created_at->toFormattedDateString()}}</td>
                                            <td>
                                                        <a class="" onclick="myFunction(this)" class="btn-toggle-close" data-toggle="collapse"
                                                href="#multiCollapseExample{{$key+1}}" aria-expanded="false"
                                                aria-controls="multiCollapseExample{{$key+1}}">
                                                    <div class="bar1"></div>
                                                    <div class="bar2"></div>
                                                    <div class="bar3"></div>
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <div class="clearfix"></div>
    </div>
</div>
@endsection
