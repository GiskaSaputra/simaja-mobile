<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<!-- HERO SECTION -->
<div class="container-fluid py-5 text-center text-white" style="background: linear-gradient(180deg, #004d40, #00796b);">
  <h2 class="fw-bold mb-2">Jadwal Study Jam</h2>
  <p class="mb-0">Atur waktu belajarmu dengan mudah.<br>Lihat jadwal Study Jam terbaru dan jangan lewatkan sesi penting!
  </p>
</div>

<!-- FLASH MESSAGE (Pesan Sukses/Gagal) -->
<div class="container mt-4">
  <?php if (session()->getFlashdata('pesan')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('pesan'); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error'); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('warning')): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <i class="bi bi-info-circle-fill me-2"></i><?= session()->getFlashdata('warning'); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
</div>

<!-- LIST JADWAL -->
<div class="container my-5">
  <div class="row justify-content-center">

    <?php if (empty($jadwal)): ?>
      <div class="col-12 text-center py-5">
        <div class="text-muted display-1 mb-3"><i class="bi bi-calendar-x"></i></div>
        <h4 class="text-muted">Belum ada jadwal tersedia saat ini.</h4>
      </div>
    <?php else: ?>

      <?php foreach ($jadwal as $item): ?>
        <!-- START LOOP CARD -->
        <div class="col-md-8 mb-4">
          <div class="card shadow-sm border-2 rounded-4 p-3 d-flex flex-row justify-content-between align-items-center">

            <!-- Info Jadwal -->
            <div class="flex-grow-1 pe-3">
              <h6 class="fw-bold mb-1 text-dark"><?= esc($item['judul']); ?></h6>
              <p class="text-muted small mb-2"><?= esc($item['deskripsi']); ?></p>

              <div class="d-flex flex-wrap small text-muted gap-3">
                <div>
                  <i class="bi bi-calendar-event me-1 text-success"></i>
                  <?= date('d F Y', strtotime($item['tanggal'])); ?>
                </div>
                <div>
                  <i class="bi bi-clock me-1 text-primary"></i>
                  <?= date('H.i', strtotime($item['waktu_mulai'])); ?> -
                  <?= date('H.i', strtotime($item['waktu_selesai'])); ?>
                </div>
                <div>
                  <i class="bi bi-people me-1 text-warning"></i>
                  <?= esc($item['terisi']); ?> / <?= esc($item['kuota']); ?> Peserta
                </div>
              </div>
            </div>

            <!-- Logic Tombol -->
            <div style="min-width: 140px;">
              <?php
              // Cek apakah ID jadwal ini ada di array 'jadwal_saya' (dikirim dari Controller)
              $isRegistered = in_array($item['id'], $jadwal_saya ?? []);
              $isFull = $item['terisi'] >= $item['kuota'];
              ?>

              <?php if ($isRegistered): ?>
                <!-- Tombol: SUDAH TERDAFTAR -->
                <button class="btn btn-secondary fw-semibold w-100 mb-2" disabled style="cursor: not-allowed; opacity: 0.8;">
                  <i class="bi bi-check-lg"></i> Terdaftar
                </button>
                <a href="<?= base_url('absensi/' . $item['id']); ?>" class="btn btn-outline-success btn-sm w-100">
                  Absen
                </a>

              <?php elseif ($isFull): ?>
                <!-- Tombol: PENUH -->
                <button class="btn btn-danger fw-semibold w-100" disabled style="cursor: not-allowed;">
                  <i class="bi bi-x-circle"></i> Penuh
                </button>

              <?php else: ?>
                <!-- Tombol: DAFTAR (Form POST) -->
                <form action="<?= base_url('/jadwal/daftar/' . $item['id']); ?>" method="post">
                  <?= csrf_field(); ?>
                  <button type="submit" class="btn text-white fw-semibold w-100 shadow-sm"
                    style="background: linear-gradient(90deg,#004d40,#00796b);">
                    Daftar
                  </button>
                </form>
              <?php endif; ?>
            </div>

          </div>
        </div>
        <!-- END LOOP CARD -->
      <?php endforeach; ?>

    <?php endif; ?>

  </div>
</div>

<?= $this->endSection(); ?>