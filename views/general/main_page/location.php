<?php
include_once __DIR__ . '/../../../index.php';

include DB_PATH . 'db.php';
include_once HELPER_PATH . 'client_helper.php';
function getCarFiles()
{
    $carDir = realpath(__DIR__ . '/../../../uploads/cars');

    if ($carDir === false || !is_dir($carDir)) {
        $docRoot = getenv('DOCUMENT_ROOT') ?: null;
        if ($docRoot) {
            $possible = realpath($docRoot . '/new_project_bk/uploads/cars');
            if ($possible !== false && is_dir($possible)) {
                $carDir = $possible;
            }
        }
    }

    if ($carDir === false || !is_dir($carDir)) {
        return [];
    }

    $carFiles = glob($carDir . "/*.{jpg,png,jpeg,webp}", GLOB_BRACE);
    if ($carFiles === false) {
        return [];
    }

    return array_filter($carFiles, fn($file) => basename($file)[0] != '.');
}

$carFiles = getCarFiles();

$query = "SELECT id, full_name FROM clients ORDER BY full_name ASC";
$result = $mysqli->query($query);

$clients = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}


?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="<?= BASE_URL ?>public/assets/images/favicon.ico">
    <link href="<?= BASE_URL ?>public/assets/css/icons.min.css" rel="stylesheet" type="text/css" />



</head>

