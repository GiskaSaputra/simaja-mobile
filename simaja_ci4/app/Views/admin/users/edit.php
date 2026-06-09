<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Edit User</h1>
    <a href="<?= base_url('admin/users'); ?>" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                
                <form action="<?= base_url('admin/users/update/' . $user['id']); ?>" method="post">
                    <?= csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control rounded-3" value="<?= esc($user['email']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control rounded-3" value="<?= esc($user['username']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control rounded-3" placeholder="Biarkan kosong jika tidak ingin mengganti password">
                        <div class="form-text text-muted">Isi hanya jika ingin mereset password user ini.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Role (Peran)</label>
                        <select name="role" class="form-select rounded-3">
                            <option value="user" <?= $user['role'] == 'user' ? 'selected' : ''; ?>>User Biasa (Mahasiswa)</option>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>Administrator</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning text-dark rounded-pill py-2 fw-bold">
                            <i class="bi bi-pencil-square me-2"></i> Update User
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>