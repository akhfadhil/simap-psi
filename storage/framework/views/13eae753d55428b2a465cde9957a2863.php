<?php $__env->startSection('title', 'Isi Rekap ' . \App\Models\RekapHeader::JENIS_LABELS[$jenis]); ?>

<?php $__env->startSection('content'); ?>
<?php
    $isAdminRekapEdit = Auth::user()->role === 'admin_partai';
    $isCoordinatorRekapEdit = in_array(Auth::user()->role, ['kordes', 'korcam'], true);
    $backUrl = $isAdminRekapEdit
        ? (session('admin_rekap_return_url') ?: route('admin.rekap.show', $jenis))
        : route('rekap.index');
    $canEditRekap = in_array(Auth::user()->role, ['saksi_tps', 'kordes', 'korcam'], true) || $isAdminRekapEdit;
    $isFinal = $rekap && $rekap->status === 'final';
    $readOnly = !$canEditRekap || ($isFinal && !$isAdminRekapEdit);
    $statusLabels = [
        'draft' => 'Draft',
        'perlu_dicek' => 'Perlu Dicek',
        'final' => 'Final',
    ];
?>

<div class="mb-6">
    <a href="<?php echo e($backUrl); ?>"
       class="inline-flex items-center gap-2 text-xs dark:text-gray-500 text-gray-400 hover:text-[var(--color-brand)] transition font-medium mb-4">
        &larr; Kembali
    </a>
    <p class="text-[10px] tracking-[3px] dark:text-gray-500 text-gray-400 uppercase mb-2 font-semibold">
        // SAKSI TPS - <?php echo e($tps->nama); ?> / <?php echo e($tps->desa->nama); ?>

    </p>

    <div class="flex items-center justify-between flex-wrap gap-3">
        <h1 class="font-display text-4xl tracking-[2px] text-[var(--color-brand)]">
            <?php echo e(strtoupper(\App\Models\RekapHeader::JENIS_LABELS[$jenis])); ?>

        </h1>
        <div class="flex items-center gap-2">
            <?php if($rekap): ?>
            <?php
                $statusClass = match($rekap->status) {
                    'final' => 'bg-teal-500/20 text-teal-400 border-teal-500/40',
                    'perlu_dicek' => 'bg-red-500/20 text-red-400 border-red-500/40',
                    default => 'bg-orange-400/20 text-orange-400 border-orange-400/40',
                };
            ?>
            <span class="px-4 py-1.5 rounded-lg text-xs font-semibold border <?php echo e($statusClass); ?>">
                <?php echo e($statusLabels[$rekap->status] ?? ucfirst($rekap->status)); ?>

            </span>
            <?php endif; ?>
            <?php if($rekap && $rekap->status === 'final'): ?>
            <span class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-teal-500/20 text-teal-400 border border-teal-500/40">
                Sudah Difinalisasi
            </span>
            <?php endif; ?>
            <?php if($rekap): ?>
            <a href="<?php echo e(route('rekap.export', $jenis)); ?>"
            class="inline-flex items-center gap-2 px-4 py-1.5 rounded-lg text-xs font-semibold bg-[var(--color-brand)] hover:bg-[var(--color-brand-dark)] text-white transition">
                Export Excel
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if(session('error')): ?>
<div class="bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 text-xs mb-6 rounded-lg font-medium">
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?>

<?php if($isAdminRekapEdit): ?>
<div class="dark:bg-red-950 bg-red-50 border dark:border-red-900 border-red-200 px-5 py-3 mb-6 rounded-lg">
    <p class="text-xs font-semibold text-red-500">Mode koreksi admin sementara. Perubahan langsung mengubah data suara TPS ini.</p>
</div>
<?php elseif($isCoordinatorRekapEdit): ?>
<div class="dark:bg-sky-950 bg-sky-50 border dark:border-sky-900 border-sky-200 px-5 py-3 mb-6 rounded-lg">
    <p class="text-xs font-semibold text-sky-500">Mode koreksi koordinator. Perubahan hanya berlaku untuk TPS dalam scope wilayah Anda.</p>
</div>
<?php elseif(!$canEditRekap): ?>
<div class="dark:bg-orange-950 bg-orange-50 border dark:border-orange-900 border-orange-200 px-5 py-3 mb-6 rounded-lg">
    <p class="text-xs font-semibold text-orange-500">Mode lihat saja. Rekapitulasi data hanya bisa diubah oleh Saksi TPS, Kordes, Korcam, atau Admin Partai sesuai scope.</p>
