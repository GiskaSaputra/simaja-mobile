<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold">Kelola Jadwal</h1>
    <a href="<?= base_url('admin/jadwal/create'); ?>" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-plus-lg me-1"></i> Tambah Jadwal Baru
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">Judul Kegiatan</th>
                        <th class="py-3 border-0">Tanggal & Waktu</th>
                        <th class="py-3 border-0">Kuota</th>
                        <th class="px-4 py-3 border-0 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jadwal)) : ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                Belum ada data jadwal. Silakan tambah baru.
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($jadwal as $j) : ?>
                            <tr>
                                <td class="px-4 py-3">
                                    <h6 class="mb-0 fw-bold"><?= esc($j['judul']); ?></h6>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                        <?= esc($j['deskripsi']); ?>
                                    </small>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold"><i class="bi bi-calendar-event me-2 text-success"></i><?= date('d M Y', strtotime($j['tanggal'])); ?></span>
                                        <small class="text-muted mt-1"><i class="bi bi-clock me-2 text-primary"></i><?= date('H:i', strtotime($j['waktu_mulai'])); ?> - <?= date('H:i', strtotime($j['waktu_selesai'])); ?></small>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <?php 
                                        $persen = ($j['kuota'] > 0) ? ($j['terisi'] / $j['kuota']) * 100 : 0;
                                        $warna = $persen >= 100 ? 'bg-danger' : ($persen >= 80 ? 'bg-warning' : 'bg-success');
                                    ?>
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="fw-bold me-2"><?= $j['terisi']; ?></span>
                                        <span class="text-muted small">/ <?= $j['kuota']; ?></span>
                                    </div>
                                    <div class="progress" style="height: 5px; width: 100px;">
                                        <div class="progress-bar <?= $warna; ?>" role="progressbar" style="width: <?= $persen; ?>%"></div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <a href="<?= base_url('admin/jadwal/edit/' . $j['id']); ?>" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= base_url('admin/jadwal/delete/' . $j['id']); ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus jadwal ini? Data pendaftaran terkait juga mungkin akan terhapus.');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>