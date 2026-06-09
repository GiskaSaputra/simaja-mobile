<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Edit Tampilan Beranda</h1>
    <a href="<?= base_url('/'); ?>" target="_blank" class="btn btn-outline-primary rounded-pill px-4">
        <i class="bi bi-eye me-1"></i> Lihat Hasil
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        
        <?php if (session()->getFlashdata('pesan')) : ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('pesan'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                    <div>
                        <strong>Terjadi Kesalahan:</strong>
                        <?php if(is_array(session()->getFlashdata('error'))): ?>
                            <ul class="mb-0 ps-3">
                                <?php foreach(session()->getFlashdata('error') as $err): ?>
                                    <li><?= $err; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="mb-0"><?= session()->getFlashdata('error'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                
                <form action="<?= base_url('admin/home/update'); ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    
                    <input type="hidden" name="foto_lama" value="<?= $content['hero_image'] ?? 'studyjam.png'; ?>">

                    <div class="mb-4">
                        <label class="form-label fw-bold mb-2 text-secondary">Preview Tampilan Banner Saat Ini</label>
                        <div class="text-center p-4 rounded-4 position-relative overflow-hidden shadow-sm" style="background: linear-gradient(180deg, #004d40, #00796b); color: white;">
                            <div class="row align-items-center position-relative" style="z-index: 2;">
                                <div class="col-md-7 text-start">
                                    <h4 class="fw-bold mb-2"><?= esc($content['hero_title'] ?? 'Judul Default'); ?></h4>
                                    <h6 class="fw-normal opacity-75 mb-3"><?= esc($content['hero_subtitle'] ?? ''); ?></h6>
                                    <p class="small opacity-50 d-none d-md-block" style="font-size: 0.8rem;">
                                        <?= character_limiter(esc($content['hero_description'] ?? ''), 100); ?>
                                    </p>
                                </div>
                                <div class="col-md-5">
                                    <?php 
                                        $imgName = $content['hero_image'] ?? 'studyjam.png';
                                        // Cek apakah string dimulai dengan http/https (URL Eksternal)
                                        if (filter_var($imgName, FILTER_VALIDATE_URL)) {
                                            $imgUrl = $imgName;
                                        } else {
                                            // Jika bukan URL, anggap file di folder public/img
                                            $imgUrl = base_url('img/' . $imgName);
                                        }
                                    ?>
                                    <img src="<?= $imgUrl; ?>" 
                                         alt="Preview Banner"
                                         class="img-fluid rounded-3 shadow-lg mt-3 mt-md-0" 
                                         style="max-height: 120px; border: 2px solid rgba(255,255,255,0.3);">
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 text-muted opacity-25">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Utama (Hero Title) <span class="text-danger">*</span></label>
                        <input type="text" name="hero_title" class="form-control rounded-3 p-3" 
                               value="<?= esc($content['hero_title'] ?? ''); ?>" required placeholder="Misal: Hi, Selamat Datang di SIMAJA">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Sub-Judul</label>
                        <input type="text" name="hero_subtitle" class="form-control rounded-3 p-3" 
                               value="<?= esc($content['hero_subtitle'] ?? ''); ?>" placeholder="Misal: Sistem Manajemen Study Jam">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi Singkat</label>
                        <textarea name="hero_description" class="form-control rounded-3 p-3" rows="4" 
                                  placeholder="Jelaskan singkat tentang website ini..."><?= esc($content['hero_description'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Ganti Gambar Banner</label>
                        <input type="file" name="hero_image" class="form-control" accept="image/png, image/jpeg, image/jpg">
                        <div class="form-text mt-2 text-muted">
                            <i class="bi bi-info-circle me-1"></i> Kosongkan jika tidak ingin mengganti gambar.<br>
                            <i class="bi bi-file-earmark-image me-1"></i> Format: JPG, PNG. Maksimal 2MB.
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="<?= base_url('admin/dashboard'); ?>" class="btn btn-light rounded-pill px-4 py-2 text-muted fw-semibold">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-success rounded-pill px-5 py-2 fw-bold shadow-sm">
                            <i class="bi bi-save me-2"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>