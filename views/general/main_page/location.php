<?php
include_once '../../../index.php';
?>
<!DOCTYPE html>
<html lang="sq">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Future Block - Luxury Car Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #fb9b50;
            --secondary: #5b84c4;
            --dark: #1a1a1a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f8ff;
            overflow-x: hidden;
        }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 15px 30px;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(15px);
            transition: all 0.5s ease;
            animation: slideDown 0.8s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .navbar.scrolled {
            background: rgba(0, 0, 0, 0.85);
            padding: 10px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .navbar .container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
            height: 95px;
            width: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
            display: block;

        }

        .logo:hover img {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .logo img {
                height: 35px;
            }
        }

        .navbar.scrolled .logo img {
            height: 60px;
        }

        .logo:hover img {
            transform: scale(1.1) rotate(2deg);
        }

        .nav-links {
            display: flex;
            gap: 35px;
            list-style: none;
            align-items: center;
        }

        .nav-links li a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-links li a::before {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transition: all 0.4s ease;
            transform: translateX(-50%);
        }

        .nav-links li a:hover::before {
            width: 100%;
        }

        .nav-links li a:hover {
            color: var(--primary);
        }

        .nav-links .phone a {
            background: linear-gradient(135deg, var(--primary), #ff7e3a);
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.4s ease;
        }

        .nav-links .phone a:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(251, 155, 80, 0.4);
        }

        .hero-section {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .hero-section video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: 0;
            transform: translate(-50%, -50%);
            object-fit: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            top: 50%;
            transform: translateY(-50%);
        }

        .hero-content h1 {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: white;
        }

        .hero-content p {
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        .cta-button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #fb9b50, #ff7e3a);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            transform: scale(1.05);
        }

        #video-toggle {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 3;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            font-size: 1.5rem;
            padding: 10px 15px;
            border-radius: 50%;
            cursor: pointer;
        }

        /* SECTIONS */
        .section-block {
            display: flex;
            align-items: center;
            gap: 60px;
            padding: 120px 80px;
            opacity: 0;
            transform: translateY(50px);
            transition: all 1s ease;
        }

        .section-block.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .section-block.reverse {
            flex-direction: row-reverse;
        }

        .text-block {
            flex: 1;
        }

        .text-block h2 {
            font-size: 3rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 25px;
            position: relative;
            display: inline-block;
        }

        .text-block h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
        }

        .text-block p {
            font-size: 0.8rem;
            line-height: 1.8;
            color: #555;
        }

        .image-block {
            flex: 1;
        }

        .image-block img {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            transition: all 0.6s ease;
        }

        .image-block img:hover {
            transform: scale(1.05);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
        }

        /* MAP */
        .map-section {
            padding: 80px 40px;
            text-align: center;
        }

        .map-section h1 {
            font-size: 2.5rem;
            margin-bottom: 50px;
            color: var(--dark);
        }

        .map-section iframe {
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* PARTNERS */
        .partners-section {
            padding: 100px 40px;
            background: linear-gradient(135deg, var(--dark), #2a2a2a);
            overflow: hidden;
        }

        .partners-section h2 {
            text-align: center;
            font-size: 3rem;
            color: white;
            margin-bottom: 60px;
        }

        .partner-marquee {
            overflow: hidden;
            position: relative;
            padding: 40px 0;
        }

        .partner-track {
            display: flex;
            gap: 80px;
            animation: scroll 30s linear infinite;
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .partner-track img {
            height: 80px;
            transition: all 0.4s ease;
        }

        .partner-track img:hover {
            filter: brightness(1) invert(0);
            opacity: 1;
            transform: scale(1.3);
        }

        .footer-section {
            font-family: 'Poppins', sans-serif;
            background: #1a1a1a;
            color: #fff;
            padding: 60px 20px 20px;
            line-height: 1.6;
        }

        .footer-top {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
            margin-bottom: 50px;
        }

        .footer-top h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #f1f1f1;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-top p {
            font-size: 14px;
            color: #ccc;
        }

        .footer-top .change-region {
            display: inline-block;
            margin-top: 5px;
            font-size: 14px;
            color: #fb9b50;
            text-decoration: underline;
            cursor: pointer;
        }

        .footer-top .region-block p::before {

            display: inline-block;
            width: 20px;
            height: 14px;
            margin-right: 6px;
            vertical-align: middle;
        }

        .footer-top .newsletter-form {
            margin-top: 10px;
            display: flex;
            gap: 8px;
        }

        .footer-top .newsletter-form input {
            flex: 1;
            padding: 8px 10px;
            border-radius: 5px;
            border: 1px solid #555;
            background: #222;
            color: #fff;
        }

        .footer-top .newsletter-form input::placeholder {
            color: #aaa;
        }

        .footer-top .newsletter-form button {
            padding: 8px 16px;
            background: #f37a1dff;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 13px;
            cursor: pointer;
            text-transform: uppercase;
            transition: background 0.3s ease;
        }

        .footer-top .newsletter-form button:hover {
            background: #f5a160ff;
        }

        .footer-top .social-icons {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .footer-top .social-icons a {
            color: #fb9b50;
            text-decoration: none;
            font-size: 14px;
        }

        .footer-top .social-icons a:hover {
            text-decoration: underline;
        }

        .footer-top .company-block ul {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }

        .footer-top .company-block li {
            margin-bottom: 6px;
        }

        .footer-top .company-block a {
            color: #fb9b50;
            text-decoration: none;
            font-size: 14px;
        }

        .footer-top .company-block a:hover {
            text-decoration: underline;
        }

        .footer-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 40px;
        }

        .footer-column {
            flex: 1 1 250px;
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 12px;
        }

        .footer-column h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #fff;
        }

        .footer-column p,
        .footer-column li {
            font-size: 14px;
            color: #ccc;
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-column ul li::before {
            content: "– ";
            color: #fb9b50;
        }

        .footer-column a {
            color: #fb9b50;
            text-decoration: none;
        }

        .footer-column a:hover {
            text-decoration: underline;
        }

        .footer-column.cta a {
            display: inline-block;
            padding: 10px 20px;
            background: #f37a1dff;
            color: #fff;
            border-radius: 25px;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .footer-column.cta a:hover {
            background: #fcab6dff;
        }

        .footer-column.social-extra a {
            display: inline-block;
            margin-right: 10px;
            margin-top: 5px;
        }

        .footer-column.social-extra img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .footer-column.social-extra img:hover {
            transform: scale(1.1);
        }

        .footer-bottom {
            border-top: 1px solid #333;
            padding-top: 20px;
            font-size: 13px;
            color: #aaa;
        }

        .footer-bottom .copyright {
            margin-bottom: 10px;
            font-weight: 500;
            text-align: center;
        }

        .footer-bottom .legal-links {
            margin-bottom: 10px;
            line-height: 1.8;
            text-align: center;
        }

        .footer-bottom .legal-links a {
            color: #fb9b50;
            text-decoration: none;
        }

        .footer-bottom .legal-links a:hover {
            text-decoration: underline;
        }

        .footer-bottom .legal-text p {
            font-size: 12px;
            color: #bbb;
            line-height: 1.6;
            text-align: center;
        }

        @media (max-width: 1024px) {
            .footer-top {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .footer-container {
                flex-direction: column;
            }
        }

        @media (max-width: 700px) {
            .footer-top {
                grid-template-columns: 1fr;
            }

            .footer-top .newsletter-form {
                flex-direction: column;
            }

            .footer-top .newsletter-form button {
                width: 100%;
            }

            .footer-column {
                flex: 1 1 100%;
            }

            .footer-column.social-extra a {
                margin-right: 15px;
            }
        }

        @media (max-width: 992px) {
            .section-block {
                flex-direction: column !important;
            }

            .hero-content h1 {
                font-size: 2.5rem;
            }

        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <a href="<?= BASE_URL ?>views/general/home/list.php" class="logo collapsed-sidebar-logo" id="navbar-brand-box img">
                <img src="<?= UPLOADS_URL ?>cars/LOGO.png" alt="Logo">
            </a>
            <ul class="nav-links">
                <li><a href="<?= GENERAL_URL ?>main_page/list.php">Home</a></li>
                <li><a href="<?= GENERAL_URL ?>main_page/list.php">Book Now</a></li>
                <li><a href="<?= GENERAL_URL ?>main_page/our_fleet.php">Our Fleet</a></li>
                <li class="phone"><a href="https://wa.me/355695555556" target="_blank">+355 69 555 5556</a></li>
                <li class="nav-item dropdown ms-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="page-header-user-dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= BASE_URL ?>public/assets/images/users/avatar-1.jpg" alt="Header Avatar" class="rounded-circle me-2" width="35" height="35">
                        <span>
                            <span class="fw-semibold user-name-text">Emiljano Perhati</span><br>
                            <span class="text-muted fs-12 user-name-sub-text">Founder</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="page-header-user-dropdown" style="min-width: 200px;">
                        <li>
                            <h6 class="dropdown-header text-center mb-1 text-dark">Welcome Emiljano!</h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center text-muted" href="<?= BASE_URL ?>views/general/home/list.php">
                                <i class="mdi mdi-account-circle text-primary fs-16 me-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center text-muted" href="<?= BASE_URL ?>views/general/home/list.php">
                                <i class="mdi mdi-lock text-primary fs-16 me-2"></i> Lock screen
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center text-muted" href="<?= BASE_URL ?>controllers/logout.php">
                                <i class="mdi mdi-logout text-danger fs-16 me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <section class="hero-section">

        <video id="hero-video" autoplay muted loop playsinline poster="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800">
            <source src="https://v.ftcdn.net/04/89/28/91/700_F_489289172_T37vHr3L2NjedSpGRXwvs33EWHvyac2f_ST.mp4" type="video/mp4">
            Your browser does not support HTML5 video.
        </video>


        <div class="hero-content">
            <h1>Drive Your Dreams</h1>
            <p>Experience luxury and performance</p>
            <a href="#fleet" class="cta-button">Explore Cars</a>
        </div>


        <button id="video-toggle" title="Pause Video">⏸️</button>
    </section>

    <div class="content-section" id="auto-future">
        <div class="section-block">
            <div class="text-block">
                <h2>Auto Future Block</h2>
                <p>
                    Auto Future Block është udhëheqës në industrinë e qirasë së makinave në Shqipëri, duke ofruar një gamë të gjerë automjetesh luksoze dhe premium për çdo nevojë. Ne sigurojmë një eksperiencë të qetë dhe të personalizuar për klientët tanë, duke përfshirë shërbime të shpejta rezervimi, asistencë 24/7, dhe mundësi fleksibile të qiradhënies për periudha të shkurtra apo të gjata. Çdo automjet është i mirëmbajtur me standarde të larta sigurie dhe pastërtie, duke garantuar rehati dhe performancë maksimale gjatë çdo udhëtimi. Ne synojmë të krijojmë eksperiencën perfekte të vozitjes, qoftë për klientë lokalë apo vizitorë ndërkombëtarë, duke e kombinuar luksin me besueshmërinë dhe shërbimin e shkëlqyer.
                </p>

            </div>
            <div class="image-block">
                <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800" alt="Cars">
            </div>
        </div>
    </div>


    <div class="content-section" id="karriera"></div>
    <div class="section-block reverse">
        <div class="text-block">
            <h2>Karriera</h2>
            <p>
                Bashkohu me ekipin tonë të talentuar dhe inovativ në Auto Future Block! Ne ofrojmë një ambient pune dinamik, ku kreativiteti dhe profesionalizmi vlerësohen çdo ditë. Punonjësit tanë kanë mundësi të zhvillohen profesionalisht përmes trajnimeve të avancuara, mentorimit dhe projekteve sfiduese që forcojnë aftësitë e tyre. Ne besojmë në promovimin e talenteve të brendshme, ofrojmë rritje karriere të qëndrueshme dhe një paketë përfitimesh konkurruese që përfshin fleksibilitet, shëndetësi dhe stimuj motivues. Nëse dëshiron të rritesh profesionalisht dhe të kontribuosh në suksesin e një kompanie lider në industrinë e qiradhënies së makinave luksoze, kjo është mundësia jote ideale!
            </p>
        </div>

        <div class="image-block">
            <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800" alt="Career">
        </div>
    </div>
    </div>

    <div class="content-section" id="qendrueshmeria">
        <div class="section-block">
            <div class="text-block">
                <h2>Qëndrueshmëria</h2>
                <p>
                    Angazhimi ynë për mjedisin është prioritet kryesor në Auto Future Block. Ne zbatojmë praktika të qëndrueshme në çdo aspekt të biznesit tonë, nga menaxhimi i flotës së makinave me teknologji energjie të pastër, përdorimi i materialeve të riciklueshme dhe reduktimi i mbeturinave deri tek optimizimi i konsumit të energjisë në zyrat dhe pikat tona. Përveç kësaj, ne promovojmë ngasjen e përgjegjshme dhe edukimin e klientëve tanë mbi përdorimin e efikas të burimeve. Synimi ynë është të krijojmë një industri të qiradhënies së makinave që jo vetëm ofron eksperiencë luksoze, por gjithashtu ruan dhe mbështet mjedisin për brezat e ardhshëm.
                </p>
            </div>
            <div class="image-block">
                <img src="https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=800" alt="Sustainability">
            </div>
        </div>
    </div>


    <div class="content-section" id="pikat-tona"></div>
    <div class="map-section">
        <h1>Our location</h1>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2996.852531726948!2d19.805350276461905!3d41.31207160066284!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x135031293aa19c85%3A0xbd392fe96905553a!2sFuture%20Block%20Group!5e0!3m2!1sen!2s!4v1763128719276!5m2!1sen!2s" width="100%" height="600" style="border:0;" allowfullscreen=""></iframe>
    </div>
    </div>

    <div class="content-section" id="partnere">
        <div class="partners-section">
            <h2>Partnerët Tanë Globalë</h2>
            <div class="partner-marquee">
                <div class="partner-track">
                    <img src="/new_project_bk/uploads/chat.robot/partner2.png" alt="Mercedes-Benz">
                    <img src="/new_project_bk/uploads/chat.robot/partner3.png" alt="BMW">
                    <img src="/new_project_bk/uploads/chat.robot/partner4.png" alt="Ferrari">
                    <img src="/new_project_bk/uploads/chat.robot/partner8.png" alt="Jaguar">
                    <img src="/new_project_bk/uploads/chat.robot/partner9.png" alt="Rolls-Royce">
                    <img src="/new_project_bk/uploads/chat.robot/partner10.png" alt="Bentley">
                    <img src="/new_project_bk/uploads/chat.robot/partner12.png" alt="McLaren">
                </div>

            </div>
        </div>
    </div>


    <footer class="footer-section">

        <div class="footer-top">
            <div class="region-block">
                <h3>Rajoni / Gjuha</h3>
                <div class="region-select">
                    <button id="current-region">🇦🇱 Shqipëri / Shqip ▼</button>
                    <ul id="region-list">
                        <li data-flag="🇦🇱" data-country="Shqipëri" data-lang="Shqip">🇦🇱 Shqipëri / Shqip</li>
                        <li data-flag="🇺🇸" data-country="USA" data-lang="English">🇺🇸 USA / English</li>
                        <li data-flag="🇬🇧" data-country="UK" data-lang="English">🇬🇧 UK / English</li>
                        <li data-flag="🇩🇪" data-country="Germany" data-lang="Deutsch">🇩🇪 Gjermani / Deutsch</li>
                        <li data-flag="🇫🇷" data-country="France" data-lang="Français">🇫🇷 Francë / Français</li>
                        <li data-flag="🇪🇬" data-country="Egypt" data-lang="English">🇪🇬 Egypt / English</li>
                    </ul>
                </div>
            </div>

            <div class="newsletter-block">
                <h3>Newsletter</h3>
                <p>Lajmet më të fundit direkt në email-in tuaj</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Vendos email-in tuaj">
                    <button type="submit">Abonohu</button>
                </form>
            </div>

            <div class="social-block">
                <h3>Rrjetet Sociale</h3>
                <p>Na ndiqni në rrjetet sociale.</p>

                <div class="social-icons">
                    <a href="https://www.facebook.com/faverent" target="_blank">Facebook</a>
                    <a href="https://www.instagram.com/emiljano_perhati" target="_blank">Instagram</a>
                    <a href="https://www.pinterest.com/faverent" target="_blank">Pinterest</a>
                    <a href="https://www.youtube.com/@faverent" target="_blank">YouTube</a>
                    <a href="https://twitter.com/faverent" target="_blank">Twitter</a>
                    <a href="https://www.linkedin.com/company/faverent" target="_blank">LinkedIn</a>
                </div>
            </div>

            <div class="company-block">
                <h3>Kompania</h3>
                <ul>
                    <li><a href="#auto-future">Auto Future Block</a></li>
                    <li><a href="#pikat-tona">Pikat tona</a></li>
                    <li><a href="#karriera">Karriera</a></li>
                    <li><a href="#komunikata">Komunikata për shtyp</a></li>
                    <li><a href="#qendrueshmeria">Qëndrueshmëria</a></li>
                    <li><a href="#partnere">Partnerë Globalë</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-container">

            <div class="footer-column contact-info">
                <h3>Kontakt</h3>
                <p>Tirana International Airport, Uzina, Tirana 1504, Albania</p>
                <p><a href="mailto:info@faverent.al">info@faverent.al</a></p>
                <p class="phone-number">+355 69 55 55 556</p>
            </div>

            <div class="footer-column services">
                <h3>Shërbime</h3>
                <ul class="services">
                    <li><a href="#nightparties">Rent For Night Parties</a></li>
                    <li><a href="#weddings">Rent For Weddings</a></li>
                    <li><a href="#airport">Rent For Airport Transfers</a></li>
                    <li><a href="#casinos">Rent For Casinos</a></li>
                    <li><a href="#birthdays">Rent For Birthdays</a></li>
                </ul>
            </div>

            <div class="footer-column cta">
                <h3>Rezervo makinën tënde!</h3>
                <a href="<?= GENERAL_URL ?>main_page/list.php" target="_blank">Rezervo Tani</a>
            </div>

            <div class="footer-column social-extra">
                <h3>Na kontakto</h3>
                <a href="https://www.instagram.com/emiljano_perhati/" target="_blank">
                    <img src="/new_project_bk/uploads/chat.robot/instagram.png"
                        style="width:40px; height:40px; object-fit:contain;">
                </a>

                <a href="https://wa.me/355695555556" target="_blank">
                    <img src="/new_project_bk/uploads/chat.robot/social.png"
                        style="width:40px; height:40px; object-fit:contain;">
                </a>
            </div>
        </div>

        <div class="footer-bottom">

            <p class="copyright">
                © 2025 Të gjitha të drejtat e rezervuara për Auto Future Block dhe licencuesit e saj.
            </p>

            <div class="legal-links">
                <a href="#">Kushtet e përdorimit</a> |
                <a href="#">Politika e Privatësisë</a> |
                <a href="#">Cookies</a> |
                <a href="#">Rregulloret</a> |
                <a href="#">Markat tregtare</a> |
                <a href="#">Deklarata kundër skllavërisë</a> |
                <a href="#">Kushtet e UGC</a> |
                <a href="#">Strategjia e taksave</a> |
                <a href="#">Skema e pensioneve</a> |
                <a href="#">Deklarata S172</a> |
                <a href="#">Open Source Software Notice</a> |
                <a href="#">Sistemi i sinjalizimit</a> |
                <a href="#">Kodi i Sjelljes Porsche</a> |
                <a href="#">EU Data Act</a>
            </div>

            <div class="legal-text">
                <p>
                    * Të dhënat e performancës janë bazuar në standardin WLTP. Për automjetet hibride,
                    distanca elektrike varet nga ngarkesa e baterisë dhe kushtet e ngasjes.
                </p>
                <p>
                    ** Informacion i rëndësishëm rreth modeleve elektrike mund të gjendet këtu.
                </p>
            </div>

        </div>

    </footer>

    <script>
        window.addEventListener('scroll', () => {
            document.querySelector('.navbar').classList.toggle('scrolled', window.scrollY > 50);
        });

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        });

        document.querySelectorAll('.section-block').forEach(el => observer.observe(el));
    </script>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const currentBtn = document.getElementById('current-region');
    const regionList = document.getElementById('region-list');

    currentBtn.addEventListener('click', () => {
        regionList.style.display = regionList.style.display === 'block' ? 'none' : 'block';
    });

    regionList.querySelectorAll('li').forEach(li => {
        li.addEventListener('click', () => {
            currentBtn.textContent = li.textContent;
            regionList.style.display = 'none';
        });
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.region-select')) {
            regionList.style.display = 'none';
        }
    });
</script>

<script>
    $(document).ready(function() {

        function loadCars(serviceType) {
            let pickup_date = $('[name="pickup_date"]').val() || new Date().toISOString().split('T')[0];
            let pickup_time = $('[name="pickup_time"]').val() || '09:00';
            let dropoff_date = $('[name="dropoff_date"]').val() || new Date().toISOString().split('T')[0];
            let dropoff_time = $('[name="dropoff_time"]').val() || '23:59:59';
            $('.car-grid').remove();
            $('#availableCars').html('<div class="spinner-container"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');

            $.ajax({
                url: '/new_project_bk/helper/reservations.php',
                type: 'POST',
                data: {
                    action: 'search_cars',
                    pickup_date,
                    pickup_time,
                    dropoff_date,
                    dropoff_time,
                    service_type: serviceType
                },
                dataType: 'json',
                success: function(cars) {
                    const serviceLabels = {
                        'all': 'Të gjitha shërbimet',
                        'Weddings': 'Dasmë',
                        'Night Parties': 'Night Party',
                        'Airport Transfers': 'Transfer Aeroporti',
                        'Casinos': 'Kazino',
                        'Birthdays': 'Ditëlindje',
                        'Business': 'Biznes'
                    };

                    let html = '';
                    if (cars.length > 0) {
                        html += `<h3 style="text-align:center;margin-bottom:20px;">Makinat për: <strong>${serviceLabels[serviceType] || serviceType}</strong></h3><div class="car-grid">`;

                        cars.forEach(car => {
                            html += `
                        <div class="car-card">
                            <img src="${car.image}" alt="${car.model}" class="car-image">
                            <div class="car-content">
                                <h3 class="car-name">${car.model}</h3>
                                <div class="car-specs">
                                    <span class="spec">👥 ${car.seats || 5} vende</span>
                                    <span class="spec">⚙️ ${car.transmission || 'Manual'}</span>
                                    <span class="spec">🚗 ${car.type || 'Sedan'}</span>
                                </div>
                                <div class="car-specs" style="margin-top:8px;">
                                    <span class="spec" style="background:#e3f2fd;color:#1976d2;font-weight:600;">
                                        ${serviceLabels[car.service_type] || car.service_type}
                                    </span>
                                </div>
                                <div class="car-footer">
                                    <div class="car-price-clean"><span class="price-value">${car.price_per_day}€</span> / ditë</div>
                                    <button class="btn btn-success btn-sm reserve-btn"
                                        data-car-id="${car.id}"
                                        data-car-name="${car.model}"
                                        data-pickup-date="${pickup_date}"
                                        data-pickup-time="${pickup_time}"
                                        data-dropoff-date="${dropoff_date}"
                                        data-dropoff-time="${dropoff_time}"
                                        data-service-type="${car.service_type}">
                                        Rezervo
                                    </button>
                                </div>
                            </div>
                        </div>`;
                        });

                        html += '</div>';
                    } else {
                        html = `<div style="text-align:center;padding:40px;"><h3>Nuk ka makina për: <strong>${serviceLabels[serviceType] || serviceType}</strong></h3></div>`;
                    }

                    $('#availableCars').html(html);

                    $('.reserve-btn').off('click').on('click', function() {
                        let carId = $(this).data('car-id');
                        let carName = $(this).data('car-name');
                        let pickupDate = $(this).data('pickup-date');
                        let pickupTime = $(this).data('pickup-time');
                        let dropoffDate = $(this).data('dropoff-date');
                        let dropoffTime = $(this).data('dropoff-time');
                        let serviceType = $(this).data('service-type');

                        $('#addReservationModal select[name="car_id"]').val(carId);
                        $('#addReservationModal input[name="start_date"]').val(pickupDate);
                        $('#addReservationModal input[name="time"]').val(pickupTime);
                        $('#addReservationModal input[name="end_date"]').val(dropoffDate);
                        $('#addReservationModal input[name="service_type"]').val(serviceType);

                        $('#addReservationModal').modal('show');
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Gabim gjatë marrjes së makinave.');
                }
            });
        }

        $('.services a').on('click', function(e) {
            e.preventDefault();
            let href = $(this).attr('href');
            let serviceType = 'all';
            if (href.includes('#nightparties')) serviceType = 'Night Parties';
            else if (href.includes('#weddings')) serviceType = 'Weddings';
            else if (href.includes('#airport')) serviceType = 'Airport Transfers';
            else if (href.includes('#casinos')) serviceType = 'Casinos';
            else if (href.includes('#birthdays')) serviceType = 'Birthdays';
            else if (href.includes('#business')) serviceType = 'Business';

            loadCars(serviceType);
        });

        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(el => new bootstrap.Tooltip(el, {
            html: true,
            placement: 'top',
            trigger: 'hover focus'
        }));

        setTimeout(() => {
            const alert = document.getElementById('alertMessage');
            if (alert) alert.remove();
        }, 5000);

        const backdrop = document.getElementById('customBackdrop');
        const modals = document.querySelectorAll('.modal');
        if (backdrop) {
            modals.forEach(modal => {
                modal.addEventListener('show.bs.modal', () => backdrop.style.display = 'block');
                modal.addEventListener('hidden.bs.modal', () => backdrop.style.display = 'none');
            });
        }

        let currentReservationModalId = null;
        document.querySelectorAll('.add-client-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentReservationModalId = this.dataset.currentReserveModal;
            });
        });

        const addClientForm = document.getElementById('addClientForm');
        if (addClientForm) {
            addClientForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;

                fetch('/new_project_bk/helper/save_client_ajax.php', {
                    method: 'POST',
                    body: formData
                }).then(res => res.json()).then(data => {
                    submitBtn.disabled = false;
                    const msgDiv = document.getElementById('clientFormMessage');

                    if (data.success) {
                        msgDiv.innerHTML = '<div class="alert alert-success">Klienti u ruajt me sukses!</div>';

                        if (currentReservationModalId) {
                            const reserveSelect = document.querySelector('#' + currentReservationModalId + ' select.reserve-client-select');
                            if (reserveSelect) {
                                const option = new Option(data.client_name, data.client_id, true, true);
                                reserveSelect.appendChild(option);
                                reserveSelect.value = data.client_id;
                            }
                            const reserveModal = new bootstrap.Modal(document.getElementById(currentReservationModalId));
                            reserveModal.show();
                        }

                        const addClientModal = bootstrap.Modal.getInstance(document.getElementById('addClientModal'));
                        if (addClientModal) addClientModal.hide();
                        addClientForm.reset();
                        currentReservationModalId = null;
                        setTimeout(() => msgDiv.innerHTML = '', 3000);
                    } else {
                        msgDiv.innerHTML = '<div class="alert alert-danger">' + (data.message || 'Gabim në ruajtjen e klientit') + '</div>';
                    }
                }).catch(err => {
                    submitBtn.disabled = false;
                    const msgDiv = document.getElementById('clientFormMessage');
                    if (msgDiv) msgDiv.innerHTML = '<div class="alert alert-danger">Gabim në server</div>';
                    console.error(err);
                });
            });
        }

    });
