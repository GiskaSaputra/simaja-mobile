<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<?php
// --- HELPER FUNCTIONS ---

/**
 * 1. Deteksi ID Youtube yang Lebih Cerdas
 * Mampu menangani link dengan parameter tambahan (?si=..., &feature=...)
 */
function getYoutubeId($url) {
    $url = trim($url);
    if (empty($url)) return false;

    // Coba parse URL secara struktural
    $parts = parse_url($url);
    
    if ($parts === false || !isset($parts['host'])) return false;
    
    $host = strtolower($parts['host']);
    $path = isset($parts['path']) ? $parts['path'] : '';
    $query = isset($parts['query']) ? $parts['query'] : '';
    
    // CASE 1: Link Pendek (youtu.be/ID)
    // Contoh: https://youtu.be/0seNHpFGTS4?si=MMixKtGI9nyDsUHX
    if (strpos($host, 'youtu.be') !== false) {
        $pathParts = explode('/', trim($path, '/'));
        return $pathParts[0] ?? false;
    }
    
    // CASE 2: Link Panjang (youtube.com)
    if (strpos($host, 'youtube') !== false) {
        // Format query: v=ID (https://www.youtube.com/watch?v=0seNHpFGTS4&si=...)
        parse_str($query, $queryParams);
        if (isset($queryParams['v'])) {
            return $queryParams['v'];
        }
        
        // Format path: /embed/ID, /v/ID, /shorts/ID, /live/ID
        if (preg_match('%^/(?:embed|v|shorts|live)/([a-zA-Z0-9_-]{11})%', $path, $matches)) {
            return $matches[1];
        }
    }
    
    return false;
}

/**
 * 2. Deteksi apakah string adalah file PDF (berakhiran .pdf)
 */
function isPdf($url) {
    return (substr(strtolower(trim($url)), -4) === '.pdf');
}

// --- LOGIKA UTAMA ---
$isiMateri = $pertemuan['isi_materi'];
$videoId = getYoutubeId($isiMateri);
$isPdf   = isPdf($isiMateri);

// Jika PDF adalah path lokal (misal: uploads/materi.pdf), tambahkan base_url
$pdfUrl = $isiMateri;
if ($isPdf && !filter_var($isiMateri, FILTER_VALIDATE_URL)) {
    $pdfUrl = base_url($isiMateri);
}
?>

<!-- NAVBAR NAVIGASI ATAS (Sticky) -->
<div class="bg-white border-bottom py-3 sticky-top shadow-sm" style="z-index: 1020; top: 0;">
    <div class="container d-flex align-items-center justify-content-between">
        <!-- Tombol Kembali -->
        <a href="<?= base_url('materi/detail/' . $materi['id']); ?>" class="text-decoration-none text-muted fw-semibold d-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> <span class="d-none d-md-inline">Daftar Materi</span>
        </a>
        
        <!-- Info Materi Tengah -->
        <div class="text-center">
            <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                <?= esc($materi['judul']); ?>
            </small>
            <span class="badge bg-light text-dark border rounded-pill mt-1">
                <i class="bi bi-book-half me-1"></i> Pertemuan <?= esc($pertemuan['urutan']); ?>
            </span>
        </div>

        <!-- Spacer Kosong -->
        <div style="width: 100px;" class="d-none d-md-block"></div>
    </div>
</div>

