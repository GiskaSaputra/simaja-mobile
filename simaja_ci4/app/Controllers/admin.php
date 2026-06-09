<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JadwalModel;
use App\Models\MateriModel;
use App\Models\PertemuanModel;
use App\Models\SoalModel;
use App\Models\JawabanModel;
use App\Models\HomeContentModel;
// Gunakan model User bawaan Myth/Auth atau buat UserModel sendiri jika perlu kustomisasi
use Myth\Auth\Models\UserModel; 
use Myth\Auth\Entities\User;

class Admin extends BaseController
{
    public function __construct()
    {
        // Load Helper Auth & Form agar fungsi logged_in() dan form validation bisa dipakai
        helper(['auth', 'form', 'text']);
    }

    // =======================================================================
    // 1. DASHBOARD UTAMA
    // =======================================================================
    public function dashboard()
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $db = \Config\Database::connect();
        
        // Hitung Statistik
        $totalJadwal = $db->table('jadwal')->countAllResults();
        $totalMateri = $db->table('materi')->countAllResults();
        $totalUser   = $db->table('users')->countAllResults();
        $totalSoal   = $db->table('soal')->countAllResults();

        $data = [
            'title' => 'Dashboard Overview',
            'stats' => [
                'jadwal' => $totalJadwal,
                'materi' => $totalMateri,
                'user'   => $totalUser,
                'soal'   => $totalSoal
            ]
        ];

