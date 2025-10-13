<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>Auto Future Block</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="/new_project_bk/public/assets/images/favicon.ico">

    <link href="/new_project_bk/public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/new_project_bk/public/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/new_project_bk/public/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="/new_project_bk/public/assets/css/custom.min.css" rel="stylesheet" type="text/css" />

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        body,
        body * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .app-menu.mobile-open {
            transform: translateX(0);
        }

        #sidebar-toggle-btn {
            display: none;
            border: none;
            background: transparent;
            color: white;
            font-size: 16px;
            font-weight: 600;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 6px;
        }

        #sidebar-toggle-btn i {
            font-size: 20px;
        }

        @media (max-width: 768px) {
            #sidebar-toggle-btn {
                display: inline-flex;
                background: #1e1e2f;
            }

            .app-menu {
                position: fixed;
                top: 0;
                left: -260px;
                width: 260px;
                height: 100%;
                background: #1e1e2f;
                transition: 0.3s ease;
                z-index: 1200;
            }

            .app-menu.mobile-open {
                left: 0;
            }
        }

        body.dark-mode {
            background-color: #0d0d0d;
            color: #ffffff !important;
        }

        body.dark-mode *,
        body.dark-mode input,
        body.dark-mode select,
        body.dark-mode textarea,
        body.dark-mode table,
        body.dark-mode th,
        body.dark-mode td,
        body.dark-mode span,
        body.dark-mode strong,
        body.dark-mode b {
            color: #ffffff !important;
        }

        body.dark-mode .app-menu,
        body.dark-mode #layout-wrapper,
        body.dark-mode .navbar-header,
        body.dark-mode .navbar-brand-box,
        body.dark-mode input,
        body.dark-mode select,
        body.dark-mode textarea,
        body.dark-mode table,
        body.dark-mode th,
        body.dark-mode td,
        body.dark-mode .card,
        body.dark-mode .dropdown-menu,
        body.dark-mode .modal-content {
            background-color: #1a1a1a !important;
            border-color: #333333 !important;
            box-shadow: none !important;
        }

        body.dark-mode a.nav-link,
        body.dark-mode .menu-title,
        body.dark-mode .navbar-brand-box .logo-text,
        body.dark-mode .navbar-brand-box .full-logo span {
            color: #ffffff !important;
        }

        body.dark-mode input,
        body.dark-mode select,
        body.dark-mode textarea {
            background-color: #121212 !important;
            color: #ffffff !important;
            border-color: #333333 !important;
        }

        body.dark-mode .dropdown-menu {
            background-color: #1a1a1a !important;
            color: #ffffff !important;
            border: 1px solid #333333;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            z-index: 1500;
        }

        body.dark-mode .dropdown-menu h6.dropdown-header,
        body.dark-mode .dropdown-menu a.dropdown-item,
        body.dark-mode .dropdown-menu a.dropdown-item i {
            color: #ffffff !important;
        }

        body.dark-mode .dropdown-menu a.dropdown-item:hover {
            background-color: #333333 !important;
            color: #ffffff !important;
        }

        body.dark-mode .dropdown-menu .dropdown-divider {
            border-top: 1px solid #333333 !important;
        }

        body.dark-mode .dropdown-menu .dropdown-header {
            color: #bbbbbb !important;
        }

        body.dark-mode .topbar-user .btn,
        body.dark-mode .topbar-user .dropdown-toggle {
            background-color: #1a1a1a !important;
            color: #ffffff !important;
            border: none !important;
        }

        body.dark-mode .topbar-user .btn:hover,
        body.dark-mode .topbar-user .dropdown-toggle:hover {
            background-color: #333333 !important;
            color: #ffffff !important;
        }

        body.dark-mode .user-name-text,
        body.dark-mode .user-name-sub-text {
            color: #ffffff !important;
        }

        body.dark-mode #page-topbar,
        body.dark-mode .navbar-header {
            background-color: #1a1a1a !important;
            color: #ffffff !important;
            border-bottom: 1px solid #333333;
        }

        body.dark-mode .header-item .btn-topbar,
        body.dark-mode .header-item .btn-topbar i {
            color: #ffffff !important;
        }

        body.dark-mode .page-title-box {
            background-color: #1a1a1a !important;
            color: #ffffff !important;
            border-bottom: 1px solid #333333;
        }

        body.dark-mode .page-title-box .page-title {
            color: #ffffff !important;
        }

        body.dark-mode .page-title-box .btn {
            color: #ffffff !important;
            background-color: #333333 !important;
            border-color: #444444 !important;
        }

        body.dark-mode .page-title-box .btn:hover {
            background-color: #444444 !important;
            color: #ffffff !important;
        }

        body.dark-mode .footer {
            background-color: #1a1a1a !important;
            color: #ffffff !important;
            border-top: 1px solid #333333 !important;
        }

        body.dark-mode .footer a {
            color: #0dcaf0 !important;
        }

        body.dark-mode .footer a:hover {
            color: #ffffff !important;
        }

        body.dark-mode .modal-content {
            background-color: #1a1a1a !important;
            color: #ffffff !important;
            border: 1px solid #333333 !important;
        }

        body.dark-mode .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%) !important;
            opacity: 1 !important;
        }

        body.dark-mode .btn-close:hover {
            opacity: 0.8 !important;
        }

        body.dark-mode .swal2-popup {
            background-color: #1a1a1a !important;
            color: #ffffff !important;
        }

        body.dark-mode .swal2-title,
        body.dark-mode .swal2-content {
            color: #ffffff !important;
        }

        body.dark-mode .swal2-confirm {
            background-color: #a83232 !important;
            color: #ffffff !important;
        }

        body.dark-mode .swal2-cancel {
            background-color: #444444 !important;
            color: #ffffff !important;
        }

        [id^="reserveCarModal"].dark-mode .modal-content,
        [id^="viewCarModal"].dark-mode .modal-content {
            background-color: #1a1a1a;
            color: #f1f1f1;
            border: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        [id^="reserveCarModal"].dark-mode .btn-close,
        [id^="viewCarModal"].dark-mode .btn-close {
            filter: invert(1);
        }

        [id^="reserveCarModal"].dark-mode input,
        [id^="reserveCarModal"].dark-mode textarea,
        [id^="viewCarModal"].dark-mode input,
        [id^="viewCarModal"].dark-mode textarea {
            background-color: #333;
            color: #f1f1f1;
            border: 1px solid #555;
        }

        [id^="reserveCarModal"].dark-mode label,
        [id^="viewCarModal"].dark-mode label {
            color: #f1f1f1;
        }

        [id^="reserveCarModal"].dark-mode .btn,
        [id^="viewCarModal"].dark-mode .btn {
            border-radius: 0.25rem;
        }

        body.dark-mode .swal2-popup {
            background-color: #1a1a1a !important;
            color: #ffffff !important;
        }

        body.dark-mode .swal2-title,
        body.dark-mode .swal2-content {
            color: #ffffff !important;
        }

        body.dark-mode .swal2-confirm {
            background-color: #a83232 !important;
            color: #ffffff !important;
        }

        body.dark-mode .swal2-cancel {
            background-color: #444444 !important;
            color: #ffffff !important;
        }

        .navbar-brand-box a {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1px;
            padding-top: 8px;
            transition: all 0.3s ease;
        }

        .navbar-brand-box img {
            height: 70px;
            transition: all 0.3s ease;
        }

        .logo-text {
            font-weight: bold;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            text-align: center;
            color: #ffffffff;
            line-height: 1.8;
        }

        .full-logo {
            display: none;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 2px;
        }

        .full-logo span.title {
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            line-height: 1.2;
        }

        .full-logo span.slogan {
            font-size: 0.85rem;
            font-style: italic;
            color: #fff;
            line-height: 1.1;
        }

        .dropdown-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            display: none;
        }

        [data-bs-theme="dark"] .dropdown-backdrop {
            background: rgba(0, 0, 0, 0.75);
        }
    </style>

