<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Edit Pertemuan</h1>
    <a href="<?= base_url('admin/pertemuan/' . $materi['id']); ?>" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                
                <form action="<?= base_url('admin/pertemuan/update/' . $pertemuan['id']); ?>" method="post">
                    <?= csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Pertemuan</label>
                        <input type="text" name="judul_pertemuan" class="form-control rounded-3" value="<?= esc($pertemuan['judul_pertemuan']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Urutan</label>
                        <input type="number" name="urutan" class="form-control rounded-3" value="<?= esc($pertemuan['urutan']); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Isi Materi (Link)</label>
                        <input type="text" name="isi_materi" class="form-control rounded-3" value="<?= esc($pertemuan['isi_materi']); ?>" required>
                        <div class="form-text">
                            Link YouTube atau path file PDF.
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning text-dark rounded-pill py-2 fw-bold">
                            <i class="bi bi-pencil-square me-2"></i> Update Pertemuan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>