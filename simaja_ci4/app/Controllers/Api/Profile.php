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

        // 1. Ambil Biodata
        $userProfile = $profileModel->where('user_id', $userId)->first();

        // 2. Hitung Total Poin
        $totalPoinArr = $nilaiModel->where('user_id', $userId)->selectSum('skor')->first();
        $totalPoin = $totalPoinArr['skor'] ?? 0;

        // 3. Hitung Jam Belajar (1 materi selesai = 2 jam)
        $materiSelesai = $progressModel->where(['user_id' => $userId, 'is_completed' => 1])->countAllResults();
        $jamBelajar = $materiSelesai * 2; 

        // 4. Hitung Ranking
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
        $data = $this->request->getJSON();
        $userId = $data->user_id ?? null;

        if (!$userId) return $this->fail('User ID wajib dikirim', 400);

        $profileModel = new ProfileModel();
        $profile = $profileModel->where('user_id', $userId)->first();

        $updateData = [
            'nama_lengkap'  => $data->nama_lengkap ?? ($profile['nama_lengkap'] ?? null),
            'nim'           => $data->nim ?? ($profile['nim'] ?? null),
            'kelas'         => $data->kelas ?? ($profile['kelas'] ?? null),
            'prodi'         => $data->prodi ?? ($profile['prodi'] ?? null),
            'jurusan'       => $data->jurusan ?? ($profile['jurusan'] ?? null),
            'semester'      => $data->semester ?? ($profile['semester'] ?? null),
            'jenis_kelamin' => $data->jenis_kelamin ?? ($profile['jenis_kelamin'] ?? null),
            'alamat'        => $data->alamat ?? ($profile['alamat'] ?? null),
        ];

        if ($profile) {
            $profileModel->update($profile['id'], $updateData);
        } else {
            $updateData['user_id'] = $userId;
            $profileModel->save($updateData);
        }

        return $this->respond(['status' => 200, 'message' => 'Profil berhasil diperbarui!']);
    }
}