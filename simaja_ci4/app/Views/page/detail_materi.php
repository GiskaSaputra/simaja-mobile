<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<!-- HERO SECTION -->
<div class="container-fluid py-5 text-center text-white" style="background: linear-gradient(180deg, #004d40, #00796b);">
  <h2 class="fw-bold mb-2">Materi <?= esc($materi['judul']); ?></h2>
  <p class="mb-0">
    Akses materi pembelajaran yang sudah dipilih dan disusun oleh mentor.<br>
    Pelajari konsep, praktekkan, dan kuasai langkah demi langkah
  </p>
</div>

<!-- LIST PERTEMUAN -->
<div class="container my-5" style="max-width: 800px;">
    
    <!-- Notifikasi Jika Ada Pesan -->
    <?php if (session()->getFlashdata('pesan')) : ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('pesan'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="mb-4 d-flex align-items-center justify-content-between">
        <a href="<?= base_url('materi'); ?>" class="text-decoration-none text-muted fw-semibold">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Materi
        </a>
        <!-- Tampilkan total progress di sini -->
        <?php 
            $completedCount = count($completed_list);
            $totalPertemuan = $materi['total_pertemuan'];
            $percentage = $totalPertemuan > 0 ? round(($completedCount / $totalPertemuan) * 100) : 0;
        ?>
        <span class="badge bg-success py-2 px-3 fw-bold shadow-sm">
            Progress: <?= $completedCount; ?>/<?= $totalPertemuan; ?> (<?= $percentage; ?>%)
        </span>
    </div>

    <?php if(empty($pertemuan)): ?>
        <div class="alert alert-info text-center">Belum ada sesi pertemuan yang diunggah untuk materi ini.</div>
    <?php else: ?>
        
        <div class="d-flex flex-column gap-3">
            <?php foreach($pertemuan as $p): ?>
                <?php $isCompleted = in_array($p['id'], $completed_list); ?>

                <!-- CARD ITEM -->
                <div class="card border shadow-sm rounded-3 <?= $isCompleted ? 'border-success' : ''; ?>">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        
                        <!-- Status dan Judul -->
                        <div class="d-flex align-items-center">
                            <?php if ($isCompleted): ?>
                                <!-- Icon Ceklis jika Selesai -->
                                <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                            <?php else: ?>
                                <!-- Icon Numerik jika Belum Selesai -->
                                <span class="badge bg-light text-dark border rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                    <?= esc($p['urutan']); ?>
                                </span>
                            <?php endif; ?>

                            <div>
                                <h5 class="fw-bold mb-0 text-dark <?= $isCompleted ? 'text-decoration-line-through text-muted' : ''; ?>">
                                    <?= esc($p['judul_pertemuan']); ?>
                                </h5>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex align-items-center">
                            <?php if ($isCompleted): ?>
                                <span class="badge bg-success-subtle text-success fw-semibold me-3 d-none d-md-block">SELESAI</span>
                            <?php endif; ?>
                            
                            <!-- Icon Panah Kanan -->
                            <a href="<?= base_url('materi/belajar/' . $p['id']); ?>" class="btn btn-light btn-sm rounded-circle border shadow-sm" title="Akses Materi">
                                <i class="bi bi-chevron-right text-dark"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Link Full Card (Mengarahkan ke halaman belajar) -->
                    <a href="<?= base_url('materi/belajar/' . $p['id']); ?>" class="stretched-link"></a> 
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>

<style>
    /* Styling agar kartu terlihat interaktif saat dihover */
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transition: all 0.2s;
    }
</style>

<?= $this->endSection(); ?>