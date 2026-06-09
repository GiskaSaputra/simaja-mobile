<?php

namespace App\Controllers;

use App\Models\UserProgressModel;
use App\Models\MateriModel;

class ProgresController extends BaseController
{
    public function __construct()
    {
        helper(['auth']);
    }

    public function progres()
    {
        if (!logged_in()) return redirect()->to('/login');

        $userId = user_id();
        $progressModel = new UserProgressModel();
        $materiModel = new MateriModel();

        $totalSelesai = $progressModel->where(['user_id' => $userId, 'is_completed' => 1])->countAllResults();
        $totalJam = $totalSelesai * 2;

        $aktivitas = $progressModel->select('DATE(completed_at) as tanggal')
                                   ->where('user_id', $userId)
                                   ->groupBy('DATE(completed_at)')
                                   ->orderBy('tanggal', 'DESC') 
                                   ->findAll();
        
        $streak = 0;
        if (!empty($aktivitas)) {
            $today = date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            $lastDate = $aktivitas[0]['tanggal'];
            
            if ($lastDate == $today || $lastDate == $yesterday) {
                $streak = 1; 
                $checkDate = $lastDate;
                for ($i = 1; $i < count($aktivitas); $i++) {
                    $prevDate = $aktivitas[$i]['tanggal'];
                    $expectedDate = date('Y-m-d', strtotime($checkDate . ' -1 day'));
                    if ($prevDate == $expectedDate) {
                        $streak++;
                        $checkDate = $prevDate; 
                    } else {
                        break; 
                    }
                }
            }
        }

        $semuaMateri = $materiModel->findAll();
        $targetBulanan = [];

        foreach ($semuaMateri as $m) {
            $completedCount = $progressModel->where([
                'user_id' => $userId, 
                'materi_id' => $m['id'],
                'is_completed' => 1
            ])->countAllResults();

            $totalPertemuan = $m['total_pertemuan'];
            $persen = ($totalPertemuan > 0) ? round(($completedCount / $totalPertemuan) * 100) : 0;

            $targetBulanan[] = [
                'judul' => $m['judul'],
                'persen' => $persen
            ];
        }

        $jamMingguan = [];
        for ($i = 1; $i <= 4; $i++) {
            $startDay = ($i - 1) * 7 + 1;
            $endDay = $i * 7;
            $startDate = date('Y-m-') . sprintf("%02d", $startDay); 
            $endDate = date('Y-m-') . sprintf("%02d", $endDay);     

            $count = $progressModel->where('user_id', $userId)
                                   ->where('completed_at >=', $startDate . ' 00:00:00')
                                   ->where('completed_at <=', $endDate . ' 23:59:59')
                                   ->countAllResults();
            $persenAktivitas = min(($count * 10), 100); 
            $jamMingguan[] = [
                'minggu' => 'Minggu ' . $i,
                'persen' => $persenAktivitas
            ];
        }

        $data = [
            'title' => 'Progres Belajar',
            'total_jam' => $totalJam,
            'streak' => $streak,
            'target_bulanan' => $targetBulanan,
            'jam_mingguan' => $jamMingguan
        ];

        return view('page/progres', $data);
    }

    public function peringkat()
    {
        if (!logged_in()) return redirect()->to('/login');

        $db = \Config\Database::connect();
        
        $builder = $db->table('nilai');
        $builder->select('nilai.user_id, SUM(nilai.skor) as total_skor, profiles.nama_lengkap, profiles.jurusan, profiles.prodi');
        $builder->join('profiles', 'profiles.user_id = nilai.user_id', 'left');
        $builder->groupBy('nilai.user_id, profiles.nama_lengkap, profiles.jurusan, profiles.prodi');
        $builder->orderBy('total_skor', 'DESC');
        
        $query = $builder->get();
        $allRankings = $query->getResultArray();

        $top3 = [];
        $rest = [];

        if (count($allRankings) > 0) {
            $top3 = array_slice($allRankings, 0, 3);
            if (count($allRankings) > 3) {
                $rest = array_slice($allRankings, 3);
            }
        }

        $data = [
            'title' => 'Peringkat Belajar',
            'top3'  => $top3,
            'rest'  => $rest
        ];

        return view('page/peringkat', $data);
    }

    public function resetProgress()
    {
        $db = \Config\Database::connect();
        $db->table('user_progress')->truncate();
        echo "<h1>Berhasil Reset!</h1><a href='".base_url('/materi')."'>Kembali ke Materi</a>";
    }
}