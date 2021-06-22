@extends('layouts.front')

@section('style')
<style>
.pagination {
  display: inline-block;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
}
</style>
@endsection
 
 

 

@section('content')
 
<div class="container"> 
  <div class="row"> 
        <div class="col-md-12 about-us-page">
          <h1 class="about-title_h1"><span> {!! $scheme->title !!} </span></h1> 
              <div class="page-content m-10">
                  <div id="about">
                     
                      {!! $scheme->content !!}
                        <!-- <p>{{ $scheme->content }}</p> -->
                       
                  </div>

                  <div class="col-sm-12">
                  </div>
              </div>
        </div> 
  </div>
</div>
 


    

@endsection
 