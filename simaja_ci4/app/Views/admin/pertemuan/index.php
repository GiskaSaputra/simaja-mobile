<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 fw-bold">Kelola Pertemuan</h1>
        <p class="text-muted mb-0">Materi: <strong><?= esc($materi['judul']); ?></strong></p>
    </div>
    <div>
        <a href="<?= base_url('admin/materi'); ?>" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="<?= base_url('admin/pertemuan/create/' . $materi['id']); ?>" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg"></i> Tambah Pertemuan
        </a>
    </div>
</div>

<!-- Flash Message -->
<?php if (session()->getFlashdata('pesan')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('pesan'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-center" style="width: 80px;">Urutan</th>
                        <th>Judul Pertemuan</th>
                        <th>Isi Materi (Link/File)</th>
                        <th class="text-end px-4" style="width: 250px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pertemuan)) : ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                                Belum ada pertemuan untuk materi ini.
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($pertemuan as $p) : ?>
                            <tr>
                                <td class="px-4 text-center">
                                    <span class="badge bg-light text-dark border rounded-circle" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;">
                                        <?= $p['urutan']; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark"><?= esc($p['judul_pertemuan']); ?></span>
                                </td>
                                <td class="text-truncate" style="max-width: 250px;">
                                    <?php 
                                        $link = $p['isi_materi'];
                                        // Deteksi tipe konten sederhana
                                        $icon = 'bi-link-45deg'; // Default link
                                        $label = 'Buka Link';
                                        
                                        if (strpos($link, 'youtu') !== false) {
                                            $icon = 'bi-youtube text-danger';
                                            $label = 'Video Youtube';
                                        } elseif (strpos($link, '.pdf') !== false) {
                                            $icon = 'bi-file-earmark-pdf text-danger';
                                            $label = 'File PDF';
                                            // Jika file lokal, tambahkan base_url jika belum ada http
                                            if (!filter_var($link, FILTER_VALIDATE_URL)) {
                                                $link = base_url($link);
                                            }
                                        }
                                    ?>
                                    <a href="<?= $link; ?>" target="_blank" class="text-decoration-none text-secondary d-flex align-items-center">
                                        <i class="<?= $icon; ?> fs-5 me-2"></i> <?= $label; ?>
                                    </a>
                                </td>
                                <td class="text-end px-4">
                                    <div class="btn-group">
                                        <!-- TOMBOL KELOLA SOAL QUIZ -->
                                        <a href="<?= base_url('admin/soal/' . $p['id']); ?>" class="btn btn-sm btn-warning text-dark fw-semibold" title="Kelola Soal Quiz">
                                            <i class="bi bi-question-circle-fill me-1"></i> Soal
                                        </a>
                                        
                                        <!-- TOMBOL EDIT -->
                                        <a href="<?= base_url('admin/pertemuan/edit/' . $p['id']); ?>" class="btn btn-sm btn-outline-secondary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <!-- TOMBOL HAPUS -->
                                        <a href="<?= base_url('admin/pertemuan/delete/' . $p['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus pertemuan ini? Data progress user dan soal quiz terkait juga akan dihapus.');" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>