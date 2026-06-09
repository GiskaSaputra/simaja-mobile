<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2 fw-bold">Kelola User</h1>
    <a href="<?= base_url('admin/users/create'); ?>" class="btn btn-primary rounded-pill px-4">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah User
    </a>
</div>

<!-- Flash Message -->
<?php if (session()->getFlashdata('pesan')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('pesan'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Bergabung</th>
                        <th class="text-end px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u) : ?>
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                        <!-- Perbaikan: Gunakan ->username bukan ['username'] -->
                                        <?= strtoupper(substr($u->username, 0, 1)); ?>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold"><?= esc($u->username); ?></h6>
                                        <small class="text-muted"><?= esc($u->email); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <!-- Perbaikan: Gunakan ->role -->
                                <?php 
                                    // Handle jika role tidak ada di entitas (biasanya diakses manual atau via group)
                                    // Asumsi kita pakai kolom manual 'role' di tabel users
                                    $role = $u->role ?? 'user'; 
                                ?>
                                <?php if ($role == 'admin'): ?>
                                    <span class="badge bg-danger">ADMIN</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-dark">USER</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Perbaikan: Gunakan ->active -->
                                <?php if ($u->active): ?>
                                    <span class="text-success small fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Aktif</span>
                                <?php else: ?>
                                    <span class="text-muted small fw-bold"><i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Non-aktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small">
                                <!-- Perbaikan: Gunakan ->created_at -->
                                <?= date('d M Y', strtotime($u->created_at)); ?>
                            </td>
                            <td class="text-end px-4">
                            
                                <?php if (user_id() != $u->id): ?>
                                    <a href="<?= base_url('admin/users/delete/' . $u->id); ?>" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus user ini? Semua data terkait (profil, nilai, progress) akan hilang permanen.');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary" disabled title="Tidak bisa hapus diri sendiri"><i class="bi bi-trash"></i></button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>