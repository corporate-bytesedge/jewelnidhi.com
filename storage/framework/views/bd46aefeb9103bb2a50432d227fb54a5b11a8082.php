<script src="<?php echo e(asset('js/intlTelInput.min.js')); ?>"></script>
<script>
	telInput = $("#phone-number");
    $(document).ready(function() {
        telInput.intlTelInput({
            allowExtensions: true,
            nationalMode: true,
            hiddenInput: "phone",
            initialCountry: '<?php echo e(config('googlemap.admin_country_code') ? config('googlemap.admin_country_code') : 'IN'); ?>',
		  	separateDialCode: true,
            preventInvalidNumbers: true,
            utilsScript: "<?php echo e(asset('js/utils.js')); ?>"
        });
    });
</script>