<!-- KONTEN UTAMA -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            
            <!-- Judul Pertemuan -->
            <h2 class="fw-bold mb-4 text-center text-dark"><?= esc($pertemuan['judul_pertemuan']); ?></h2>

            <!-- KONTAINER UTAMA (VIDEO / PDF / TEXT) -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
                
                <!-- 1. JIKA VIDEO YOUTUBE -->
                <?php if ($videoId): ?>
                    <div class="ratio ratio-16x9 bg-dark">
                        <iframe src="https://www.youtube.com/embed/<?= $videoId; ?>?rel=0&modestbranding=1" 
                                title="YouTube video player" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                        </iframe>
                    </div>

                <!-- 2. JIKA FILE PDF -->
                <?php elseif ($isPdf): ?>
                    <div class="bg-light border-bottom" style="height: 80vh; min-height: 500px;">
                        <object data="<?= $pdfUrl; ?>" type="application/pdf" width="100%" height="100%">
                            <div class="d-flex align-items-center justify-content-center h-100 flex-column text-center p-4">
                                <i class="bi bi-file-earmark-pdf display-1 text-danger mb-3"></i>
                                <p class="mb-3">Browser Anda tidak mendukung preview PDF.</p>
                                <a href="<?= $pdfUrl; ?>" target="_blank" class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-download me-2"></i> Download PDF
                                </a>
                            </div>
                        </object>
                    </div>
                    <div class="bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Gunakan mode layar penuh untuk pengalaman lebih baik.</small>
                        <a href="<?= $pdfUrl; ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka di Tab Baru
                        </a>
                    </div>

                <!-- 3. JIKA TEKS BIASA -->
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center text-white flex-column position-relative overflow-hidden py-5" 
                            style="background: linear-gradient(135deg, #004d40, #00796b); min-height: 200px;">
                        <i class="bi bi-file-text display-1 text-white opacity-25 position-absolute" style="transform: rotate(-15deg); left: -20px; bottom: -20px;"></i>
                        <div class="text-center z-1">
                            <i class="bi bi-book-fill display-3 mb-3 text-white"></i>
                            <h4 class="fw-light">Mode Baca</h4>
                            <p class="small text-white-50 mb-0">Silakan pelajari modul di bawah ini</p>
                        </div>
                    </div>
                <?php endif; ?>


                <!-- AREA DESKRIPSI (Teks di bawah Video/PDF) -->
                <div class="card-body p-4 p-lg-5 bg-white">
                    
                    <ul class="nav nav-tabs mb-4 border-bottom-0" id="belajarTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-semibold text-dark border-bottom border-3 border-success bg-transparent" 
                                    id="deskripsi-tab" data-bs-toggle="tab" data-bs-target="#deskripsi" type="button" role="tab">
                                <i class="bi bi-info-circle me-2"></i>Deskripsi Materi
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="belajarTabContent">
                        <div class="tab-pane fade show active" id="deskripsi" role="tabpanel">
                            
                            <div class="content-text text-secondary" style="line-height: 1.8; font-size: 1.05rem; text-align: justify;">
                                <?php if (!$videoId && !$isPdf): ?>
                                    <h5 class="text-dark fw-bold mb-3">Isi Materi:</h5>
                                    <?= nl2br(esc($pertemuan['isi_materi'])); ?>
                                <?php elseif ($isPdf): ?>
                                    <p>Silakan baca dokumen PDF di atas dengan seksama. Anda dapat mengunduhnya jika perlu.</p>
                                <?php else: ?>
                                    <p>Pelajari materi melalui video di atas.</p>
                                <?php endif; ?>
                            </div>

                            <!-- Tips Belajar -->
                            <div class="alert alert-light border-start border-4 border-success shadow-sm d-flex align-items-start mt-5 p-3 rounded-3">
                                <i class="bi bi-lightbulb-fill text-success fs-4 me-3 mt-1"></i>
                                <div>
                                    <strong class="text-dark d-block mb-1">Tips Belajar:</strong>
                                    <span class="text-muted small">Catat poin penting. Jika kurang jelas, ulangi materi di atas.</span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <!-- TOMBOL NAVIGASI (PREV / NEXT) -->
            <div class="d-flex justify-content-between align-items-center py-4 border-top mt-5">
                
                <!-- TOMBOL PREV (Link Biasa) -->
                <?php if ($prev): ?>
                    <a href="<?= base_url('materi/belajar/' . $prev['id']); ?>" class="btn btn-outline-secondary rounded-pill px-4 py-2 d-flex align-items-center transition-hover">
                        <i class="bi bi-chevron-left me-2"></i> 
                        <div class="text-start lh-1">
                            <span class="d-block small text-uppercase fw-bold" style="font-size: 0.65rem;">Sebelumnya</span>
                            <span class="d-none d-sm-inline">Pertemuan <?= $prev['urutan']; ?></span>
                            <span class="d-inline d-sm-none">Prev</span>
                        </div>
                    </a>
                <?php else: ?>
                    <button class="btn btn-outline-secondary rounded-pill px-4 py-2 opacity-50" disabled>
                        <i class="bi bi-chevron-left me-2"></i> Awal
                    </button>
                <?php endif; ?>

                <!-- TOMBOL NEXT / SELESAI (FORM UNTUK PROGRESS) -->
                <form action="<?= base_url('materi/complete'); ?>" method="post" style="display: inline;">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="pertemuan_id" value="<?= $pertemuan['id']; ?>">

                    <?php if ($next): ?>
                        <input type="hidden" name="next_id" value="<?= $next['id']; ?>">
                        <button type="submit" class="btn text-white rounded-pill px-4 py-2 shadow d-flex align-items-center transition-hover" style="background: linear-gradient(90deg,#004d40,#00796b); border: none;">
                            <div class="text-end lh-1 me-2">
                                <span class="d-block small text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">Selesai & Lanjut</span>
                                <span class="d-none d-sm-inline">Pertemuan <?= $next['urutan']; ?></span>
                                <span class="d-inline d-sm-none">Next</span>
                            </div>
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-success rounded-pill px-4 py-2 shadow d-flex align-items-center transition-hover border-0">
                            <div class="text-end lh-1 me-2">
                                <span class="d-block small text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem;">Selesai</span>
                                <span>Materi Lengkap</span>
                            </div>
                            <i class="bi bi-check-circle-fill ms-2"></i>
                        </button>
                    <?php endif; ?>
                </form>

            </div>

        </div>
    </div>
</div>

<style>
    .transition-hover { transition: all 0.2s ease-in-out; }
    .transition-hover:hover { transform: translateY(-3px); box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important; }
    .nav-tabs .nav-link.active { background-color: transparent; border-color: transparent transparent #198754 transparent; }
    .nav-tabs .nav-link:hover { border-color: transparent transparent #ddd transparent; }
</style>

<?= $this->endSection(); ?>