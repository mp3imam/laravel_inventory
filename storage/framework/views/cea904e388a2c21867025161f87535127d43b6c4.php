<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" data-topbar="light" data-sidebar-image="none">
    <head>
        <meta charset="utf-8" />
        <title><?php echo e(config('app.name')); ?> | <?php echo $__env->yieldContent('title'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="description" content="<?php echo e(config('app.name')); ?>" />
        <meta name="author" content="<?php echo e(config('app.name')); ?>" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo e(URL::asset('assets/images/logo/favicon-kejaksaan.png')); ?>">
        <?php echo $__env->make('layouts.head-css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </head>

    <?php echo $__env->yieldContent('body'); ?>

        <?php echo $__env->yieldContent('content'); ?>

        <?php echo $__env->make('layouts.vendor-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </body>
</html>
<?php /**PATH D:\laragon\www\c2_satker\resources\views/layouts/master-without-nav.blade.php ENDPATH**/ ?>