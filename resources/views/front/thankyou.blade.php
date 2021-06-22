@extends('layouts.front')
@section('content')
<div class="thanks-page">
	<div class="container">
		<div class="text-center">
			<i class="fa fa-check-circle"></i>
			<h1>Thank You</h1>
			<h5>Your order was completed successfully.</h5>
			<a href="{{ url('/') }}">Back to Home</a>
		</div>
	</div>
</div>
@endsection