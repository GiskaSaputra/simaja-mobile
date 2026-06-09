<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<!-- HEADER -->
<div class="w-100 py-5 text-center text-white" style="background: linear-gradient(135deg, #004d40, #009688);">
    <h1 class="fw-bold display-4">Quiz</h1>
    <p class="mt-2">Uji pemahamanmu dengan kuis interaktif.<br>Semakin banyak latihan, semakin tajam logikamu!</p>
</div>

<div class="container my-5">
    
    <!-- Notifikasi Nilai -->
    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success text-center fw-bold shadow-sm mb-4">
            <i class="bi bi-trophy-fill me-2"></i> <?= session()->getFlashdata('pesan'); ?>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('warning')) : ?>
        <div class="alert alert-warning text-center fw-bold shadow-sm mb-4">
            <?= session()->getFlashdata('warning'); ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger text-center fw-bold shadow-sm mb-4">
            <?= session()->getFlashdata('error'); ?>
        </div>
    <?php endif; ?>

    <!-- DAFTAR QUIZ -->
    <div class="row g-4 justify-content-center">
        <?php if(empty($list_pertemuan)): ?>
            <div class="text-center text-muted py-5">
                <i class="bi bi-clipboard-x display-1"></i>
                <p class="mt-3">Belum ada kuis yang tersedia.</p>
            </div>
        <?php else: ?>
            
            <?php foreach ($list_pertemuan as $p): ?>
                <?php 
                    // Cek apakah user sudah pernah mengerjakan kuis ini
                    $sudahMengerjakan = isset($nilai_user[$p['id']]); 
                    $nilai = $sudahMengerjakan ? $nilai_user[$p['id']] : 0;
                ?>
                <div class="col-md-6 col-lg-4">
                    <a href="<?= base_url('quiz/pertemuan/' . $p['id']); ?>" class="text-decoration-none">
                        <div class="card p-3 shadow-sm border-0 h-100 hover-shadow transition <?= $sudahMengerjakan ? 'border-success' : ''; ?>">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 fw-bold text-dark"><?= esc($p['judul_pertemuan']); ?></h5>
                                    <?php if($sudahMengerjakan): ?>
                                        <small class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Selesai dikerjakan</small>
                                    <?php else: ?>
                                        <small class="text-muted">Klik untuk mulai</small>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if($sudahMengerjakan): ?>
                                    <div class="text-center">
                                        <span class="badge bg-success rounded-pill px-3 py-2 fs-6 shadow-sm">
                                            Nilai: <?= $nilai; ?>
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <div class="bg-light rounded-circle p-3 shadow-sm">
                                        <i class="bi bi-play-fill fs-4 text-dark"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>

<style>
    .hover-shadow:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1)!important; }
    .transition { transition: all 0.3s; }
</style>

<?= $this->endSection(); ?>