<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Tambah Soal Baru</h1>
    <a href="<?= base_url('admin/soal/' . $pertemuan['id']); ?>" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                
                <form action="<?= base_url('admin/soal/store'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="pertemuan_id" value="<?= $pertemuan['id']; ?>">

                    <!-- Input Pertanyaan -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Pertanyaan</label>
                        <textarea name="pertanyaan" class="form-control rounded-3" rows="3" required placeholder="Tulis pertanyaan di sini..."></textarea>
                    </div>

                    <!-- Input Pilihan Jawaban -->
                    <label class="form-label fw-bold mb-3">Pilihan Jawaban (Pilih salah satu sebagai kunci)</label>
                    
                    <?php for($i=0; $i<4; $i++): ?>
                        <div class="input-group mb-3">
                            <div class="input-group-text bg-light">
                                <input class="form-check-input mt-0" type="radio" name="kunci" value="<?= $i; ?>" required aria-label="Kunci Jawaban">
                            </div>
                            <input type="text" name="opsi[]" class="form-control" placeholder="Pilihan Jawaban <?= $i+1; ?>" required>
                        </div>
                    <?php endfor; ?>

                    <div class="alert alert-info py-2 small">
                        <i class="bi bi-info-circle me-1"></i> Klik lingkaran (radio button) di sebelah kiri untuk menandai jawaban yang benar.
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success rounded-pill py-2 fw-bold">Simpan Soal</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
