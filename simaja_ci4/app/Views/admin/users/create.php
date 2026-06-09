<?= $this->extend('layout/admin_layout'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Tambah User Baru</h1>
    <a href="<?= base_url('admin/users'); ?>" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                
                <form action="<?= base_url('admin/users/store'); ?>" method="post">
                    <?= csrf_field(); ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control rounded-3" placeholder="contoh@email.com" required value="<?= old('email'); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control rounded-3" placeholder="Username tanpa spasi" required value="<?= old('username'); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control rounded-3" placeholder="Minimal 6 karakter" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Role (Peran)</label>
                        <select name="role" class="form-select rounded-3">
                            <option value="user">User Biasa (Mahasiswa)</option>
                            <option value="admin">Administrator</option>
                        </select>
                        <div class="form-text">Admin memiliki akses penuh ke dashboard ini.</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold">
                            <i class="bi bi-save me-2"></i> Simpan User
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>