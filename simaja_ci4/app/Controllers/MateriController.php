<?php

namespace App\Controllers;

use App\Models\MateriModel;
use App\Models\PertemuanModel;
use App\Models\UserProgressModel;
use CodeIgniter\I18n\Time;

class MateriController extends BaseController
{
    public function __construct()
    {
        helper(['auth', 'form']);
    }

    public function materi()
    {
        $materiModel = new MateriModel();
        $progressModel = new UserProgressModel();
        
        $dataMateri = $materiModel->findAll();
        $userId = user_id();
        $progressData = [];

        if ($userId) {
            $userProgress = $progressModel->select('materi_id, COUNT(id) as completed')
                                ->where('user_id', $userId)
                                ->where('is_completed', 1)
                                ->groupBy('materi_id')
                                ->findAll();

            foreach ($userProgress as $p) {
                if (!empty($p['materi_id'])) {
                    $progressData[$p['materi_id']] = ['completed' => (int)$p['completed']];
                }
            }
        }

        $data = [
            'title' => 'Materi Study Jam',
            'materi_list' => $dataMateri,
            'progress_data' => $progressData
        ];

        return view('page/materi', $data);
    }

    public function detailMateri($materiId)
    {
        if (!logged_in()) return redirect()->to('/login');

        $materiModel = new MateriModel();
        $pertemuanModel = new PertemuanModel();
        $progressModel = new UserProgressModel();
        $userId = user_id();

        $materi = $materiModel->find($materiId);
        if(!$materi) return redirect()->back();

        $completedIds = $progressModel->select('pertemuan_id')
                            ->where('user_id', $userId)
                            ->where('materi_id', $materiId)
                            ->where('is_completed', 1)
                            ->findAll();
        
        $data = [
            'title' => 'Detail Materi',
            'materi' => $materi,
            'pertemuan' => $pertemuanModel->getByMateri($materiId),
            'completed_list' => array_column($completedIds, 'pertemuan_id')
        ];

        return view('page/detail_materi', $data);
    }

    public function belajar($pertemuanId)
    {
        if (!logged_in()) return redirect()->to('/login');

        $pertemuanModel = new PertemuanModel();
        $materiModel = new MateriModel();
        $progressModel = new UserProgressModel();
        $userId = user_id();

        $pertemuan = $pertemuanModel->find($pertemuanId);
        if (!$pertemuan) return redirect()->back();

        $materi = $materiModel->find($pertemuan['materi_id']);
        $allPertemuan = $pertemuanModel->getByMateri($materi['id']); 
        
        $completedIds = $progressModel->select('pertemuan_id')
                            ->where('user_id', $userId)
                            ->where('materi_id', $materi['id'])
                            ->where('is_completed', 1)
                            ->findAll();
        $completedList = array_column($completedIds, 'pertemuan_id');

        $prev = null;
        $next = null;

        foreach ($allPertemuan as $index => $p) {
            if ($p['id'] == $pertemuanId) {
                if (isset($allPertemuan[$index - 1])) $prev = $allPertemuan[$index - 1];
                if (isset($allPertemuan[$index + 1])) $next = $allPertemuan[$index + 1];
                break;
            }
        }

        $data = [
            'title' => $pertemuan['judul_pertemuan'],
            'materi' => $materi,
            'pertemuan' => $pertemuan,
            'prev' => $prev,
            'next' => $next,
            'list_pertemuan' => $allPertemuan,
            'completed_list' => $completedList
        ];

        return view('page/belajar', $data);
    }

    public function markComplete()
    {
        if (!logged_in()) return redirect()->to('/login');

        $pertemuanId = $this->request->getPost('pertemuan_id');
        $nextId = $this->request->getPost('next_id'); 
        $userId = user_id();

        $progressModel = new UserProgressModel();
        $pertemuanModel = new PertemuanModel();

        $pertemuan = $pertemuanModel->find($pertemuanId);
        if (!$pertemuan) {
            return redirect()->back()->with('error', 'Data pertemuan tidak valid.');
        }

        $materiId = $pertemuan['materi_id']; 

        $exists = $progressModel->where([
            'user_id' => $userId, 
            'pertemuan_id' => $pertemuanId
        ])->first();

        if (!$exists) {
            $progressModel->save([
                'user_id' => $userId,
                'materi_id' => $materiId, 
                'pertemuan_id' => $pertemuanId,
                'is_completed' => 1,
                'completed_at' => Time::now()
            ]);
        }
        
        if ($nextId) {
            return redirect()->to('/materi/belajar/' . $nextId);
        }

        return redirect()->to('/materi/detail/' . $materiId)->with('pesan', 'Progress berhasil dicatat!');
    }
}