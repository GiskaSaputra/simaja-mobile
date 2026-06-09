<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<!-- HERO SECTION -->
<div class="container-fluid py-5 text-center text-white" style="background: linear-gradient(180deg, #004d40, #00796b);">
  <h2 class="fw-bold mb-2">Progres Belajar</h2>
  <p class="mb-0">
    Pantau sejauh mana kamu berkembang.<br>
    Lihat pencapaian, target, dan kemajuan belajarmu secara real-time.
  </p>
</div>

<!-- STATISTIK UTAMA -->
<div class="container my-5">
  <div class="row justify-content-center text-center mb-5">
    
    <!-- Total Jam -->
    <div class="col-md-4 col-10 mb-3">
      <div class="border rounded-4 p-4 shadow-sm bg-white h-100">
        <div class="d-flex align-items-center justify-content-center mb-2">
            <i class="bi bi-clock-history fs-3 text-warning me-2"></i>
            <h6 class="text-muted mb-0">Total Jam Belajar</h6>
        </div>
        <!-- DATA DINAMIS -->
        <h2 class="fw-bold mb-1 text-dark"><?= $total_jam; ?> Jam</h2>
        <small class="text-success fw-semibold">
            <i class="bi bi-graph-up-arrow"></i> Terus Meningkat
        </small>
      </div>
    </div>

    <!-- Streak -->
    <div class="col-md-4 col-10 mb-3">
      <div class="border rounded-4 p-4 shadow-sm bg-white h-100">
        <div class="d-flex align-items-center justify-content-center mb-2">
            <i class="bi bi-fire fs-3 text-danger me-2"></i>
            <h6 class="text-muted mb-0">Streak Harian</h6>
        </div>
        <!-- DATA DINAMIS -->
        <h2 class="fw-bold mb-1 text-dark"><?= $streak; ?> Hari</h2>
        <small class="text-muted">Pertahankan konsistensimu!</small>
      </div>
    </div>

  </div>

  <div class="row g-4">
      
      <!-- JAM BELAJAR MINGGUAN -->
      <div class="col-lg-6">
        <div class="border rounded-4 p-4 shadow-sm h-100 bg-white">
            <h5 class="fw-bold mb-4" style="color: #004d40;"><i class="bi bi-bar-chart-fill me-2"></i> Aktivitas Mingguan</h5>
            
            <?php if(empty($jam_mingguan)): ?>
                <p class="text-muted text-center py-4">Belum ada aktivitas minggu ini.</p>
            <?php else: ?>
                <?php foreach ($jam_mingguan as $m) : ?>
                    <div class="mb-4">
                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                        <span><?= esc($m['minggu']); ?></span>
                        <span class="text-muted"><?= $m['persen']; ?>% Aktivitas</span>
                    </div>
                    <div class="progress" style="height: 10px; border-radius: 5px;">
                        <div class="progress-bar" role="progressbar"
                            style="width: <?= $m['persen']; ?>%; background: linear-gradient(90deg,#004d40,#00796b);"
                            aria-valuenow="<?= $m['persen']; ?>" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
      </div>

      <!-- TARGET MATERI (BULANAN) -->
      <div class="col-lg-6">
        <div class="border rounded-4 p-4 shadow-sm h-100 bg-white">
            <h5 class="fw-bold mb-4" style="color: #004d40;"><i class="bi bi-bullseye me-2"></i> Progress Materi</h5>
            
            <?php if(empty($target_bulanan)): ?>
                <p class="text-muted text-center py-5">Belum ada data materi yang diikuti.</p>
            <?php else: ?>
                <?php foreach ($target_bulanan as $t) : ?>
                    <div class="mb-4">
                    <div class="d-flex justify-content-between small fw-semibold mb-1">
                        <span class="text-uppercase"><?= esc($t['judul']); ?></span>
                        <span class="<?= $t['persen'] == 100 ? 'text-success' : 'text-muted'; ?>">
                            <?= $t['persen']; ?>% Selesai
                            <?php if($t['persen'] == 100): ?> <i class="bi bi-check-circle-fill ms-1"></i> <?php endif; ?>
                        </span>
                    </div>
                    <div class="progress" style="height: 10px; border-radius: 5px;">
                        <div class="progress-bar <?= $t['persen'] == 100 ? 'bg-success' : ''; ?>" role="progressbar"
                            style="width: <?= $t['persen']; ?>%; background: <?= $t['persen'] == 100 ? '' : 'linear-gradient(90deg,#004d40,#00796b)'; ?>;"
                            aria-valuenow="<?= $t['persen']; ?>" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
      </div>

  </div>
</div>

<?= $this->endSection(); ?>