<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<!-- Header Pencarian -->
<div class="container mt-5">
  <div class="text-center mb-5">
    <h4 class="fw-bold">Hasil Pencarian untuk: <span style="color: #009688;">"<?= esc($query) ?>"</span></h4>
    <p class="text-muted">Menampilkan hasil dari Materi dan Jadwal Study Jam</p>
  </div>
  
  <div class="row justify-content-center">
    <div class="col-lg-10">

      <!-- BAGIAN 1: MATERI -->
      <div class="mb-5">
        <h5 class="mb-3 fw-bold border-bottom pb-2" style="color: #004d40;">
            <i class="bi bi-book-half me-2"></i> Materi
        </h5>
        
        <?php if (count($materi) > 0): ?>
          <div class="list-group shadow-sm">
            <?php foreach ($materi as $m): ?>
              <div class="list-group-item list-group-item-action border-0 border-bottom p-4">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="fw-bold mb-1 text-dark"><?= esc($m['judul']); ?></h6>
                    <small class="text-muted"><?= esc($m['deskripsi']); ?></small>
                  </div>
                  <div class="ms-3">
                    <a href="<?= base_url('materi/detail/' . $m['id']); ?>" 
                       class="btn btn-sm btn-outline-theme rounded-pill px-3">
                       Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="alert alert-light text-center border-0 shadow-sm py-4">
            <i class="bi bi-search text-muted mb-2 fs-4 d-block"></i>
            <span class="text-muted">Tidak ada materi yang cocok dengan kata kunci tersebut.</span>
          </div>
        <?php endif; ?>
      </div>
      

      <!-- BAGIAN 2: JADWAL STUDY JAM -->
      <div class="mb-5">
        <h5 class="mb-3 fw-bold border-bottom pb-2" style="color: #004d40;">
            <i class="bi bi-calendar2-week me-2"></i> Jadwal Study Jam
        </h5>

        <?php if (count($jadwal) > 0): ?>
          <div class="list-group shadow-sm">
            <?php foreach ($jadwal as $j): 
              // Format Data
              $tanggal = date('d F Y', strtotime($j['tanggal']));
              $waktu_mulai = date('H.i', strtotime($j['waktu_mulai']));
              $waktu_selesai = date('H.i', strtotime($j['waktu_selesai']));
              
              // Status Penuh
              $isFull = $j['terisi'] >= $j['kuota'];
            ?>
              <div class="list-group-item list-group-item-action border-0 border-bottom p-4">
                <div class="row align-items-center">
                    
                    <!-- Info Utama -->
                    <div class="col-md-8 mb-3 mb-md-0">
                        <h6 class="fw-bold mb-1 text-dark"><?= esc($j['judul']); ?></h6>
                        <p class="text-muted small mb-2"><?= esc($j['deskripsi']); ?></p>
                        
                        <div class="d-flex flex-wrap gap-3 small">
                            <span class="text-muted"><i class="bi bi-calendar-event me-1 text-success"></i> <?= $tanggal ?></span>
                            <span class="text-muted"><i class="bi bi-clock me-1 text-primary"></i> <?= $waktu_mulai ?> - <?= $waktu_selesai ?> WIB</span>
                            <span class="text-muted"><i class="bi bi-people me-1 text-warning"></i> <?= esc($j['terisi']); ?> / <?= esc($j['kuota']); ?> Peserta</span>
                        </div>
                    </div>

                    <!-- Tombol & Status -->
                    <div class="col-md-4 text-md-end">
                        <?php if($isFull): ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle mb-2 mb-md-0 me-md-2">
                                <i class="bi bi-x-circle me-1"></i> Penuh
                            </span>
                        <?php else: ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle mb-2 mb-md-0 me-md-2">
                                <i class="bi bi-check-circle me-1"></i> Tersedia
                            </span>
                        <?php endif; ?>

                        <a href="<?= base_url('jadwal'); ?>" class="btn btn-sm btn-theme rounded-pill px-4 text-white">
                            Lihat Jadwal
                        </a>
                    </div>

                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="alert alert-light text-center border-0 shadow-sm py-4">
            <i class="bi bi-calendar-x text-muted mb-2 fs-4 d-block"></i>
            <span class="text-muted">Tidak ada jadwal yang cocok dengan kata kunci tersebut.</span>
          </div>
        <?php endif; ?>
      </div>

    </div>
  </div>
</div>

<style>
    /* Custom Button Theme (Solid) */
    .btn-theme {
        background: linear-gradient(90deg, #004d40, #00796b);
        border: none;
        transition: all 0.2s ease;
    }
    .btn-theme:hover {
        background: linear-gradient(90deg, #00382e, #005c50);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    /* Custom Button Outline */
    .btn-outline-theme {
        color: #004d40;
        border-color: #004d40;
        background-color: transparent;
    }
    .btn-outline-theme:hover {
        background-color: #004d40;
        color: white;
    }

    /* List Item Hover Effect */
    .list-group-item-action:hover {
        background-color: #f8fcfb; /* Hijau sangat muda */
    }
</style>

<?= $this->endSection(); ?>