</div>
<?php endif; ?>

<form method="POST" action="<?php echo e(route('rekap.store', $jenis)); ?>" id="rekap-form">
<?php echo csrf_field(); ?>

<?php if($isAdminRekapEdit): ?>
<div class="dark:bg-gray-800 bg-white rounded-xl border dark:border-gray-700 border-gray-200 shadow-sm mb-4 overflow-hidden">
    <div class="px-6 py-4 border-b dark:border-gray-700 border-gray-200 dark:bg-gray-700/50 bg-gray-50">
        <p class="text-xs font-bold dark:text-gray-300 text-gray-700 uppercase tracking-wider">Status Internal Partai</p>
    </div>
    <div class="p-6 grid grid-cols-1 lg:grid-cols-[220px_1fr] gap-4">
        <div>
            <label for="status_internal" class="block text-xs font-semibold dark:text-gray-400 text-gray-600 uppercase tracking-wider mb-2">Status</label>
            <select id="status_internal" name="status_internal"
                    class="w-full dark:bg-gray-900 bg-gray-50 border dark:border-gray-700 border-gray-300 dark:text-gray-100 text-gray-800 px-4 py-3 text-sm rounded-lg focus:border-[var(--color-brand)] focus:ring-0 focus:outline-none">
                <?php $__currentLoopData = $statusLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusValue => $statusLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($statusValue); ?>" <?php echo e(old('status_internal', $rekap->status ?? 'draft') === $statusValue ? 'selected' : ''); ?>>
                        <?php echo e($statusLabel); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label for="catatan_internal" class="block text-xs font-semibold dark:text-gray-400 text-gray-600 uppercase tracking-wider mb-2">Catatan Internal</label>
            <textarea id="catatan_internal" name="catatan_internal" rows="3"
                      placeholder="Contoh: angka caleg nomor 2 perlu dikonfirmasi ulang ke saksi."
                      class="w-full dark:bg-gray-900 bg-gray-50 border dark:border-gray-700 border-gray-300 dark:text-gray-100 text-gray-800 px-4 py-3 text-sm rounded-lg focus:border-[var(--color-brand)] focus:ring-0 focus:outline-none"><?php echo e(old('catatan_internal', $rekap->catatan_internal ?? '')); ?></textarea>
        </div>
    </div>
</div>
<?php elseif($rekap?->catatan_internal): ?>
<div class="dark:bg-red-950 bg-red-50 border dark:border-red-900 border-red-200 px-5 py-3 mb-6 rounded-lg">
    <p class="text-xs font-semibold text-red-500 uppercase tracking-wider mb-1">Catatan Internal</p>
    <p class="text-sm dark:text-red-100 text-red-700"><?php echo e($rekap->catatan_internal); ?></p>
</div>
<?php endif; ?>

