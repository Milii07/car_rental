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

            .region-block {
                position: relative;
                width: 220px;
                font-family: 'Poppins', sans-serif;
            }

            #region-list {
                display: none;
                list-style: none;
                margin: 0;
                padding: 0;
                position: absolute;
                width: 100%;
                background: #fff;
                border-radius: 10px;
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
                z-index: 1000;
            }

            #region-list li {
                padding: 10px 15px;
                cursor: pointer;
                font-size: 14px;
                transition: all 0.2s ease;
            }

            #region-list li:hover {
                background: #FF7A00;
                color: #fff;
            }

            #region-current-region {
                cursor: pointer;
                padding: 10px 15px;
                border-radius: 10px;
                border: 1px solid #ddd;
                background: #fff;
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 14px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            .arrow {
                transition: transform 0.3s ease;
            }
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

        #chatUserBody::-webkit-scrollbar {
            display: none;
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

        .msg-time {
            font-size: 10px;
            color: #666;
            text-align: right;
        }

        .chat-img {
            max-width: 150px;
            border-radius: 6px;
        }

        #chatUserForm {
            display: flex;
            border-top: 1px solid #ddd;
        }

        #chatUserInput {
            flex: 1;
            border: none;
            padding: 6px 10px;
        }

        #chatUserInput:focus {
            outline: none;
        }

        #chatUserSend {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 0 15px;
            cursor: pointer;
        }

        #chatUserFile {
            display: none;
        }

        #typingIndicator {
            font-size: 12px;
            color: #666;
            margin: 2px 10px;
            display: none;
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
                    <li><a href="#">Auto Future Block</a></li>
                    <li><a href="#">Pikat tona</a></li>
                    <li><a href="#">Karriera</a></li>
                    <li><a href="#">Komunikata për shtyp</a></li>
                    <li><a href="#">Qëndrueshmëria</a></li>
                    <li><a href="#">Partnerë Globalë</a></li>
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


</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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