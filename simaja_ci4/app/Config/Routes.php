<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =======================================================================
// 1. ROUTE USER BIASA (FRONTEND WEB)
// =======================================================================

// Home & Umum
$routes->get('/', 'HomeController::index');
$routes->get('/search', 'HomeController::search');

// Jadwal & Pendaftaran
$routes->get('/jadwal', 'JadwalController::jadwal');
$routes->post('/jadwal/daftar/(:num)', 'JadwalController::daftar/$1');
$routes->get('/jadwal/rekap/(:num)', 'JadwalController::rekap/$1');

// Absensi
$routes->get('/absensi', 'JadwalController::absensi');
$routes->get('/absensi/(:num)', 'JadwalController::absensi/$1');
$routes->post('/absensi/kirim', 'JadwalController::kirimAbsensi');

// Materi & Progress (LMS)
$routes->get('/materi', 'MateriController::materi');
$routes->get('/materi/detail/(:num)', 'MateriController::detailMateri/$1');
$routes->get('/materi/belajar/(:num)', 'MateriController::belajar/$1');
$routes->post('/materi/complete', 'MateriController::markComplete');

// Quiz
$routes->get('/quiz', 'QuizController::quiz');
$routes->get('/quiz/list/(:num)', 'QuizController::quizList/$1');
$routes->get('/quiz/pertemuan/(:num)', 'QuizController::mulaiQuiz/$1');
$routes->post('/quiz/submit', 'QuizController::submitQuiz');

// Profile & Edit Profile
$routes->get('/profile', 'ProfileController::profile');
$routes->get('/profile/edit', 'ProfileController::editProfile');
$routes->post('/profile/update', 'ProfileController::updateProfile');

// Progres & Peringkat
$routes->get('/progres', 'ProgresController::progres');
$routes->get('/peringkat', 'ProgresController::peringkat');

// Utilitas
$routes->get('/reset-db', 'ProgresController::resetProgress');
$routes->get('/logout', "\Myth\Auth\Controllers\AuthController::logout");



// =======================================================================
// 2. ROUTE KHUSUS ADMIN (BACKEND)
// =======================================================================
// Semua URL di dalam grup ini diawali dengan /admin
// Contoh: localhost:8080/admin/dashboard

$routes->group('admin', ['filter' => 'login'], function ($routes) {

    // Dashboard Admin
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('/', 'Admin::dashboard'); // Redirect /admin ke dashboard

    // --- MANAJEMEN JADWAL ---
    $routes->get('jadwal', 'Admin::jadwal');                    // Read (List)
    $routes->get('jadwal/create', 'Admin::jadwalCreate');       // Form Tambah
    $routes->post('jadwal/store', 'Admin::jadwalStore');        // Simpan Data Baru
    $routes->get('jadwal/edit/(:num)', 'Admin::jadwalEdit/$1');   // Form Edit
    $routes->post('jadwal/update/(:num)', 'Admin::jadwalUpdate/$1'); // Simpan Perubahan
    $routes->get('jadwal/delete/(:num)', 'Admin::jadwalDelete/$1'); // Hapus Data

    // --- MANAJEMEN MATERI ---
    $routes->get('materi', 'Admin::materi');                    // Read (List)
    $routes->get('materi/create', 'Admin::materiCreate');       // Form Tambah
    $routes->post('materi/store', 'Admin::materiStore');        // Simpan Data
    $routes->get('materi/edit/(:num)', 'Admin::materiEdit/$1');   // Form Edit
    $routes->post('materi/update/(:num)', 'Admin::materiUpdate/$1'); // Simpan Perubahan
    $routes->get('materi/delete/(:num)', 'Admin::materiDelete/$1'); // Hapus Data

    // --- MANAJEMEN PERTEMUAN (Detail Materi) ---
    $routes->get('pertemuan/(:num)', 'Admin::pertemuan/$1');           // List Pertemuan per Materi
    $routes->get('pertemuan/create/(:num)', 'Admin::pertemuanCreate/$1'); // Form Tambah
    $routes->post('pertemuan/store', 'Admin::pertemuanStore');         // Simpan
    $routes->get('pertemuan/edit/(:num)', 'Admin::pertemuanEdit/$1');   // Form Edit
    $routes->post('pertemuan/update/(:num)', 'Admin::pertemuanUpdate/$1'); // Simpan Perubahan
    $routes->get('pertemuan/delete/(:num)', 'Admin::pertemuanDelete/$1'); // Hapus Data

    // --- MANAJEMEN SOAL QUIZ (Detail Pertemuan) ---
    $routes->get('soal/(:num)', 'Admin::soal/$1');                     // List Soal per Pertemuan
    $routes->get('soal/create/(:num)', 'Admin::soalCreate/$1');        // Form Tambah
    $routes->post('soal/store', 'Admin::soalStore');                   // Simpan Baru
    $routes->get('soal/edit/(:num)', 'Admin::soalEdit/$1');            // Form Edit
    $routes->post('soal/update/(:num)', 'Admin::soalUpdate/$1');       // Simpan Perubahan
    $routes->get('soal/delete/(:num)', 'Admin::soalDelete/$1');        // Hapus Data

    // --- MANAJEMEN USER ---
    $routes->get('users', 'Admin::users');                     // Read (List User)
    $routes->get('users/create', 'Admin::userCreate');         // Form Tambah
    $routes->post('users/store', 'Admin::userStore');          // Simpan User Baru
    $routes->get('users/edit/(:num)', 'Admin::userEdit/$1');   // Form Edit
    $routes->post('users/update/(:num)', 'Admin::userUpdate/$1'); // Simpan Perubahan
    $routes->get('users/delete/(:num)', 'Admin::userDelete/$1');  // Hapus User

    // --- MANAJEMEN BERANDA (Home Edit) ---
    $routes->get('home', 'Admin::homeEdit');

    // URL aksi: localhost:8080/admin/home/update
    $routes->post('home/update', 'Admin::homeUpdate');
});


// =======================================================================
// ROUTE KHUSUS API (UNTUK FLUTTER MOBILE)
// =======================================================================
$routes->group('api', function ($routes) {

    // Pengaman CORS Chrome (Wajib)
    $routes->options('(:any)', function() {
        return response()->setStatusCode(200);
    });
    
    // Auth
    $routes->post('login', 'Api\Auth::login');
    $routes->post('register', 'Api\Auth::register');
    $routes->post('forgot', 'Api\Auth::forgot');
    $routes->post('reset', 'Api\Auth::reset');
    $routes->post('logout', 'Api\Auth::logout');

    // Jadwal
    $routes->get('jadwal', 'Api\Jadwal::index'); 
    $routes->post('jadwal/daftar', 'Api\Jadwal::daftar');
    $routes->post('jadwal/absensi', 'Api\Jadwal::absensi');

    // Materi
    $routes->get('materi', 'Api\Materi::index');
    $routes->post('materi/complete', 'Api\Materi::complete');

    // Profile
    $routes->get('profile', 'Api\Profile::index');
    $routes->post('profile/update', 'Api\Profile::updateData');

    // Progres & Peringkat
    $routes->get('progres', 'Api\Progres::index');
    $routes->get('peringkat', 'Api\Progres::peringkat');

    // Quiz
    $routes->get('quiz/pertemuan', 'Api\Quiz::listPertemuan'); // List pertemuan berdasarkan materi_id
    $routes->get('quiz/mulai', 'Api\Quiz::mulai');             // Get soal berdasarkan pertemuan_id
    $routes->post('quiz/submit', 'Api\Quiz::submit');
});