<body>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }

        .footer-section {
            background: #2f3236ff;
            color: #fff;
            padding: 50px 20px 20px;
        }

        .footer-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            gap: 30px;
        }

        .footer-column {
            flex: 1 1 250px;
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 15px;
        }

        .footer-column h3 {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #11224d;
        }

        .footer-column p,
        .footer-column li {
            font-size: 0.95rem;
            color: #f1f1f1;
            line-height: 1.6;
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
        }

        .footer-column ul li::before {
            content: "– ";
            color: #2c599d;
        }

        .footer-column a {
            color: #2c599d;
            text-decoration: none;
        }

        .footer-column a:hover {
            text-decoration: underline;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 40px;
            font-size: 0.9rem;
            color: #cccccc;
        }

        .footer-bottom a {
            color: #11224d;
            text-decoration: none;
        }

        .footer-bottom a:hover {
            text-decoration: underline;
        }

        .footer-section .services ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-section .services ul li {
            margin-bottom: 10px;
        }

        .footer-section .services ul li a {
            text-decoration: none;
            color: #ffffff;
            transition: color 0.3s ease;
        }

        .footer-section .services ul li a:hover {
            color: #2c599d;
        }

        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column;
                text-align: center;
                gap: 25px;
            }

            .footer-column {
                flex: unset;
            }
        }




        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f5f8ffff;
            background-size: cover;
            background-position: center;
        }

        .navbar {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 15px 30px;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            display: flex;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .navbar .container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo {
            color: #fb9b50;
            font-size: 1.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .navbar .logo:hover {
            transform: scale(1.1);
        }

        .nav-links {
            display: flex;
            gap: 25px;
            list-style: none;
            align-items: center;
            color: #fff
        }

        .nav-links li a {
            text-decoration: none;
            color: #fff;
            font-weight: 500;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-links li a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background: #5b84c4;
            transition: width 0.3s ease;
        }

        .nav-links li a:hover::after {
            width: 100%;
        }

        .nav-links li a:hover {
            color: #5b84c4;
        }

        .nav-links .phone a {
            background: #fb9b50;
            color: #f1f3f5ff;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            border: 2px solid #fb9b50;
            transition: all 0.3s ease;
        }

        .nav-links .phone a:hover {
            background: transparent;
            color: #fb9b50;
            transform: translateY(-2px);
            box-shadow: 0 0 8px rgba(255, 213, 79, 0.4);
        }



        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: #fff;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .nav-links {
                position: fixed;
                top: 60px;
                right: -100%;
                width: 200px;
                height: calc(100% - 60px);
                background: rgba(0, 0, 0, 0.9);
                flex-direction: column;
                padding: 20px;
                gap: 15px;
                transition: right 0.3s ease;
            }

            .nav-links.active {
                right: 0;
            }

            .hamburger {
                display: flex;
            }
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

        .fleet-section {
            position: relative;
            background-image: url("/new_project_bk/uploads/chat.robot/background.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 0 40px;
        }

        .fleet-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 1;
        }

        .fleet-content {
            position: relative;
            z-index: 2;
            max-width: 900px;
            text-align: left;
            transform: translateX(-10%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            line-height: 1.2;
        }

        .fleet-title {
            font-size: 6rem;
            font-weight: 900;
            color: #FFB800;
            margin: 0;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.7);
            transform: translateX(-70%);
        }

        .fleet-subtitle {
            font-size: 1.8rem;
            color: #fff;
            margin-top: 10px;
            line-height: 1.3;
        }


        .fleet-content {
            animation: slideFadeLeft 1s ease forwards;
        }

        @keyframes slideFadeLeft {
            0% {
                opacity: 0;
                transform: translateX(-50px);
            }

            100% {
                opacity: 1;
                transform: translateX(-10%);
            }
        }

        @media (max-width: 768px) {
            .fleet-section {
                justify-content: center;
            }

            .fleet-content {
                transform: translateX(0);
                text-align: center;
            }

            .fleet-title {
                font-size: 3rem;
            }

            .fleet-subtitle {
                font-size: 1.2rem;
                line-height: 1.2;
            }
        }

        .location-btn {
            padding: 14px 28px;
            margin-bottom: 30px;
            font-size: 18px;
            font-weight: 600;
            background-color: #fd903dff;
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;

        }

        .location-btn:hover {
            background-color: #fd903dff;
            transform: translateY(-3px) scale(1.05);

        }
    </style>

    <style>
        .chat-image-wrapper {
            margin: 8px 0;
            max-width: 100%;
        }

        .chat-image {
            max-width: 250px;
            max-height: 300px;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s;
            display: block;
        }

        .chat-image:hover {
            transform: scale(1.02);
        }

        .chat-file-link {
            display: inline-block;
            padding: 8px 12px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            text-decoration: none;
            color: inherit;
            margin: 4px 0;
            transition: background 0.2s;
        }

        .chat-file-link:hover {
            background: rgba(0, 0, 0, 0.15);
        }

        .my-message .chat-image-wrapper {
            text-align: right;
        }

        .their-message .chat-image-wrapper {
            text-align: left;
        }

        #chatRobot {
            position: fixed;
            bottom: 30px;
            right: 16px;
            z-index: 3002;
            cursor: pointer;
        }

        #chatRobot img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s;
        }

        #chatRobot img:hover {
            transform: scale(1.2);
        }

        #chatWidget {
            display: none;
            flex-direction: column;
            position: fixed;
            bottom: 100px;
            right: 90px;
            width: 360px;
            height: 520px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            z-index: 3001;
        }

        #chatHeader {
            background: #075E54;
            color: #fff;
            padding: 10px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #chatHeader span.close-chat {
            font-size: 18px;
            cursor: pointer;
        }

        #chatBody {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: #e5ddd5;
        }

        .chat-message {
            padding: 6px 10px;
            border-radius: 12px;
            max-width: 85%;
            word-break: break-word;
        }

        .chat-user {
            background: #34b7f1;
            color: #fff;
            align-self: flex-end;
        }

        .chat-bot {
            background: #fff;
            color: #000;
            align-self: flex-start;
        }

        #chatInputWrapper {
            display: flex;
            align-items: center;
            padding: 5px;
            border-top: 1px solid #ddd;
            background: #fff;
        }

        #chatInput {
            flex: 1;
            padding: 8px 40px 8px 35px;
            border-radius: 18px;
            border: 1px solid #ccc;
            position: relative;
            outline: none;
            background-size: 20px 20px;
        }


        #chatUserWidget {
            display: none;
            position: fixed;
            bottom: 120px;
            right: 90px;
            width: 550px;
            height: 650px;
            background: #f0f2f5;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: row;
            overflow: hidden;
            z-index: 3002;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #chatSidebar {
            width: 150px;
            background: #fff;
            border-right: 1px solid #ccc;
            overflow-y: auto;
        }

        #chatSidebarHeader {
            padding: 16px;
            font-weight: bold;
            background: #075E54;
            color: #fff;
            text-align: center;
            font-size: 18px;
        }

        #chatContacts {
            flex: 1;
            overflow-y: auto;
        }

        .contact-item {
            padding: 10px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            flex-direction: column;
        }

        .contact-item:hover {
            background: #f0f0f0;
        }

        .contact-item.active {
            background: #dcf8c6;
        }

        .unread-count {
            background: red;
            color: #fff;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 12px;
            align-self: flex-end;
        }

        #chatArea {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        #chatUserHeader {
            background: #075E54;
            color: #fff;
            padding: 16px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
        }

        #chatUserBody {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background: #f9f9f9;
        }

        #chatUserHeader .close-chat {
            cursor: pointer;
            font-size: 12px;
        }

        #chatUserBody,
        #chatBody {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 8px;
            background-color: #e5ddd5;
            background-image: url("/new_project_bk/uploads/chat.robot/whatsapp.png");
            background-repeat: repeat;
            background-size: cover;
            background-position: center;
        }


        .chat-bubble {
            padding: 10px 14px;
            border-radius: 20px;
            max-width: 75%;
            word-break: break-word;
            font-size: 14px;
            line-height: 1.4;
        }

        .my-message {
            background-color: #dcf8c6;
            color: #000;
            align-self: flex-end;
        }

        .their-message {
            background-color: #fff;
            color: #000;
            align-self: flex-start;
        }

        .msg-time {
            font-size: 10px;
            color: #666;
            margin-top: 4px;
            text-align: right;
        }

        #chatInputWrapper {
            display: flex;
            gap: 8px;
            padding: 10px;
            border-top: 1px solid #ccc;
            background: #fff;
        }

        #chatUserInput {
            flex: 1;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        #chatInputWrapper input[type="text"] {
            flex: 1;
            padding: 10px 14px 10px 18px;
            border-radius: 25px;
            border: 1px solid #ccc;
            outline: none;
            background-size: 20px 20px;
        }

        #chatInputWrapper input[type="file"] {
            display: none;
        }

        #chatUserIcon {
            position: fixed;
            bottom: 100px;
            right: 16px;
            z-index: 3003;
            cursor: pointer;
        }

        #chatUserIcon .chat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s;
        }

        #chatUserIcon .chat-icon:hover {
            transform: scale(1.2);
        }



        #chatInputWrapper button {
            padding: 10px 14px;
            border-radius: 50%;
            border: none;
            background: #075E54;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        #chatInputWrapper button:hover {
            background: #064d46;
            transform: scale(1.05);
        }

        #chatUserBody::-webkit-scrollbar,
        #chatContacts::-webkit-scrollbar {
            width: 6px;
        }

        #chatUserBody::-webkit-scrollbar-thumb,
        #chatContacts::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }

        .chat-date-separator {
            text-align: center;
            background: rgba(0, 0, 0, 0.1);
            color: #555;
            font-size: 12px;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 12px;
            margin: 10px auto;
            max-width: 60%;
        }

        .chat-calendar {
            background: #f4f6f8;
            border-radius: 10px;
            padding: 10px;
            margin: 10px 0;
        }

        .chat-calendar input {
            display: block;
            width: 100%;
            margin: 6px 0;
            padding: 5px;
        }

        .chat-calendar button {
            margin-top: 8px;
            background: #007bff;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .chat-calendar button:hover {
            background: #0056b3;
        }

        #chatBody::-webkit-scrollbar,
        #chatUserBody::-webkit-scrollbar,
        #chatContacts::-webkit-scrollbar {
            width: 6px;
        }

        #chatBody::-webkit-scrollbar-thumb,
        #chatUserBody::-webkit-scrollbar-thumb,
        #chatContacts::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }
    </style>

    <div id="chatRobot">
        <img src="/new_project_bk/uploads/chat.robot/Chat.jpg" alt="Chat Robot" style="width:60px; height:60px; border-radius:50%; box-shadow:0 4px 15px rgba(0,0,0,0.3);">
    </div>
    <div id="chatWidget">
        <div id="chatHeader">
            Chat Auto Future Block
            <span id="chatClose" style="float:right; cursor:pointer;">✖</span>
        </div>
        <div id="chatBody"></div>
        <div style="padding:10px; border-top:1px solid #ddd; display:flex; gap:10px; align-items:center;">
            <input id="chatInput" type="text" placeholder="Shkruaj mesazhin..."
                style="flex:1; padding:10px 13px; font-size:14px; border-radius:23px; border:1px solid #ccc; outline:none;">
        </div>

    </div>

    <div id="chatUserIcon">
        <img src="/new_project_bk/uploads/chat.robot/mm.jpg" class="chat-icon" alt="Chat">
    </div>

    <div id="chatUserWidget">
        <div id="chatSidebar">
            <div id="chatSidebarHeader">Kontakti</div>
            <div id="chatContacts"></div>
        </div>

        <div id="chatArea">
            <div id="chatUserHeader">
                <span id="chatUserTitle">Biseda</span>
                <span id="chatUserClose" class="close-chat">✖</span>
            </div>

            <div id="chatUserBody"></div>

            <form id="chatUserForm">
                <input type="hidden" id="receiver_id" name="receiver_id">
                <input type="hidden" id="receiver_type" name="receiver_type">

                <div id="chatInputWrapper">
                    <span id="chatFileIcon" style="cursor:pointer; font-size:24px; margin-right:8px;">📷</span>
                    <input type="file" id="chatUserFile" name="file" style="display:none;">
                    <input type="text" id="chatUserInput" name="message" placeholder="Shkruaj mesazhin...">
                    <button type="submit">➤</button>
                </div>
            </form>
        </div>
    </div>

    <div class="main-content">
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



        <div class="page-content">
            <h1 style=" padding: 5px 10px; 
             border-radius: 5px; text-align: center; margin-top: 130px;">
                Book with us for a seamless journey, where convenience <br>meets reliability in every rental.
            </h1>
            <div style="width: 100%; display: flex; justify-content: center; margin-top: 30px; margin-bottom: 30px;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2996.852531726948!2d19.805350276461905!3d41.31207160066284!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x135031293aa19c85%3A0xbd392fe96905553a!2sFuture%20Block%20Group!5e0!3m2!1sen!2s!4v1763128719276!5m2!1sen!2s" width="100%" height="650" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <footer class="footer-section">
                <div class="footer-container">
                    <div class="footer-column contact-info">
                        <h3>Contact</h3>
                        <p>Tirana International Airport, Uzina, Tirana 1504, Albania</p>
                        <p><a href="mailto:info@faverent.al">info@faverent.al</a></p>
                        <p class="phone-number">+355 69 55 55 556</p>
                    </div>

                    <div class="footer-column cta">
                        <h3>Rent a car now!</h3>
                        <a href="<?= GENERAL_URL ?>main_page/list.php" target="_blank">Book Now</a>
                    </div>
                </div>

                <div class="footer-bottom">
                    <p style="position: relative; padding-right: 80px;">
                        Copyright © 2025 <strong>Auto Future Block</strong> | Powered by
                        <a href="<?= BASE_URL ?>views/general/order_status/list.php" target="_blank">FutureBlock.al</a>

                        <span style="position: absolute; right: 600; top: 50%; transform: translateY(-50%); display: flex; gap: 12px;">
                            <a href="https://www.instagram.com/username/" target="_blank">
                                <img src="/new_project_bk/uploads/chat.robot/instagram.png"
                                    style="width:40px; height:40px; object-fit:contain;">
                            </a>

                            <a href="https://wa.me/355695555556" target="_blank">
                                <img src="/new_project_bk/uploads/chat.robot/social.png"
                                    style="width:40px; height:40px; object-fit:contain;">
                            </a>
                        </span>
                    </p>
                </div>
            </footer>
        </div>
    </div>
    </div>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    const BASE_URL = "<?php echo BASE_URL; ?>";
    const UPLOADS_URL = "<?php echo UPLOADS_URL; ?>";
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

