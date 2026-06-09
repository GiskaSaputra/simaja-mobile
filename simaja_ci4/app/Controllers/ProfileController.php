<?php

namespace App\Controllers;

use App\Models\ProfileModel;
use App\Models\NilaiModel;
use App\Models\UserProgressModel;

class ProfileController extends BaseController
{
    public function __construct()
    {
        helper(['auth', 'form']);
    }

    public function profile()
    {
        if (!logged_in()) return redirect()->to('/login');

        $userId = user_id();
        $profileModel = new ProfileModel();
        $nilaiModel = new NilaiModel();
        $progressModel = new UserProgressModel();

        $userProfile = $profileModel->getProfile($userId);

        $totalPoinArr = $nilaiModel->where('user_id', $userId)->selectSum('skor')->first();
        $totalPoin = $totalPoinArr['skor'] ?? 0;

        $materiSelesai = $progressModel->where(['user_id' => $userId, 'is_completed' => 1])->countAllResults();
        $jamBelajar = $materiSelesai * 2; 

        $db = \Config\Database::connect();
        $query = $db->query("SELECT user_id, SUM(skor) as total_skor FROM nilai GROUP BY user_id ORDER BY total_skor DESC");
        $rankings = $query->getResultArray();
        
        $myRank = '-';
        foreach($rankings as $index => $r) {
            if($r['user_id'] == $userId) {
                $myRank = $index + 1;
                break;
            }
        }

        $data = [
            'title' => 'Profil Saya',
            'profile' => $userProfile,
            'stats' => [
                'poin' => $totalPoin,
                'jam' => $jamBelajar,
                'selesai' => $materiSelesai,
                'ranking' => $myRank
            ]
        ];

        return view('page/profile', $data);
    }

    public function editProfile()
    {
        if (!logged_in()) return redirect()->to('/login');

        $userId = user_id();
        $profileModel = new ProfileModel();
        $userProfile = $profileModel->getProfile($userId);

        $data = [
            'title' => 'Edit Profil',
            'profile' => $userProfile,
            'validation' => \Config\Services::validation()
        ];

        return view('page/edit_profile', $data);
    }

    public function updateProfile()
    {
        if (!logged_in()) return redirect()->to('/login');

        $userId = user_id();
        $profileModel = new ProfileModel();

        if (!$this->validate([
            'nama_lengkap' => 'required',
            'foto' => ['rules' => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]']
        ])) {
            return redirect()->back()->withInput();
        }

        $fileFoto = $this->request->getFile('foto');
        $namaFotoLama = $this->request->getPost('foto_lama');
        
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('img', $namaFoto);
            if ($namaFotoLama != 'default.png' && file_exists('img/' . $namaFotoLama)) {
                unlink('img/' . $namaFotoLama);
            }
        } else {
            $namaFoto = $namaFotoLama;
        }

        $dataSimpan = [
            'user_id'       => $userId,
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'nim'           => $this->request->getPost('nim'),
            'kelas'         => $this->request->getPost('kelas'),
            'prodi'         => $this->request->getPost('prodi'),
            'jurusan'       => $this->request->getPost('jurusan'),
            'semester'      => $this->request->getPost('semester'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'alamat'        => $this->request->getPost('alamat'),
            'foto'          => $namaFoto
        ];

        $existingProfile = $profileModel->getProfile($userId);
        if ($existingProfile) {
            $dataSimpan['id'] = $existingProfile['id'];
        }

        $profileModel->save($dataSimpan);

        return redirect()->to('/profile')->with('pesan', 'Profil berhasil diperbarui!');
    }
}