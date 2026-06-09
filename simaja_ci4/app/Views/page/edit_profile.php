<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<!-- Header Section -->
<section class="text-center text-white py-5"
         style="background: linear-gradient(135deg, #004d40, #009688);">
  <div class="container">
    <h1 class="fw-bold mb-0">Edit Profil</h1>
  </div>
</section>

<!-- Form Container -->
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8"> <!-- Ukuran kolom disesuaikan agar proporsional -->
      <div class="p-4 border rounded-4 shadow-sm bg-white">

        <h4 class="fw-bold mb-3 border-bottom pb-3">Edit Informasi Profil</h4>

        <!-- FORM UPDATE PROFIL -->
        <form action="<?= base_url('profile/update'); ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field(); ?>
          
          <!-- Hidden Input: Foto Lama (Penting untuk Controller) -->
          <input type="hidden" name="foto_lama" value="<?= $profile['foto'] ?? 'default.png'; ?>">

          <!-- Foto Profil -->
          <div class="text-center mb-4">
            <?php 
                $foto = $profile['foto'] ?? 'default.png';
                // Cek apakah URL eksternal atau file lokal
                $fotoUrl = (filter_var($foto, FILTER_VALIDATE_URL)) ? $foto : base_url('img/' . $foto);
            ?>
            <img src="<?= $fotoUrl; ?>"
                 class="rounded-circle shadow-sm mb-3"
                 style="width: 130px; height: 130px; object-fit: cover; border: 3px solid #009688;">

            <div class="w-75 mx-auto">
                <input type="file" name="foto" class="form-control mt-2" accept="image/*">
                <div class="form-text text-muted small">Format: JPG, PNG. Maks: 2MB</div>
            </div>
          </div>

          <!-- INPUT: NAMA -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Masukkan Nama</label>
            <input type="text" name="nama_lengkap" class="form-control rounded-3 p-2"
                   value="<?= esc($profile['nama_lengkap'] ?? user()->username) ?>" required>
          </div>

          <!-- INPUT: NIM -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Masukkan NIM</label>
            <input type="text" name="nim" class="form-control rounded-3 p-2"
                   value="<?= esc($profile['nim'] ?? '') ?>">
          </div>

          <!-- INPUT: KELAS -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Masukkan Kelas</label>
            <input type="text" name="kelas" class="form-control rounded-3 p-2"
                   value="<?= esc($profile['kelas'] ?? '') ?>">
          </div>

          <!-- INPUT: PRODI -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Masukkan Prodi</label>
            <input type="text" name="prodi" class="form-control rounded-3 p-2"
                   value="<?= esc($profile['prodi'] ?? '') ?>">
          </div>

          <!-- INPUT: JURUSAN -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Masukkan Jurusan</label>
            <input type="text" name="jurusan" class="form-control rounded-3 p-2"
                   value="<?= esc($profile['jurusan'] ?? '') ?>">
          </div>

          <!-- INPUT: SEMESTER -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Masukkan Semester</label>
            <input type="text" name="semester" class="form-control rounded-3 p-2"
                   value="<?= esc($profile['semester'] ?? '') ?>">
          </div>

          <!-- INPUT: JENIS KELAMIN -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Masukkan Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select rounded-3 p-2">
              <option value="">Pilih Jenis Kelamin</option>
              <option value="Laki-laki" <?= ($profile['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'selected' : ''; ?>>
                Laki-laki
              </option>
              <option value="Perempuan" <?= ($profile['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : ''; ?>>
                Perempuan
              </option>
            </select>
          </div>

          <!-- INPUT: ALAMAT -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Masukkan Alamat</label>
            <input type="text" name="alamat" class="form-control rounded-3 p-2"
                   value="<?= esc($profile['alamat'] ?? '') ?>">
          </div>

          <!-- TOMBOL AKSI -->
          <div class="d-flex justify-content-end mt-4 pt-3 border-top">
            <a href="<?= base_url('/profile'); ?>" class="btn btn-outline-secondary px-4 me-2 rounded-3">Batal</a>
            <button type="submit" class="btn text-white px-4 rounded-3" style="background-color: #004d40;">Simpan</button>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>