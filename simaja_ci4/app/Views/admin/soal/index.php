<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 fw-bold">Kelola Soal Quiz</h1>
        <p class="text-muted mb-0">Pertemuan: <strong><?= esc($pertemuan['judul_pertemuan']); ?></strong></p>
    </div>
    <div>
        <!-- Tombol Kembali ke Daftar Pertemuan -->
        <a href="<?= base_url('admin/pertemuan/' . $materi['id']); ?>" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        
        <!-- Tombol Tambah Soal Baru -->
        <a href="<?= base_url('admin/soal/create/' . $pertemuan['id']); ?>" class="btn btn-success rounded-pill px-4">
            <i class="bi bi-plus-lg"></i> Tambah Soal
        </a>
    </div>
</div>

<!-- Flash Message Notifikasi -->
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

<!-- Container Soal -->
<div class="row g-4">
    <?php if (empty($soal_list)) : ?>
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-clipboard-x fs-1 d-block mb-2"></i>
            <p>Belum ada soal untuk pertemuan ini. Silakan tambah soal baru.</p>
        </div>
    <?php else : ?>
        
        <?php foreach ($soal_list as $index => $s) : ?>
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <!-- Header Soal -->
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0">
                        <h6 class="fw-bold mb-0 text-success">
                            <span class="badge bg-success me-2">No. <?= $index + 1; ?></span>
                        </h6>
                        <div>
                            <a href="<?= base_url('admin/soal/edit/' . $s['id']); ?>" class="btn btn-sm btn-outline-warning me-1" title="Edit Soal">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="<?= base_url('admin/soal/delete/' . $s['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus soal ini beserta jawabannya?');" title="Hapus Soal">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <!-- Teks Pertanyaan -->
                        <p class="mb-4 fs-5 fw-medium text-dark ps-2 border-start border-4 border-success">
                            <?= esc($s['pertanyaan']); ?>
                        </p>
                        
                        <!-- List Jawaban -->
                        <div class="row g-2">
                            <?php if (!empty($s['jawaban'])): ?>
                                <?php foreach ($s['jawaban'] as $j) : ?>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 d-flex align-items-center h-100 <?= $j['is_correct'] ? 'bg-success-subtle border-success' : 'bg-light'; ?>">
                                            <?php if ($j['is_correct']) : ?>
                                                <!-- Jawaban Benar -->
                                                <i class="bi bi-check-circle-fill text-success fs-5 me-3"></i>
                                                <span class="fw-bold text-success"><?= esc($j['teks_jawaban']); ?></span>
                                                <span class="badge bg-success ms-auto">Kunci</span>
                                            <?php else : ?>
                                                <!-- Jawaban Salah -->
                                                <i class="bi bi-circle text-muted fs-5 me-3"></i>
                                                <span class="text-muted"><?= esc($j['teks_jawaban']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-warning py-2 small">
                                        <i class="bi bi-exclamation-triangle me-1"></i> Pilihan jawaban belum tersedia atau error.
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    /* Sedikit styling tambahan agar tampilan soal lebih rapi */
    .bg-success-subtle {
        background-color: #d1e7dd !important;
    }
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
</style>

<?= $this->endSection(); ?>