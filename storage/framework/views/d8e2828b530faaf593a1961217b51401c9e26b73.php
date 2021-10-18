<script type="text/javascript">
	var APP_URL = <?php echo json_encode(url('/')); ?>

</script>
<?php if(config('settings.enable_google_translation')): ?>
    <script type="text/javascript" src="<?php echo e(asset('js/google-translate.js')); ?>"></script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
<?php endif; ?>

