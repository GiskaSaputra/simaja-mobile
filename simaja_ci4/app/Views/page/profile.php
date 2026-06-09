<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<style>
    .bg-custom-teal {
        background-color: #004d40;
        padding-bottom: 100px;
    }

    .profile-overlap {
        margin-top: -80px;
    }

    .info-box {
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 12px 20px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
    }

    .info-label {
        font-weight: 700;
        color: #000;
    }

    .info-value {
        font-weight: 500;
        color: #6c757d;
    }

    .stat-card {
        border: 1px solid #6c757d;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        background: white;
    }

    .stat-icon {
        font-size: 2rem;
        margin-right: 15px;
        color: #004d40;
    }
</style>

<div class="container-fluid bg-custom-teal text-center text-white pt-5">
    <h2 class="fw-bold mb-2">Profil</h2>
    <p class="mb-0 px-3">
        Tampilkan identitas dan perjalanan belajarmu di Study Jam.<br>
        Jadikan profilmu bukti nyata dari usaha dan prestasi.
    </p>
    <div style="height: 40px;"></div>
</div>

<div class="container profile-overlap mb-5">

    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 text-center py-4">
                <div class="card-body">
                    <!-- FOTO PROFIL -->
                    <?php
                    $foto = $profile['foto'] ?? 'default.png';
                    // Cek apakah foto url luar atau lokal
                    $fotoUrl = (filter_var($foto, FILTER_VALIDATE_URL)) ? $foto : base_url('img/' . $foto);
                    ?>
                    <img src="<?= $fotoUrl; ?>" alt="Foto Profil" class="rounded-circle mb-3 shadow-sm"
                        style="width: 100px; height: 100px; object-fit: cover; border: 3px solid white;">

                    <!-- NAMA & NIM DINAMIS -->
                    <h4 class="fw-bold mb-1"><?= esc($profile['nama_lengkap'] ?? user()->username); ?></h4>
                    <p class="text-muted small mb-0">NIM: <?= esc($profile['nim'] ?? '-'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border border-secondary border-opacity-25 rounded-4 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Informasi Profil</h5>
                        <!-- UBAH HREF DI SINI -->
                        <a href="<?= base_url('/profile/edit'); ?>" class="text-dark" title="Edit Profil">
                            <i class="bi bi-pencil-square fs-5"></i>
                        </a>
                    </div>

                    <!-- DATA DINAMIS -->
                    <div class="info-box">
                        <span class="info-label">Kelas</span>
                        <span class="info-value"><?= esc($profile['kelas'] ?? '-'); ?></span>
                    </div>
                    <div class="info-box">
                        <span class="info-label">Prodi</span>
                        <span class="info-value"><?= esc($profile['prodi'] ?? '-'); ?></span>
                    </div>
                    <div class="info-box">
                        <span class="info-label">Jurusan</span>
                        <span class="info-value"><?= esc($profile['jurusan'] ?? '-'); ?></span>
                    </div>
                    <div class="info-box">
                        <span class="info-label">Semester</span>
                        <span class="info-value"><?= esc($profile['semester'] ?? '-'); ?></span>
                    </div>
                    <div class="info-box">
                        <span class="info-label">Jenis Kelamin</span>
                        <span class="info-value"><?= esc($profile['jenis_kelamin'] ?? '-'); ?></span>
                    </div>
                    <div class="info-box mb-0">
                        <span class="info-label">Alamat</span>
                        <span class="info-value text-end"><?= esc($profile['alamat'] ?? '-'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center g-3">
        <div class="col-md-8">
            <div class="row g-3">
                <!-- STATISTIK DINAMIS -->
                <div class="col-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-trophy stat-icon"></i>
                            <div class="text-start">
                                <h4 class="fw-bold mb-0"><?= $stats['poin']; ?></h4>
                                <small class="fw-bold text-dark">Total Poin</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock stat-icon"></i>
                            <div class="text-start">
                                <h4 class="fw-bold mb-0"><?= $stats['jam']; ?></h4>
                                <small class="fw-bold text-dark">Jam Belajar</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-book stat-icon"></i>
                            <div class="text-start">
                                <h4 class="fw-bold mb-0"><?= $stats['selesai']; ?></h4>
                                <small class="fw-bold text-dark">Materi Selesai</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="stat-card">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-star stat-icon"></i>
                            <div class="text-start">
                                <h4 class="fw-bold mb-0">#<?= $stats['ranking']; ?></h4>
                                <small class="fw-bold text-dark">Ranking</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="<?= base_url('logout'); ?>" class="btn btn-outline-danger px-4 py-2 rounded-pill">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
        </a>
    </div>

</div>

<?= $this->endSection(); ?>