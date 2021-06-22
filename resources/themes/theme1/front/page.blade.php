@extends('layouts.front')
 
@if(Request::segment(1) == 'about')
 
	<title>{{ config('settings.about_us_title') }}</title>
@else
	<title>{{ isset($page->meta_title) ? $page->meta_title : config('app.name') }}</title>
@endif
@section('meta-tags')<meta name="description" content="{{ isset($page->meta_desc) ? $page->meta_desc : ''}}">
@if(isset($page->meta_keywords) )<meta name="keywords" content="{{$page->meta_keywords}}">@endif
@endsection
@section('meta-tags-og')<meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ isset($page->meta_title) ? $page->meta_title : config('app.name')}}" />
    <meta property="og:description" content="{{ isset($page->meta_desc) ? $page->meta_desc : ''}}" />
@endsection

@section('styles')
	
@endsection

@section('content')
 
<div class="container">
	<div class="row">
		<div class="col-md-12 about-us-page" >
			<h1 class="about-title_h1"><span> {{ isset($page->title) ? $page->title : '' }} </span></h1>
			<div class="page-content m-10">
				{!! isset($page->content) ? $page->content : '' !!}
			</div>
		</div>
	</div>
</div>
@endsection
