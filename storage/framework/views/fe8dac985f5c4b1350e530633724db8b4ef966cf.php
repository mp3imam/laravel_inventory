<?php echo $__env->yieldContent('css'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<!-- Layout config Js -->
<script src="<?php echo e(URL::asset('assets/js/layout.js')); ?>"></script>
<!-- Bootstrap Css -->
<link href="<?php echo e(URL::asset('assets/css/bootstrap.min.css')); ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="<?php echo e(URL::asset('assets/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="<?php echo e(URL::asset('assets/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('assets/css/custom.css')); ?>" rel="stylesheet" type="text/css" />

<!-- custom Css-->
<link href="<?php echo e(URL::asset('assets/css/custom.min.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />
<!--datatable css-->
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<!--datatable responsive css-->
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
    type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('assets/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"
    type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
<link rel="stylesheet" href="<?php echo e(URL::asset('assets/libs/@simonwep/@simonwep.min.css')); ?>" /> <!-- 'classic' theme -->
<link href="<?php echo e(URL::asset('assets/css/custom.min.css')); ?>" rel="stylesheet" type="text/css" />
<script src="https://themesbrand.com/velzon/html/default/assets/js/pages/form-input-spin.init.js"></script>
<style>
    .select2-selection__choice__display {
        color: black !important;
    }

    .dtfc-fixed-right {
        background-color: rgb(214, 210, 210) !important;
    }
</style>


<?php /**PATH D:\laragon\www\c2_satker\resources\views/layouts/head-css.blade.php ENDPATH**/ ?>