<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<!-- HERO SECTION -->
<div class="container-fluid py-5 text-center text-white" style="background: linear-gradient(180deg, #004d40, #00796b);">
  <h2 class="fw-bold mb-2">Materi Study Jam</h2>
  <p class="mb-0">
    Akses materi pembelajaran yang sudah dipilih dan disusun oleh mentor.<br>
    Pelajari konsep, praktekan, dan kuasai langkah demi langkah.
  </p>
</div>

<!-- DAFTAR MATERI -->
<div class="container my-5">
  <div class="row g-4 justify-content-center">

    <?php if(empty($materi_list)): ?>
        <div class="col-12 text-center py-5">
            <div class="text-muted mb-3" style="font-size: 3rem;"><i class="bi bi-journal-x"></i></div>
            <h5 class="text-muted">Belum ada materi tersedia saat ini.</h5>
        </div>
    <?php else: ?>

        <?php foreach ($materi_list as $m) : 
            // Ambil data progress dari array yang dikirim Controller
            $progressData = $progress_data[$m['id']] ?? ['completed' => 0];
            $completed = $progressData['completed'];
            $total = $m['total_pertemuan'];
            
            // Hitung Persentase
            $percentage = $total > 0 ? round(($completed / $total) * 100) : 0;
            
            // Cek status selesai (hanya untuk visual border & ikon ceklis)
            $isComplete = ($completed > 0 && $completed == $total);
        ?>
          <div class="col-10 col-md-5 col-lg-4">
            <div class="card border rounded-4 shadow-sm text-center p-4 h-100 <?= $isComplete ? 'border-success border-3' : ''; ?>">
              
              <h6 class="fw-bold mb-3 text-uppercase text-dark"><?= esc($m['judul']); ?></h6>
              <p class="text-muted small mb-4"><?= esc($m['deskripsi']); ?></p>

              <!-- Progress Bar -->
              <div class="progress mb-2" style="height: 8px; border-radius: 4px;">
                <div class="progress-bar" role="progressbar" 
                    style="width: <?= $percentage; ?>%; background: linear-gradient(90deg,#004d40,#00796b);"
                    aria-valuenow="<?= $percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                </div>
              </div>
              <small class="text-muted mb-3 d-block">
                <?= $completed; ?>/<?= $total; ?> Selesai 
                <?php if($isComplete): ?>
                    <i class="bi bi-check-circle-fill text-success ms-1"></i>
                <?php endif; ?>
              </small>

              <!-- Tombol Aksi -->
              <div class="d-grid gap-2 mt-auto">
                <a href="<?= base_url('materi/detail/' . $m['id']); ?>" class="btn btn-outline-dark fw-semibold">
                    <?= ($isComplete) ? 'Ulangi Materi' : 'Lihat Materi'; ?>
                </a>
                
                <!-- TOMBOL QUIZ (SEKARANG SELALU AKTIF) -->
                <a href="<?= base_url('/quiz'); ?>" class="btn text-white fw-semibold" style="background: linear-gradient(90deg,#004d40,#00796b);">
                    Quiz
                </a>
              </div>

            </div>
          </div>
        <?php endforeach; ?>

    <?php endif; ?>

  </div>
</div>

<?= $this->endSection(); ?>