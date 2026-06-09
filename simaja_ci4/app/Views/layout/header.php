<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIMAJA - Sistem Manajemen Study Jam</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    /* ===== Navbar tetap di atas ===== */
    .navbar {
      position: sticky;
      top: 0;
      z-index: 2000;
    }

    /* ===== Dropdown profile ===== */
    .profile-menu {
      position: absolute;
      top: 70px;
      right: 20px;
      background: white;
      border-radius: 25px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
      width: 150px;
      display: none;
      text-align: center;
      padding: 10px 0;
      z-index: 3000;
    }

    .profile-menu a {
      display: block;
      padding: 8px 0;
      color: #000;
      font-weight: 600;
      text-decoration: none;
      border-radius: 30px;
      margin: 5px 10px;
      transition: 0.3s;
    }

    .profile-menu a:hover {
      background-color: #e0f2f1;
    }

    /* ======== Search Button ======== */
    .search-btn-custom {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 6px 14px;
      border-radius: 14px;
      background: transparent;
      color: white;
      border: 1.5px solid rgba(255, 255, 255, 0.4);
      font-size: 14px;
      cursor: pointer;
      transition: 0.2s;
    }

    .search-btn-custom:hover {
      background: rgba(255, 255, 255, 0.12);
    }

    #searchBox {
      position: absolute;
      right: 160px; 
      top: 50%;
      transform: translateY(-50%);
      display: none;
      z-index: 1500;
    }

    @media (max-width: 991px) {
      #searchBox {
        display: none !important;
      }

      #searchBoxMobile {
        display: none;
        padding: 10px 15px;
      }

      #searchBoxMobile input {
        border-radius: 12px;
        width: 100%;
      }
    }
  </style>

</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark"
    style="background: linear-gradient(90deg, #004d40, #00796b);">

    <div class="container-fluid">

      <!-- LOGO -->
      <a class="navbar-brand fw-bold text-white" href="/">
        <div class="d-flex align-items-center">
          <img src="/img/protic.png" alt="logo" width="40" height="40" class="me-2">
          <div>
            SIMAJA<br>
            <small class="fw-normal">Sistem Manajemen Study Jam</small>
          </div>
        </div>
      </a>

      <!-- TOGGLER -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- MENU NAV -->
      <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link <?= (uri_string() == '' ? 'active' : '') ?>" href="/">Beranda</a></li>
          <li class="nav-item"><a class="nav-link <?= (uri_string() == 'jadwal' ? 'active' : '') ?>" href="/jadwal">Jadwal</a></li>
          <li class="nav-item"><a class="nav-link <?= (uri_string() == 'materi' ? 'active' : '') ?>" href="/materi">Materi</a></li>
          <li class="nav-item"><a class="nav-link <?= (uri_string() == 'progres' ? 'active' : '') ?>" href="/progres">Progres</a></li>
          <li class="nav-item"><a class="nav-link <?= (uri_string() == 'peringkat' ? 'active' : '') ?>" href="/peringkat">Peringkat</a></li>
        </ul>
      </div>

      <!-- SEARCH + PROFILE -->
      <div class="d-flex align-items-center position-relative">

        <!-- Search Floating (Desktop) -->
        <div id="searchBox">
          <form action="/search" method="get">
            <input type="text" name="q" class="form-control form-control-sm"
              placeholder="Cari materi atau jadwal..."
              style="border-radius: 14px; width: 200px;">
          </form>
        </div>

        <!-- Search Button -->
        <button id="searchBtn" class="search-btn-custom">
          <i class="bi bi-search"></i> Search
        </button>

        <!-- Profile -->
        <div class="profile-btn ms-3">
          <button id="profileToggle" class="btn btn-outline-light rounded-circle"
            style="width: 40px; height: 40px;">
            <i class="bi bi-person"></i>
          </button>

          <div id="profileMenu" class="profile-menu">
            <a href="/profile">Profile</a>
            <a href="<?= base_url('logout'); ?>">Logout</a>
          </div>
        </div>

      </div>

    </div>
  </nav>

  <!-- Search Mobile -->
  <div id="searchBoxMobile" class="container d-lg-none mt-2">
    <form action="/search" method="get">
      <input type="text" name="q" class="form-control" placeholder="Cari materi atau jadwal...">
    </form>
  </div>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    /* ======================== PROFILE TOGGLE ======================== */
    const profileToggle = document.getElementById('profileToggle');
    const profileMenu = document.getElementById('profileMenu');

    profileToggle.addEventListener('click', () => {
      profileMenu.style.display =
        (profileMenu.style.display === "block") ? "none" : "block";
    });

    document.addEventListener('click', (e) => {
      if (!profileToggle.contains(e.target) && !profileMenu.contains(e.target)) {
        profileMenu.style.display = "none";
      }
    });

    /* ======================== SEARCH TOGGLE (SIMPLE VERSION) ======================== */
    const searchBtn = document.getElementById('searchBtn');
    const searchBox = document.getElementById('searchBox');
    const searchBoxMobile = document.getElementById('searchBoxMobile');

    function isMobile() {
      return window.innerWidth <= 991;
    }

    // Toggle search saat tombol ditekan
    searchBtn.addEventListener('click', (e) => {
      const mobile = isMobile();

      if (mobile) {
        searchBoxMobile.style.display =
          searchBoxMobile.style.display === "block" ? "none" : "block";
        searchBox.style.display = "none";
      } else {
        searchBox.style.display =
          searchBox.style.display === "block" ? "none" : "block";
        searchBoxMobile.style.display = "none";
      }
    });

    // Tutup search jika klik di luar
    document.addEventListener("click", (e) => {
      if (!searchBtn.contains(e.target) &&
        !searchBox.contains(e.target) &&
        !searchBoxMobile.contains(e.target)) {
        searchBox.style.display = "none";
        searchBoxMobile.style.display = "none";
      }
    });

    // Tutup saat resize
    window.addEventListener("resize", () => {
      searchBox.style.display = "none";
      searchBoxMobile.style.display = "none";
    });
  </script>

</body>
</html>
