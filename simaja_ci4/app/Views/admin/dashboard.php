<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Overview</h1>
</div>

<div>

<?php if (session()->getFlashdata('unauthorized')): ?>
    <div class="alert alert-danger" role="alert">
  <?= session()->getFlashdata('unauthorized') ?>
</div>

    <?php endif;?>
</div>

<div class="row g-4">

    <!-- Card Jadwal -->
    <div class="col-md-4">
        <div class="card text-white bg-primary shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Jadwal</h6>
                        <h2 class="mt-2 mb-0 fw-bold"><?= $stats['jadwal']; ?></h2>
                    </div>
                    <i class="bi bi-calendar-event fs-1 opacity-50"></i>
                </div>
            </div>
            <a href="<?= base_url('admin/jadwal'); ?>" class="card-footer text-white text-decoration-none d-flex justify-content-between align-items-center bg-primary border-top border-white border-opacity-25">
                <small>Lihat Detail</small>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card Materi -->
    <div class="col-md-4">
        <div class="card text-white bg-success shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Materi</h6>
                        <h2 class="mt-2 mb-0 fw-bold"><?= $stats['materi']; ?></h2>
                    </div>
                    <i class="bi bi-book fs-1 opacity-50"></i>
                </div>
            </div>
            <a href="<?= base_url('admin/materi'); ?>" class="card-footer text-white text-decoration-none d-flex justify-content-between align-items-center bg-success border-top border-white border-opacity-25">
                <small>Lihat Detail</small>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card User -->
    <div class="col-md-4">
        <div class="card text-white bg-warning shadow h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0 text-dark">Total User</h6>
                        <h2 class="mt-2 mb-0 fw-bold text-dark"><?= $stats['user']; ?></h2>
                    </div>
                    <i class="bi bi-people fs-1 opacity-50 text-dark"></i>
                </div>
            </div>
            <a href="<?= base_url('admin/users'); ?>" class="card-footer text-dark text-decoration-none d-flex justify-content-between align-items-center bg-warning border-top border-dark border-opacity-10">
                <small>Lihat Detail</small>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="mt-5">
    <h4>Aktivitas Terbaru</h4>
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i> Fitur log aktivitas akan segera hadir.
    </div>
</div>

<?= $this->endSection(); ?>