</script>

<script>
    const chatRobot = document.getElementById('chatRobot');
    const chatWidget = document.getElementById('chatWidget');
    const chatClose = document.getElementById('chatClose');
    const chatBody = document.getElementById('chatBody');
    const chatInput = document.getElementById('chatInput');
    const chatSendBtn = document.getElementById('chatSendBtn');

    let lastBotResponse = null;

    const appendMessage = (msg, sender = 'bot') => {
        const div = document.createElement('div');
        div.classList.add('chat-message', sender === 'bot' ? 'chat-bot' : 'chat-user');
        div.innerHTML = msg;
        chatBody.appendChild(div);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    const sendMessage = async (payload, autoAppend = true) => {
        try {
            const res = await fetch('/new_project_bk/helper/chatHandler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            lastBotResponse = data;
            if (autoAppend && data.reply) appendMessage(data.reply, 'bot');
            return data;
        } catch (err) {
            console.error(err);
            appendMessage('Gabim gjatë komunikimit me serverin', 'bot');
        }
    }

    const greetOnOpen = async () => await sendMessage({
        event: 'open'
    });

    const showReservationCalendar = (startDate) => {
        if (document.querySelector('.chat-calendar')) return;

        const calendarDiv = document.createElement('div');
        calendarDiv.classList.add('chat-calendar');
        calendarDiv.innerHTML = `
        <div class="calendar-box">
            <p><strong>Zgjidh datat e rezervimit:</strong></p>
            <label>Data e nisjes:</label>
            <input type="date" id="start-date" min="${startDate}" value="${startDate}">
            <label>Data e mbarimit:</label>
            <input type="date" id="end-date" min="${startDate}">
            <button id="confirm-reservation">Rezervo</button>
        </div>
    `;
        chatBody.appendChild(calendarDiv);
        chatBody.scrollTop = chatBody.scrollHeight;

        const oldBtn = document.getElementById('confirm-reservation');
        if (oldBtn) oldBtn.replaceWith(oldBtn.cloneNode(true));

        document.getElementById('confirm-reservation').addEventListener('click', async () => {
            const start = document.getElementById('start-date').value;
            const end = document.getElementById('end-date').value;

            if (!start || !end) {
                alert("Zgjidh datat e rezervimit!");
                return;
            }

            appendMessage(`Rezervimi nga ${start} deri më ${end}.`, 'user');

            const res = await sendMessage({
                action: 'reserve',
                start_date: start,
                end_date: end
            }, false);
            if (res.reply) appendMessage(res.reply, 'bot');

            document.querySelector('.chat-calendar')?.remove();
        });
    }

    const handleUserMessage = async (msg) => {
        appendMessage(msg, 'user');

        if (lastBotResponse && lastBotResponse.expected_confirmations && lastBotResponse.next_available_date) {
            const positiveWords = lastBotResponse.expected_confirmations.map(w => w.toLowerCase());
            if (positiveWords.some(w => msg.toLowerCase().includes(w))) {
                showReservationCalendar(lastBotResponse.next_available_date);
                return;
            }
        }

        await sendMessage({
            message: msg
        });
    }

    chatInput.addEventListener('keypress', async e => {
        if (e.key === 'Enter' && chatInput.value.trim() !== '') {
            const msg = chatInput.value.trim();
            chatInput.value = '';
            await handleUserMessage(msg);
        }
    });

    chatSendBtn?.addEventListener('click', async () => {
        const msg = chatInput.value.trim();
        if (!msg) return;
        chatInput.value = '';
        await handleUserMessage(msg);
    });

    chatRobot.addEventListener('click', async () => {
        if (chatWidget.style.display !== 'flex') {
            chatWidget.style.display = 'flex';
            chatInput.focus();
            await greetOnOpen();
        } else {
            chatWidget.style.display = 'none';
        }
    });

    chatClose.addEventListener('click', () => chatWidget.style.display = 'none');
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
<script>
    $(function() {
        const USER_ID = <?php echo $_SESSION['user_id'] ?? 0; ?>;
        const IS_ADMIN = <?php echo $_SESSION['is_admin'] ?? 0; ?>;
        const GUEST_ID = <?php echo $_SESSION['guest_id'] ?? 0; ?>;

        const CURRENT_USER_TYPE = USER_ID > 0 ? (IS_ADMIN ? 'admin' : 'user') : 'guest';
        const CURRENT_USER_ID = USER_ID > 0 ? USER_ID : GUEST_ID;

        let selectedContact = null;
        let loadedMessageIds = new Set();
        let contactsData = {};
        let pollInterval = null;
        let typingTimeout = null;
        let lastMessageDates = new Map();

        Pusher.logToConsole = true;
        const pusher = new Pusher('d0652d5ed102a0e6056c', {
            cluster: 'eu',
            useTLS: true
        });
        const myChannelName = `chat-${CURRENT_USER_TYPE}-${CURRENT_USER_ID}`;
        const myChannel = pusher.subscribe(myChannelName);

        myChannel.bind('pusher:subscription_succeeded', () => console.log('Subscribed to:', myChannelName));

        myChannel.bind('new-message', function(msg) {
            if (!loadedMessageIds.has(msg.id)) {
                const contactKey = `${msg.sender_type}-${msg.sender_id===CURRENT_USER_ID?msg.receiver_id:msg.sender_id}`;
                if (contactsData[contactKey]) {
                    if (!selectedContact || selectedContact.contact_id != msg.sender_id) {
                        contactsData[contactKey].unread_count++;
                        updateUnreadBadge();
                    }
                    contactsData[contactKey].last_message = msg.message;
                    contactsData[contactKey].last_message_time = msg.created_at;
                    contactsData[contactKey].is_mine = msg.sender_id === CURRENT_USER_ID;
                    renderContacts();
                }
                if (selectedContact && (msg.sender_id == selectedContact.contact_id || msg.receiver_id == selectedContact.contact_id)) {
                    appendMessage(msg);
                }
                if (!selectedContact || msg.sender_id != selectedContact.contact_id) {
                    if (Notification.permission === "granted") {
                        new Notification("Mesazh i ri", {
                            body: msg.message,
                            icon: '/new_project_bk/uploads/chat.robot/chat-icon.png'
                        });
                    }
                }
            }
        });

        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));

            if (days === 0) {
                return date.toLocaleTimeString('sq-AL', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
            if (days === 1) {
                return 'Dje';
            }
            if (days < 7) {
                return date.toLocaleDateString('sq-AL', {
                    weekday: 'long'
                });
            }
            return date.toLocaleDateString('sq-AL', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        function getDateSeparator(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const msgDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            const diff = today - msgDate;
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));

            if (days === 0) return 'SOT';
            if (days === 1) return 'DJE';
            if (days < 7) return date.toLocaleDateString('sq-AL', {
                weekday: 'long'
            }).toUpperCase();
            return date.toLocaleDateString('sq-AL', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
        }

        function needsDateSeparator(msgDate) {
            const dateKey = new Date(msgDate).toDateString();
            if (!lastMessageDates.has(dateKey)) {
                lastMessageDates.set(dateKey, true);
                return true;
            }
            return false;
        }

        function appendMessage(msg, scroll = true) {
            if (loadedMessageIds.has(msg.id)) return;
            loadedMessageIds.add(msg.id);

            const body = $('#chatUserBody');

            if (needsDateSeparator(msg.created_at)) {
                const separator = $('<div>').addClass('chat-date-separator').text(getDateSeparator(msg.created_at));
                body.append(separator);
            }

            const isMine = String(msg.sender_id) === String(CURRENT_USER_ID) && msg.sender_type === CURRENT_USER_TYPE;
            const bubble = $('<div>').addClass('chat-bubble').addClass(isMine ? 'my-message' : 'their-message');

            let content = '';
            if (msg.message) {
                const emojiOnly = /^[\p{Emoji}\s]+$/u.test(msg.message) && msg.message.length <= 6;
                if (emojiOnly) {
                    content += `<div class="emoji-large">${$('<div>').text(msg.message).html()}</div>`;
                } else {
                    content += $('<div>').text(msg.message).html();
                }
            }

            if (msg.file_path) {
                if (msg.file_path.match(/\.(jpeg|jpg|png|gif|webp)$/i)) {
                    content += `<div class="chat-image-wrapper"><img src="/new_project_bk/uploads/chat_files/${msg.file_path}" class="chat-image"/></div>`;
                } else {
                    content += `<br><a href="/new_project_bk/uploads/chat_files/${msg.file_path}" class="chat-file-link" target="_blank">📎 ${msg.file_path}</a>`;
                }
            }

            const time = new Date(msg.created_at).toLocaleTimeString('sq-AL', {
                hour: '2-digit',
                minute: '2-digit'
            });
            let statusIcon = '';
            if (isMine) {
                if (msg.is_seen) {
                    statusIcon = '<span class="msg-status seen">✓✓</span>';
                } else if (msg.is_delivered) {
                    statusIcon = '<span class="msg-status delivered">✓✓</span>';
                } else {
                    statusIcon = '<span class="msg-status sent">✓</span>';
                }
            }

            content += `<div class="msg-time">${time} ${statusIcon}</div>`;
            bubble.html(content);
            body.append(bubble);

            if (scroll) body.scrollTop(body[0].scrollHeight);
        }

        function updateUnreadBadge() {
            const totalUnread = Object.values(contactsData).reduce((sum, c) => sum + (c.unread_count || 0), 0);
            let badge = $('#chatUserIcon .unread-badge');

            if (totalUnread > 0) {
                if (badge.length === 0) {
                    badge = $('<span class="unread-badge"></span>');
                    $('#chatUserIcon').append(badge);
                }
                badge.text(totalUnread > 99 ? '99+' : totalUnread);
            } else {
                badge.remove();
            }
        }

        function fetchContacts() {
            $.post('/new_project_bk/helper/send_message.php', {
                action: 'fetch_contacts'
            }, function(resp) {
                if (!resp.success) return;
                contactsData = {};
                resp.contacts.forEach(c => {
                    contactsData[`${c.contact_type}-${c.contact_id}`] = c;
                });
                renderContacts();
                updateUnreadBadge();
            }, 'json');
        }

        function renderContacts() {
            const $list = $('#chatContacts').empty();
            Object.values(contactsData)
                .sort((a, b) => new Date(b.last_message_time) - new Date(a.last_message_time))
                .forEach(c => {
                    const unread = c.unread_count > 0 ? `<span class="unread-count">${c.unread_count}</span>` : '';
                    const time = c.last_message_time ? formatTime(c.last_message_time) : '';
                    const lastMsg = c.last_message || 'Nuk ka mesazhe';

                    let msgPreview = lastMsg;
                    if (c.is_mine) {
                        const statusIcon = c.is_seen ? '✓✓' : '✓';
                        msgPreview = `<span class="msg-status ${c.is_seen ? 'seen' : 'delivered'}">${statusIcon}</span> ${lastMsg}`;
                    }

                    const $item = $(`
                    <div class="contact-item ${selectedContact && selectedContact.contact_id === c.contact_id ? 'active' : ''}" data-id="${c.contact_id}" data-type="${c.contact_type}">
                        <div class="contact-header">
                            <span class="contact-name">${c.contact_name}</span>
                            <span class="contact-time">${time}</span>
                        </div>
                        <div class="contact-preview">
                            <div class="contact-last-message">${msgPreview}</div>
                            ${unread}
                        </div>
                    </div>
                `);
                    $item.off('click').on('click', () => selectContact(c));
                    $list.append($item);
                });
        }

        function selectContact(contact) {
            selectedContact = contact;
            $('#receiver_id').val(contact.contact_id);
            $('#receiver_type').val(contact.contact_type);

            const headerHtml = `
            <div class="chat-header-info">
                <div class="chat-header-name">${contact.contact_name}</div>
                <div class="chat-header-status ${contact.is_online ? 'status-online' : ''}">
                    ${contact.is_online ? 'online' : (contact.last_seen ? 'last seen ' + formatTime(contact.last_seen) : 'offline')}
                </div>
            </div>
            <span class="close-chat" id="chatUserClose">✕</span>
        `;
            $('#chatUserHeader').html(headerHtml);

            $('#chatUserBody').empty();
            loadedMessageIds.clear();
            lastMessageDates.clear();

            contact.unread_count = 0;
            renderContacts();
            updateUnreadBadge();

            fetchMessages();
            if (pollInterval) clearInterval(pollInterval);
            pollInterval = setInterval(fetchMessages, 3000);

            $('#chatUserClose').off('click').on('click', () => $('#chatUserWidget').hide());
        }

        function fetchMessages() {
            if (!selectedContact) return;
            $.post('/new_project_bk/helper/send_message.php', {
                action: 'fetch_messages',
                receiver_id: selectedContact.contact_id,
                receiver_type: selectedContact.contact_type
            }, function(resp) {
                if (!resp.success) return;
                resp.messages.forEach(m => appendMessage(m, false));
                $('#chatUserBody').scrollTop($('#chatUserBody')[0].scrollHeight);
            }, 'json');
        }

        $('#chatUserForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            if (!selectedContact) return alert('Zgjidhni një kontakt!');

            const msg = $('#chatUserInput').val().trim();
            const fileInput = $('#chatUserFile')[0];
            if (!msg && (!fileInput || fileInput.files.length === 0)) return;

            const fd = new FormData();
            fd.append('action', 'send');
            fd.append('receiver_id', selectedContact.contact_id);
            fd.append('receiver_type', selectedContact.contact_type);
            fd.append('message', msg);
            if (fileInput && fileInput.files.length > 0) fd.append('file', fileInput.files[0]);

            $('#chatUserInput').val('');
            $('#chatUserFile').val('');

            $.ajax({
                url: '/new_project_bk/helper/send_message.php',
                method: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(resp) {
                    if (resp.success) {
                        appendMessage(resp.message_data);
                        const contactKey = `${selectedContact.contact_type}-${selectedContact.contact_id}`;
                        if (contactsData[contactKey]) {
                            contactsData[contactKey].last_message = resp.message_data.message;
                            contactsData[contactKey].last_message_time = resp.message_data.created_at;
                            contactsData[contactKey].is_mine = true;
                            renderContacts();
                        }
                    }
                }
            });
        });

        $('#chatFileIcon').on('click', () => $('#chatUserFile').click());

        $('#chatUserIcon').on('click', () => {
            $('#chatUserWidget').toggle();
            if ($('#chatUserWidget').is(':visible')) {
                fetchContacts();
            }
        });

        $('#chatUserClose').on('click', () => $('#chatUserWidget').hide());

        if (Notification.permission !== "granted" && Notification.permission !== "denied") {
            Notification.requestPermission();
        }

        $(document).ready(() => {
            $('#chatUserWidget').hide();
            fetchContacts();
            updateUnreadBadge();
        });
    });
</script>

<script>
    const video = document.getElementById('heroVideo');
    const btn = document.getElementById('pauseBtn');

    btn.addEventListener('click', () => {
        if (video.paused) {
            video.play();
            btn.textContent = 'Pause';
        } else {
            video.pause();
            btn.textContent = 'Play';
        }
    });
</script>