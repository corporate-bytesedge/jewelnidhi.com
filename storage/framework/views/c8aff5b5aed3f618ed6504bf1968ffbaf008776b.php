<div class="panel panel-default"> <div class="panel-heading"> <?php echo app('translator')->getFromJson('List Catalog'); ?> </div> <div class="panel-body"> <form id="delete-form" action="bulk_catalog_delete" method="post" class="form-inline"> <div class="row"> <?php echo e(csrf_field()); ?> <div class="col-md-4"> <div class="text-muted"> <label for="checkboxArray"><?php echo app('translator')->getFromJson('Bulk Options'); ?> <i class="fa fa-cog" aria-hidden="true"></i></label> </div> <input type="hidden" name="_method" value="DELETE"> <div class="form-group"> <select name="checkboxArray" class="form-control"> <option value=""><?php echo app('translator')->getFromJson('Delete'); ?></option> </select> </div> <div class="form-group"> <input id="delete_all" name="" class="btn fa btn-warning" value="&#xf1d8;" onclick=" if(confirm('<?php echo app('translator')->getFromJson('Are you sure you want to delete selected catalog?'); ?>')) { $('#delete_all').attr('name', 'delete_all'); event.preventDefault(); $('#delete-form').submit(); } else { event.preventDefault(); } " > </div> </div> <div class="advanced-search col-md-<?php echo e(Auth::user()->can('delete', App\Testimonial::class) ? '8' : '8 col-md-offset-4'); ?>"> <div class="row"> <div class="col-md-7"> <input class="form-control" type="text" id="search-by-column" placeholder="<?php echo app('translator')->getFromJson('Search by Column'); ?>"> </div> </div> </div> </div> <div class="table-responsive"> <?php if(Session::has('message')): ?> <p class="alert <?php echo e(Session::get('alert-class', 'alert-info')); ?>"><?php echo e(Session::get('message')); ?></p> <?php endif; ?> <table class="display table table-striped table-bordered table-hover" id="certificate-table"> <thead> <tr> <th><input type="checkbox" id="options"></th> <th><?php echo app('translator')->getFromJson('SKU'); ?></th> <th><?php echo app('translator')->getFromJson('Image'); ?></th> <th><?php echo app('translator')->getFromJson('Action'); ?></th> </tr> </thead> <tbody> <?php if($catalogs): ?> <?php $__currentLoopData = $catalogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <tr> <td><input class="checkboxes" type="checkbox" name="checkboxArray[]" value="<?php echo e($value->id); ?>"></td> <td><?php echo e($value->sku); ?></td> <td> <?php 
                                    $file = public_path().'/storage/catalog/'.basename($value->image);
                                    
                                ?> <?php if(file_exists($file)): ?> <img src="<?php echo e(asset('storage/catalog/'.$value->image)); ?>" height="50" width="50" class="center" > <?php else: ?> <img src="<?php echo e(asset('img/noimage.png')); ?>" height="50px" alt="" /> <?php endif; ?> </td> <td> <a class="btn btn-info btn-sm" href="<?php echo e(route('manage.settings.edit_catalog', $value->id)); ?>"> <i class="fa fa-pencil"></i> </a> <a class="btn btn-danger btn-sm" href="<?php echo e(route('manage.settings.delete_catalog', $value->id)); ?>" onclick="return confirm('Are you sure')"> <i class="fa fa-trash-o"></i> </a> </td> </tr> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?> </tbody> </table> </div> </form> </div> </div>