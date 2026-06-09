<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Edit Soal</h1>
    <a href="<?= base_url('admin/soal/' . $pertemuan['id']); ?>" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                
                <form action="<?= base_url('admin/soal/update/' . $soal['id']); ?>" method="post">
                    <?= csrf_field(); ?>

                    <!-- Input Pertanyaan -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Pertanyaan</label>
                        <textarea name="pertanyaan" class="form-control rounded-3" rows="3" required><?= esc($soal['pertanyaan']); ?></textarea>
                    </div>

                    <!-- Input Pilihan Jawaban -->
                    <label class="form-label fw-bold mb-3">Pilihan Jawaban</label>
                    
                    <?php foreach($jawaban as $index => $j): ?>
                        <div class="input-group mb-3">
                            <div class="input-group-text bg-light">
                                <input class="form-check-input mt-0" type="radio" name="kunci" value="<?= $index; ?>" 
                                       <?= $j['is_correct'] ? 'checked' : ''; ?> 
                                       required aria-label="Kunci Jawaban">
                            </div>
                            <input type="text" name="opsi[]" class="form-control" value="<?= esc($j['teks_jawaban']); ?>" required>
                        </div>
                    <?php endforeach; ?>

                    <div class="alert alert-warning py-2 small">
                        <i class="bi bi-info-circle me-1"></i> Jangan lupa pilih radio button (lingkaran) di kiri untuk menentukan kunci jawaban yang benar.
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-warning text-dark rounded-pill py-2 fw-bold">
                            <i class="bi bi-pencil-square me-2"></i> Update Soal
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>