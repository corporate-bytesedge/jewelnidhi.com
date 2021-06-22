
<?php if(count($products) > 0): ?>
  <?php $__env->startSection('title'); ?><?php echo e($keyword); ?> - <?php echo e(config('app.name')); ?><?php $__env->stopSection(); ?>
  <?php $__env->startSection('meta-tags'); ?><meta name="description" content="<?php echo app('translator')->getFromJson('Search results for'); ?>: <?php echo e($keyword); ?>">
  <?php $__env->stopSection(); ?>
  <?php $__env->startSection('meta-tags-og'); ?><meta property="og:url" content="<?php echo e(url()->current()); ?>" />
      <meta property="og:type" content="website" />
      <meta property="og:title" content="<?php echo e($keyword); ?> - <?php echo e(config('app.name')); ?>" />
      <meta property="og:description" content="<?php echo app('translator')->getFromJson('Search results for'); ?>: <?php echo e($keyword); ?>" />
  <?php $__env->stopSection(); ?>
<?php endif; ?>
<?php $__env->startSection('scripts'); ?>
 
<script type="text/javascript">
var addWishlist;
var removeWishlist;

  var page = 1;
  var didScroll = false;
  $(window).scroll(function() {
    didScroll = true;
  });

  setInterval(function() {
    if (didScroll){
       didScroll = false;
       var body = window.innerHeight + window.scrollY + 600;
       var footer = document.body.offsetHeight;
        
      
      if ((body) >= document.body.offsetHeight) {
         /* if($(window).scrollTop() + $(window).height() >= $(document).height()) {*/
          pageCountUpdate();
         /* page++; 
          loadMoreData(page);
          */
        }
   }
}, 250)

  function pageCountUpdate() {
    var page = parseInt($('#page').val());
    var max_page = parseInt($('#max_page').val());
    if(page < max_page) {
      $('.ajax-load').show();
      $('#page').val(page+1);
      var pages = $('#page').val();
      var url = window.location.href;
      
      var arr = url.split('?');
      console.log(arr);
      loadMoreData(pages,arr);
        
      } 
  }
  

  function loadMoreData(page,arr) {
      
    $.ajax({
      
        url: '?page=' + page+'&'+arr[1],
        type: "get",
        
        beforeSend: function()
        {
            $('.ajax-load').show();
        }
    }).done(function(data) {
      
      $('[data-toggle="tooltip"]').tooltip();
        if(data.html == " "){
            $('.ajax-load').html("No more records found");
            return;
        }
        $('.ajax-load').hide();
        if($('.listProduct').hasClass('active')) {
          
          $("#contentProduct #itemList").removeClass("col-6");
          $("#contentProduct #itemList").addClass("col-sm-6");
        }

        if($('.gridProduct').hasClass('active')) {
          
          $("#contentProduct #itemList").removeClass("col-sm-6");
          $("#contentProduct #itemList").addClass("col-6");
        }
        
        $("#contentProduct").append(data.html);
        
    }).fail(function(jqXHR, ajaxOptions, thrownError) {
          //alert('server not responding...');
    });
  }

    
  jQuery(document).ready(function() {
     
    
     

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".priceFilter").on("change",function() {

        
         
        $.ajax({
          type:"POST",
          url:"<?php echo e(route('front.search_price_filter')); ?>",
          data:{value:$(this).val(),slug:$(this).data('slug'),price:$(this).find(':selected').attr('data-price')},
          beforeSend: function() {
            // Show image container
            $("#contentProduct").hide();
            $("#ajaxcontentProduct").hide();
            $("#loader").show();
          },
          success:function(data) {
            $("#loader").hide(); 
            $("#ajaxcontentProduct").show();
            $("#ajaxcontentProduct").html(data);
            $('[data-toggle="tooltip"]').tooltip();
            gridFunction();
          } 
        });
     

    });

    $("#priceSorting").on("change",function() {
      
      if($(this).val()!='') {
        $.ajax({
          type:"POST",
          url:"<?php echo e(route('front.search_price_sorting')); ?>",
          data:{value:$(this).val(),slug:$(this).data('slug')},
          beforeSend: function() {
            // Show image container
            $("#contentProduct").hide();
            $("#ajaxcontentProduct").hide();
            $("#loader").show();
            
          },
          success:function(data) {
            
            $("#loader").hide(); 
            $("#ajaxcontentProduct").show();
            $("#ajaxcontentProduct").html(data);
            $('[data-toggle="tooltip"]').tooltip();
            gridFunction();
           
            
          } 
        });
      }

    });

    function gridFunction() {

            
           if($(".listProduct").hasClass('active')) {
             $(".gridProduct").removeClass("active");
             $(this).addClass("active");
            
             $("#ajaxcontentProduct #itemList").addClass("col-sm-6");
             $("#ajaxcontentProduct #itemList").removeClass("col-6");
           }
           $(".listProduct").on("click",function() {
              
             $(".gridProduct").removeClass("active");
             $(this).addClass("active");
            
             $("#ajaxcontentProduct #itemList").addClass("col-sm-6");
             $("#ajaxcontentProduct #itemList").removeClass("col-6");
             
           });
           if($(".gridProduct").hasClass('active')) {
             $(".listProduct").removeClass("active");
             $(this).addClass("active");
             $("#ajaxcontentProduct #itemList").removeClass("col-sm-6");
             $("#ajaxcontentProduct #itemList").addClass("col-6");
           }

           $(".gridProduct").on("click",function() {
             $(".listProduct").removeClass("active");
             $(this).addClass("active");
             $("#ajaxcontentProduct #itemList").removeClass("col-sm-6");
             $("#ajaxcontentProduct #itemList").addClass("col-6");
             
           });
    }

    $("#metalFilter").on("change",function() {
         
      
        $.ajax({
          type:"POST",
          url:"<?php echo e(route('front.metal_filter')); ?>",
          data:{category_id:$(this).val(),slug:$(this).data('slug'),metal:$(this).find(':selected').attr('data-metal')},
          beforeSend: function() {
            // Show image container
            $("#contentProduct").hide();
            $("#ajaxcontentProduct").hide();
            $("#loader").show();
            
          },
          success:function(data) {
            
            $("#loader").hide(); 
            $("#ajaxcontentProduct").show();
            $("#ajaxcontentProduct").html(data);
            $('[data-toggle="tooltip"]').tooltip();
            gridFunction();
          } 
        });
      

    });
    $("#typeFilter").on("change",function() {
         
      
         $.ajax({
           type:"POST",
           url:"<?php echo e(route('front.type_filter')); ?>",
           data:{type:$(this).val(),slug:$(this).data('slug'),attr:$(this).data('attr')},
           beforeSend: function() {
             // Show image container
             $("#contentProduct").hide();
             $("#ajaxcontentProduct").hide();
             $("#loader").show();
             
           },
           success:function(data) {
             
             $("#loader").hide(); 
             $("#ajaxcontentProduct").show();
             $("#ajaxcontentProduct").html(data);
             $('[data-toggle="tooltip"]').tooltip();
             gridFunction();
           } 
         });
       
 
     });
    

    

    $("#purityFilter").on("change",function() {
         
          
         $.ajax({
           type:"POST",
           url:"<?php echo e(route('front.search_purity_filter')); ?>",
           data:{value:$(this).val(),slug:$(this).data('slug')},
           beforeSend: function() {
             // Show image container
             $("#contentProduct").hide();
             $("#ajaxcontentProduct").hide();
             $("#loader").show();
             
           },
           success:function(data) {
             
             $("#loader").hide(); 
             $("#ajaxcontentProduct").show();
             $("#ajaxcontentProduct").html(data);
             $('[data-toggle="tooltip"]').tooltip();
             gridFunction(); 
           } 
         });
        
 
     });


    $("#genderFilter").on("change",function() {
         
          
           $.ajax({
             type:"POST",
             url:"<?php echo e(route('front.search_gender_filter')); ?>",
             data:{value:$(this).val(),slug:$(this).data('slug')},
             beforeSend: function() {
               // Show image container
               $("#contentProduct").hide();
               $("#ajaxcontentProduct").hide();
               $("#loader").show();
               
             },
             success:function(data) {
               
               $("#loader").hide(); 
               $("#ajaxcontentProduct").show();
               $("#ajaxcontentProduct").html(data);
               $('[data-toggle="tooltip"]').tooltip();
               gridFunction();
             } 
           });
          
   
       });

       $("#offerFilter").on("change",function() {
         
          
         $.ajax({
           type:"POST",
           url:"<?php echo e(route('front.search_offer_filter')); ?>",
           data:{value:$(this).val(),slug:$(this).data('slug')},
           beforeSend: function() {
             // Show image container
             $("#contentProduct").hide();
             $("#ajaxcontentProduct").hide();
             $("#loader").show();
             
           },
           success:function(data) {
             
             $("#loader").hide(); 
             $("#ajaxcontentProduct").show();
             $("#ajaxcontentProduct").html(data);
             $('[data-toggle="tooltip"]').tooltip();
             gridFunction();
           } 
         });
        
 
     });

                                             
     addWishlist =function (id) {
         
       $.ajax({
           type:"POST",
           url:$("#product-favourite-form_"+id).attr('action'),
           data:{id:id},
           
           success:function(data) {
             
             
              $(".toast").toast({
               autohide:true,
               delay:2000
              });
              $(".toast").toast('show');

              
              $(".toast .toast-body").html('<i class="fa fa-check-circle"></i>&nbsp;&nbsp;Added to your wishlist');
             
              
             $("#removeBtn_"+id).removeAttr('hidden');
             $("#removeBtn_"+id).show();
              $("#addBtn_"+id).hide();
             
           } 
         });
       
     }

     removeWishlist = function (id) {
       
       
         
          $.ajax({
           type:"GET",
           url:"<?php echo e(route('front.product.favourite.destroywishlist')); ?>"+'?id='+id,
            
           
           success:function(data) {
            
            $(".toast").toast({
               autohide:true,
               delay:2000
              });
               
            $(".toast").toast('show');
            $(".toast .toast-body").html('<i class="fa fa-check-circle"></i>&nbsp;&nbsp;Removed from your wishlist');
              
             
              $("#removeBtn_"+id).hide();
              $("#addBtn_"+id).removeAttr('hidden');
              $("#addBtn_"+id).show();
             
             
           } 
         });
     }
       
     if($(window).width() > 767) {
        
        $(".nice-select").mouseover(
          function(){
            $(this).addClass("open");
          }
        ).mouseout(
          function(){
            $(this).removeClass("open");
          }
        );
      }
      $(".FillterBY").on("click",function(){
        $("#filterDiv").toggle();
         
      });
      $('.SortBy').click(function() { 
        $("#SortDiv").toggle();
      });

      $(".listProduct").on("click",function() {
        $(".gridProduct").removeClass("active");
        $(this).addClass("active");
        if($(this).hasClass('active')) {
          $("#contentProduct #itemList").removeClass("col-6");
          $("#contentProduct #itemList").addClass("col-sm-6");
        }
        

      });
      
      $(".gridProduct").on("click",function() {
        $(".listProduct").removeClass("active");
        $(this).addClass("active");
        if($(this).hasClass('active')) {
          $("#contentProduct #itemList").removeClass("col-sm-6");
          $("#contentProduct #itemList").addClass("col-6");
        }
        
      });
  });
