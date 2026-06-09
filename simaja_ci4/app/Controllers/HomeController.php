<?php

namespace App\Controllers;

use App\Models\JadwalModel;
use App\Models\PendaftaranModel;
use App\Models\HomeContentModel;
use App\Models\MateriModel;

class HomeController extends BaseController
{
    public function __construct()
    {
        helper(['auth', 'form']);
    }

    public function index()
    {
        $jadwalModel = new JadwalModel();
        $pendaftaranModel = new PendaftaranModel();
        $homeModel = new HomeContentModel();

        $homeContent = $homeModel->find(1);
        if (!$homeContent) {
            $homeContent = [
                'hero_title'       => 'Hi, Selamat Datang di SIMAJA',
                'hero_subtitle'    => 'Sistem Manajemen Study Jam',
                'hero_description' => 'Temukan pengalaman belajar baru bersama SIMAJA.',
                'hero_image'       => 'studyjam.png'
            ];
        }

        $dataJadwal = $jadwalModel->orderBy('tanggal', 'ASC')->findAll(6);
        $userId = user_id();
        $daftarJadwalSaya = [];

        if ($userId) {
            $dataPendaftaran = $pendaftaranModel->where('user_id', $userId)->findAll();
            $daftarJadwalSaya = array_column($dataPendaftaran, 'jadwal_id');
        }

        $data = [
            'title'        => 'Home - SIMAJA',
            'jadwal_home'  => $dataJadwal,
            'jadwal_saya'  => $daftarJadwalSaya,
            'home_content' => $homeContent
        ];

        return view('layout/home', $data); 
    }

    public function search()
    {
        $query = $this->request->getGet('q');
        if (!$query) return redirect()->back();

        $jadwalModel = new JadwalModel();
        $materiModel = new MateriModel();
        
        $data = [
            'judul'  => 'Hasil Pencarian',
            'query'  => $query,
            'materi' => $materiModel->like('judul', $query)->findAll(),
            'jadwal' => $jadwalModel->like('judul', $query)->findAll()
        ];

        return view('page/search', $data);
    }
}