        return view('admin/dashboard', $data);
    }

    // =======================================================================
    // 2. MANAJEMEN JADWAL (CRUD)
    // =======================================================================
    
    // READ: Tampilkan daftar jadwal
    public function jadwal()
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $jadwalModel = new JadwalModel();
        $data = [
            'title' => 'Kelola Jadwal',
            'jadwal' => $jadwalModel->orderBy('tanggal', 'DESC')->findAll()
        ];
        
        return view('admin/jadwal/index', $data);
    }

    // CREATE: Form Tambah
    public function jadwalCreate()
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $data = [
            'title' => 'Tambah Jadwal Baru',
            'validation' => \Config\Services::validation()
        ];
        
        return view('admin/jadwal/create', $data);
    }

    // STORE: Proses Simpan
    public function jadwalStore()
    {
        if (!logged_in()) return redirect()->to('/login');
        
        if (!$this->validate([
            'judul' => 'required|min_length[3]',
            'tanggal' => 'required|valid_date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'kuota' => 'required|numeric'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Cek kembali isian form Anda.');
        }

        $jadwalModel = new JadwalModel();
        $jadwalModel->save([
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'tanggal' => $this->request->getPost('tanggal'),
            'waktu_mulai' => $this->request->getPost('waktu_mulai'),
            'waktu_selesai' => $this->request->getPost('waktu_selesai'),
            'kuota' => $this->request->getPost('kuota'),
            'terisi' => 0 
        ]);

        return redirect()->to('/admin/jadwal')->with('pesan', 'Jadwal berhasil ditambahkan!');
    }

    // EDIT: Form Edit
    public function jadwalEdit($id)
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $jadwalModel = new JadwalModel();
        $jadwal = $jadwalModel->find($id);

        if (!$jadwal) return redirect()->to('/admin/jadwal')->with('error', 'Data tidak ditemukan.');

        $data = [
            'title' => 'Edit Jadwal',
            'jadwal' => $jadwal,
            'validation' => \Config\Services::validation()
        ];
        
        return view('admin/jadwal/edit', $data);
    }

    // UPDATE: Proses Update
    public function jadwalUpdate($id)
    {
        if (!logged_in()) return redirect()->to('/login');
        
        if (!$this->validate([
            'judul' => 'required|min_length[3]',
            'tanggal' => 'required|valid_date',
            'kuota' => 'required|numeric'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Gagal update. Cek isian form.');
        }

        $jadwalModel = new JadwalModel();
        $jadwalModel->update($id, [
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'tanggal' => $this->request->getPost('tanggal'),
            'waktu_mulai' => $this->request->getPost('waktu_mulai'),
            'waktu_selesai' => $this->request->getPost('waktu_selesai'),
            'kuota' => $this->request->getPost('kuota')
        ]);

        return redirect()->to('/admin/jadwal')->with('pesan', 'Jadwal berhasil diperbarui.');
    }

    // DELETE: Hapus Data
    public function jadwalDelete($id)
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $jadwalModel = new JadwalModel();
        $jadwal = $jadwalModel->find($id);

        if ($jadwal) {
            $jadwalModel->delete($id);
            return redirect()->to('/admin/jadwal')->with('pesan', 'Jadwal berhasil dihapus.');
        } else {
            return redirect()->to('/admin/jadwal')->with('error', 'Jadwal tidak ditemukan.');
        }
    }

    // =======================================================================
    // 3. MANAJEMEN MATERI (CRUD)
    // =======================================================================
    
    // READ
    public function materi()
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $materiModel = new MateriModel();
        $data = [
            'title' => 'Kelola Materi',
            'materi' => $materiModel->findAll()
        ];
        
        return view('admin/materi/index', $data);
    }

    // CREATE
    public function materiCreate()
    {
        if (!logged_in()) return redirect()->to('/login');
        return view('admin/materi/create', ['title' => 'Tambah Materi']);
    }

    // STORE
    public function materiStore()
    {
        if (!logged_in()) return redirect()->to('/login');
        
        if (!$this->validate([
            'judul' => 'required|min_length[3]',
            'total_pertemuan' => 'required|numeric'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid.');
        }

        $materiModel = new MateriModel();
        $materiModel->save([
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'total_pertemuan' => $this->request->getPost('total_pertemuan')
        ]);

        return redirect()->to('/admin/materi')->with('pesan', 'Materi berhasil ditambahkan.');
    }

    // EDIT
    public function materiEdit($id)
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $materiModel = new MateriModel();
        $materi = $materiModel->find($id);

        if (!$materi) return redirect()->to('/admin/materi')->with('error', 'Data tidak ditemukan.');

        return view('admin/materi/edit', ['title' => 'Edit Materi', 'materi' => $materi]);
    }

    // UPDATE
    public function materiUpdate($id)
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $materiModel = new MateriModel();
        $materiModel->update($id, [
            'judul' => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'total_pertemuan' => $this->request->getPost('total_pertemuan')
        ]);

        return redirect()->to('/admin/materi')->with('pesan', 'Materi berhasil diperbarui.');
    }

    // DELETE
    public function materiDelete($id)
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $materiModel = new MateriModel();
        // Hapus juga pertemuan terkait (opsional, tapi disarankan untuk kebersihan DB)
        $pertemuanModel = new PertemuanModel();
        $pertemuanModel->where('materi_id', $id)->delete();
        
        $materiModel->delete($id);

        return redirect()->to('/admin/materi')->with('pesan', 'Materi berhasil dihapus.');
    }

    // =======================================================================
    // 4. MANAJEMEN PERTEMUAN (Detail Materi)
    // =======================================================================
    
    public function pertemuan($materiId)
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $materiModel = new MateriModel();
        $pertemuanModel = new PertemuanModel();
        
        $materi = $materiModel->find($materiId);
        if (!$materi) return redirect()->to('/admin/materi')->with('error', 'Materi tidak ditemukan');

        $data = [
            'title' => 'Kelola Pertemuan - ' . $materi['judul'],
            'materi' => $materi,
            'pertemuan' => $pertemuanModel->where('materi_id', $materiId)->orderBy('urutan', 'ASC')->findAll()
        ];
        
        return view('admin/pertemuan/index', $data);
    }

    public function pertemuanCreate($materiId)
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $materiModel = new MateriModel();
        $materi = $materiModel->find($materiId);
        
        $data = [
            'title' => 'Tambah Pertemuan Baru',
            'materi' => $materi,
            'validation' => \Config\Services::validation()
        ];
        
        return view('admin/pertemuan/create', $data);
    }

    public function pertemuanStore()
    {
        if (!logged_in()) return redirect()->to('/login');

        $materiId = $this->request->getPost('materi_id');
        
        if (!$this->validate([
            'judul_pertemuan' => 'required',
            'isi_materi' => 'required', // Link Youtube atau PDF
            'urutan' => 'required|numeric'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Data tidak lengkap');
        }

        $pertemuanModel = new PertemuanModel();
        $pertemuanModel->save([
            'materi_id' => $materiId,
            'judul_pertemuan' => $this->request->getPost('judul_pertemuan'),
            'isi_materi' => $this->request->getPost('isi_materi'),
            'urutan' => $this->request->getPost('urutan')
        ]);

        return redirect()->to('/admin/pertemuan/' . $materiId)->with('pesan', 'Pertemuan berhasil ditambahkan');
    }
    
    public function pertemuanEdit($id)
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $pertemuanModel = new PertemuanModel();
        $pertemuan = $pertemuanModel->find($id);

        if (!$pertemuan) {
            return redirect()->back()->with('error', 'Data pertemuan tidak ditemukan.');
        }

        // Ambil data materi induk untuk judul/navigasi
        $materiModel = new MateriModel();
        $materi = $materiModel->find($pertemuan['materi_id']);

        $data = [
            'title' => 'Edit Pertemuan',
            'pertemuan' => $pertemuan,
            'materi' => $materi,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/pertemuan/edit', $data);
    }

    public function pertemuanUpdate($id)
    {
        if (!logged_in()) return redirect()->to('/login');

        $pertemuanModel = new PertemuanModel();
        $pertemuan = $pertemuanModel->find($id);

        if (!$pertemuan) return redirect()->back()->with('error', 'Data tidak ditemukan.');
        
        // Validasi
        if (!$this->validate([
            'judul_pertemuan' => 'required',
            'isi_materi' => 'required',
            'urutan' => 'required|numeric'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Gagal update. Cek isian form.');
        }

        $pertemuanModel->update($id, [
            'judul_pertemuan' => $this->request->getPost('judul_pertemuan'),
            'isi_materi' => $this->request->getPost('isi_materi'),
            'urutan' => $this->request->getPost('urutan')
        ]);

        return redirect()->to('/admin/pertemuan/' . $pertemuan['materi_id'])->with('pesan', 'Pertemuan berhasil diperbarui.');
    }

    public function pertemuanDelete($id)
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $pertemuanModel = new PertemuanModel();
        $pertemuan = $pertemuanModel->find($id);

        if ($pertemuan) {
            $materiId = $pertemuan['materi_id'];
            
            // Hapus juga data terkait (Soal & Progress User) agar bersih
            $soalModel = new SoalModel();
            $soalModel->where('pertemuan_id', $id)->delete(); // Hapus soal quiz terkait

            // Hapus progress user terkait pertemuan ini (opsional)
            // $progressModel = new UserProgressModel();
            // $progressModel->where('pertemuan_id', $id)->delete(); 
            
            $pertemuanModel->delete($id);

            return redirect()->to('/admin/pertemuan/' . $materiId)->with('pesan', 'Pertemuan berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    }

    // =======================================================================
    // 5. MANAJEMEN SOAL QUIZ (Detail Pertemuan)
    // =======================================================================
    
    public function soal($pertemuanId)
    {
        if (!logged_in()) return redirect()->to('/login');

        $pertemuanModel = new PertemuanModel();
        $soalModel = new SoalModel();
        $jawabanModel = new JawabanModel();
        $materiModel = new MateriModel();

        $pertemuan = $pertemuanModel->find($pertemuanId);
        if (!$pertemuan) return redirect()->back();
        
        $materi = $materiModel->find($pertemuan['materi_id']);

        // Ambil semua soal
        $soalList = $soalModel->where('pertemuan_id', $pertemuanId)->findAll();

        // Ambil jawaban untuk setiap soal agar bisa ditampilkan di view
        foreach ($soalList as &$s) {
            $s['jawaban'] = $jawabanModel->where('soal_id', $s['id'])->findAll();
        }

        $data = [
            'title' => 'Kelola Soal - ' . $pertemuan['judul_pertemuan'],
            'pertemuan' => $pertemuan,
            'materi' => $materi,
            'soal_list' => $soalList
        ];

        return view('admin/soal/index', $data);
    }

    public function soalCreate($pertemuanId)
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $pertemuanModel = new PertemuanModel();
        $pertemuan = $pertemuanModel->find($pertemuanId);

        $data = [
            'title' => 'Tambah Soal Baru',
            'pertemuan' => $pertemuan,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/soal/create', $data);
    }

    public function soalStore()
    {
        if (!logged_in()) return redirect()->to('/login');

        $pertemuanId = $this->request->getPost('pertemuan_id');

        // Simpan Pertanyaan
        $soalModel = new SoalModel();
        $soalModel->save([
            'pertemuan_id' => $pertemuanId,
            'pertanyaan' => $this->request->getPost('pertanyaan')
        ]);
        $soalId = $soalModel->getInsertID();

        // Simpan 4 Pilihan Jawaban
        $jawabanModel = new JawabanModel();
        $opsi = $this->request->getPost('opsi'); 
        $kunci = $this->request->getPost('kunci'); 

        foreach ($opsi as $index => $teks) {
            $jawabanModel->save([
                'soal_id' => $soalId,
                'teks_jawaban' => $teks,
                'is_correct' => ($index == $kunci) ? 1 : 0
            ]);
        }

        return redirect()->to('/admin/soal/' . $pertemuanId)->with('pesan', 'Soal berhasil ditambahkan');
    }
    
    // EDIT SOAL
    public function soalEdit($id)
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $soalModel = new SoalModel();
        $jawabanModel = new JawabanModel();
        $pertemuanModel = new PertemuanModel();

        // Ambil Data Soal
        $soal = $soalModel->find($id);
        if (!$soal) return redirect()->back()->with('error', 'Soal tidak ditemukan');

        // Ambil Data Jawaban
        $jawaban = $jawabanModel->where('soal_id', $id)->findAll();
        
        // Ambil Data Pertemuan (untuk tombol kembali)
        $pertemuan = $pertemuanModel->find($soal['pertemuan_id']);

        $data = [
            'title' => 'Edit Soal',
            'soal' => $soal,
            'jawaban' => $jawaban,
            'pertemuan' => $pertemuan,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/soal/edit', $data);
    }

    // UPDATE SOAL
    public function soalUpdate($id)
    {
        if (!logged_in()) return redirect()->to('/login');

        $soalModel = new SoalModel();
        $jawabanModel = new JawabanModel();
        
        // 1. Update Pertanyaan
        $soalModel->update($id, [
            'pertanyaan' => $this->request->getPost('pertanyaan')
        ]);

        // 2. Update Jawaban (Hapus lama, insert baru)
        $jawabanModel->where('soal_id', $id)->delete();

        $opsi = $this->request->getPost('opsi'); 
        $kunci = $this->request->getPost('kunci'); 
        
        foreach ($opsi as $index => $teks) {
            $jawabanModel->save([
                'soal_id' => $id,
                'teks_jawaban' => $teks,
                'is_correct' => ($index == $kunci) ? 1 : 0
            ]);
        }

        $soal = $soalModel->find($id);
        return redirect()->to('/admin/soal/' . $soal['pertemuan_id'])->with('pesan', 'Soal berhasil diperbarui');
    }

    // DELETE SOAL
    public function soalDelete($id)
    {
        if (!logged_in()) return redirect()->to('/login');
        
        $soalModel = new SoalModel();
        $jawabanModel = new JawabanModel();
        
        $soal = $soalModel->find($id);
        
        if ($soal) {
            $pertemuanId = $soal['pertemuan_id'];
            
            // Hapus jawaban dulu (karena foreign key)
            $jawabanModel->where('soal_id', $id)->delete();
            $soalModel->delete($id);

            return redirect()->to('/admin/soal/' . $pertemuanId)->with('pesan', 'Soal berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }

    // =======================================================================
    // 6. MANAJEMEN USER (CRUD)
    // =======================================================================

    // READ: Daftar User
    public function users()
    {
        if (!logged_in()) return redirect()->to('/login');

        $userModel = new UserModel();
        $users = $userModel->orderBy('id', 'DESC')->findAll();

        $data = [
            'title' => 'Kelola User',
            'users' => $users
        ];

        return view('admin/users/index', $data);
    }

    // CREATE: Form Tambah User
    public function userCreate()
    {
        if (!logged_in()) return redirect()->to('/login');

        $data = [
            'title' => 'Tambah User Baru',
            'validation' => \Config\Services::validation()
        ];

        return view('admin/users/create', $data);
    }

    // STORE: Simpan User Baru
    public function userStore()
    {
        if (!logged_in()) return redirect()->to('/login');

        // Validasi
        if (!$this->validate([
            'email'    => 'required|valid_email|is_unique[users.email]',
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'role'     => 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid atau sudah digunakan.');
        }

        $userModel = new UserModel();
        
        // Gunakan Entitas User untuk handle password hashing otomatis
        $user = new \Myth\Auth\Entities\User($this->request->getPost());
        $user->setPassword($this->request->getPost('password'));
        $user->activate(); 
        
        if (! $userModel->save($user)) {
             return redirect()->back()->withInput()->with('error', $userModel->errors());
        }
        
        // Update Role manual
        $db = \Config\Database::connect();
        $db->table('users')->where('id', $userModel->getInsertID())->update(['role' => $this->request->getPost('role')]);

        return redirect()->to('/admin/users')->with('pesan', 'User baru berhasil ditambahkan.');
    }

    // EDIT: Form Edit User
    public function userEdit($id)
    {
        if (!logged_in()) return redirect()->to('/login');

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit User',
            'user'  => $user,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/users/edit', $data);
    }

    // UPDATE: Simpan Perubahan User
    public function userUpdate($id)
    {
        if (!logged_in()) return redirect()->to('/login');

        // Validasi
        if (!$this->validate([
            'email'    => "required|valid_email|is_unique[users.email,id,$id]",
            'username' => "required|min_length[3]|is_unique[users.username,id,$id]",
            'role'     => 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);
        
        $user->email = $this->request->getPost('email');
        $user->username = $this->request->getPost('username');
        
        // Cek password baru
        $newPass = $this->request->getPost('password');
        if (!empty($newPass)) {
            $user->setPassword($newPass);
        }

        $userModel->save($user);

        // Update Role Manual
        $db = \Config\Database::connect();
        $db->table('users')->where('id', $id)->update(['role' => $this->request->getPost('role')]);

        return redirect()->to('/admin/users')->with('pesan', 'Data user berhasil diperbarui.');
    }

    // DELETE: Hapus User
    public function userDelete($id)
    {
        if (!logged_in()) return redirect()->to('/login');

        if (user_id() == $id) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $db = \Config\Database::connect();
        
        // Bersihkan data terkait
        $db->table('profiles')->where('user_id', $id)->delete();
        $db->table('nilai')->where('user_id', $id)->delete();
        $db->table('user_progress')->where('user_id', $id)->delete();
        
        // Hapus User
        $userModel = new UserModel();
        $userModel->delete($id);

        return redirect()->to('/admin/users')->with('pesan', 'User berhasil dihapus.');
    }
    
// =======================================================================
    // 7. MANAJEMEN BERANDA (HOME EDIT) - FINAL FIX
    // =======================================================================

    public function homeEdit()
    {
        if (!logged_in()) return redirect()->to('/login');

        $homeModel = new \App\Models\HomeContentModel();
        // Ambil data ID 1
        $content = $homeModel->find(1);

        // Jika database masih kosong (jaga-jaga), set default array
        if (!$content) {
            $content = [
                'id' => 1,
                'hero_title' => 'Judul Default', 
                'hero_subtitle' => 'Sub Judul Default',
                'hero_description' => 'Deskripsi Default',
                'hero_image' => 'studyjam.png' 
            ];
        }

        $data = [
            'title' => 'Edit Tampilan Beranda',
            'content' => $content, // Variabel ini harus bernama 'content'
            'validation' => \Config\Services::validation()
        ];

        return view('admin/home/edit', $data);
    }

    public function homeUpdate()
    {
        if (!logged_in()) return redirect()->to('/login');

        // 1. Validasi
        if (!$this->validate([
            'hero_title' => 'required',
            'hero_image' => [
                'rules' => 'max_size[hero_image,2048]|is_image[hero_image]|mime_in[hero_image,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar (Maks 2MB)',
                    'is_image' => 'File yang diupload bukan gambar',
                    'mime_in'  => 'Format gambar harus JPG atau PNG'
                ]
            ]
        ])) {
            // Kembalikan dengan pesan error agar muncul di alert view
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $homeModel = new \App\Models\HomeContentModel();
        
        $fileFoto = $this->request->getFile('hero_image');
        $namaFotoLama = $this->request->getPost('foto_lama');
        $namaFoto = $namaFotoLama; // Default pakai foto lama

        // 2. Logika Upload
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            // Generate nama baru
            $namaBaru = $fileFoto->getRandomName();
            // Pindah ke folder public/img
            $fileFoto->move('img', $namaBaru);
            $namaFoto = $namaBaru;

            // Hapus foto lama jika bukan default dan file-nya ada
            if ($namaFotoLama != 'studyjam.png' && 
                $namaFotoLama != 'default.png' && 
                !filter_var($namaFotoLama, FILTER_VALIDATE_URL) &&
                file_exists('img/' . $namaFotoLama)) {
                unlink('img/' . $namaFotoLama);
            }
        }

        // 3. Simpan ke Database (Paksa ID 1)
        $homeModel->save([
            'id'               => 1, 
            'hero_title'       => $this->request->getPost('hero_title'),
            'hero_subtitle'    => $this->request->getPost('hero_subtitle'),
            'hero_description' => $this->request->getPost('hero_description'),
            'hero_image'       => $namaFoto
        ]);

        return redirect()->to('/admin/home')->with('pesan', 'Tampilan Beranda berhasil diperbarui!');
    }
}