<div class="dark:bg-gray-800 bg-white rounded-xl border dark:border-gray-700 border-gray-200 shadow-sm mb-4 overflow-hidden">
    <div class="px-6 py-4 border-b dark:border-gray-700 border-gray-200 dark:bg-gray-700/50 bg-gray-50">
        <p class="text-xs font-bold dark:text-gray-300 text-gray-700 uppercase tracking-wider">Perolehan Suara <?php echo e(config('party.name')); ?></p>
    </div>
    <div class="p-6">
        <?php if($data['partais']->isEmpty()): ?>
        <div class="text-center py-8 dark:text-gray-500 text-gray-400 text-sm">
            Belum ada data <?php echo e(config('party.name')); ?>. Minta admin untuk menginput terlebih dahulu.
        </div>
        <?php else: ?>
        <div class="space-y-4">
        <?php $__currentLoopData = $data['partais']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="border dark:border-gray-700 border-gray-200 rounded-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 dark:bg-gray-700/50 bg-gray-50 border-b dark:border-gray-700 border-gray-200 gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-7 h-7 rounded-lg bg-[var(--color-brand)] text-white text-xs font-bold flex items-center justify-center flex-shrink-0">
                        <?php echo e($partai->nomor_urut); ?>

                    </span>
                    <p class="text-sm font-semibold dark:text-gray-100 text-gray-800 truncate"><?php echo e($partai->nama_partai); ?></p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <label class="text-xs dark:text-gray-400 text-gray-500 font-medium">Suara Partai</label>
                    <input type="number" name="suara_partai[<?php echo e($partai->id); ?>]" min="0"
                           value="<?php echo e(old('suara_partai.'.$partai->id, $data['suara_partai'][$partai->id] ?? 0)); ?>"
                           <?php echo e($readOnly ? 'disabled' : ''); ?>

                           class="w-28 dark:bg-gray-900 bg-white border dark:border-gray-600 border-gray-300 dark:text-gray-100 text-gray-800 px-3 py-1.5 text-sm rounded-lg focus:border-[var(--color-brand)] focus:ring-0 focus:outline-none text-right <?php echo e($readOnly ? 'opacity-60 cursor-not-allowed' : ''); ?>">
                </div>
            </div>

            <?php $__currentLoopData = $partai->calegs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caleg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center justify-between px-5 py-3 border-b dark:border-gray-700 border-gray-100 last:border-0 gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="text-xs dark:text-gray-500 text-gray-400 w-5 text-center flex-shrink-0"><?php echo e($caleg->nomor_urut); ?></span>
                    <p class="text-sm dark:text-gray-300 text-gray-600 truncate"><?php echo e($caleg->nama_caleg); ?></p>
                </div>
                <input type="number" name="suara_caleg[<?php echo e($caleg->id); ?>]" min="0"
                       value="<?php echo e(old('suara_caleg.'.$caleg->id, $data['suara_caleg'][$caleg->id] ?? 0)); ?>"
                       <?php echo e($readOnly ? 'disabled' : ''); ?>

                       class="w-28 flex-shrink-0 dark:bg-gray-900 bg-gray-50 border dark:border-gray-700 border-gray-300 dark:text-gray-100 text-gray-800 px-3 py-1.5 text-sm rounded-lg focus:border-[var(--color-brand)] focus:ring-0 focus:outline-none text-right <?php echo e($readOnly ? 'opacity-60 cursor-not-allowed' : ''); ?>">
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="flex items-center justify-between px-5 py-3 border-t dark:border-gray-600 border-gray-300 dark:bg-gray-700/50 bg-gray-50">
                <p class="text-xs font-bold dark:text-gray-300 text-gray-700 uppercase tracking-wider">
                    Total suara <?php echo e($partai->nama_partai); ?>

                    <span class="text-[10px] font-normal dark:text-gray-500 text-gray-400 normal-case tracking-normal">(partai + seluruh caleg)</span>
                </p>
                <div class="w-28 dark:bg-gray-900 bg-white border dark:border-gray-600 border-gray-300 px-3 py-1.5 text-sm font-bold text-[var(--color-brand)] rounded-lg text-right partai-subtotal"
                     data-partai-id="<?php echo e($partai->id); ?>">
                    0
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="dark:bg-gray-800 bg-white rounded-xl border dark:border-gray-700 border-gray-200 shadow-sm mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b dark:border-gray-700 border-gray-200 dark:bg-gray-700/50 bg-gray-50">
        <p class="text-xs font-bold dark:text-gray-300 text-gray-700 uppercase tracking-wider">Total Suara <?php echo e(config('party.short_name')); ?></p>
    </div>
    <div class="p-6">
        <div class="flex items-center gap-4">
            <p class="flex-1 text-sm dark:text-gray-300 text-gray-700">
                Total suara <?php echo e(config('party.name')); ?> dan seluruh caleg <?php echo e(config('party.short_name')); ?>

                <span class="text-[10px] dark:text-gray-600 text-gray-400 font-normal">(otomatis dari input di atas)</span>
            </p>
            <div id="display-suara-partai"
                 class="w-36 dark:bg-gray-900 bg-gray-100 border dark:border-gray-700 border-gray-300 px-3 py-2 text-sm font-bold text-[var(--color-brand)] rounded-lg text-right">
                0
            </div>
        </div>
    </div>
</div>

<?php if($isAdminRekapEdit): ?>
<div class="flex gap-3">
    <button type="submit"
            class="flex-1 bg-[var(--color-brand)] hover:bg-[var(--color-brand-dark)] text-white font-semibold py-3 rounded-xl text-sm transition">
        Simpan Perubahan Admin
    </button>
</div>
<?php elseif($canEditRekap && !$isFinal): ?>
<div class="flex gap-3">
    <button type="submit"
            class="flex-1 bg-[var(--color-brand)] hover:bg-[var(--color-brand-dark)] text-white font-semibold py-3 rounded-xl text-sm transition">
        Simpan Draft
    </button>
    <button type="button" onclick="confirmFinalisasi()"
            class="flex-1 bg-teal-500 hover:bg-teal-600 text-white font-semibold py-3 rounded-xl text-sm transition">
        Simpan & Finalisasi
    </button>
