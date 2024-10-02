
<?php $__env->startSection('title', 'Error 404'); ?>
<?php $__env->startSection('content'); ?>
<div class='container'>
    <div class='row'>
        <div class='col fw-bold display-1 text-center d-flex flex-column justify-content-center ubuntu-regular'>
            <p style='color: #465a65;'>PAGE</p>
            <p style='color: #fdc727;'>404</p>
            <a href="javascript:history.back()" class='text-base'><i class="fa-solid fa-arrow-left"></i> 回上一頁</a>
        </div>
        <div class='col vh-100' style='background-image: url("/images/error-404.svg"); '></div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/Front/error404.blade.php ENDPATH**/ ?>