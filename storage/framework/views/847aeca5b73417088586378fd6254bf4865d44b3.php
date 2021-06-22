

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->getFromJson('Resend Activation Email'); ?> - <?php echo e(config('app.name')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <style>
        #myFooter {
            bottom: -100px;
            position: relative
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="text-center">
        <div class="col-md-12">
            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            <br/><br/>
            <h2> <?php echo e(config('app.name')); ?> : <?php echo app('translator')->getFromJson('Resend Activation Email'); ?></h2>
            <h5>( <?php echo app('translator')->getFromJson('Resend Activation Email'); ?> )</h5>
            <br/>
        </div>
    </div>
    <div>
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><?php echo app('translator')->getFromJson('Enter your email to resend activation email'); ?></strong>
                </div>
                <div class="panel-body">
                    <form onsubmit="login();" method="POST" action="<?php echo e(route('auth.activate.resend')); ?>">
                        <?php echo e(csrf_field()); ?>

                        <br/>
                        <div class="form-group input-group<?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
                            <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                            <input required type="email" name="email" class="form-control" placeholder="<?php echo app('translator')->getFromJson('Enter your email'); ?>"/>
                        </div>
                        <?php if($errors->has('email')): ?>
                            <span class="help-block">
                                <strong class="text-danger">
                                    <?php echo e($errors->first('email')); ?>

                                </strong>
                            </span>
                        <?php endif; ?>
                        <button id="resend-button" type="submit" class="btn btn-primary btn-block"><?php echo app('translator')->getFromJson('Resend'); ?></button>
                        <hr />
                        <?php echo app('translator')->getFromJson('Not Register ?'); ?> <a href="<?php echo e(url('/register')); ?>" ><?php echo app('translator')->getFromJson('Click here'); ?> </a>
                    </form>
                    <script>
                        function login() {
                            $('#resend-button')
                                .text('<?php echo e(__('Please Wait...')); ?>')
                                .prop('disabled', true);
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>