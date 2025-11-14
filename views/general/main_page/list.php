<?php
include_once __DIR__ . '/../../../index.php';
include DB_PATH . 'db.php';
include_once HELPER_PATH . 'client_helper.php';

function getCarFiles()
{
    $carDir = $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/uploads/cars';
    $carFiles = glob($carDir . "/*.{jpg,png,jpeg,webp}", GLOB_BRACE);
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

        .booking-section {
            position: relative;
            background-image: url("/new_project_bk/uploads/chat.robot/background.jpg");
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 50px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        }

        .booking-overlay {
            position: absolute;

            inset: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;

        }

        .booking-box {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px 40px;
            width: 90%;
            max-width: 900px;
            color: #fff;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
            animation: fadeInUp 0.8s ease;
        }

        .booking-title {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 25px;
            color: #fff;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
        }

        .booking-form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            width: 100%;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .form-group label {
            font-size: 0.9rem;
            margin-bottom: 5px;
            font-weight: 500;
            color: #e5e7eb;
        }

        .form-group select,
        .form-group input {
            padding: 8px 10px;
            border-radius: 10px;
            border: none;
            outline: none;
            background: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            box-shadow: 0 0 8px #1E40AF;
        }

        .checkbox-inline {
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
            gap: 6px;
        }

        .search-btn {
            margin-top: 25px;
            background: #5b84c4;
            color: #fff;
            border: none;
            padding: 12px 40px;
            font-size: 1rem;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .search-btn:hover {
            background: #5b84c4;

            box-shadow: 0 6px 15px #5b84c4;
        }

        @keyframes fadeInUp {
            0% {
                transform: translateY(30px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
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

        .modal {
            z-index: 2000 !important;
            transition: transform 0.5s ease, opacity 0.5s ease;
        }

        .modal.fade .modal-dialog {
            transform: translateY(-50px);
            opacity: 0;
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
            opacity: 1;
        }

        .modal-backdrop {
            z-index: 1990 !important;
            background: rgba(0, 0, 0, 0.45);
        }

        .modal-dialog {
            margin-top: 50px;
        }



        .car-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .car-card {
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            height: 100%;
            position: relative;
        }

        .car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .car-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }

        .car-card:hover .car-image {
            transform: scale(1.05);
        }

        .car-content-wrapper {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            justify-content: space-between;
            padding: 15px;
        }

        .car-name {
            font-weight: bold;
            font-size: 1rem;
            margin-bottom: 6px;
            text-transform: uppercase;
            color: #333;
            text-align: center;
        }

        .car-specs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px;
            margin: 8px 0;
            color: #6c757d;
            font-size: 0.85rem;
        }

        .spec {
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .car-footer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            margin-top: 10px;
        }

        .car-price-clean {
            color: #0d8d62ff;
            font-weight: 700;
            font-size: 1.6rem;
            letter-spacing: 0.5px;
        }

        .car-price-clean .price-label {
            font-size: 0.9rem;
            color: #777;
            font-weight: 400;
        }

        .car-rating {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #f98125;
            color: #000;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 5px 8px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }



        .car-content {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
            padding: 15px;
            text-align: center;
        }

        .car-specs {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px;
            margin: 8px 0;
            color: #6c757d;
            font-size: 0.85rem;
        }

        .spec {
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .car-footer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            margin-top: auto;
        }

        .car-price-clean {
            color: #2c599d;
            font-weight: 700;
            font-size: 1.6rem;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .car-price-clean .price-label {
            font-size: 0.9rem;
            color: #777;
            font-weight: 400;
        }

        .spinner-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .spinner-container .spinner-border {
            width: 3rem;
            height: 3rem;
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

        .card.total-card.keep-color.show {
            transition: transform 0.3s ease, box-shadow 0.3s ease !important;
            cursor: pointer;
            overflow: hidden;
            border-radius: 15px;
        }

        .card.total-card.keep-color.show:hover {
            transform: scale(1.05) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2) !important;
            z-index: 10;
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

        #chatInputWrapper {
            display: flex;
            gap: 8px;
            padding: 10px;
            border-top: 1px solid #ccc;
            background: #fff;
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
                    <li><a href="#booking-title">Book Now</a></li>
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





            <div class="row mb-3">
                <div class="col-12">


                    <section class="booking-section">
                        <div class="booking-overlay">
                            <div class="booking-box">
                                <h2 id="booking-title">Rezervo makinën tënde tani</h2>
                                <form id="bookingForm" class="booking-form">
                                    <div class="form-grid">

                                        <div class="form-group">
                                            <label>Pick-up location</label>
                                            <input type="text" name="pickup_location" placeholder="TIA" required>
                                        </div>

                                        <div class="form-group checkbox-inline">
                                            <input type="checkbox" id="same-location" checked>
                                            <label for="same-location">Return car in same location</label>
                                        </div>

                                        <div class="form-group">
                                            <label>Pick-up date</label>
                                            <input type="date" name="pickup_date" value="2025-10-29" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Pick-up time</label>
                                            <input type="time" name="pickup_time" value="08:00" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Drop-off date</label>
                                            <input type="date" name="dropoff_date" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Drop-off time</label>
                                            <input type="time" name="dropoff_time" value="08:00" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Driver's country</label>
                                            <input type="text" name="country" value="Albania" required>
                                        </div>


                                        <div class="form-group">
                                            <label>Driver's age</label>
                                            <input type="text" name="age" placeholder="18 - 65" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Service Type</label>
                                            <select name="service_type" required>
                                                <option value="all">Të gjitha</option>
                                                <option value="Weddings">Dasmë</option>
                                                <option value="Night Parties">Night Party</option>
                                                <option value="Airport Transfers">Transfer Aeroporti</option>
                                                <option value="Casinos">Kazino</option>
                                                <option value="Birthdays">Ditëlindje</option>
                                                <option value="business">Biznes</option>
                                            </select>
                                        </div>

                                    </div>


                                    <button type="submit" class="search-btn">Search</button>
                                </form>
                            </div>
                        </div>
                    </section>

                </div>


                <div id="availableCars" style="margin-top:30px;"></div>


                <?php
                $allCars = [];
                $newCars = 0;
                $usedCars = 0;
                $uploadDir = '/new_project_bk/uploads/cars/';
                $query = "SELECT * FROM cars ORDER BY id DESC";
                $result = $mysqli->query($query);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        if (isset($row['category_id'])) {
                            if ($row['category_id'] == 1) $newCars++;
                            elseif ($row['category_id'] == 2) $usedCars++;
                        }
                        $firstImage = '';
                        if (!empty($row['images'])) {
                            $imagesArray = explode(',', $row['images']);
                            $firstImage = trim($imagesArray[0]);
                        }
                        $allCars[] = [
                            'id' => $row['id'],
                            'name' => $row['model'],
                            'image' => $uploadDir . $firstImage,
                            'rating' => $row['rating'] ?? (rand(3, 10) . '.' . rand(0, 9)),
                            'seats' => $row['seating_capacity'],
                            'transmission' => $row['transmission'],
                            'type' => $row['body_type'],
                            'price' => $row['price_per_day'],
                            'category_id' => $row['category_id'] ?? 0
                        ];
                    }
                }
                $totalCars = count($allCars);
                ?>




                <div class="car-grid">
                    <?php foreach ($allCars as $carData):
                        $modalId = "carModal" . $carData['id'];
                    ?>
                        <div class="car-card">
                            <img src="<?= htmlspecialchars($carData['image']) ?>" alt="<?= htmlspecialchars($carData['name']) ?>"
                                class="car-image" data-bs-toggle="modal" data-bs-target="#<?= $modalId ?>">
                            <div class="car-rating">⭐ <?= $carData['rating'] ?></div>
                            <div class="car-content">
                                <h3 class="car-name"><?= htmlspecialchars($carData['name']) ?></h3>
                                <div class="car-specs">
                                    <span class="spec">👥 <?= $carData['seats'] ?> vende</span>
                                    <span class="spec">⚙️ <?= htmlspecialchars($carData['transmission']) ?></span>
                                    <span class="spec">🚗 <?= htmlspecialchars($carData['type']) ?></span>
                                </div>
                                <div class="car-footer d-flex justify-content-between align-items-center">
                                    <div class="car-price-clean"> <span class="price-value"><?= $carData['price'] ?>€</span> <span class="price-label">/ditë</span></div>
                                    <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                                        <button class="btn btn-sm btn-outline-primary edit-btn"
                                            data-id="<?= $carData['id'] ?>"
                                            data-name="<?= htmlspecialchars($carData['name']) ?>"
                                            data-price="<?= $carData['price'] ?>"
                                            data-seats="<?= $carData['seats'] ?>"
                                            data-transmission="<?= htmlspecialchars($carData['transmission']) ?>"
                                            data-type="<?= htmlspecialchars($carData['type']) ?>"
                                            data-rating="<?= $carData['rating'] ?>">
                                            Edito
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body text-center p-4">
                                        <img src="<?= htmlspecialchars($carData['image']) ?>" class="img-fluid rounded mb-3" style="max-height:400px; object-fit:cover;">
                                        <h5 class="fw-semibold"><?= htmlspecialchars($carData['name']) ?></h5>
                                        <p class="text-muted mb-1"><?= htmlspecialchars($carData['type']) ?> | <?= htmlspecialchars($carData['transmission']) ?></p>
                                        <p class="fs-5 fw-bold " style="color: #2c599d;"> <?= $carData['price'] ?> €/ditë</p>
                                        <p class="text-muted small">⭐ <?= $carData['rating'] ?> | 💺 <?= $carData['seats'] ?> vende</p>
                                        <hr class="my-3">
                                        <p>Ky model makine ofron një eksperiencë të jashtëzakonshme udhëtimi. Sediljet janë të rehatshme dhe të rregullueshme sipas preferencave.</p>
                                        <p>Pajisjet teknologjike, përfshirë navigacionin, sistemin e ndihmës për parkim dhe asistencën e vozitjes, garantojnë një eksperiencë të sigurt.</p>
                                        <p>Pajisjet moderne të sigurisë, si airbag-et, ABS, kontrolli i stabilitetit dhe sistemi i paralajmërimit për rrezik.</p>
                                        <button class="btn btn-secondary mt-3" data-bs-dismiss="modal">Mbyll</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>


                <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form id="editCarForm">
                                <div class="modal-header">
                                    <h5 class="modal-title">Ndrysho të dhënat e makinës</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="editCarId" name="id">
                                    <div class="mb-3"><label class="form-label">Emri i makinës</label><input type="text" id="editCarName" name="name" class="form-control" required></div>
                                    <div class="mb-3"><label class="form-label">Çmimi (€ / ditë)</label><input type="number" id="editCarPrice" name="price" class="form-control" required></div>
                                    <div class="mb-3"><label class="form-label">Vende</label><input type="number" id="editCarSeats" name="seats" class="form-control" required></div>
                                    <div class="mb-3"><label class="form-label">Transmetimi</label><input type="text" id="editCarTransmission" name="transmission" class="form-control" required></div>
                                    <div class="mb-3"><label class="form-label">Tipi i makinës</label><input type="text" id="editCarType" name="type" class="form-control" required></div>
                                    <div class="mb-3"><label class="form-label">Rating</label><input type="number" step="0.1" id="editCarRating" name="rating" class="form-control" required></div>

                                    <div class="mb-3">
                                        <label class="form-label">Service Type</label>
                                        <select name="service_type" class="form-select" required>
                                            <option value="Weddings">Dasmë</option>
                                            <option value="Night Parties">Night Party</option>
                                            <option value="Airport Transfers">Transfer Aeroporti</option>
                                            <option value="Casinos">Kazino</option>
                                            <option value="birthday">Ditëlindje</option>
                                            <option value="Birthdays">Biznes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulo</button>
                                    <button type="submit" class="btn btn-primary">Ruaj Ndryshimet</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="addReservationModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content p-4">
                            <div class="modal-header">
                                <h5 class="modal-title">Shto Rezervim</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="<?= BASE_URL ?>helper/reservations.php" id="reservationForm">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Klienti</label>
                                        <div class="input-group">
                                            <select name="client_id" id="clientSelect" class="form-select" required>
                                                <option value="">Zgjidh klientin</option>
                                                <?php foreach ($clients as $cl): ?>
                                                    <option value="<?= $cl['id'] ?>"><?= htmlspecialchars($cl['full_name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="button" class="btn btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addClientModal"
                                                data-current-reserve-modal="addReservationModal">
                                                + Shto Klient
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Makina</label>
                                        <select name="car_id" id="carSelect" class="form-select" required>
                                            <option value="">Zgjidh makinen</option>
                                            <?php foreach ($allCars as $car): ?>
                                                <option value="<?= $car['id'] ?>"><?= htmlspecialchars($car['name'] . ' | ' . $car['price'] . '€/ditë') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Service Type</label>
                                        <input type="text" name="service_type" class="form-control" readonly style="background-color: #e9ecef;">
                                        <small class="text-muted">Service type është vendosur automatikisht nga kërkimi.</small>
                                    </div>

                                    <div class="row">
                                        <div class="col mb-3">
                                            <label class="form-label">Data Fillimit</label>
                                            <input type="date" name="start_date" class="form-control" required>
                                        </div>
                                        <div class="col mb-3">
                                            <label class="form-label">Ora Fillimit</label>
                                            <input type="time" name="time" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Data Mbarimit</label>
                                        <input type="date" name="end_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="create" class="btn btn-success w-30">Krijo Rezervim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="addClientModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content p-4">
                            <div class="modal-header">
                                <h5 class="modal-title">Shto Klient</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form id="addClientForm">
                                <input type="hidden" name="from" value="reservation_modal">
                                <div class="modal-body row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Profile Type</label>
                                        <input type="text" class="form-control" name="profile_type" value="client" readonly>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Partner Type</label>
                                        <select class="form-control" name="partner_type" required>
                                            <option value="business">Business</option>
                                            <option value="individual" selected>Individual</option>
                                        </select>
                                    </div>
                                    <?php
                                    $clientFields = ['full_name', 'company_name', 'nipt', 'email', 'phone', 'birthday', 'country', 'city', 'zip', 'reference', 'address', 'payment_terms', 'remarks'];
                                    foreach ($clientFields as $field) {
                                        $label = ucwords(str_replace('_', ' ', $field));
                                        echo '<div class="col-md-3 mb-3">
                                        <label class="form-label">' . $label . '</label>
                                        <input type="text" class="form-control" name="' . $field . '">
                                    </div>';
                                    }
                                    ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="save_client" class="btn btn-success">Save Client</button>
                                </div>
                            </form>
                            <div id="clientFormMessage" class="mt-2"></div>
                        </div>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>





            </div>
        </div>
    </div>
    <footer class="footer-section">
        <div class="footer-container">
            <div class="footer-column contact-info">
                <h3>Contact</h3>
                <p>Tirana International Airport, Uzina, Tirana 1504, Albania</p>
                <p><a href="mailto:info@faverent.al">info@faverent.al</a></p>
                <p class="phone-number">+355 69 55 55 556</p>
            </div>

            <div class="footer-column services">
                <h3>Services</h3>
                <ul>
                    <li><a href="...#nightparties">Rent For Night Parties</a></li>
                    <li><a href="...#weddings">Rent For Weddings</a></li>
                    <li><a href="...#airport">Rent For Airport Transfers</a></li>
                    <li><a href="...#casinos">Rent For Casinos</a></li>
                    <li><a href="...#birthdays">Rent For Birthdays</a></li>

                </ul>
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
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let today = new Date().toISOString().split('T')[0];
        $('[name="pickup_date"]').val(today);

        let tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        let tomorrowDate = tomorrow.toISOString().split('T')[0];
        $('[name="dropoff_date"]').val(tomorrowDate);

        $('.footer-section .services a').on('click', function(e) {
            e.preventDefault();

            let href = $(this).attr('href');

            let serviceType = 'all';
            if (href.includes('#nightparties')) {
                serviceType = 'Night Parties';
            } else if (href.includes('#weddings')) {
                serviceType = 'Weddings';
            } else if (href.includes('#airport')) {
                serviceType = 'Airport Transfers';
            } else if (href.includes('#casinos')) {
                serviceType = 'Casinos';
            } else if (href.includes('#birthdays')) {
                serviceType = 'Birthdays';
            } else if (href.includes('#business')) {
                serviceType = 'Business';
            }

            $('[name="service_type"]').val(serviceType);

            $('html, body').animate({
                scrollTop: $('.booking-section').offset().top - 100
            }, 800);

            setTimeout(function() {
                $('#bookingForm').submit();
            }, 500);
        });

        $('#bookingForm').on('submit', function(e) {
            e.preventDefault();

            let pickup_date = $('[name="pickup_date"]').val();
            let pickup_time = $('[name="pickup_time"]').val();
            let dropoff_date = $('[name="dropoff_date"]').val();
            let dropoff_time = $('[name="dropoff_time"]').val();
            let service_type = $('[name="service_type"]').val();

            if (!pickup_date || !pickup_time || !dropoff_date || !dropoff_time || !service_type) {
                alert('Plotëso të gjitha fushat!');
                return;
            }

            let loadingHtml = '<div style="text-align: center; padding: 15px; display: block;"><div class="text-primary" role="status"><span class="visually-hidden"></span></div><p class="mt-3"></p></div>';

            if ($('.car-grid').length > 0) {
                $('.car-grid').html(loadingHtml);
            }

            $.ajax({
                url: '/new_project_bk/helper/reservations.php',
                type: 'POST',
                data: {
                    action: 'search_cars',
                    pickup_date: pickup_date,
                    pickup_time: pickup_time,
                    dropoff_date: dropoff_date,
                    dropoff_time: dropoff_time,
                    service_type: service_type
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);

                    if (response.error) {
                        alert(response.error);
                        return;
                    }

                    let cars = response;
                    let html = '';

                    let serviceLabels = {
                        'all': 'Të gjitha shërbimet',
                        'Weddings': 'Dasmë',
                        'Night Parties': 'Night Party',
                        'Airport Transfers': 'Transfer Aeroporti',
                        'Casinos': 'Kazino',
                        'Birthdays': 'Ditëlindje',
                        'Business': 'Biznes'
                    };

                    if (cars.length > 0) {
                        let serviceLabel = serviceLabels[service_type] || service_type;
                        html += '<h3 style="margin-bottom: 20px; text-align: center;">Makinat e lira për: <strong>' + serviceLabel + '</strong> (' + cars.length + ' makina)</h3><div class="car-grid">';

                        cars.forEach(function(car) {
                            let modalId = 'carModalAvailable' + car.id;
                            let rating = car.rating || '4.5';
                            let seats = car.seats || '5';
                            let transmission = car.transmission || 'Manual';
                            let type = car.type || 'Sedan';
                            let carServiceType = car.service_type || 'N/A';

                            html += `
        <div class="car-card">
            <img src="${car.image}" alt="${car.model}" class="car-image" style="cursor: pointer;" onclick="openCarModal('${modalId}')">
            <div class="car-rating">⭐ ${rating}</div>
            <div class="car-content">
                <h3 class="car-name">${car.model}</h3>
                <div class="car-specs">
                    <span class="spec">👥 ${seats} vende</span>
                    <span class="spec">⚙️ ${transmission}</span>
                    <span class="spec">🚗 ${type}</span>
                </div>
                <div class="car-specs" style="margin-top: 8px;">
                    <span class="spec" style="background: #e3f2fd; color: #1976d2; font-weight: 600;"> ${serviceLabels[carServiceType] || carServiceType}</span>
                </div>
                <div class="car-footer">
                    <div class="car-price-clean"> <span class="price-value">${car.price_per_day}€</span> <span class="price-label">/ditë</span></div>
                    <button class="btn btn-success btn-sm reserve-btn" 
                        data-car-id="${car.id}"
                        data-car-name="${car.model}"
                        data-pickup-date="${pickup_date}"
                        data-pickup-time="${pickup_time}"
                        data-dropoff-date="${dropoff_date}"
                        data-dropoff-time="${dropoff_time}"
                        data-service-type="${carServiceType}">
                        Rezervo
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${car.model}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <img src="${car.image}" class="img-fluid rounded mb-3" style="max-height:400px; object-fit:cover;">
                        <p class="text-muted mb-1">${type} | ${transmission}</p>
                        <p class="fs-5 fw-bold " style="2c599d"> ${car.price_per_day} €/ditë</p>
                        <p class="text-muted small">⭐ ${rating} | 💺 ${seats} vende |  ${serviceLabels[carServiceType] || carServiceType}</p>
                        <hr class="my-3">
                        <p>Ky model makine ofron një eksperiencë të jashtëzakonshme udhëtimi. Sediljet janë të rehatshme dhe të rregullueshme sipas preferencave.</p>
                        <p>Pajisjet teknologjike, përfshirë navigacionin, sistemin e ndihmës për parkim dhe asistencën e vozitjes, garantojnë një eksperiencë të sigurt.</p>
                        <p>Pajisjet moderne të sigurisë, si airbag-et, ABS, kontrolli i stabilitetit dhe sistemi i paralajmërimit për rrezik.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success reserve-btn-modal"
                            data-car-id="${car.id}"
                            data-car-name="${car.model}"
                            data-pickup-date="${pickup_date}"
                            data-pickup-time="${pickup_time}"
                            data-dropoff-date="${dropoff_date}"
                            data-dropoff-time="${dropoff_time}"
                            data-service-type="${carServiceType}">
                            Rezervo Këtë Makinë
                        </button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Mbyll</button>
                    </div>
                </div>
            </div>
        </div>
        `;
                        });

                        html += '</div>';
                    } else {
                        let serviceLabel = serviceLabels[service_type] || service_type;
                        html = '<div style="text-align: center; padding: 40px;"><h3>Nuk ka makina të lira për: <strong>' + serviceLabel + '</strong></h3><p>Ju lutem zgjidhni një opsion tjetër ose data të tjera.</p></div>';
                    }

                    if ($('#availableCars').length > 0) {
                        $('#availableCars').html(html);
                    } else if ($('.car-grid').length > 0) {
                        $('.car-grid').replaceWith(html);
                    } else {
                        $('#bookingForm').after('<div id="availableCars">' + html + '</div>');
                    }

                    $('.dashboard-cards').hide();
                    $('#salesChart').hide();

                    attachReserveButtonHandlers();

                    if ($('#availableCars').length > 0) {
                        $('html, body').animate({
                            scrollTop: $('#availableCars').offset().top - 100
                        }, 500);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', xhr.responseText);
                    alert('Ka ndodhur një gabim gjatë kërkimit të makinave!');
                }
            });
        });

        window.openCarModal = function(modalId) {
            $('#' + modalId).modal('show');
        };

        function attachReserveButtonHandlers() {
            $('.reserve-btn, .reserve-btn-modal').off('click').on('click', function() {
                let carId = $(this).data('car-id');
                let carName = $(this).data('car-name');
                let pickupDate = $(this).data('pickup-date');
                let pickupTime = $(this).data('pickup-time');
                let dropoffDate = $(this).data('dropoff-date');
                let dropoffTime = $(this).data('dropoff-time');
                let serviceType = $(this).data('service-type');

                console.log('Reserve clicked:', {
                    carId,
                    carName,
                    pickupDate,
                    pickupTime,
                    dropoffDate,
                    dropoffTime,
                    serviceType
                });

                $('.modal').modal('hide');

                setTimeout(function() {
                    $('#addReservationModal select[name="car_id"]').val(carId);
                    $('#addReservationModal input[name="start_date"]').val(pickupDate);
                    $('#addReservationModal input[name="time"]').val(pickupTime);
                    $('#addReservationModal input[name="end_date"]').val(dropoffDate);

                    if ($('#addReservationModal input[name="service_type"]').length) {
                        $('#addReservationModal input[name="service_type"]').val(serviceType);
                    }

                    console.log('Opening reservation modal with service type:', serviceType);

                    $('#addReservationModal').modal('show');
                }, 500);
            });
        }

        let currentReservationModalId = null;

        $(document).on('click', '[data-bs-target="#addClientModal"]', function() {
            currentReservationModalId = $(this).data('current-reserve-modal');
            console.log('Opening client modal from:', currentReservationModalId);

            if (currentReservationModalId) {
                $('#' + currentReservationModalId).modal('hide');
            }
        });

        $('#addClientForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();
            let submitBtn = $(this).find('button[type="submit"]');
            submitBtn.prop('disabled', true);

            $.ajax({
                url: '/new_project_bk/helper/save_client_ajax.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    submitBtn.prop('disabled', false);

                    if (response.success) {
                        $('#clientFormMessage').html('<div class="alert alert-success">Klienti u shtua me sukses!</div>');

                        let newOption = new Option(response.client_name, response.client_id, true, true);

                        $('#clientSelect').append(newOption).trigger('change');
                        $('#addReservationModal select[name="client_id"]').append(newOption.cloneNode(true)).val(response.client_id);

                        setTimeout(function() {
                            $('#addClientModal').modal('hide');

                            if (currentReservationModalId) {
                                setTimeout(function() {
                                    $('#' + currentReservationModalId).modal('show');
                                    currentReservationModalId = null;
                                }, 300);
                            }

                            $('#addClientForm')[0].reset();
                            $('#clientFormMessage').html('');
                        }, 1000);
                    } else {
                        $('#clientFormMessage').html('<div class="alert alert-danger">' + (response.message || response.error || 'Gabim gjatë shtimit të klientit!') + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    submitBtn.prop('disabled', false);
                    console.error('Error:', xhr.responseText);
                    $('#clientFormMessage').html('<div class="alert alert-danger">Ka ndodhur një gabim në server!</div>');
                }
            });
        });

        $('#addClientModal').on('hidden.bs.modal', function() {
            $('#clientFormMessage').html('');
        });
    });
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
        hamburger.classList.toggle('toggle');
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

    let selectedContact = null;
    let lastMessageId = 0;
    let lastDate = '';
    let pollInterval = null;
    let isFetching = false;
    let isSending = false;
    let unreadCheckInterval = null;
    let totalUnreadCount = 0;
    let totalUnreadContacts = 0;

    function formatTime(dateStr) {
        const d = new Date(dateStr);
        return d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
    }

    function formatDateSeparator(dateStr) {
        const d = new Date(dateStr);
        const dateKey = d.toDateString();
        if (dateKey !== lastDate) {
            lastDate = dateKey;
            return `<div class="chat-date-separator">${d.toLocaleDateString('sq-AL',{weekday:'long',day:'2-digit',month:'2-digit',year:'numeric'})}</div>`;
        }
        return '';
    }

    function isImageFile(filename) {
        if (!filename) return false;
        const ext = filename.toLowerCase().split('.').pop();
        return ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'].includes(ext);
    }

    function formatFileAttachment(filePath) {
        const fullPath = `/new_project_bk/uploads/chat_files/${filePath}`;
        if (isImageFile(filePath)) {
            return `<div class="chat-image-wrapper"><a href="${fullPath}" target="_blank"><img src="${fullPath}" alt="Image" class="chat-image" style="max-width:200px;border-radius:6px;"/></a></div>`;
        } else {
            const fileName = filePath.split('/').pop();
            return `<a href="${fullPath}" target="_blank" class="chat-file-link">📎 ${fileName}</a>`;
        }
    }

    function updateUnreadBadge() {
        let $badge = $('#chatUnreadBadge');
        if ($badge.length === 0) {
            $('#chatUserIcon').append('<span id="chatUnreadBadge" style="position:absolute;top:-6px;right:-6px;background:#ff4444;color:white;border-radius:50%;min-width:20px;height:20px;display:none;align-items:center;justify-content:center;font-size:11px;font-weight:bold;padding:2px 6px;box-shadow:0 2px 4px rgba(0,0,0,0.2);z-index:1001;"></span>');
            $badge = $('#chatUnreadBadge');
        }
        const isChatOpen = $('#chatUserWidget').is(':visible');
        if (isChatOpen) {
            $badge.hide();
            return;
        }
        if (totalUnreadContacts > 0) {
            $badge.text(totalUnreadContacts).css('display', 'flex');
        } else {
            $badge.hide();
        }
    }

    function checkUnreadMessages() {
        $.post('../../../helper/send_message.php', {
            action: 'fetch_contacts'
        }, function(resp) {
            if (!resp.success) return;
            totalUnreadCount = 0;
            totalUnreadContacts = 0;
            resp.contacts.forEach(c => {
                if (c.unread_count > 0) {
                    totalUnreadCount += c.unread_count;
                    totalUnreadContacts++;
                }
            });
            updateUnreadBadge();
        }, 'json');
    }

    function markMessagesAsRead(contactId, contactType) {
        $.post('../../../helper/send_message.php', {
            action: 'mark_as_read',
            contact_id: contactId,
            contact_type: contactType
        }, function(resp) {
            if (resp.success) {
                $(`.contact-item[data-id="${contactId}"] .unread-count`).fadeOut(200, function() {
                    $(this).remove();
                });
                checkUnreadMessages();
            }
        }, 'json');
    }

    function fetchContacts() {
        $.post('../../../helper/send_message.php', {
            action: 'fetch_contacts'
        }, function(resp) {
            if (!resp.success) return;
            const $list = $('#chatContacts').empty();
            totalUnreadCount = 0;
            totalUnreadContacts = 0;
            resp.contacts.forEach(c => {
                if (c.unread_count > 0) {
                    totalUnreadCount += c.unread_count;
                    totalUnreadContacts++;
                }
            });
            updateUnreadBadge();
            resp.contacts.forEach(c => {
                const unread = c.unread_count > 0 ? `<span class="unread-count">${c.unread_count}</span>` : '';
                const contactType = IS_ADMIN ? 'user' : 'admin';
                const $item = $(`<div class="contact-item" data-id="${c.contact_id}" data-type="${contactType}"><div style="display:flex;flex-direction:column;"><strong>${c.contact_name}</strong><small style="color:#666">${c.last_message??''}</small></div>${unread}</div>`);
                $item.on('click', () => selectContact({
                    contact_id: c.contact_id,
                    contact_name: c.contact_name,
                    contact_type: contactType
                }));
                $list.append($item);
            });
        }, 'json');
    }

    function selectContact(contact) {
        selectedContact = contact;
        $('#receiver_id').val(contact.contact_id);
        $('#receiver_type').val(contact.contact_type);
        $('#chatUserTitle').text('Biseda me: ' + contact.contact_name);
        lastMessageId = 0;
        lastDate = '';
        $('#chatUserBody').empty();
        $('.contact-item').removeClass('active');
        $(`.contact-item[data-id="${contact.contact_id}"]`).addClass('active');
        markMessagesAsRead(contact.contact_id, contact.contact_type);
        if (pollInterval) clearInterval(pollInterval);
        fetchMessages(true);
        pollInterval = setInterval(() => fetchMessages(false), 2000);
        updateUnreadBadge();
    }

    function fetchMessages(initial = false) {
        if (!selectedContact || isFetching || isSending) return;
        isFetching = true;
        const payload = {
            action: 'fetch_messages',
            receiver_id: selectedContact.contact_id,
            receiver_type: selectedContact.contact_type
        };
        if (!initial && lastMessageId > 0) payload.last_id = lastMessageId;
        $.post('../../../helper/send_message.php', payload, function(resp) {
            isFetching = false;
            if (!resp.success || !resp.messages) return;
            const $body = $('#chatUserBody');
            if (initial) {
                $body.empty();
                lastMessageId = 0;
                lastDate = '';
            }
            let newMessages = resp.messages.filter(m => m.id > lastMessageId);
            if (newMessages.length === 0) return;
            if (newMessages.length > 0 && !initial) markMessagesAsRead(selectedContact.contact_id, selectedContact.contact_type);
            newMessages.forEach(m => {
                lastMessageId = Math.max(lastMessageId, m.id);
                const isMine = (m.sender_id == USER_ID && m.sender_type == (IS_ADMIN ? 'admin' : 'user'));
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
            fetchContacts();
        }, 'json').fail(() => {
            isFetching = false;
        });
    }

    $('#chatUserForm').on('submit', function(e) {
        e.preventDefault();
        if (!selectedContact) {
            alert('Zgjidhni një kontakt!');
            return;
        }
        const messageText = $('#chatUserInput').val().trim();
        const fileInput = $('#chatUserFile')[0];
        if (!messageText && (!fileInput || fileInput.files.length === 0)) return;
        if (isSending) return;
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
                if (resp.success) {
                    setTimeout(() => {
                        isSending = false;
                        fetchMessages(false);
                        checkUnreadMessages();
                    }, 300);
                } else {
                    isSending = false;
                    alert(resp.message || 'Gabim gjatë dërgimit');
                }
            },
            error: function() {
                isSending = false;
                alert('Gabim në lidhje me serverin');
            }
        });
    });

    $('#chatFileIcon').on('click', () => $('#chatUserFile').click());
    $('#chatUserIcon').on('click', () => {
        const isVisible = $('#chatUserWidget').is(':visible');
        if (isVisible) {
            $('#chatUserWidget').fadeOut(150);
            if (pollInterval) clearInterval(pollInterval);
            if (unreadCheckInterval) clearInterval(unreadCheckInterval);
        } else {
            $('#chatUserWidget').fadeIn(150);
            fetchContacts();
            if (unreadCheckInterval) clearInterval(unreadCheckInterval);
            unreadCheckInterval = setInterval(() => checkUnreadMessages(), 5000);
            updateUnreadBadge();
        }
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#chatUserWidget,#chatUserIcon').length) {
            $('#chatUserWidget').fadeOut(100);
            if (pollInterval) clearInterval(pollInterval);
            if (unreadCheckInterval) clearInterval(unreadCheckInterval);
        }
    });

    $('#chatUserClose').on('click', () => {
        $('#chatUserWidget').fadeOut(100);
        if (pollInterval) clearInterval(pollInterval);
        if (unreadCheckInterval) clearInterval(unreadCheckInterval);
    });

    $('#chatUserInput').on('keypress', function(e) {
        if (e.key === 'Enter' && $(this).val().trim() !== '') $('#chatUserForm').submit();
    });

    $(document).ready(() => {
        checkUnreadMessages();
        setInterval(() => checkUnreadMessages(), 10000);
        $('#chatUserWidget').hide();
    });
</script>