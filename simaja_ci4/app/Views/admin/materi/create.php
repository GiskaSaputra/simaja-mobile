<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Tambah Materi Baru</h1>
    <a href="<?= base_url('admin/materi'); ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                
                <form action="<?= base_url('admin/materi/store'); ?>" method="post">
                    <?= csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Materi</label>
                        <input type="text" name="judul" class="form-control rounded-3" placeholder="Contoh: UI/UX Design" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Singkat</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="3" placeholder="Jelaskan tentang materi ini..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Total Pertemuan</label>
                        <input type="number" name="total_pertemuan" class="form-control rounded-3" placeholder="Contoh: 6" required>
                        <div class="form-text">Jumlah sesi pertemuan yang direncanakan.</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success rounded-pill py-2 fw-bold">
                            <i class="bi bi-save me-2"></i> Simpan Materi
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>