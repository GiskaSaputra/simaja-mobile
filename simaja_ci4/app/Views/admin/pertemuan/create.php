<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Tambah Pertemuan Baru</h1>
    <a href="<?= base_url('admin/pertemuan/' . $materi['id']); ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                
                <form action="<?= base_url('admin/pertemuan/store'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="materi_id" value="<?= $materi['id']; ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Pertemuan</label>
                        <input type="text" name="judul_pertemuan" class="form-control rounded-3" placeholder="Contoh: Pertemuan 1: Pengenalan UI" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Urutan</label>
                        <input type="number" name="urutan" class="form-control rounded-3" placeholder="Contoh: 1" required>
                        <div class="form-text">Urutan pertemuan dalam materi ini.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Isi Materi (Link)</label>
                        <input type="text" name="isi_materi" class="form-control rounded-3" placeholder="https://youtu.be/... atau uploads/file.pdf" required>
                        <div class="form-text">
                            Masukkan <strong>Link YouTube</strong> untuk video, atau <strong>path file PDF</strong> (misal: uploads/modul1.pdf).
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">
                            <i class="bi bi-save me-2"></i> Simpan Pertemuan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>