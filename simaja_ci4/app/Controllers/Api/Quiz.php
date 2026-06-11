<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MateriModel;
use App\Models\PertemuanModel;
use App\Models\SoalModel;
use App\Models\JawabanModel;
use App\Models\NilaiModel;
use CodeIgniter\I18n\Time;

class Quiz extends ResourceController
{
    protected $format = 'json';

    // 1. Ambil daftar pertemuan untuk quiz berdasarkan materi_id
    public function listPertemuan()
    {
        $materiId = $this->request->getVar('materi_id');
        $userId = $this->request->getVar('user_id');

        if (!$materiId || !$userId) return $this->fail('Materi ID dan User ID wajib dikirim', 400);

        $pertemuanModel = new PertemuanModel();
        $nilaiModel = new NilaiModel();

        $listPertemuan = $pertemuanModel->where('materi_id', $materiId)->findAll();
        
        $nilaiUser = [];
        $dataNilai = $nilaiModel->where('user_id', $userId)->findAll();
        foreach($dataNilai as $n) {
            $nilaiUser[$n['pertemuan_id']] = $n['skor'];
        }

        // Sisipkan skor user ke dalam list pertemuan
        $result = [];
        foreach ($listPertemuan as $p) {
            $p['skor_user'] = $nilaiUser[$p['id']] ?? null;
            $result[] = $p;
        }

        return $this->respond(['status' => 200, 'data' => $result]);
    }

    // 2. Ambil soal beserta opsi jawabannya
    public function mulai()
    {
        $pertemuanId = $this->request->getVar('pertemuan_id');
        if (!$pertemuanId) return $this->fail('Pertemuan ID wajib dikirim', 400);

        $soalModel = new SoalModel();
        $jawabanModel = new JawabanModel();

        $soal = $soalModel->where('pertemuan_id', $pertemuanId)->findAll();
        
        if (empty($soal)) {
            return $this->failNotFound('Belum ada soal untuk pertemuan ini.');
        }

        foreach ($soal as &$s) {
            $opsi = $jawabanModel->where('soal_id', $s['id'])->findAll();
            // Acak opsi jawaban agar tidak selalu sama urutannya
            shuffle($opsi);
            
            // Hapus field 'is_correct' agar tidak dicontek dari Flutter/Postman
            foreach ($opsi as &$o) {
                unset($o['is_correct']);
            }
            $s['opsi'] = $opsi;
        }

        return $this->respond(['status' => 200, 'data' => $soal]);
    }

    // 3. Submit Jawaban dan Kalkulasi Nilai
    public function submit()
    {
        $data = $this->request->getJSON();
        $userId = $data->user_id ?? null;
        $pertemuanId = $data->pertemuan_id ?? null;
        $jawabanUser = $data->jawaban ?? []; // Harus array, contoh: {"1": 5, "2": 8} -> Soal ID : Jawaban ID

        if (!$userId || !$pertemuanId || empty($jawabanUser)) {
            return $this->fail('Data tidak lengkap', 400);
        }

        $jawabanModel = new JawabanModel();
        $nilaiModel = new NilaiModel();

        $skorBenar = 0;
        $totalSoal = count((array)$jawabanUser);

        // Cek satu per satu jawaban user
        foreach ($jawabanUser as $soalId => $jawabanId) {
            $cekJawaban = $jawabanModel->find($jawabanId);
            if ($cekJawaban && $cekJawaban['is_correct'] == 1) {
                $skorBenar++;
            }
        }

        $nilaiAkhir = ($totalSoal > 0) ? round(($skorBenar / $totalSoal) * 100) : 0;

        // Cek apakah sudah pernah mengerjakaan
        $existingNilai = $nilaiModel->where(['user_id' => $userId, 'pertemuan_id' => $pertemuanId])->first();
        
        $dataSimpan = [
            'user_id' => $userId,
            'pertemuan_id' => $pertemuanId,
            'skor' => $nilaiAkhir,
            'created_at' => Time::now()
        ];

        if ($existingNilai) {
            $dataSimpan['id'] = $existingNilai['id'];
        }
        
        $nilaiModel->save($dataSimpan);
        
        return $this->respond([
            'status' => 200,
            'message' => "Quiz Selesai! Anda menjawab $skorBenar dari $totalSoal benar.",
            'skor_akhir' => $nilaiAkhir
        ]);
    }
}