<script>
    const USER_ID = <?php echo $_SESSION['user_id'] ?? 0; ?>;
    const IS_ADMIN = <?php echo $_SESSION['is_admin'] ?? 0; ?>;
    const GUEST_ID = <?php echo $_SESSION['guest_id'] ?? 0; ?>;

    let selectedContact = null;
    let lastDate = '';
    let pollInterval = null;
    let isFetching = false;
    let isSending = false;

    const CURRENT_USER_TYPE = USER_ID > 0 ? (IS_ADMIN ? 'admin' : 'user') : 'guest';
    const CURRENT_USER_ID = USER_ID > 0 ? USER_ID : GUEST_ID;

    function formatTime(dateStr) {
        const d = new Date(dateStr);
        return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
    }

    function formatDateSeparator(dateStr) {
        const d = new Date(dateStr);
        const dateKey = d.toDateString();
        if (dateKey !== lastDate) {
            lastDate = dateKey;
            return `<div class="chat-date-separator">${d.toLocaleDateString('sq-AL', {weekday:'long', day:'2-digit', month:'2-digit', year:'numeric'})}</div>`;
        }
        return '';
    }

    function isImageFile(filename) {
        if (!filename) return false;
        const ext = filename.toLowerCase().split('.').pop();
        return ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'].includes(ext);
    }

    function formatFileAttachment(filePath) {
        if (!filePath) return '';
        const fullPath = `/new_project_bk/uploads/chat_files/${filePath}`;
        if (isImageFile(filePath)) {
            return `<div class="chat-image-wrapper"><a href="${fullPath}" target="_blank"><img src="${fullPath}" class="chat-image" style="max-width:200px;border-radius:6px;"></a></div>`;
        } else {
            const fileName = filePath.split('/').pop();
            return `<a href="${fullPath}" target="_blank" class="chat-file-link">📎 ${fileName}</a>`;
        }
    }

    function fetchContacts() {
        $.post('../../../helper/send_message.php', {
            action: 'fetch_contacts'
        }, function(resp) {
            if (!resp.success) {
                $('#chatContacts').html('<div style="padding:15px;text-align:center;color:red;">Gabim: ' + (resp.message || 'Unknown') + '</div>');
                return;
            }

            const $list = $('#chatContacts').empty();
            if (!resp.contacts || resp.contacts.length === 0) {
                $list.html('<div style="padding:15px;text-align:center;color:#999;">Nuk ka kontakte</div>');
                return;
            }

            resp.contacts.forEach(c => {
                const contactId = c.contact_id ?? 0;
                const contactType = c.contact_type ?? 'admin';
                const contactName = c.contact_name ?? 'N/A';
                const unread = c.unread_count > 0 ? `<span class="unread-count">${c.unread_count}</span>` : '';

                const $item = $(`
                <div class="contact-item" data-id="${contactId}" data-type="${contactType}">
                    <div style="display:flex;flex-direction:column;">
                        <strong>${contactName}</strong>
                        <small style="color:#666">${c.last_message ?? ''}</small>
                    </div>
                    ${unread}
                </div>
            `);

                $item.on('click', () => selectContact({
                    contact_id: contactId,
                    contact_name: contactName,
                    contact_type: contactType
                }));

                $list.append($item);
            });

            const first = resp.contacts[0];
            if (first) selectContact({
                contact_id: first.contact_id,
                contact_name: first.contact_name,
                contact_type: first.contact_type
            });
        }, 'json');
    }

    function selectContact(contact) {
        selectedContact = contact;
        $('#receiver_id').val(contact.contact_id);
        $('#receiver_type').val(contact.contact_type);

        let displayName = contact.contact_name;
        if (contact.contact_type === 'guest') displayName = `Guest #${contact.contact_id}`;
        $('#chatUserTitle').text('Biseda me: ' + displayName);

        lastDate = '';
        $('#chatUserBody').empty();

        $('.contact-item').removeClass('active');
        $(`.contact-item[data-id="${contact.contact_id}"][data-type="${contact.contact_type}"]`).addClass('active');

        fetchMessages(true);

        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(fetchMessages, 2000);
    }

    function fetchMessages(initial = false) {
        if (!selectedContact || isFetching || isSending) return;
        isFetching = true;

        $.post('../../../helper/send_message.php', {
            action: 'fetch_messages',
            receiver_id: selectedContact.contact_id,
            receiver_type: selectedContact.contact_type
        }, function(resp) {
            isFetching = false;
            if (!resp.success || !resp.messages) return;

            const $body = $('#chatUserBody');
            $body.empty();

            resp.messages.forEach(m => {
                const isMine = (m.sender_id == CURRENT_USER_ID && m.sender_type == CURRENT_USER_TYPE);
                const $msg = $('<div class="chat-bubble">').addClass(isMine ? 'my-message' : 'their-message');

                let content = '';
                if (m.message) content = $('<div>').text(m.message).html();
                if (m.file_path) {
                    if (content) content += '<br>';
                    content += formatFileAttachment(m.file_path);
                }

                const sep = formatDateSeparator(m.created_at);
                if (sep) $body.append(sep);

                $msg.html(content + `<div class="msg-time">${formatTime(m.created_at)}</div>`);
                $body.append($msg);
            });

            $body.scrollTop($body[0].scrollHeight);

        }, 'json').fail(() => isFetching = false);
    }

    $('#chatUserForm').on('submit', function(e) {
        e.preventDefault();
        console.log('sss');
        if (!selectedContact) {
            fetchContacts();
            return false;
        }
        if (isSending) return;

        const messageText = $('#chatUserInput').val().trim();
        const hasFile = $('#chatUserFile')[0].files.length > 0;
        if (!messageText && !hasFile) return;

        isSending = true;
        const fd = new FormData(this);
        fd.append('action', 'send');
        fd.set('receiver_id', selectedContact.contact_id);
        fd.set('receiver_type', selectedContact.contact_type);

        $('#chatUserInput').val('');
        $('#chatUserFile').val('');

        $.ajax({
            url: '../../../helper/send_message.php',
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(resp) {
                isSending = false;
                if (resp.success) {
                    fetchMessages(false);
                    fetchContacts();
                } else alert(resp.message || 'Gabim gjatë dërgimit');
            },
            error: function() {
                isSending = false;
                alert('Gabim në lidhje me serverin');
            }
        });
    });

    $('#chatFileIcon').on('click', () => $('#chatUserFile').click());

    $('#chatUserIcon').on('click', () => {
        const visible = $('#chatUserWidget').is(':visible');
        if (visible) {
            $('#chatUserWidget').fadeOut(150);
            if (pollInterval) clearInterval(pollInterval);
        } else {
            $('#chatUserWidget').fadeIn(150);
            fetchContacts();
        }
    });

    $('#chatUserClose').on('click', () => {
        $('#chatUserWidget').fadeOut(100);
        if (pollInterval) clearInterval(pollInterval);
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#chatUserWidget,#chatUserIcon').length) {
            $('#chatUserWidget').fadeOut(100);
            if (pollInterval) clearInterval(pollInterval);
        }
    });

    $('#chatUserInput').on('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if ($(this).val().trim() !== '') $('#chatUserForm').submit();
        }
    });

    $(document).ready(() => $('#chatUserWidget').hide());
</script>