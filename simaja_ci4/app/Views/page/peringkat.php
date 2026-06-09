<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<!-- HERO SECTION -->
<div class="container-fluid py-5 text-center text-white" style="background: linear-gradient(180deg, #004d40, #00796b);">
  <h2 class="fw-bold mb-2">Peringkat</h2>
  <p class="mb-0">
    Naik ke puncak peringkat!<br>
    Raih posisi terbaik lewat semangat belajar dan kolaborasi.
  </p>
</div>

<div class="container my-5 text-center">

  <!-- CEK APAKAH ADA DATA -->
  <?php if (empty($top3)): ?>
      <div class="py-5 text-muted">
          <i class="bi bi-trophy display-1 opacity-25"></i>
          <p class="mt-3">Belum ada data peringkat tersedia. Mulailah mengerjakan kuis!</p>
      </div>
  <?php else: ?>

      <!-- TOP 3 RANK (PODIUM) -->
      <div class="d-flex flex-wrap justify-content-center align-items-end gap-3 mb-5">
        
        <!-- JUARA 2 (Index 1) - Posisi Kiri -->
        <?php if (isset($top3[1])): ?>
            <div class="order-1 order-md-0">
                <div class="border rounded-4 p-4 shadow-sm text-center position-relative" 
                     style="width:180px; background-color:#fff; border-top: 4px solid #009688;">
                  <div class="fw-bold text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm" 
                       style="background-color:#00796b; width: 40px; height: 40px; margin-top: -35px;">#2</div>
                  <h5 class="fw-bold mb-1 text-truncate"><?= esc($top3[1]['nama_lengkap'] ?? 'User #' . $top3[1]['user_id']); ?></h5>
                  <small class="text-muted d-block mb-2"><?= esc($top3[1]['prodi'] ?? '-'); ?></small>
                  <span class="badge bg-light text-dark border"><?= $top3[1]['total_skor']; ?> Poin</span>
                </div>
            </div>
        <?php endif; ?>

        <!-- JUARA 1 (Index 0) - Posisi Tengah (Lebih Besar/Tinggi) -->
        <?php if (isset($top3[0])): ?>
            <div class="order-0 order-md-1 mb-4 mb-md-0">
                <div class="border rounded-4 p-4 shadow text-center position-relative transform-scale" 
                     style="width:200px; background-color:#e0f2f1; border:2px solid #004d40;">
                  <!-- Mahkota Icon -->
                  <i class="bi bi-trophy-fill text-warning fs-3 position-absolute start-50 translate-middle-x" style="top: -25px;"></i>
                  
                  <div class="fw-bold text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 shadow" 
                       style="background-color:#004d40; width: 50px; height: 50px; font-size: 1.2rem;">#1</div>
                  <h5 class="fw-bold mb-1 text-truncate"><?= esc($top3[0]['nama_lengkap'] ?? 'User #' . $top3[0]['user_id']); ?></h5>
                  <small class="text-muted d-block mb-2"><?= esc($top3[0]['prodi'] ?? '-'); ?></small>
                  <span class="badge bg-warning text-dark shadow-sm"><?= $top3[0]['total_skor']; ?> Poin</span>
                </div>
            </div>
        <?php endif; ?>

        <!-- JUARA 3 (Index 2) - Posisi Kanan -->
        <?php if (isset($top3[2])): ?>
            <div class="order-2">
                <div class="border rounded-4 p-4 shadow-sm text-center position-relative" 
                     style="width:180px; background-color:#fff; border-top: 4px solid #4db6ac;">
                  <div class="fw-bold text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm" 
                       style="background-color:#4db6ac; width: 40px; height: 40px; margin-top: -35px;">#3</div>
                  <h5 class="fw-bold mb-1 text-truncate"><?= esc($top3[2]['nama_lengkap'] ?? 'User #' . $top3[2]['user_id']); ?></h5>
                  <small class="text-muted d-block mb-2"><?= esc($top3[2]['prodi'] ?? '-'); ?></small>
                  <span class="badge bg-light text-dark border"><?= $top3[2]['total_skor']; ?> Poin</span>
                </div>
            </div>
        <?php endif; ?>

      </div>

      <!-- RANKING LIST (RANK 4 KE ATAS) -->
      <?php if (!empty($rest)): ?>
          <div class="row justify-content-center">
            <div class="col-md-8">
                <h5 class="fw-bold text-start mb-3 ps-2 border-start border-4 border-success">Peringkat Berikutnya</h5>
                
                <div class="list-group shadow-sm rounded-4">
                  <?php foreach ($rest as $index => $r): ?>
                    <div class="list-group-item border-0 border-bottom p-3 d-flex align-items-center">
                      <div class="fw-bold text-secondary me-3" style="width: 30px;">
                        #<?= $index + 4; ?>
                      </div>
                      
                      <!-- Avatar Initials (Optional) -->
                      <div class="rounded-circle bg-light text-success d-flex align-items-center justify-content-center me-3 fw-bold" 
                           style="width: 40px; height: 40px;">
                           <?= substr($r['nama_lengkap'] ?? 'U', 0, 1); ?>
                      </div>

                      <div class="text-start flex-grow-1">
                        <h6 class="fw-bold mb-0 text-dark"><?= esc($r['nama_lengkap'] ?? 'User #' . $r['user_id']); ?></h6>
                        <small class="text-muted"><?= esc($r['prodi'] ?? '-'); ?> • <?= esc($r['jurusan'] ?? ''); ?></small>
                      </div>
                      
                      <div class="fw-bold text-success">
                        <?= $r['total_skor']; ?> <span class="small text-muted fw-normal">Poin</span>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
            </div>
          </div>
      <?php endif; ?>

  <?php endif; ?>

</div>

<style>
    /* Efek scale sedikit untuk Juara 1 */
    .transform-scale {
        transform: scale(1.05);
        z-index: 2;
    }
    @media (max-width: 768px) {
        .transform-scale { transform: scale(1); }
    }
</style>

<?= $this->endSection(); ?>