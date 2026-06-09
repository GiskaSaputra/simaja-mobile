<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<!-- ======================================================================= -->
<!-- 1. HERO SECTION (DINAMIS DARI DATABASE HOME_CONTENT) -->
<!-- ======================================================================= -->
<div class="container-fluid py-5" style="background: linear-gradient(180deg, #004d40, #00796b); color: white;">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                
                <!-- Judul Utama -->
                <h2 class="fw-bold mb-2">
                    <?= esc($home_content['hero_title'] ?? 'Hi, Selamat Datang di SIMAJA'); ?>
                </h2>
                
                <!-- Sub Judul -->
                <h5 class="fw-normal mb-4">
                    <?= esc($home_content['hero_subtitle'] ?? 'Sistem Manajemen Study Jam'); ?>
                </h5>
                
                <!-- Deskripsi -->
                <p class="mb-4">
                    <?= esc($home_content['hero_description'] ?? 'Temukan pengalaman belajar baru bersama SIMAJA.'); ?>
                </p>
                
                <!-- Tombol Aksi Hero -->
                <div class="d-flex gap-3">
                    <a href="<?= base_url('/materi'); ?>" class="btn btn-light px-4 py-2 fw-semibold shadow-sm">Mulai Belajar</a>
                    <a href="<?= base_url('/jadwal'); ?>" class="btn btn-outline-light px-4 py-2 fw-semibold">Lihat Jadwal</a>
                </div>
            </div>
            
            <!-- Gambar Banner -->
            <div class="col-lg-6 text-center">
                <?php 
                    $imgName = $home_content['hero_image'] ?? 'studyjam.png';
                    // Cek apakah gambar berupa URL eksternal atau file lokal
                    $imgUrl = filter_var($imgName, FILTER_VALIDATE_URL) ? $imgName : base_url('img/' . $imgName);
                ?>
                <img src="<?= $imgUrl; ?>" alt="Kegiatan Study Jam" class="img-fluid rounded-4 shadow-lg" style="max-width: 90%;">
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================= -->
<!-- 2. MENU ICON SHORTCUT -->
<!-- ======================================================================= -->
<div class="container text-center my-5">
    <div class="row justify-content-center">

        <!-- Shortcut Jadwal -->
        <div class="col-6 col-md-3 mb-4">
            <a href="<?= base_url('/jadwal'); ?>" class="text-decoration-none text-dark">
                <i class="bi bi-calendar3" style="font-size: 3rem; color: #00796b;"></i>
                <h6 class="mt-2 fw-semibold">Jadwal</h6>
            </a>
        </div>

        <!-- Shortcut Materi -->
        <div class="col-6 col-md-3 mb-4">
            <a href="<?= base_url('/materi'); ?>" class="text-decoration-none text-dark">
                <i class="bi bi-book" style="font-size: 3rem; color: #00796b;"></i>
                <h6 class="mt-2 fw-semibold">Materi</h6>
            </a>
        </div>

        <!-- Shortcut Progres -->
        <div class="col-6 col-md-3 mb-4">
            <a href="<?= base_url('/progres'); ?>" class="text-decoration-none text-dark">
                <i class="bi bi-bar-chart-line" style="font-size: 3rem; color: #00796b;"></i>
                <h6 class="mt-2 fw-semibold">Progres</h6>
            </a>
        </div>

        <!-- Shortcut Peringkat -->
        <div class="col-6 col-md-3 mb-4">
            <a href="<?= base_url('/peringkat'); ?>" class="text-decoration-none text-dark">
                <i class="bi bi-trophy" style="font-size: 3rem; color: #00796b;"></i>
                <h6 class="mt-2 fw-semibold">Peringkat</h6>
            </a>
        </div>

    </div>
</div>


<!-- ======================================================================= -->
<!-- 3. DAFTAR KELAS (DINAMIS DARI DATABASE JADWAL) -->
<!-- ======================================================================= -->
<div class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Jadwal Kelas Terdekat</h3>
        <a href="<?= base_url('/jadwal'); ?>" class="text-decoration-none fw-semibold" style="color: #00796b;">Lihat Semua <i class="bi bi-arrow-right"></i></a>
    </div>

    <div class="row g-4 justify-content-center">
        
        <!-- Cek jika data jadwal kosong -->
        <?php if (empty($jadwal_home)) : ?>
            <div class="col-12 text-center py-5">
                <div class="text-muted mb-2" style="font-size: 2rem;"><i class="bi bi-calendar-x"></i></div>
                <p class="text-muted">Belum ada jadwal kelas terdekat yang tersedia saat ini.</p>
            </div>
        <?php else : ?>
            
            <!-- Loop Data Jadwal -->
            <?php foreach ($jadwal_home as $j) : 
                // Logika Status: Apakah user sudah daftar? Apakah kelas penuh?
                $isRegistered = in_array($j['id'], $jadwal_saya ?? []);
                $isFull = $j['terisi'] >= $j['kuota'];
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-2 shadow-sm rounded-4 h-100 hover-up transition">
                        <div class="card-body d-flex flex-column">
                            <!-- Judul & Deskripsi -->
                            <h5 class="fw-bold mb-1"><?= esc($j['judul']); ?></h5>
                            <p class="text-muted mb-3 small text-truncate-2">
                                <?= esc($j['deskripsi'] ?? 'Deskripsi belum tersedia'); ?>
                            </p> 
                            
                            <!-- Detail Waktu & Kuota -->
                            <ul class="list-unstyled small mb-4 flex-grow-1">
                                <li class="mb-2">
                                    <i class="bi bi-calendar-event me-2 text-success"></i>
                                    <?= date('D, d M Y', strtotime($j['tanggal'])); ?>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-clock me-2 text-primary"></i>
                                    <?= date('H:i', strtotime($j['waktu_mulai'])); ?> - <?= date('H:i', strtotime($j['waktu_selesai'])); ?> WIB
                                </li>
                                <li>
                                    <i class="bi bi-people me-2 text-warning"></i>
                                    <?= esc($j['terisi']); ?> / <?= esc($j['kuota']); ?> peserta
                                </li>
                            </ul>
                            
                            <!-- Tombol Aksi (Logic) -->
                            <div class="d-flex gap-2 mt-auto">
                                <?php if ($isRegistered) : ?>
                                    <!-- Jika Sudah Terdaftar -->
                                    <button class="btn btn-outline-secondary w-100" disabled>
                                        <i class="bi bi-check-lg me-1"></i> Terdaftar
                                    </button>
                                    <a href="<?= base_url('absensi/' . $j['id']); ?>" class="btn btn-success w-100">Absen</a>
                                
                                <?php elseif ($isFull) : ?>
                                    <!-- Jika Kuota Penuh -->
                                    <button class="btn btn-danger w-100" disabled>Penuh</button>
                                
                                <?php else : ?>
                                    <!-- Jika Belum Daftar & Masih Ada Slot -->
                                    <form action="<?= base_url('/jadwal/daftar/' . $j['id']); ?>" method="post" class="w-100">
                                        <?= csrf_field(); ?>
                                        <button type="submit" class="btn text-white w-100 fw-semibold" style="background: linear-gradient(90deg,#004d40,#00796b);">
                                            Daftar
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>

<!-- Style Tambahan Khusus Halaman Ini -->
<style>
    /* Efek hover kartu agar sedikit naik */
    .hover-up:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .transition {
        transition: all 0.3s ease;
    }
    /* Membatasi teks deskripsi max 2 baris agar kartu rapi */
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<?= $this->endSection(); ?>