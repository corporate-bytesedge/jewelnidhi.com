<div class="section block-section" data-aos="fade-up" data-aos-once="true">
    <div class="container">
    @if($collections->image_two)
      <div class="row">
        <div class="col-md-6">
          <div class="img-sec">
          @if($collections->image_two)
							 
							<img src="{{ asset('storage/collection/'.$collections->image_two) }}" alt="{{ $collections->title_two ? $collections->title_two : __('Banner')}}"  class="img-responsive"  />
						@else
							<img src="https://via.placeholder.com/200x200?text=No+Image" alt="{{ $collections->title_two ? $collections->title_two : __('Banner')}}"  class="img-responsive" />
						@endif
             
          </div>
        </div>
       <div class="col-md-6">
         <div class="content">
           <h2>{{ $collections->title_two }}</h2>
           <p>
           {{ $collections->description_two }}
           </p>
           <a href="{{ $collections->link_two ? $collections->link_two : url()->current()}}">View all</a>
         </div>
       </div>
      
      </div>
    </div>
  @endif
  </div>