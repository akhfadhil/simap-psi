<?php $__env->startSection('icon'); ?>
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16.5 10.5V7.75a4.5 4.5 0 0 0-9 0v2.75M6.75 10.5h10.5A1.75 1.75 0 0 1 19 12.25v6A1.75 1.75 0 0 1 17.25 20H6.75A1.75 1.75 0 0 1 5 18.25v-6a1.75 1.75 0 0 1 1.75-1.75Z"/>
    </svg>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('errors.layout', [
    'code' => '403',
    'title' => 'Akses Ditolak',
    'message' => 'Akun ini tidak memiliki izin untuk membuka halaman tersebut.',
    'tone' => 'red',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\simap-partai-template\resources\views/errors/403.blade.php ENDPATH**/ ?>