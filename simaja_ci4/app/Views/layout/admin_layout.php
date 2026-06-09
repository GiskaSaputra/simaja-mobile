<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SIMAJA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        
        /* --- PENGATURAN SCROLLING SIDEBAR --- */
        .sidebar {
            background: linear-gradient(180deg, #004d40, #00695c);
            color: white;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            
            /* Agar sidebar tetap di tempat (Fixed) */
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            
            /* Lebar Sidebar (Sesuai col-lg-2 Bootstrap) */
            width: 16.66667%;
            
            /* SCROLLING: Munculkan scrollbar jika konten panjang / di-zoom */
            overflow-y: auto;
            
            /* Mencegah scroll parent (body) ikut bergerak saat scroll sidebar mentok */
            overscroll-behavior: contain;
        }
        
        /* Mempercantik Scrollbar (Opsional) */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255,255,255,0.2);
            border-radius: 3px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        /* Agar Logo tetap di atas saat sidebar di-scroll */
        .sidebar-brand {
            position: sticky;
            top: 0;
            background: inherit; /* Mengikuti background gradient sidebar */
            z-index: 10;
            padding: 25px 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        /* --- END PENGATURAN SCROLLING --- */

        .sidebar .nav-link {
            color: rgba(255,255,255,0.85);
            margin-bottom: 8px;
            border-radius: 8px;
            padding: 12px 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.1rem;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            padding-left: 20px;
        }
        
        .sidebar .nav-link.active {
            background-color: #ffffff;
            color: #004d40;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-weight: 700;
        }

        .main-content {
            padding: 30px;
            margin-left: 16.66667%; /* Memberi jarak kiri selebar sidebar */
            min-height: 100vh;
        }
        
        .rounded-4 { border-radius: 1rem !important; }
        .shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }

        /* RESPONSIVE: Mengatur ulang scrolling di Mobile */
        @media (max-width: 767.98px) {
            .sidebar {
                position: static; /* Sidebar mengalir normal di dokumen */
                width: 100%;
                height: auto;     /* Tinggi otomatis sesuai konten */
                overflow-y: visible; /* Scrollbar ikut body */
                overscroll-behavior: auto;
            }
            .sidebar-brand {
                position: static; /* Logo tidak sticky di mobile */
            }
            .main-content {
                margin-left: 0; /* Hapus margin kiri */
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        
        <nav id="sidebarMenu" class="sidebar d-md-block collapse">
            
            <div class="sidebar-brand d-flex align-items-center">
                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                    <i class="bi bi-grid-fill fs-5"></i>
                </div>
                <span class="fs-5 fw-bold tracking-wide">Admin SIMAJA</span>
            </div>
            
            <div class="px-2 pb-5"> 
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link <?= uri_string() == 'admin/dashboard' ? 'active' : '' ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/jadwal'); ?>" class="nav-link <?= strpos(uri_string(), 'admin/jadwal') !== false ? 'active' : '' ?>">
                            <i class="bi bi-calendar-check"></i> Kelola Jadwal
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/materi'); ?>" class="nav-link <?= (strpos(uri_string(), 'admin/materi') !== false || strpos(uri_string(), 'admin/pertemuan') !== false || strpos(uri_string(), 'admin/soal') !== false) ? 'active' : '' ?>">
                            <i class="bi bi-journal-text"></i> Kelola Materi & Kuis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/users'); ?>" class="nav-link <?= strpos(uri_string(), 'admin/users') !== false ? 'active' : '' ?>">
                            <i class="bi bi-people"></i> Kelola User
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <small class="text-white-50 text-uppercase px-3 fw-bold" style="font-size: 0.75rem;">Pengaturan</small>
                    </li>
                    <li class="nav-item mt-1">
                        <a href="<?= base_url('admin/home'); ?>" class="nav-link <?= strpos(uri_string(), 'admin/home') !== false ? 'active' : '' ?>">
                            <i class="bi bi-house-gear"></i> Tampilan Beranda
                        </a>
                    </li>
                </ul>

                <div class="mt-5 mb-4">
                    <div class="p-3 rounded-3" style="background: rgba(0,0,0,0.2);">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-white text-dark rounded-circle me-2 d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px;">
                                A
                            </div>
                            <div class="text-truncate">
                                <small class="d-block text-white-50" style="font-size: 0.7rem;">Login sebagai</small>
                                <strong class="text-white">Administrator</strong>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="<?= base_url('/'); ?>" target="_blank" class="btn btn-sm btn-light text-success fw-bold"><i class="bi bi-eye"></i> Lihat Web</a>
                            <a href="<?= base_url('logout'); ?>" class="btn btn-sm btn-outline-light"><i class="bi bi-box-arrow-right"></i> Logout</a>
                        </div>
                    </div>
                </div>

            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom d-md-none">
                <h1 class="h2 text-success fw-bold">Admin Panel</h1>
                <button class="btn btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                    <i class="bi bi-list fs-4"></i>
                </button>
            </div>

            <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-5 border-success" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                        <div><strong>Berhasil!</strong> <?= session()->getFlashdata('pesan'); ?></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-5 border-danger" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i>
                        <div>
                            <strong>Terjadi Kesalahan!</strong>
                            <?php 
                                $errors = session()->getFlashdata('error');
                                if (is_array($errors)) : 
                            ?>
                                <ul class="mb-0 ps-3">
                                    <?php foreach ($errors as $e) : ?>
                                        <li><?= esc($e); ?></li>
                                    <?php endforeach ?>
                                </ul>
                            <?php else : ?>
                                <br><?= esc($errors); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="py-3">
                <?= $this->renderSection('content'); ?>
            </div>
            
            <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
                <div class="col-md-4 d-flex align-items-center">
                    <span class="text-muted small">&copy; 2025 SIMAJA Admin Panel</span>
                </div>
            </footer>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000); 
</script>
</body>
</html>