<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProfileModel;
use App\Models\NilaiModel;
use App\Models\UserProgressModel;

class Profile extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $userId = $this->request->getVar('user_id');
        if (!$userId) return $this->fail('User ID wajib dikirim', 400);

        $profileModel = new ProfileModel();
        $nilaiModel = new NilaiModel();
        $progressModel = new UserProgressModel();

        $userProfile = $profileModel->where('user_id', $userId)->first();
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

        return $this->respond([
            'status' => 200,
            'message' => 'Data Profil',
            'data' => [
                'biodata' => $userProfile,
                'stats' => [
                    'poin' => (int)$totalPoin,
                    'jam' => $jamBelajar,
                    'selesai' => $materiSelesai,
                    'ranking' => $myRank
                ]
            ]
        ]);
    }

    public function updateData()
    {
        // 1. Tangkap User ID dari POST (Bukan JSON lagi)
        $userId = $this->request->getPost('user_id');
        if (!$userId) return $this->fail('User ID wajib dikirim', 400);

        $profileModel = new ProfileModel();
        $profile = $profileModel->where('user_id', $userId)->first();

        // 2. Tangkap data teks
        $updateData = [
            'nama_lengkap'  => $this->request->getPost('nama_lengkap') ?? ($profile['nama_lengkap'] ?? null),
            'nim'           => $this->request->getPost('nim') ?? ($profile['nim'] ?? null),
            'kelas'         => $this->request->getPost('kelas') ?? ($profile['kelas'] ?? null),
            'prodi'         => $this->request->getPost('prodi') ?? ($profile['prodi'] ?? null),
            'jurusan'       => $this->request->getPost('jurusan') ?? ($profile['jurusan'] ?? null),
            'semester'      => $this->request->getPost('semester') ?? ($profile['semester'] ?? null),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin') ?? ($profile['jenis_kelamin'] ?? null),
            'alamat'        => $this->request->getPost('alamat') ?? ($profile['alamat'] ?? null),
        ];

        // 3. Tangkap File Foto (Jika ada)
        $fileFoto = $this->request->getFile('foto');
        
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('img', $namaFoto); // Simpan ke folder public/img
            $updateData['foto'] = $namaFoto;

            // Hapus foto lama jika bukan default
            if ($profile && !empty($profile['foto']) && $profile['foto'] != 'default.png') {
                if (file_exists('img/' . $profile['foto'])) {
                    unlink('img/' . $profile['foto']);
                }
            }
        }

        // 4. Simpan ke database
        if ($profile) {
            $profileModel->update($profile['id'], $updateData);
        } else {
            $updateData['user_id'] = $userId;
            $profileModel->save($updateData);
        }

        return $this->respond(['status' => 200, 'message' => 'Profil berhasil diperbarui!']);
    }
}