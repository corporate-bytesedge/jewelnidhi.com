<?php if(config('settings.facebook_app_id') != ""): ?>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : "<?php echo e(config('settings.facebook_app_id')); ?>",
                xfbml      : true,
                version    : 'v2.5'
            });
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        $(document).ready(function () {
            $(".ui.facebook-share.button").click(function() {
                FB.ui({
                    method: 'share',
                    href: "<?php echo e(url()->current()); ?>"
                }, function(response){});
            })
        });

    </script>
<?php endif; ?>
<script>
    window.twttr = (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0],
            t = window.twttr || {};
        if (d.getElementById(id)) return t;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://platform.twitter.com/widgets.js";
        fjs.parentNode.insertBefore(js, fjs);

        t._e = [];
        t.ready = function(f) {
            t._e.push(f);
        };

        return t;
    }(document, "script", "twitter-wjs"));
</script>
<?php if(config('livechat.tawk_widget_code') != ""): ?><?php echo $__env->make('partials.front.tawk-widget', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?><?php endif; ?>