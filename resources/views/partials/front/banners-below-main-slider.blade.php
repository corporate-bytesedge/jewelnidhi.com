<div class="section block-section gold"  data-aos="fade-up" data-aos-once="true">
     <div class="container">
     @if($collections->image_one)
      
      
         
        <div class="row">
            <div class="col-md-6">
              <div class="content">
                <h2>{{ $collections->title_one }}</h2>
                <p>
                {{ $collections->description_one }}
                </p>
                <a href="{{ $collections->link_one  ? $collections->link_one : url()->current()}}">View all</a>
              </div>
            </div>
            <div class="col-md-6">
            <div class="img-sec">
            @if($collections->image_one)
							 
							<img src="{{asset('storage/collection/'.$collections->image_one)}}" alt="{{ $collections->title_one ? $collections->title_one : __('')}}"  class="img-responsive"  />
						@else
							<img src="{{ asset('img/noimage.png') }}" alt="{{ $collections->title_one ? $collections->title_one : __('')}}"  class="img-responsive" />
						@endif
               
            </div>
          </div>
        </div>
        
      @endif
     </div>
   </div>