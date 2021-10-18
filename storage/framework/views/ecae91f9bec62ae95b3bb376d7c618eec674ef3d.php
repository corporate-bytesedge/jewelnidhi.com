
<?php if(isset($category)): ?>
 
<?php $__env->startSection('title'); ?><?php echo e(config('app.name')." - ".$category->name); ?><?php $__env->stopSection(); ?>
    
  <?php $__env->startSection('meta-tags'); ?><meta name="description" content="<?php echo e($category->meta_desc ? $category->meta_desc : __('Showing Products for Category:') .$category->name); ?>">
  <?php if($category->meta_keywords): ?><meta name="keywords" content="<?php echo e($category->meta_keywords); ?>"><?php endif; ?>
  
  <?php $__env->stopSection(); ?>
  <?php $__env->startSection('meta-tags-og'); ?><meta property="og:url" content="<?php echo e(url()->current()); ?>" />
      <meta property="og:type" content="website" />
      <meta property="og:title" content="<?php echo e(isset($category->meta_title) ? $category->meta_title : $category->name.' - '.config('app.name')); ?>" />
      <meta property="og:description" content="<?php echo e(isset($category->meta_desc) ? $category->meta_desc : __('Showing Products for Category:') .$category->name); ?>" />
      <?php if($category->photo): ?><meta property="og:image" content="<?php echo e($category->photo->name); ?>" /><?php endif; ?>
  <?php $__env->stopSection(); ?>
<?php endif; ?>
<?php $__env->startSection('scripts'); ?>
 
