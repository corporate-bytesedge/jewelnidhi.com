<div class="section block-section gold" data-aos="fade-up" data-aos-once="true">
    <div class="container">
    @if($collections->image_three)
        <div class="row">
            <div class="col-md-6">
              <div class="content">
                <h2>{{ $collections->title_three }}</h2>
                <p>
                  {{ $collections->description_three }}
                </p>
                <a href="{{ $collections->link_three ? $collections->link_three : url()->current()}}">View all</a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="img-sec">
              @if($collections->image_three)
						 
							<img src="{{ asset('storage/collection/'.$collections->image_three) }}" alt="{{ $collections->title_three ? $collections->title_three : __('Banner')}}"  />
						@else
							<img src="https://via.placeholder.com/200x200?text=No+Image" alt="{{ $collections->title_three ? $collections->title_three : __('Banner')}}"   />
						@endif
                 
              </div>
            </div>
        </div>
      @endif  
    </div>
  </div>