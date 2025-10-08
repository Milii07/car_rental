<?php
include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/db/db.php';
?>

<!DOCTYPE html>
<html lang="sq">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
    }


    .sidebar {
      width: 260px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      background: #1E40AF;
      padding: 1rem;
      display: flex;
      flex-direction: column;
      overflow-y: auto;
      transition: all 0.3s ease;
      z-index: 1050;
    }

    .sidebar.collapsed {
      width: 70px;
    }

    .sidebar .nav-link {
      display: flex;
      align-items: center;
      padding: 10px;
      color: white;
      text-decoration: none;
      position: relative;
    }

    .sidebar .nav-link i {
      font-size: 1.5rem;
    }

    .sidebar .nav-link-text {
      margin-left: 10px;
    }

    .sidebar-header {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
    }

    .sidebar-header img {
      height: 50px;
    }

    .sidebar-header .title-slogan {
      display: flex;
      flex-direction: column;
      margin-left: 10px;
      color: #fff;
      font-size: 14px;
      overflow: hidden;
      white-space: nowrap;
    }

    .sidebar-header .title {
      font-weight: 700;
    }

    .sidebar-header .slogan {
      font-size: 11px;
      color: #f0f0f0;
    }

    @media (min-width: 769px) {
      .sidebar.collapsed .nav-link-text {
        display: none;
      }

      .sidebar.collapsed .nav-link:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background: #333;
        color: #fff;
        padding: 4px 8px;
        border-radius: 4px;
        white-space: nowrap;
        z-index: 1000;
      }
    }

    @media (max-width: 768px) {
      .sidebar {
        left: -260px;
        transition: left 0.3s ease;
      }

      .sidebar.mobile-open {
        left: 0;
      }

      .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        transition: opacity 0.3s ease;
      }

      .sidebar-overlay.active {
        display: block;
      }

      .sidebar-toggle {
        display: flex;
        position: fixed;
        top: 20px;
        left: 20px;
        width: 40px;
        height: 40px;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 50%;
        color: #fff;
        font-size: 1.2rem;
        z-index: 1060;
        cursor: pointer;
      }

      .sidebar-toggle:hover {
        background: rgba(255, 255, 255, 0.3);
      }
    }


    .navbar,
    .main-content {
      margin-left: 260px;
      transition: all 0.3s ease;
    }

    .sidebar.collapsed~.navbar,
    .sidebar.collapsed~.main-content {
      margin-left: 70px !important;
    }

    @media (max-width: 768px) {

      .navbar,
      .main-content {
        margin-left: 0 !important;
      }
    }
  </style>
</head>

<body>


  <div class="sidebar-overlay" id="sidebarOverlay"></div>


  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <img src="/new_project_bk/uploads/cars/LOGO.jpg" alt="Logo">
      <div class="title-slogan">
        <span class="title">A.F.B</span>
        <span class="slogan">Rent Cars with Style!</span>
      </div>
    </div>

    <ul class="nav flex-column mb-auto mt-3">
      <li class="nav-item">
        <a href="/new_project_bk/views/general/brands/list.php" class="nav-link fw-semibold" data-tooltip="Brand-et">
          <i class="bi bi-tags-fill me-2 fs-5"></i>
          <span class="nav-link-text">Brand-et</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="/new_project_bk/views/general/categories/list.php" class="nav-link fw-semibold" data-tooltip="Kategoritë">
          <i class="bi bi-grid-fill me-2 fs-5"></i>
          <span class="nav-link-text">Kategoritë</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="/new_project_bk/views/general/cars/list.php" class="nav-link fw-semibold" data-tooltip="Makina">
          <i class="bi bi-car-front-fill me-2 fs-5"></i>
          <span class="nav-link-text">Makina</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="/new_project_bk/views/general/reservations/list.php" class="nav-link fw-semibold" data-tooltip="Rezervime">
          <i class="bi bi-calendar-check-fill me-2 fs-5"></i>
          <span class="nav-link-text">Rezervime</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="/new_project_bk/views/general/client_management/list.php" class="nav-link fw-semibold" data-tooltip="Klientët">
          <i class="bi bi-people-fill me-2 fs-5"></i>
          <span class="nav-link-text">Klientët</span>
        </a>
      </li>
    </ul>
  </div>


  <nav class="navbar navbar-expand-lg navbar-dark shadow-sm bg-dark">
    <div class="container-fluid">
      <button class="btn btn-sm btn-outline-light me-3 d-none d-md-block" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
      </button>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white fs-5" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-1 fs-5"></i> Përdoruesi
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="#"><i class="bi bi-person-lines-fill me-2"></i> Profili</a></li>
              <li><a class="dropdown-item" href="#"><i class="bi bi-gear-fill me-2"></i> Cilësimet</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i> Dil</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>




  <div class="main-content" style="padding:20px;">
    <h2>Dashboard</h2>
    <p>Kjo është faqja kryesore e panelit.</p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function toggleSidebar() {
      if (window.innerWidth > 768) {
        sidebar.classList.toggle('collapsed');
      }
    }


    function handleResponsiveSidebar() {
      if (window.innerWidth <= 768) {
        sidebar.classList.remove('collapsed');
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
        toggle.querySelector('i').classList.remove('bi-arrow-left');
        toggle.querySelector('i').classList.add('bi-arrow-right');
      } else if (window.innerWidth <= 992) {
        sidebar.classList.add('collapsed');
      } else {
        sidebar.classList.remove('collapsed');
      }
    }

    window.addEventListener('resize', handleResponsiveSidebar);
    handleResponsiveSidebar();
  </script>
</body>

</html>