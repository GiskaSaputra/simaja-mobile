<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\MateriModel;
use App\Models\PertemuanModel;
use App\Models\UserProgressModel;
use CodeIgniter\I18n\Time;

class Materi extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $materiModel = new MateriModel();
        $progressModel = new UserProgressModel();
        
        $userId = $this->request->getVar('user_id');
        $dataMateri = $materiModel->findAll();
        $progressData = [];

        if ($userId) {
            $userProgress = $progressModel->select('materi_id, COUNT(id) as completed')
                                          ->where('user_id', $userId)
                                          ->where('is_completed', 1)
                                          ->groupBy('materi_id')
                                          ->findAll();

            foreach ($userProgress as $p) {
                if (!empty($p['materi_id'])) {
                    $progressData[$p['materi_id']] = (int)$p['completed'];
                }
            }
        }

        $result = [];
        foreach ($dataMateri as $m) {
            $m['completed_count'] = $progressData[$m['id']] ?? 0;
            $result[] = $m;
        }

        return $this->respond([
            'status' => 200, 
            'data' => $result
        ]);
    }

    public function complete()
    {
        $data = $this->request->getJSON();
        $userId = $data->user_id ?? null;
        $pertemuanId = $data->pertemuan_id ?? null;

        if (!$userId || !$pertemuanId) return $this->fail('Data tidak lengkap', 400);

        $progressModel = new UserProgressModel();
        $pertemuanModel = new PertemuanModel();

        $pertemuan = $pertemuanModel->find($pertemuanId);
        if (!$pertemuan) return $this->failNotFound('Data pertemuan tidak valid.');

        $materiId = $pertemuan['materi_id'];
        $exists = $progressModel->where(['user_id' => $userId, 'pertemuan_id' => $pertemuanId])->first();

        if (!$exists) {
            $progressModel->save([
                'user_id' => $userId,
                'materi_id' => $materiId,
                'pertemuan_id' => $pertemuanId,
                'is_completed' => 1,
                'completed_at' => Time::now()
            ]);
        }

        return $this->respond(['status' => 200, 'message' => 'Progress berhasil dicatat!']);
    }
}