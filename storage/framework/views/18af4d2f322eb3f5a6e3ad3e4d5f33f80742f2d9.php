

<?php $__env->startSection('style'); ?>
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
<?php $__env->stopSection(); ?>
 
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript">
  jQuery(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#priceFilter").on("change",function() {

      if($(this).val()!='') {
        $.ajax({
          type:"POST",
          url:"<?php echo e(route('front.price_filter')); ?>",
          data:{value:$(this).val()},
          beforeSend: function() {
            // Show image container
            $("#loader").show();
          },
          success:function(data) {
            alert(data.success);
          },
          complete:function(data) {
            // Hide image container
            $("#loader").hide();
          }
        });
      }

    });

  });
</script>
     
<?php $__env->stopSection(); ?>

 

<?php $__env->startSection('content'); ?>
 
<div class="inner-banner">
<?php 

if(isset($catalog->banner)) {
  $bannerpath = public_path().'/storage/category/banner/'.$catalog->banner;
} else {
  $bannerpath ='';
}

 
?>

	<img src="<?php echo e(URL::asset('img/catalogue.png')); ?>">
   </div>
  <div class="breadcrumb-sec">
    <div class="container">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
         
        <li class="breadcrumb-item">Catalog</li>
         
      
      </ol>
    </div>
  </div>
   
  <div class="category-page">
    <div class="container">
      <div class="category-head">
	  	<h2>Catalog Designs</h2>
	  </div>

       
      <!-- Image loader -->
		<div id='loader' style='display: none;'>
		<img src="<?php echo e(URL::asset('img/reload.gif')); ?>">
		</div>
<!-- Image loader -->

      	<div class="row">
		   
		  <?php if($catalogs->isNotEmpty()): ?>
		  	<?php $__currentLoopData = $catalogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="cat-item catalogItem">
						<figure class="product-thumb">
								<?php if($value->image): ?>
									<?php if($value->image): ?>
										 
										<img  src="<?php echo e(asset('public/storage/catalog/'.$value->image)); ?>" alt="<?php echo e($value->name); ?>">
										
										<?php else: ?>
										<img src="https://via.placeholder.com/150x150?text=No+Image" class="img-responsive product-feature-image" alt="<?php echo e($product->name); ?>" />
									<?php endif; ?>
								<?php endif; ?>
						</figure>
						<div class="product-caption">
							<div class="price-box">
								<span class="price-regular"> 18Kt Wt :- <?php echo e($value->weight); ?> <br/> D Ct Wt :- <?php echo e($value->diamond_weight); ?> </span>
								 
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		  <?php endif; ?>
      
		</div>
    <?php echo e($catalogs->appends(['sort' => 'votes'])->links()); ?>

     
      
    </div>
  </div>





    

<?php $__env->stopSection(); ?>
 
<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>