</div>
<?php else: ?>
<div class="dark:bg-gray-800 bg-gray-50 rounded-xl border dark:border-gray-700 border-gray-200 p-4 text-center">
    <p class="text-sm dark:text-gray-400 text-gray-500">
        <?php echo e($isFinal ? 'Data sudah difinalisasi dan tidak bisa diubah.' : 'Mode lihat saja. Data hanya bisa diubah oleh Saksi TPS, Kordes, Korcam, atau Admin Partai sesuai scope.'); ?>

    </p>
</div>
<?php endif; ?>

</form>

<?php $__env->startPush('scripts'); ?>
<script>
function updateSuaraPartai() {
    let totalPartai = 0;

    document.querySelectorAll('.partai-subtotal').forEach(elSubtotal => {
        const partaiId = elSubtotal.dataset.partaiId;
        let subtotal = 0;

        const inpPartai = document.querySelector(`[name="suara_partai[${partaiId}]"]`);
        subtotal += parseInt(inpPartai?.value) || 0;

        const containerPartai = elSubtotal.closest('.border.dark\\:border-gray-700');
        if (containerPartai) {
            containerPartai.querySelectorAll('[name^="suara_caleg["]').forEach(inp => {
                subtotal += parseInt(inp.value) || 0;
            });
        }

        elSubtotal.textContent = subtotal.toLocaleString('id-ID');
        totalPartai += subtotal;
    });

    const elTotal = document.getElementById('display-suara-partai');
    if (elTotal) elTotal.textContent = totalPartai.toLocaleString('id-ID');
}

document.querySelectorAll('input[type="number"]').forEach(inp => {
    inp.addEventListener('input', updateSuaraPartai);
});

updateSuaraPartai();

async function confirmFinalisasi() {
    const ok = await confirmFinal();
    if (ok) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'finalisasi';
        input.value = '1';
        document.getElementById('rekap-form').appendChild(input);
        document.getElementById('rekap-form').submit();
    }
}

function confirmFinal() {
    return new Promise((resolve) => {
        const modal = document.getElementById('toast-final');
        const btnOk = document.getElementById('toast-final-ok');
        const btnCancel = document.getElementById('toast-final-cancel');
        const backdrop = document.getElementById('toast-final-backdrop');

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        function close(result) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            btnOk.removeEventListener('click', onOk);
            btnCancel.removeEventListener('click', onCancel);
            backdrop.removeEventListener('click', onCancel);
            resolve(result);
        }

        const onOk = () => close(true);
        const onCancel = () => close(false);

        btnOk.addEventListener('click', onOk);
        btnCancel.addEventListener('click', onCancel);
        backdrop.addEventListener('click', onCancel);
    });
}
</script>

<div id="toast-final" class="hidden fixed inset-0 z-[999] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" id="toast-final-backdrop"></div>
    <div class="relative dark:bg-gray-800 bg-white rounded-2xl shadow-2xl border dark:border-gray-700 border-gray-200 w-full max-w-sm p-6">
        <div class="flex items-start gap-4 mb-5">
            <div class="w-10 h-10 rounded-full bg-teal-500/15 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold dark:text-gray-100 text-gray-800 text-sm mb-1">Konfirmasi Finalisasi</p>
                <p class="text-xs dark:text-gray-400 text-gray-500 leading-relaxed">
                    Data rekap akan difinalisasi dan <span class="font-semibold text-teal-500">tidak bisa diubah</span> setelah ini.
                    Pastikan semua data sudah benar sebelum melanjutkan.
                </p>
            </div>
        </div>
        <div class="flex gap-2">
            <button id="toast-final-cancel"
                    class="flex-1 px-4 py-2.5 rounded-xl text-xs font-semibold border dark:border-gray-600 border-gray-300
                           dark:text-gray-400 text-gray-500 dark:hover:bg-gray-700 hover:bg-gray-100 transition">
                Batal
            </button>
            <button id="toast-final-ok"
                    class="flex-1 px-4 py-2.5 rounded-xl text-xs font-semibold bg-teal-500 hover:bg-teal-600 text-white transition">
                Ya, Finalisasi
            </button>
        </div>
    </div>
</div>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\simap-partai-template\resources\views/rekap/saksi/form.blade.php ENDPATH**/ ?>