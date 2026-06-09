<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Tambah Jadwal Baru</h1>
    <a href="<?= base_url('admin/jadwal'); ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                
                <form action="<?= base_url('admin/jadwal/store'); ?>" method="post">
                    <?= csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Kegiatan</label>
                        <input type="text" name="judul" class="form-control rounded-3" placeholder="Contoh: Workshop UI/UX Design" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Singkat</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="3" placeholder="Jelaskan detail singkat kegiatan..."></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Waktu Selesai</label>
                            <input type="time" name="waktu_selesai" class="form-control rounded-3" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Kuota Peserta</label>
                        <input type="number" name="kuota" class="form-control rounded-3" placeholder="Contoh: 50" required>
                        <div class="form-text">Jumlah maksimal peserta yang dapat mendaftar.</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">
                            <i class="bi bi-save me-2"></i> Simpan Jadwal
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>