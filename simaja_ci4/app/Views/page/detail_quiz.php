<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<!-- HEADER -->
<div class="w-100 py-5 text-center text-white" style="background: linear-gradient(135deg, #004d40, #009688);">
    <h1 class="fw-bold display-5">Quiz Time!</h1>
    <p class="mt-2">Jawablah pertanyaan di bawah ini dengan teliti.</p>
</div>

<!-- KONTEN UTAMA -->
<div class="container py-5" style="max-width: 800px;">
    
    <!-- Info Pertemuan -->
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h4 class="fw-bold mb-0 text-success" style="color: #004d40 !important;"><?= esc($pertemuan['judul_pertemuan']); ?></h4>
            <small class="text-muted">Pastikan semua soal terjawab sebelum mengirim.</small>
        </div>
        <a href="<?= base_url('quiz'); ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3">
            <i class="bi bi-x-lg me-1"></i> Batal
        </a>
    </div>

    <!-- FORM QUIZ -->
    <form action="<?= base_url('quiz/submit'); ?>" method="post">
        <?= csrf_field(); ?>
        <input type="hidden" name="pertemuan_id" value="<?= $pertemuan['id']; ?>">

        <!-- Loop Soal dari Database -->
        <?php if(empty($soal_list)): ?>
            <div class="alert alert-info text-center border-0 shadow-sm rounded-3">
                <i class="bi bi-info-circle me-2"></i> Belum ada soal yang tersedia untuk pertemuan ini.
            </div>
        <?php else: ?>

            <?php foreach($soal_list as $index => $soal): ?>
                <div class="card mb-4 border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <!-- Pertanyaan -->
                        <h5 class="fw-bold mb-4" style="color: #333; line-height: 1.6;">
                            <span class="badge me-2 rounded-pill" style="background-color: #004d40;">No. <?= $index + 1; ?></span> 
                            <?= esc($soal['pertanyaan']); ?>
                        </h5>

                        <!-- Pilihan Jawaban (Radio Button) -->
                        <div class="d-grid gap-3">
                            <?php foreach($soal['opsi'] as $opsi): ?>
                                <!-- Menggunakan class btn-outline-theme buatan sendiri agar sesuai warna -->
                                <label class="btn btn-outline-theme text-start py-3 px-4 rounded-3 position-relative border-1 option-label">
                                    <input type="radio" name="jawaban[<?= $soal['id']; ?>]" value="<?= $opsi['id']; ?>" class="me-3 form-check-input" required>
                                    <span class="fw-medium"><?= esc($opsi['teks_jawaban']); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Tombol Submit -->
            <div class="d-grid mt-5">
                <button type="submit" class="btn text-white fw-bold py-3 rounded-pill shadow-lg hover-scale" 
                        style="background: linear-gradient(90deg, #004d40, #009688); font-size: 1.1rem; border: none;">
                    <i class="bi bi-send-check-fill me-2"></i> Kirim Jawaban
                </button>
            </div>

        <?php endif; ?>
    </form>

</div>

<!-- CSS Tambahan untuk Custom Warna -->
<style>
    .btn-outline-theme {
        color: #004d40;
        border-color: #004d40;
        background-color: transparent;
        transition: all 0.2s ease-in-out;
    }

    .btn-outline-theme:hover {
        background-color: #e0f2f1; 
        color: #004d40;
        border-color: #004d40;
    }

    /* Efek saat radio button dipilih */
    .option-label:has(input:checked) {
        background-color: #004d40 !important;
        color: white !important;
        border-color: #004d40 !important;
        transform: scale(1.01);
        box-shadow: 0 4px 10px rgba(0, 77, 64, 0.2);
    }

    /* Ubah warna bulatan radio button native agar sesuai tema */
    .form-check-input:checked {
        background-color: #009688;
        border-color: #009688;
    }

    /* Efek hover tombol submit */
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: translateY(-2px); }
</style>

<?= $this->endSection(); ?>