<script src="<?php echo e(asset('js/jquery.loadBar.min.js')); ?>"></script>
<script>
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // main color
    loadBar.mainColor = 'red';
    // strip color
    loadBar.stripColor = 'black';
    // animation speed
    loadBar.barSpeed = 5;
    // bar height
    loadBar.barHeight = 5;

    var cartMessage = $('.cart-message');
    var cartCount = $('.cart-count');

    $(document).on('click', '.addCartWishlist', function(e) {

        var product_id = $(this).data('product_id');
         
        var data = $("#wishlistcart-form"+product_id).serialize();
        var url = $("#wishlistcart-form"+product_id).attr('action');
        
         
         
        console.log(url);
        $.post(url, data, function(receivedData) {
            console.log(receivedData);
            var selectorCartCount = '.cart-count';
            var selectorproductAdded = '.product-added';
            var receivedData = $("<div>" + receivedData + "</div>");
           
            var count = receivedData.find(selectorCartCount).html();
            var productAdded = receivedData.find(selectorproductAdded).html();
            receivedData.find(selectorCartCount).remove();
            receivedData.find(selectorproductAdded).remove();
            receivedData = receivedData.html();
            $("#CartMsg").html(receivedData);
            $("#cartCount").html(count);
            
            if(!receivedData.error) {
                
                jQuery("#add_to_cart_"+product_id).addClass('disableBtn');;
                 
                cartMessage.html(receivedData);
                jQuery('.dropdown-wrapper-cart').load(location.href + " " + '.dropdown-wrapper-cart', function() {});
                if (jQuery('.dropdown-cart-anchor')){
                    jQuery('.dropdown-cart-anchor').click();
                }
                // cartCount.html(count);
                // if(productAdded) {
                //     window.location.href = APP_URL + '/cart';
                // }
            } else {
                cartMessage.html('<h1><?php echo app('translator')->getFromJson('Something went wrong!'); ?></h1>');
            }
            loadBar.trigger('hide');
             
        });
    });

    
    $(document).on('click', '#add_to_cart', function(e) {
        var product_id = $(this).data('product_id');
        
        var data = $("#frontcart-form"+product_id).serialize();
        var url = $("#frontcart-form"+product_id).attr('action');
        
         
         
        console.log(data);
        $.post(url, data, function(receivedData) {
           
            var selectorCartCount = '.cart-count';
            var selectorproductAdded = '.product-added';
            var receivedData = $("<div>" + receivedData + "</div>");
           
            var count = receivedData.find(selectorCartCount).html();
            var productAdded = receivedData.find(selectorproductAdded).html();
            receivedData.find(selectorCartCount).remove();
            receivedData.find(selectorproductAdded).remove();
            receivedData = receivedData.html();
            $("#CartMsg").html(receivedData);
            $("#cartCount").html(count);
            
            if(!receivedData.error) {
                cartMessage.html(receivedData);
                jQuery('.dropdown-wrapper-cart').load(location.href + " " + '.dropdown-wrapper-cart', function() {});
                if (jQuery('.dropdown-cart-anchor')){
                    jQuery('.dropdown-cart-anchor').click();
                }
                // cartCount.html(count);
                // if(productAdded) {
                //     window.location.href = APP_URL + '/cart';
                // }
            } else {
                cartMessage.html('<h1><?php echo app('translator')->getFromJson('Something went wrong!'); ?></h1>');
            }
            loadBar.trigger('hide');
             
        });
    });

    $(document).on('click', '#add_cart', function(e) {
        
        var groupVal = $('.groupVal li.selected').data('value');;
         
     
        if(groupVal!='') {
            
            e.preventDefault();
            var data = $("#cart-form").serialize();
            var url = $("#cart-form").attr('action');
            var submitBtn = $("#cart-form").find("input[type=submit]:focus");
            submitBtn.val('Adding to Cart');
            submitBtn.closest('div').addClass('loader');
            //loadBar.trigger('show');
            
            $.post(url, data, function(receivedData) {
                 $("#cart-form").submit();
                // alert(site_url); 
                var selectorCartCount = '.cart-count';
                // var selectorproductAdded = '.product-added';
                // var receivedData = $("<div>" + receivedData + "</div>");
                //  var count = receivedData.find(selectorCartCount).html();
                // var productAdded = receivedData.find(selectorproductAdded).html();
                // receivedData.find(selectorCartCount).remove();
                // receivedData.find(selectorproductAdded).remove();
                // receivedData = receivedData.html();
                // if(!receivedData.error) {
                //     cartMessage.html(receivedData);
                //     jQuery('.dropdown-wrapper-cart').load(location.href + " " + '.dropdown-wrapper-cart', function() {});
                //     if (jQuery('.dropdown-cart-anchor')){
                //         jQuery('.dropdown-cart-anchor').click();
                //     }
                //     // cartCount.html(count);
                //     // if(productAdded) {
                //     //     window.location.href = APP_URL + '/cart';
                //     // }
                // } else {
                //     cartMessage.html('<h1><?php echo app('translator')->getFromJson('Something went wrong!'); ?></h1>');
                // }
                loadBar.trigger('hide');
                submitBtn.val('<?php echo e(__('Add to Cart')); ?>');
                submitBtn.closest('div').removeClass('loader');
            });
        } else {
            confirm('Please select size');
            return false;
        }
        
        
        
        
        
         
        
         
        
    });

</script>