</script>
<script src="<?php echo e(asset('js/nice-select.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/xzoom.js')); ?> "></script>
<script src="<?php echo e(asset('js/image-zoom.min.js')); ?>"></script>
<script src="<?php echo e(asset('js/script.js')); ?> "></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="breadcrumb-sec">
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
        <li class="breadcrumb-item"> Search results for " <?php echo e(ucwords(request()->query('keyword'))); ?> " </li>
      </ol>
    </div>
</div>
 
<div class="category-page">
  <div class="container">
  <div class="category-head">
      <h4 style="color:#D3A012">Search results for " <?php echo e(ucwords(request()->query('keyword'))); ?> "</h4>
      <span><?php echo e(count($allProduct)); ?> Designs</span>
    </div>
     
      <?php if(!empty($category)): ?>
      <div class="filter-sec">
        <div class="left">
          <h4>Filter by</h4>
          <div class="block">
            <div class="box">
              <?php if($category->category!=null): ?>
              <select class="nice-select priceFilter" data-slug="<?php echo e(request()->get('keyword')); ?>" >
                <option data-price="all" value="all" >PRICE</option>
                
                    <?php 
                      $priceRange = 0;
                      $priceRange = range($category->category->min_price,$category->category->max_price);
                      $minPrice = $category->category->min_price;
                      $maxPrice = $category->category->max_price;
                    ?>
                    <?php if($category->category->min_price!='0' && $category->category->max_price!='0'): ?>
                        <option data-price="min" value="<?php echo e($minPrice); ?>">Below ₹ <?php echo e(isset($category->category->min_price) ? $category->category->min_price: ''); ?>  </option>
                          <?php for($i = $category->category->min_price; $i < $category->category->max_price; $i = $i+10000): ?>
                          
                              <?php
                                $minimumPrice = explode('.',$i);
                                $firstNum = $i;
                                $minimumPrice = str_split($firstNum);
                                $lastNum = $i + 10000;
                                $MaximumPrice = str_split($lastNum);
                              ?>
                            <option  value="<?php echo e($firstNum); ?>/<?php echo e($lastNum); ?>"> Between <?php echo e($minimumPrice[0].$minimumPrice[1]); ?>K -  <?php echo e($MaximumPrice[0].$MaximumPrice[1]); ?>K  </option>
                          <?php endfor; ?>
                        <option data-price="above" value="<?php echo e($category->category->max_price); ?>">₹ <?php echo e(isset($category->category->max_price) ? $category->category->max_price: ''); ?> and above </option>
                      <?php endif; ?>
                      </select>
              <?php else: ?>
              <select class="nice-select priceFilter" data-slug="<?php echo e(request()->get('keyword')); ?>" >
                <option data-price="all" value="all" >PRICE</option>
                
                    
                    

                    <?php  
                     
                          $start = $category->min_price;
                          $end = $category->max_price;
                          $step = 10000;
                        ?>
                        <option data-price="min" value="0/<?php echo e($start); ?>">Under ₹ <?php echo e(isset($start) ? $start: ''); ?> </option>
                    
                      <?php for($i=$start; $i<$end; $i=$i+$step): ?>
                            <?php
                            
                              $lastNum = $i + $step;
                              $minimumPrice = explode('.',$i);
                              $firstNum = $i;
                              $minimumPrice = str_split($firstNum);
                              $lastNum = $i + $step;
                              $MaximumPrice = str_split($lastNum);
                            ?>
                            
                        <option  value="<?php echo e($firstNum); ?>/<?php echo e($lastNum); ?>"> Between <?php echo e($minimumPrice[0].$minimumPrice[1]); ?>K -  <?php echo e($MaximumPrice[0].$MaximumPrice[1]); ?>K </option>
                      <?php endfor; ?>
                    
                  
                    
                    <option data-price="above" value="<?php echo e($end); ?>">Above ₹ <?php echo e(isset($end) ? $end: ''); ?> </option>
                  
                    
                    
                  
                
              </select>
              <?php endif; ?>
            </div>
            <div class="box">
              <select class="nice-select" data-slug="<?php echo e(request()->get('keyword')); ?>" id="metalFilter">
                <option data-metal="all" value="all">METAL</option>
                
                <?php if(!empty($metal)): ?>
                  <?php $__currentLoopData = $metal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ke=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($ke==0): ?>
                    <option data-metal="<?php echo e($value->category->name); ?>" value="<?php echo e($value->category->id); ?>"><?php echo e($value->category->name); ?> ( <?php echo e(count($metal)); ?> )  </option>
                    <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                
            </select>
            </div>
            <div class="box">
              <select class="nice-select" data-slug="<?php echo e(request()->get('keyword')); ?>" id="purityFilter">
                <option>PURITY</option>
                <option value="14">14K ( <?php echo e(isset($purity_fourteen_carat) ? count($purity_fourteen_carat) : ''); ?> )  </option>
                <option value="18">18K ( <?php echo e(isset($purity_eighteen_carat) ? count($purity_eighteen_carat) : ''); ?> )  </option>
                <option value="22">22K ( <?php echo e(isset($purity_twenty_two_carat) ? count($purity_twenty_two_carat) : ''); ?> )  </option>
                <option value="24">24K ( <?php echo e(isset($purity_twenty_four_carat) ? count($purity_twenty_four_carat) : ''); ?> )  </option>
                
              
            </select>
            </div>
            <div class="box">
              <select class="nice-select" data-slug="<?php echo e(request()->get('keyword')); ?>" id="genderFilter">
              <option value ="all">GENDER</option>
              <option value="m">Men ( <?php echo e(isset($maleProduct) ? $maleProduct : ''); ?> ) </option>
              <option value="f">Women  ( <?php echo e(isset($femaleProduct) ? $femaleProduct : ''); ?> ) </option>
              <option value="u">Unisex ( <?php echo e(isset($uniSexProduct) ? $uniSexProduct : '0'); ?> ) </option>
            </select>
            </div>
            <div class="box">
              <select class="nice-select" data-slug="<?php echo e(request()->get('keyword')); ?>" id="offerFilter">
              <option value="<?php echo e(isset($offerProduct) ? $offerProduct : 0); ?>">OFFERS (<?php echo e(isset($offerProduct) ? $offerProduct : 0); ?>)</option>
              </select>
            </div>
          </div>
        </div>

        <div class="right">
          <h4>Sort by</h4>
          <div class="block">
            <div class="box">
              <select class="nice-select" data-slug="<?php echo e(request()->get('keyword')); ?>" id="priceSorting">
                
                <option value="asc" >Price (Low To High)</option>
                <option value="desc" >Price (High To Low)</option>
                <option value ="all" >Relevance</option>
                <option value="1" >New Arrivals</option>
                <option value="2">Best Selling</option>
                <option value="3" >Discounted</option>
            </select>
            </div>
          </div>
        </div>
        
      </div>
      <?php endif; ?>
  
      
         <!-- Image loader -->
<div id='loader' style='display: none;text-align:center'>
  <img src="<?php echo e(URL::asset('img/reload.gif')); ?>">
</div>
<!-- Image loader -->
<input type="hidden" id="page" value="1" />
<input type="hidden" id="max_page" value="<?php echo isset($max_page) ? ceil($max_page) : '' ?>" /> <div id="ajaxcontentProduct"></div> <div class="row" id="contentProduct"> <?php if(count($products) > 0 ): ?> <?php echo $__env->make('front.ajaxcategorypagination', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> <?php else: ?> <div class="col-lg-12 col-md-12 col-sm-12"> <h1 class="mb-2 text-center">Oops!</h1> <h2 class="mb-5 text-center"> We could not find what you are looking for!</h2> <a href="<?php echo e(url('/')); ?>" class="btn btn-primary ShoppingBtn ">CONTINUE SHOPPING</a> </div> <?php endif; ?> </div> </div> <div class="ajax-load text-center" style="display:none"> <p><img src="<?php echo e(asset('img/loader.gif')); ?>"></p> </div> </div> <div class="toast" role="alert" aria-live="assertive" aria-atomic="true"> <div class="toast-body"></div> </div> <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>