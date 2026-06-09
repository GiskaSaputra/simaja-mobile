<?php

namespace App\Controllers;

use App\Models\MateriModel;
use App\Models\PertemuanModel;
use App\Models\SoalModel;
use App\Models\JawabanModel;
use App\Models\NilaiModel;
use CodeIgniter\I18n\Time;

class QuizController extends BaseController
{
    public function __construct()
    {
        helper(['auth', 'form']);
    }

    public function quiz()
    {
        if (!logged_in()) return redirect()->to('/login');

        $materiModel = new MateriModel();
        $pertemuanModel = new PertemuanModel();
        
        $materiList = $materiModel->findAll();
        
        foreach ($materiList as &$m) {
            $firstPertemuan = $pertemuanModel->where('materi_id', $m['id'])
                                             ->orderBy('urutan', 'ASC')
                                             ->first();
            $m['first_pertemuan_id'] = $firstPertemuan ? $firstPertemuan['id'] : null;
        }

        $data = [
            'title' => 'Daftar Quiz',
            'materi_list' => $materiList
        ];

        return view('page/quiz', $data);
    }

    public function quizList($materiId)
    {
        if (!logged_in()) return redirect()->to('/login');

        $materiModel = new MateriModel();
        $pertemuanModel = new PertemuanModel();
        $nilaiModel = new NilaiModel();
        $userId = user_id();

        $materi = $materiModel->find($materiId);
        if (!$materi) return redirect()->to('/quiz');

        $listPertemuan = $pertemuanModel->getByMateri($materiId);
        
        $nilaiUser = [];
        $dataNilai = $nilaiModel->where('user_id', $userId)->findAll();
        foreach($dataNilai as $n) {
            $nilaiUser[$n['pertemuan_id']] = $n['skor'];
        }

        $data = [
            'title' => 'Quiz - ' . $materi['judul'],
            'materi' => $materi,
            'list_pertemuan' => $listPertemuan,
            'nilai_user' => $nilaiUser
        ];

        return view('page/quiz_list', $data);
    }

    public function mulaiQuiz($pertemuanId)
    {
        if (!logged_in()) return redirect()->to('/login');

        $soalModel = new SoalModel();
        $jawabanModel = new JawabanModel();
        $pertemuanModel = new PertemuanModel();

        $pertemuan = $pertemuanModel->find($pertemuanId);
        if (!$pertemuan) return redirect()->to('/quiz')->with('error', 'Pertemuan tidak ditemukan');

        $soal = $soalModel->getSoalByPertemuan($pertemuanId);
        
        if (empty($soal)) {
            return redirect()->back()->with('warning', 'Belum ada soal quiz untuk pertemuan ini.');
        }

        foreach ($soal as &$s) {
            $s['opsi'] = $jawabanModel->where('soal_id', $s['id'])->findAll();
            shuffle($s['opsi']);
        }

        $data = [
            'title' => 'Quiz - ' . $pertemuan['judul_pertemuan'],
            'pertemuan' => $pertemuan,
            'soal_list' => $soal
        ];

        return view('page/detail_quiz', $data);
    }

    public function submitQuiz()
    {
        if (!logged_in()) return redirect()->to('/login');

        $userId = user_id();
        $pertemuanId = $this->request->getPost('pertemuan_id');
        $jawabanUser = $this->request->getPost('jawaban');

        if (!$jawabanUser) return redirect()->back()->with('error', 'Anda belum menjawab soal apapun.');

        $jawabanModel = new JawabanModel();
        $nilaiModel = new NilaiModel();

        $skorBenar = 0;
        $totalSoal = count($jawabanUser);

        foreach ($jawabanUser as $soalId => $jawabanId) {
            $cekJawaban = $jawabanModel->find($jawabanId);
            if ($cekJawaban && $cekJawaban['is_correct'] == 1) {
                $skorBenar++;
            }
        }

        $nilaiAkhir = ($totalSoal > 0) ? round(($skorBenar / $totalSoal) * 100) : 0;

        $existingNilai = $nilaiModel->getNilai($userId, $pertemuanId);
        $dataSimpan = [
            'user_id' => $userId,
            'pertemuan_id' => $pertemuanId,
            'skor' => $nilaiAkhir,
            'created_at' => Time::now()
        ];

        if ($existingNilai) $dataSimpan['id'] = $existingNilai['id'];
        
        $nilaiModel->save($dataSimpan);
        
        $pesan = "Quiz Selesai! Nilai Anda: $nilaiAkhir ($skorBenar dari $totalSoal benar)";
        return redirect()->to('/quiz')->with($nilaiAkhir >= 70 ? 'pesan' : 'warning', $pesan);
    }
}