</head>

<body class="twocolumn-panel vertical-sidebar-enable">

    <div class="app-menu navbar-menu" id="sidebar">
        <div class="navbar-brand-box">
            <a href="/new_project_bk/views/general/home/list.php" class="logo collapsed-sidebar-logo">
                <img src="/new_project_bk/uploads/cars/LOGO.jpg" alt="Logo">
                <span class="logo-text">A.F.B</span>
                <div class="full-logo">
                    <span class="title">Auto Future Block</span>
                    <span class="slogan">Rent Cars with Style!</span>
                </div>
            </a>
        </div>
        <div id="scrollbar">
            <div class="container-fluid">
                <div id="two-column-menu"></div>
                <ul class="navbar-nav mt-3">
                    <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                    <li class="nav-item"><a href="/new_project_bk/views/general/home/list.php" class="nav-link fw-semibold"><i class="ri-home-4-line"></i> Home</a></li>
                    <li class="nav-item"><a href="/new_project_bk/views/general/brands/list.php" class="nav-link fw-semibold"><i class="ri-price-tag-line"></i> Brand-et</a></li>
                    <li class="nav-item"><a class="nav-link menu-link" href="/new_project_bk/views/general/categories/list.php"><i class="ri-layout-grid-fill"></i> Kategoritë</a></li>
                    <li class="nav-item"><a class="nav-link menu-link" href="/new_project_bk/views/general/cars/list.php"><i class="ri-car-fill"></i> Makina</a></li>
                    <li class="nav-item"><a href="/new_project_bk/views/general/client_management/list.php" class="nav-link fw-semibold"><i class="ri-user-add-fill"></i> Klientet</a></li>
                    <li class="nav-item"><a href="/new_project_bk/views/general/reservations/list.php" class="nav-link fw-semibold"><i class="ri-calendar-2-line"></i> Rezervime</a></li>
                    <li class="nav-item"><a href="/new_project_bk/views/general/order_status/list.php" class="nav-link fw-semibold"><i class="ri-compass-2-fill"></i> Gjendja e makines</a></li>
                    <li class="nav-item"><a href="/new_project_bk/views/general/sales_car/list.php" class="nav-link fw-semibold"><i class="ri-information-2-fill"></i> Statusi i shitjes</a></li>

                </ul>
            </div>
        </div>
    </div>

    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <button type="button" id="sidebar-toggle-btn"><i class="ri-arrow-right-line"></i> <span>Menu</span></button>
                        <form class="d-flex ms-2 position-relative" id="searchForm" method="GET" action="#">
                            <input type="text" id="search" name="q" placeholder="Kërko në katalog..." autocomplete="off" style="width:200px;padding:5px;">
                            <div id="suggestions" style="position:absolute; border:1px solid #ccc; max-height:200px; overflow-y:auto; background:#fff; z-index:1000; width:100%;"></div>
                        </form>

                        <script src="helper/search_live.js"></script>


                    </div>

                    <div class="d-flex align-items-center">
                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="btn-fullscreen">
                                <i class='bx bx-fullscreen fs-22' id="fullscreen-icon"></i>
                            </button>
                        </div>
                        <div class="ms-1 header-item d-none d-sm-flex">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode" id="darkModeToggle">
                                <i class='bx bx-moon fs-22'></i>
                            </button>
                        </div>

                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button class="btn" type="button" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user" src="/new_project_bk/public/assets/images/users/avatar-1.jpg" alt="Header Avatar" width="35" height="35">
                                    <span class="text-start ms-xl-2">
                                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">Emiljano Perhati</span>
                                        <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">Founder</span>
                                    </span>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="page-header-user-dropdown" style="z-index: 1500;">
                                <li>
                                    <h6 class="dropdown-header">Welcome Emiljano!</h6>
                                </li>
                                <li><a class="dropdown-item" href="/new_project_bk/views/general/home/list.php"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> Profile</a></li>
                                <li><a class="dropdown-item" href="/new_project_bk/views/general/home/list.php"><i class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> Lock screen</a></li>
                                <li><a class="dropdown-item" href="/new_project_bk/controllers/logout.php"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top" style="display: block;">
            <i class="ri-arrow-up-line"></i>
        </button>
    </div>

    <script src="/new_project_bk/public/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('sidebar-toggle-btn');
            const toggleIcon = toggleBtn?.querySelector('i');

            toggleBtn?.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('mobile-open');
                    toggleIcon.classList.toggle('ri-arrow-right-line');
                    toggleIcon.classList.toggle('ri-arrow-left-line');
                }
            });

            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && sidebar?.classList.contains('mobile-open')) {
                    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target) && !e.target.closest('.dropdown')) {
                        sidebar.classList.remove('mobile-open');
                        toggleIcon.classList.add('ri-arrow-right-line');
                        toggleIcon.classList.remove('ri-arrow-left-line');
                    }
                }
            });

            const btnFullscreen = document.getElementById("btn-fullscreen");
            const fullscreenIcon = document.getElementById("fullscreen-icon");
            btnFullscreen?.addEventListener("click", () => {
                if (!document.fullscreenElement) document.documentElement.requestFullscreen();
                else document.exitFullscreen();
            });
            document.addEventListener("fullscreenchange", () => {
                fullscreenIcon?.classList.toggle("bx-fullscreen");
                fullscreenIcon?.classList.toggle("bx-exit-fullscreen");
            });

            const darkModeToggle = document.getElementById("darkModeToggle");
            const icon = darkModeToggle?.querySelector("i");

            function applyDarkModeToModals() {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    if (document.body.classList.contains('dark-mode')) modal.classList.add('dark-mode');
                    else modal.classList.remove('dark-mode');
                });
            }

            if (localStorage.getItem("dark-mode") === "enabled") {
                document.body.classList.add("dark-mode");
                icon?.classList.replace("bx-moon", "bx-sun");
            }
            applyDarkModeToModals();

            darkModeToggle?.addEventListener("click", () => {
                document.body.classList.toggle("dark-mode");
                if (document.body.classList.contains("dark-mode")) {
                    localStorage.setItem("dark-mode", "enabled");
                    icon?.classList.replace("bx-moon", "bx-sun");
                } else {
                    localStorage.setItem("dark-mode", "disabled");
                    icon?.classList.replace("bx-sun", "bx-moon");
                }
                applyDarkModeToModals();
            });

            const observer = new MutationObserver(() => applyDarkModeToModals());
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });

            document.addEventListener('click', function(e) {
                const button = e.target.closest('.delete-btn');
                if (!button) return;
                e.preventDefault();
                const deleteUrl = button.href;
                const isDark = document.body.classList.contains('dark-mode');

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Jeni i sigurt?',
                        text: "Kjo nuk mund të anulohet!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Po, fshi!',
                        cancelButtonText: 'Anulo',
                        background: isDark ? '#1a1a1a' : '#fff',
                        color: isDark ? '#ffffff' : '#000000',
                        confirmButtonColor: isDark ? '#a83232' : '#3085d6',
                        cancelButtonColor: isDark ? '#444444' : '#aaa'
                    }).then(result => {
                        if (result.isConfirmed) window.location.href = deleteUrl;
                    });
                } else {
                    if (confirm("Jeni i sigurt që doni ta fshini?")) window.location.href = deleteUrl;
                }
            });

            const searchInput = document.getElementById('search');
            const suggestionsDiv = document.getElementById('suggestions');

            function highlight(text, term) {
                const re = new RegExp(`(${term})`, 'gi');
                return text.replace(re, '<mark>$1</mark>');
            }

            searchInput?.addEventListener('input', function() {
                const query = this.value.trim();
                suggestionsDiv.innerHTML = '';
                if (!query) return;

                fetch(`/new_project_bk/helper/search.php?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.classList.add('search-suggestion');
                            div.style.padding = 'px';
                            div.style.cursor = 'pointer';


                            let displayText = item.columns?.name || 'No name';

                            div.innerHTML = highlight(displayText, query);

                            div.addEventListener('click', () => {
                                if (item.table === 'Makina') {
                                    const modalId = `carModal${item.id}`;
                                    const modalEl = document.getElementById(modalId);
                                    if (modalEl) new bootstrap.Modal(modalEl).show();
                                } else {
                                    window.location.href = item.url + (item.id ? `?id=${item.id}` : '');
                                }
                                suggestionsDiv.innerHTML = '';
                                searchInput.value = '';
                            });

                            suggestionsDiv.appendChild(div);
                        });
                    })
                    .catch(err => console.error('Search error:', err));
            });

            document.addEventListener('click', function(e) {
                if (!searchInput?.contains(e.target) && !suggestionsDiv?.contains(e.target)) {
                    suggestionsDiv.innerHTML = '';
                }
            });

            const logo = document.querySelector('.collapsed-sidebar-logo');
            if (logo) {
                const AFBText = logo.querySelector('.logo-text');
                const fullLogo = logo.querySelector('.full-logo');

                logo.addEventListener('mouseenter', () => {
                    AFBText.style.display = 'none';
                    fullLogo.style.display = 'flex';
                });
                logo.addEventListener('mouseleave', () => {
                    AFBText.style.display = 'inline';
                    fullLogo.style.display = 'none';
                });
            }

            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            dropdownElementList.map(el => new bootstrap.Dropdown(el));

            const ctx = document.getElementById('salesChart')?.getContext('2d');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Shitjet e Makina',
                            data: [12, 19, 14, 18, 22, 20, 25, 30, 28, 26, 32, 35],
                            backgroundColor: '#032c69ff',
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                let count = 0;
                const step = target / 200;
                const updateCounter = () => {
                    if (count < target) {
                        count += step;
                        counter.innerText = Math.ceil(count);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCounter();
            });

            const cards = document.querySelectorAll('.card');
            window.addEventListener('load', () => {
                cards.forEach((card, index) => {
                    setTimeout(() => card.classList.add('show'), index * 100);
                });
            });

            const editModalEl = document.getElementById('editModal');
            const editModal = editModalEl ? new bootstrap.Modal(editModalEl) : null;
            const editForm = document.getElementById('editCarForm');

            document.querySelectorAll(".edit-btn").forEach(btn => {
                btn.addEventListener("click", () => {
                    editForm.id.value = btn.dataset.id;
                    editForm.name.value = btn.dataset.name;
                    editForm.price.value = btn.dataset.price;
                    editForm.seats.value = btn.dataset.seats;
                    editForm.transmission.value = btn.dataset.transmission;
                    editForm.type.value = btn.dataset.type;
                    editForm.rating.value = btn.dataset.rating;
                    editModal?.show();
                });
            });

            editForm?.addEventListener('submit', async e => {
                e.preventDefault();
                const formData = new FormData(editForm);
                try {
                    const res = await fetch('/new_project_bk/helper/updateCar.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await res.json();
                    if (result.success) {
                        editModal?.hide();
                        Swal.fire('Sukses!', 'Makina u përditësua me sukses.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Gabim!', result.message || 'Ndodhi një problem.', 'error');
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire('Gabim!', 'Gabim gjatë komunikimit me serverin.', 'error');
                }
            });

        });
    </script>
</body>

</html>