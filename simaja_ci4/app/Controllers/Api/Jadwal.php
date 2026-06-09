<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\JadwalModel;
use App\Models\PendaftaranModel;
use App\Models\AbsensiModel;
use CodeIgniter\I18n\Time;

class Jadwal extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $jadwalModel = new JadwalModel();
        $pendaftaranModel = new PendaftaranModel();
        
        $userId = $this->request->getVar('user_id');

        $dataJadwal = $jadwalModel->orderBy('tanggal', 'ASC')->findAll();
        $daftarJadwalSaya = [];

        if ($userId) {
            $dataPendaftaran = $pendaftaranModel->where('user_id', $userId)->findAll();
            $daftarJadwalSaya = array_column($dataPendaftaran, 'jadwal_id');
        }

        $result = [];
        foreach ($dataJadwal as $row) {
            $row['is_terdaftar'] = in_array($row['id'], $daftarJadwalSaya);
            $result[] = $row;
        }

        return $this->respond([
            'status' => 200,
            'message' => 'Data Jadwal',
            'data' => $result
        ]);
    }

    public function daftar()
    {
        $data = $this->request->getJSON();
        $userId = $data->user_id ?? null;
        $jadwalId = $data->jadwal_id ?? null;

        if (!$userId || !$jadwalId) return $this->fail('Data tidak lengkap', 400);

        $jadwalModel = new JadwalModel();
        $pendaftaranModel = new PendaftaranModel();

        $jadwal = $jadwalModel->find($jadwalId);
        if (!$jadwal) return $this->failNotFound('Jadwal tidak ditemukan.');

        if ($pendaftaranModel->cekTerdaftar($userId, $jadwalId)) {
            return $this->fail('Anda sudah terdaftar.', 409);
        }

        if ($jadwal['terisi'] >= $jadwal['kuota']) {
            return $this->fail('Kuota penuh.', 400);
        }

        $db = \Config\Database::connect();
        $db->transStart();
            $pendaftaranModel->save(['user_id' => $userId, 'jadwal_id' => $jadwalId]);
            $jadwalModel->update($jadwalId, ['terisi' => $jadwal['terisi'] + 1]);
        $db->transComplete();

        if ($db->transStatus() === false) return $this->failServerError('Gagal mendaftar.');

        return $this->respond(['status' => 200, 'message' => 'Berhasil mendaftar Study Jam!']);
    }

    public function absensi()
    {
        $data = $this->request->getJSON();
        $userId = $data->user_id ?? null;
        $jadwalId = $data->jadwal_id ?? null;
        $status = $data->status ?? 'Hadir';

        if (!$userId || !$jadwalId) return $this->fail('Data tidak lengkap', 400);

        $absensiModel = new AbsensiModel();
        
        if ($absensiModel->cekSudahAbsen($userId, $jadwalId)) {
            return $this->fail('Anda sudah mengisi absensi.', 409);
        }

        $absensiModel->save([
            'user_id'    => $userId,
            'jadwal_id'  => $jadwalId,
            'status'     => $status,
            'created_at' => Time::now()
        ]);

        return $this->respond(['status' => 200, 'message' => 'Absensi berhasil dikirim!']);
    }
}