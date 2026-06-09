<?php

namespace App\Controllers;

use App\Models\JadwalModel;
use App\Models\PendaftaranModel;
use App\Models\AbsensiModel;
use CodeIgniter\I18n\Time;

class JadwalController extends BaseController
{
    public function __construct()
    {
        helper(['auth', 'form']);
    }

    public function jadwal()
    {
        $jadwalModel = new JadwalModel();
        $pendaftaranModel = new PendaftaranModel();

        $dataJadwal = $jadwalModel->orderBy('tanggal', 'ASC')->findAll();
        $userId = user_id();
        $daftarJadwalSaya = [];

        if ($userId) {
            $dataPendaftaran = $pendaftaranModel->where('user_id', $userId)->findAll();
            $daftarJadwalSaya = array_column($dataPendaftaran, 'jadwal_id');
        }

        $data = [
            'title' => 'Jadwal Study Jam',
            'jadwal' => $dataJadwal,
            'jadwal_saya' => $daftarJadwalSaya
        ];

        return view('page/jadwal', $data);
    }

    public function daftar($jadwalId)
    {
        if (!logged_in()) return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');

        $jadwalModel = new JadwalModel();
        $pendaftaranModel = new PendaftaranModel();
        $userId = user_id();

        $jadwal = $jadwalModel->find($jadwalId);
        if (!$jadwal) return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');

        if ($pendaftaranModel->cekTerdaftar($userId, $jadwalId)) {
            return redirect()->back()->with('warning', 'Anda sudah terdaftar.');
        }

        if ($jadwal['terisi'] >= $jadwal['kuota']) {
            return redirect()->back()->with('error', 'Kuota penuh.');
        }

        $db = \Config\Database::connect();
        $db->transStart();
            $pendaftaranModel->save(['user_id' => $userId, 'jadwal_id' => $jadwalId]);
            $jadwalModel->update($jadwalId, ['terisi' => $jadwal['terisi'] + 1]);
        $db->transComplete();

        if ($db->transStatus() === false) return redirect()->back()->with('error', 'Gagal mendaftar.');

        return redirect()->back()->with('pesan', 'Berhasil mendaftar Study Jam!');
    }

    public function rekap($jadwalId)
    {
        $jadwalModel = new JadwalModel();
        $absensiModel = new AbsensiModel();

        $jadwal = $jadwalModel->find($jadwalId);
        if (!$jadwal) return redirect()->back();

        $data = [
            'title' => 'Rekap Absensi',
            'jadwal' => $jadwal,
            'peserta' => $absensiModel->getAbsensiByJadwal($jadwalId)
        ];

        return view('page/rekap', $data);
    }

    public function absensi($jadwalId = null)
    {
        if (!logged_in()) return redirect()->to('/login');
        if (is_null($jadwalId)) return redirect()->to('/jadwal');

        $jadwalModel = new JadwalModel();
        $absensiModel = new AbsensiModel();
        $userId = user_id();

        $jadwal = $jadwalModel->find($jadwalId);
        if (!$jadwal) return redirect()->to('/jadwal');

        $data = [
            'title' => 'Form Absensi',
            'jadwal' => $jadwal,
            'sudah_absen' => $absensiModel->cekSudahAbsen($userId, $jadwalId)
        ];

        return view('page/absensi', $data);
    }

    public function kirimAbsensi()
    {
        if (!logged_in()) return redirect()->to('/login');

        $jadwalId = $this->request->getPost('jadwal_id');
        $status = $this->request->getPost('status');
        $userId = user_id();
        $absensiModel = new AbsensiModel();
        
        if ($absensiModel->cekSudahAbsen($userId, $jadwalId)) {
            return redirect()->back()->with('error', 'Anda sudah mengisi absensi.');
        }

        $absensiModel->save([
            'user_id' => $userId,
            'jadwal_id' => $jadwalId,
            'status' => $status,
            'created_at' => Time::now()
        ]);

        return redirect()->to('/jadwal')->with('pesan', 'Absensi berhasil dikirim!');
    }
}