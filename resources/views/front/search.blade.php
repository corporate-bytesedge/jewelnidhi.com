@extends('layouts.front')

@section('title'){{$keyword}} - {{config('app.name')}}@endsection
@section('meta-tags')<meta name="description" content="@lang('Showing Search Results for'): {{$keyword}}">
@endsection
@section('meta-tags-og')<meta property="og:url" content="{{url()->current()}}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{$keyword}} - {{config('app.name')}}" />
    <meta property="og:description" content="@lang('Showing Search Results for'): {{$keyword}}" />
@endsection

@section('scripts')
    @include('includes.products-pagination-script')
    @include('includes.cart-submit-script')
@endsection

@section('content')
<div class="breadcrumb-sec">
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
         
      </ol>
    </div>
  </div>
  <div class="category-page">
    <div class="container">
      <div class="category-head">
         
        {{ isset($products) ? count($products) : 0 }} Designs 
      </div>
      
      <div class="row">
        @include('partials.front.products')
      </div>

      
    </div>
  </div>
 
@endsection
