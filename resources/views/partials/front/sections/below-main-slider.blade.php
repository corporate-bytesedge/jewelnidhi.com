<div class="marquee-sec">
     <div class="container">
       <marquee>
        Your online trusted store to buy gold  at wholesale prices, for more details  please contact <a href="#">info@jewelnidhi.com</a> 9949016121
       </marquee>
     </div>
   </div>
    
  
   <div class="section top-category "  data-aos="fade-up" data-aos-once="true">
     <div class="container">
       <div class="heading-sec">
         <h2>Top Categories</h2>
         <img src="{{ URL::asset('img/home_line.png') }}" alt=""/>
       </div>
       <div class="row">
          
       @if(!empty($topcategories))
        
       @foreach($topcategories as $category)
           
            @if($category->category_img)
                @php
                    $image_url = 'storage/style/topcategory/'.$category->category_img;
                @endphp
            @else
                @php
                  $image_url = url('/'."img/noimage.png");
                @endphp
            @endif
           
            
         <div class="col-md-4">
           <div class="category-block">
           <a href="/category/{{ $category->slug }}"><img src="{{ $image_url }}" alt=""/>
            <h3>{{ $category->name }}</h3></a>
           </div>
         </div>
         @endforeach
        @endif 
         
        
        
       
       </div>
     </div>
   </div>
