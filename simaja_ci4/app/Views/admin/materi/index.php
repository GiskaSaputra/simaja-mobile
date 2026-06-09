<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold">Kelola Materi</h1>
    <a href="<?= base_url('admin/materi/create'); ?>" class="btn btn-success rounded-pill px-4">
        <i class="bi bi-plus-lg me-1"></i> Tambah Materi Baru
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">Judul Materi</th>
                        <th class="py-3 border-0">Deskripsi</th>
                        <th class="py-3 border-0 text-center">Total Pertemuan</th>
                        <th class="px-4 py-3 border-0 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($materi)) : ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                                Belum ada data materi.
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($materi as $m) : ?>
                            <tr>
                                <td class="px-4 py-3 fw-bold"><?= esc($m['judul']); ?></td>
                                <td class="py-3 text-muted small"><?= esc($m['deskripsi']); ?></td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-light text-dark border px-3"><?= esc($m['total_pertemuan']); ?> Sesi</span>
                                </td>
                                <td class="px-4 py-3 text-end">
                                    <!-- TOMBOL BARU: KELOLA PERTEMUAN -->
                                    <a href="<?= base_url('admin/pertemuan/' . $m['id']); ?>" class="btn btn-sm btn-info text-white me-1" title="Kelola Pertemuan">
                                        <i class="bi bi-list-ul"></i> Detail
                                    </a>
                                    
                                    <a href="<?= base_url('admin/materi/edit/' . $m['id']); ?>" class="btn btn-sm btn-outline-secondary me-1" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="<?= base_url('admin/materi/delete/' . $m['id']); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus materi ini? Data pertemuan dan progress user juga akan hilang.');" title="Hapus">
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