<script type="text/javascript">
var addWishlist;
var removeWishlist;

  var page = 1;
  var latestFilter = "";
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
      loadMoreData(pages);
        
      } 
  }
  

  function loadMoreData(page) {
	  if(latestFilter == 'sort'){
		  $.ajax({
      
        url: "<?php echo e(route('front.price_sorting')); ?>",
        type: "post",
        data:{value:$("#priceSorting").val(),slug:$("#priceSorting").data('slug'),price_filter:$(".priceFilter").val(),gender_sorting:$("#genderFilter").val(),page:page},
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
         $("#ajaxcontentProduct").append(data.html);
        $('[data-toggle="tooltip"]').tooltip();
        
        
    }).fail(function(jqXHR, ajaxOptions, thrownError) {
          //alert('server not responding...');
    });
	  }else if(latestFilter == 'price'){
		  $.ajax({
      
        url: "<?php echo e(route('front.price_filter')); ?>",
        type: "post",
        data:{value:$(".priceFilter").val(),slug:$(".priceFilter").data('slug'),price:$(".priceFilter").find(':selected').attr('data-price'),price_sorting:$("#priceSorting").val(),gender_sorting:$("#genderFilter").val(),page:page },
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
         $("#ajaxcontentProduct").append(data.html);
        $('[data-toggle="tooltip"]').tooltip();
        
    }).fail(function(jqXHR, ajaxOptions, thrownError) {
          //alert('server not responding...');
    });
	  }else if(latestFilter == 'metal'){
		  $.ajax({
      
        url: "<?php echo e(route('front.metal_filter')); ?>",
        type: "post",
        data:{category_id:$("#metalFilter").val(),slug:$("#metalFilter").data('slug'),metal:$("#metalFilter").find(':selected').attr('data-metal'),page:page },
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
         $("#ajaxcontentProduct").append(data.html);
        $('[data-toggle="tooltip"]').tooltip();
        
    }).fail(function(jqXHR, ajaxOptions, thrownError) {
          //alert('server not responding...');
    });
	  }else if(latestFilter == 'type'){
		  $.ajax({
      
        url: "<?php echo e(route('front.type_filter')); ?>",
        type: "post",
        data:{type:$("#typeFilter").val(),slug:$("#typeFilter").data('slug'),attr:$("#typeFilter").data('attr'),page:page },
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
         $("#ajaxcontentProduct").append(data.html);
        $('[data-toggle="tooltip"]').tooltip();
        
    }).fail(function(jqXHR, ajaxOptions, thrownError) {
          //alert('server not responding...');
    });
	  }else if(latestFilter == 'purity'){
		  $.ajax({
      
        url: "<?php echo e(route('front.purity_filter')); ?>",
        type: "post",
        data:{value:$("#purityFilter").val(),slug:$("#purityFilter").data('slug'),page:page },
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
         $("#ajaxcontentProduct").append(data.html);
        $('[data-toggle="tooltip"]').tooltip();
        
    }).fail(function(jqXHR, ajaxOptions, thrownError) {
          //alert('server not responding...');
    });
	  }else if(latestFilter == 'gender'){
		  $.ajax({
      
        url: "<?php echo e(route('front.gender_filter')); ?>",
        type: "post",
        data:{value:$("#genderFilter").val(),slug:$("#genderFilter").data('slug'),price:$(".priceFilter").val(),price_sorting:$("#priceSorting").val(),page:page },
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
         $("#ajaxcontentProduct").append(data.html);
        $('[data-toggle="tooltip"]').tooltip();
        
    }).fail(function(jqXHR, ajaxOptions, thrownError) {
          //alert('server not responding...');
    });
	  }else{
    $.ajax({
      
        url: '?page=' + page,
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
  }

    
  jQuery(document).ready(function() {
     
    
     

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".priceFilter").on("change",function() {

        latestFilter = "price";
        $.ajax({
          type:"POST",
          url:"<?php echo e(route('front.price_filter')); ?>",
          data:{value:$(this).val(),slug:$(this).data('slug'),price:$(this).find(':selected').attr('data-price'),price_sorting:$("#priceSorting").val(),gender_sorting:$("#genderFilter").val() },
          beforeSend: function() {
            // Show image container
            $("#contentProduct").hide();
            $("#ajaxcontentProduct").hide();
            $("#loader").show(); 
          },
          success:function(data) {
            $("#loader").hide(); 
            $("#ajaxcontentProduct").show();
            $("#ajaxcontentProduct").html(data.html);
            $('[data-toggle="tooltip"]').tooltip();
            gridFunction();
			$('#page').val(1);
            $('#max_page').val(Math.ceil(data.max_page));
          } 
        });
     

    });

    $("#priceSorting").on("change",function() {
		
      
      if($(this).val()!='') {
		  latestFilter = "sort";
        $.ajax({
          type:"POST",
          url:"<?php echo e(route('front.price_sorting')); ?>",
          data:{value:$(this).val(),slug:$(this).data('slug'),price_filter:$(".priceFilter").val(),gender_sorting:$("#genderFilter").val(),page:''},
          beforeSend: function() {
            // Show image container
            $("#contentProduct").hide();
            $("#ajaxcontentProduct").hide();
            $("#loader").show();
            
          },
          success:function(data) {
            
            $("#loader").hide(); 
            $("#ajaxcontentProduct").show();
            $("#ajaxcontentProduct").html(data.html);
            $('[data-toggle="tooltip"]').tooltip();
            gridFunction();
			$('#page').val(1);
            $('#max_page').val(Math.ceil(data.max_page));
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
         
      latestFilter = "metal";
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
            $("#ajaxcontentProduct").html(data.html);
            $('[data-toggle="tooltip"]').tooltip();
            gridFunction();
			$('#page').val(1);
            $('#max_page').val(Math.ceil(data.max_page));
          } 
        });
      

    });
    $("#typeFilter").on("change",function() {
         
      latestFilter = "type";
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
             $("#ajaxcontentProduct").html(data.html);
             $('[data-toggle="tooltip"]').tooltip();
             gridFunction();
			 $('#page').val(1);
            $('#max_page').val(Math.ceil(data.max_page));
           } 
         });
       
 
     });
    

    

    $("#purityFilter").on("change",function() {
         
          latestFilter = "purity";
         $.ajax({
           type:"POST",
           url:"<?php echo e(route('front.purity_filter')); ?>",
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
             $("#ajaxcontentProduct").html(data.html);
             $('[data-toggle="tooltip"]').tooltip();
             gridFunction(); 
			 $('#page').val(1);
            $('#max_page').val(Math.ceil(data.max_page));
           } 
         });
        
 
     });


    $("#genderFilter").on("change",function() {
         
          latestFilter = "gender";
           $.ajax({
             type:"POST",
             url:"<?php echo e(route('front.gender_filter')); ?>",
             data:{value:$(this).val(),slug:$(this).data('slug'),price:$(".priceFilter").val(),price_sorting:$("#priceSorting").val()},
             beforeSend: function() {
               // Show image container
               $("#contentProduct").hide();
               $("#ajaxcontentProduct").hide();
               $("#loader").show();
               
             },
             success:function(data) {
               
               $("#loader").hide(); 
               $("#ajaxcontentProduct").show();
               $("#ajaxcontentProduct").html(data.html);
               $('[data-toggle="tooltip"]').tooltip();
               gridFunction();
			   $('#page').val(1);
            $('#max_page').val(Math.ceil(data.max_page));
             } 
           });
          
   
       });

       $("#offerFilter").on("change",function() {
         
          latestFilter = "offer";
         $.ajax({
           type:"POST",
           url:"<?php echo e(route('front.offer_filter')); ?>",
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
			  var wishlistCount = parseInt($("#wishlistCount").text());
			$("#wishlistCount").text(wishlistCount + 1);
             
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
             var wishlistCount = parseInt($("#wishlistCount").text());
			$("#wishlistCount").text(wishlistCount - 1);
             
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
 
<div class="inner-banner">
<?php 
if(isset($category)) {
  if($category->banner) {
    $bannerpath = public_path().'/storage/style/banner/'.$category->banner;
  } else {
    $bannerpath ='';
  }
}

 
?>
    <?php if(isset($category)): ?>
    
        <?php if(file_exists($bannerpath)): ?>
        
          <img src="<?php echo e(asset('storage/style/banner/'.$category->banner)); ?>">
        <?php else: ?>
      
        <img src="<?php echo e(URL::asset('img/nobanner.jpg')); ?>">
      <?php endif; ?>
      
    <?php endif; ?>  
   </div>
  <div class="breadcrumb-sec">
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
        <?php if(isset($category->category->name) && $category->category->name!=''): ?>
        <li class="breadcrumb-item"><a href="<?php echo e(strtolower($category->category->name)); ?>"><?php echo e($category->category->name); ?></a></li>
        <?php endif; ?>
        <?php if(isset($category->name) && $category->name!=''): ?>
        <li class="breadcrumb-item active" aria-current="page"><?php echo e($category->name); ?></li>
        <?php endif; ?>
      </ol>
    </div>
  </div>
   
  <div class="category-page">
    <div class="container">
      <div class="category-head">
        <h2><?php echo e($category->name ? ucwords($category->name) : ''); ?></h2>
        <span>
       
        <?php echo e(count($products) > 0 ? $products->total() : 0); ?> Designs</span>
        <div class="gridView ml-auto">
          <a href="javascript:void(0);" class="listProduct"><i class="fa fa-square"></i></a>
          <a href="javascript:void(0);" class="gridProduct active"><i class="fa fa-th-large"></i></a>
        
        </div>
      </div>
      <center>
                    
                    <?php if(session()->has('wishlist_msg')): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong><?php echo e(session('wishlist_msg')); ?></strong> 
            </div>
        <?php endif; ?>

                    </center>
                    <?php if(count($products) > 0): ?>  
      <div class="filter-sec">
      <div class="mobile-filter">
        <h4><a href="javascript:void(0);" class="FillterBY">Filter</a></h4>
        <h4><a href="javascript:void(0);" class="SortBy">Sort</a></h4>
      </div>
        <div class="left" id="filterDiv">
          <h4>Filter by</h4>
          <div class="block">
          <?php if($category->show_filter_price == '1'): ?> 
            <div class="box">
              <?php if($category->category!=null): ?>
              <select class="nice-select priceFilter" data-slug="<?php echo e(Request::segment(2)); ?>" >
                <option data-price="all" value="all" >PRICE</option>
                
                    <?php 
                      $priceRange = 0;
                      $priceRange = range($category->category->min_price,$category->category->max_price);
                      $start = $category->category->min_price;
                      $end = $category->category->max_price;
                      $step = 10000;
                      $minVal = '';
                    ?>
                    <?php if($category->category->min_price!='0' && $category->category->max_price!='0'): ?>
                        <option data-price="min" value="<?php echo e($start); ?>">Under ₹ <?php echo e(isset($category->category->min_price) ? number_format($category->category->min_price): ''); ?>  </option>
                          <?php for($i=$start; $i<$end; $i): ?>
                            <?php
                            
                              $lastNum = $i + $step;
                              $firstNum = $i;
                              $minimumPrice = str_split($firstNum);
                              $MaximumPrice = str_split($lastNum);
                              if($i < $step) {
                                $minVal = $minimumPrice[0];
                              } elseif($i==$step) {
                                $minVal = $minimumPrice[0].''.$MaximumPrice[1];
                              }else {
                                $minVal = $minimumPrice[0].''.$MaximumPrice[1];
                              }     
                            ?>
                            
                          <option  value="<?php echo e($firstNum); ?>/<?php echo e($lastNum); ?>"> Between <?php echo e($minVal); ?>K -  <?php echo e($MaximumPrice[0].$MaximumPrice[1]); ?>K </option>
                        <?php 
                        $i=$i+$step
                        ?>

                         
                         <?php endfor; ?>
                        <option data-price="above" value="<?php echo e($category->category->max_price); ?>">Above ₹ <?php echo e(isset($category->category->max_price) ? number_format($category->category->max_price): ''); ?> </option>
                      <?php endif; ?>
                      </select>
              <?php else: ?>
              <select class="nice-select priceFilter" data-slug="<?php echo e(Request::segment(2)); ?>" >
                <option data-price="all" value="all" >PRICE</option>
                
                     
                    

                    <?php  
                          $start = $category->min_price;
                          $end = $category->max_price;
                          
                          $minVal = '';
                          $step = 10000;
                          
                        ?>
                        <option data-price="min" value="0/<?php echo e($start); ?>">Under ₹ <?php echo e(isset($start) ? number_format($start): ''); ?> </option>
                      
                      <?php for($i=$start; $i<$end; $i): ?>
                            <?php
                            
                              $lastNum = $i + $step;
                              $firstNum = $i;
                              $minimumPrice = str_split($firstNum);
                              $MaximumPrice = str_split($lastNum);
                              if($i < $step) {
                                $minVal = $minimumPrice[0];
                              } elseif($i==$step) {
                                $minVal = $minimumPrice[0].''.$MaximumPrice[1];
                              }else {
                                $minVal = $minimumPrice[0].''.$MaximumPrice[1];
                              }     
                            ?>
                            
                          <option value="<?php echo e($firstNum); ?>/<?php echo e($lastNum); ?>"> Between <?php echo e($minVal); ?>K -  <?php echo e($MaximumPrice[0].$MaximumPrice[1]); ?>K </option>
                        <?php 
                        $i=$i+$step
                        ?>
                      <?php endfor; ?>
                    
                   
                    
                    <option data-price="above" value="<?php echo e($end); ?>">Above ₹ <?php echo e(isset($end) ? number_format($end) :''); ?> </option>
                   
                    
                    
                   
                 
              </select>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          
          <?php if(isset($category->category->name) &&  $category->category->name == 'Gold' || \Request::segment('2') == 'gold'): ?>
              <div class="box">
                  <select class="nice-select" data-slug="<?php echo e(Request::segment(2)); ?>" <?php if(Request::segment(3)!='' ) { ?> data-attr = "<?php echo e(Request::segment(3)); ?>" <?php } ?> id="typeFilter">
                    <option data-type="all" value="all">Type</option>
                    
                    <option data-type="p"  value="p" >Plain (<?php echo e(isset($plain) && $plain !='0' ? $plain : '0'); ?>) </option>
                    <option data-type="s"  value="s">Stone ( <?php echo e(isset($stone) && $stone !='0' ? $stone : '0'); ?> ) </option>
                    <option data-type="b"    value="b">Beads ( <?php echo e(isset($beads) && $beads !='0' ? $beads : '0'); ?>)</option>
                    
                     
                    
                </select>
                </div>
              <?php endif; ?>
          <?php if($category->show_filter_metal == '1'): ?>
              <?php if($category->slug=='gift-item'): ?>
                <div class="box">
                    <select class="nice-select" data-slug="<?php echo e(Request::segment(2)); ?>" id="metalFilter">
                      <option data-metal="all" value="all">METAL</option>
                    </select>
                  </div>
              <?php else: ?>
              
                <div class="box">
                  <select class="nice-select" data-slug="<?php echo e(Request::segment(2)); ?>" id="metalFilter">
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
            <?php endif; ?>
          <?php endif; ?>
          <?php if($category->show_filter_purity == '1'): ?>
            <div class="box">
              <select class="nice-select" data-slug="<?php echo e(Request::segment(2)); ?>" id="purityFilter">
                <option>PURITY</option>
                <option value="14">14K ( <?php echo e(isset($purity_fourteen_carat) ? count($purity_fourteen_carat) : '0'); ?> )  </option>
                <option value="18">18K ( <?php echo e(isset($purity_eighteen_carat) ? count($purity_eighteen_carat) : '0'); ?> )  </option>
                <option value="22">22K ( <?php echo e(isset($purity_twenty_two_carat) ? count($purity_twenty_two_carat) : '0'); ?> )  </option>
                <option value="24">24K ( <?php echo e(isset($purity_twenty_four_carat) ? count($purity_twenty_four_carat) : '0'); ?> )  </option>
              
            </select>
            </div>
          <?php endif; ?>
          
          <?php if($category->show_filter_gender == '1' && \Request::segment('3')!='kids'): ?>
            <div class="box">
              <select class="nice-select" data-slug="<?php echo e(Request::segment(2)); ?>" id="genderFilter">
              <option value ="all">GENDER</option>
                <option value="m">Men ( <?php echo e(isset($maleProduct) ? $maleProduct : '0'); ?> ) </option>
                <option value="f">Women  ( <?php echo e(isset($femaleProduct) ? $femaleProduct : '0'); ?> ) </option>
                
                <option value="u">Unisex ( <?php echo e(isset($uniSexProduct) ? $uniSexProduct : '0'); ?> ) </option>
                 
            </select>
            </div>
          <?php endif; ?>
          <?php if($category->show_filter_offers == '1'): ?>
            <div class="box">
              <select class="nice-select" data-slug="<?php echo e(Request::segment(2)); ?>" id="offerFilter">
                <option value="<?php echo e(isset($offerProduct) ? $offerProduct : 0); ?>">OFFERS (<?php echo e(isset($offerProduct) ? $offerProduct : 0); ?>)</option>
              </select>
            </div>
          <?php endif; ?>
           </div>
        </div>
        <?php if($category->show_filter_short_by == '1'): ?>
        <div class="right" id="SortDiv">
          <h4>Sort by</h4>
          <div class="block">
            <div class="box">
              <select class="nice-select" data-slug="<?php echo e(Request::segment(2)); ?>" id="priceSorting">
              <option value="" selected disabled hidden >Select here</option>
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
        <?php endif; ?>
        
      </div>

    <?php endif; ?>  
      <!-- Image loader -->
<div id='loader' style='display: none;text-align:center'>
  <img src="<?php echo e(URL::asset('img/reload.gif')); ?>">
</div>
<!-- Image loader -->
<input type="hidden" id="page" value="1" />
<input type="hidden" id="max_page" value="<?php echo isset($max_page) ? ceil($max_page) : '' ?>" /> <div id="ajaxcontentProduct"></div> <div class="row" id="contentProduct"> <?php if(count($products) > 0): ?> <?php echo $__env->make('front.ajaxcategorypagination', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?> <?php else: ?> <div class="col-lg-12 col-md-12 col-sm-12"> <h1 class="mb-2 text-center">Oops!</h1> <h2 class="mb-5 text-center"> We could not find what you are looking for!</h2> <a href="<?php echo e(url('/')); ?>" class="btn btn-primary ShoppingBtn ">CONTINUE SHOPPING</a> </div> <?php endif; ?> </div> </div> </div> <div class="ajax-load text-center" style="display:none"> <p><img src="<?php echo e(asset('img/loader.gif')); ?>"></p> </div> <div class="toast" role="alert" aria-live="assertive" aria-atomic="true"> <div class="toast-body"> </div> </div> <?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.befront', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>