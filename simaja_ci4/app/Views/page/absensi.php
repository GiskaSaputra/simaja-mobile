<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<!-- HERO SECTION -->
<div class="container-fluid py-5 text-center text-white" style="background: linear-gradient(180deg, #004d40, #00796b);">
    <h1 class="fw-bold display-5 mb-2">Absensi Study Jam</h1>
    <p class="mb-0">
        Catat kehadiranmu dan tunjukkan semangat belajarmu di setiap sesi.<br>
        Pilih status kehadiranmu dengan jujur dan tepat waktu.
    </p>
</div>

<!-- KONTEN UTAMA -->
<div class="container my-5">
  <div class="card border-0 shadow-lg mx-auto p-4 rounded-4" style="max-width: 500px; border-radius: 25px;">
    <div class="card-body">

      <!-- JUDUL KELAS DINAMIS (Diambil dari controller) -->
      <h4 class="text-center fw-bold text-success mb-2"><?= esc($jadwal['judul']); ?></h4>
      <p class="text-center text-muted small mb-4">
        <?= date('d F Y', strtotime($jadwal['tanggal'])); ?> | 
        <?= date('H.i', strtotime($jadwal['waktu_mulai'])); ?> WIB
      </p>

      <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger text-center fw-semibold py-2" role="alert">
          <?= session()->getFlashdata('error'); ?>
        </div>
      <?php endif; ?>

      <?php if ($sudah_absen) : ?>
        <!-- TAMPILAN JIKA SUDAH ABSEN -->
        <div class="text-center py-4">
            <i class="bi bi-check-circle-fill text-success display-1"></i>
            <h5 class="mt-3">Anda sudah mengisi absensi.</h5>
            <p class="text-muted">Status: <strong><?= $sudah_absen['status']; ?></strong></p>
            <a href="<?= base_url('jadwal'); ?>" class="btn btn-outline-success mt-2">Kembali ke Jadwal</a>
        </div>

      <?php else : ?>
        <!-- FORM ABSENSI -->
        <form action="<?= base_url('absensi/kirim'); ?>" method="post">
        <?= csrf_field(); ?>
        
        <!-- Input Hidden untuk ID Jadwal -->
        <input type="hidden" name="jadwal_id" value="<?= $jadwal['id']; ?>">

        <div class="mb-3">
          <label class="form-label fw-semibold text-dark mb-2">Keterangan Peserta</label>

          <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="status" value="Hadir" id="hadir" required>
            <label class="form-check-label" for="hadir">Hadir</label>
          </div>

          <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="status" value="Izin" id="izin">
            <label class="form-check-label" for="izin">Izin</label>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="radio" name="status" value="Sakit" id="sakit">
            <label class="form-check-label" for="sakit">Sakit</label>
          </div>
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn text-white fw-semibold rounded-pill px-5 py-2"
                  style="background: linear-gradient(90deg, #004d40, #009688);">
            <i class="bi bi-send-fill me-1"></i> Kirim Absensi
          </button>
        </div>
      </form>
      <?php endif; ?>

    </div>
  </div>
</div>

<?= $this->endSection(); ?>