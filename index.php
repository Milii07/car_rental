<?php
// index.php - qendra e paths dhe includes

// Root i projektit (folderi ku ndodhet index.php)
define('ROOT_PATH', realpath(__DIR__) . '/');

// Folders
define('DB_PATH', ROOT_PATH . 'db/');
define('HELPER_PATH', ROOT_PATH . 'helper/');
define('CONTROLLERS_PATH', ROOT_PATH . 'controllers/');
define('VIEWS_PATH', ROOT_PATH . 'views/');
define('LAYOUT_PATH', VIEWS_PATH . 'layout/');
define('AUTH_PATH', VIEWS_PATH . 'auth/');
define('GENERAL_PATH', VIEWS_PATH . 'general/');
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Includes të nevojshme për pothuajse çdo file
if (file_exists(DB_PATH . 'db.php')) {
    include_once DB_PATH . 'db.php';
}
if (file_exists(HELPER_PATH . 'client_helper.php')) {
    include_once HELPER_PATH . 'client_helper.php';
}
if (file_exists(LAYOUT_PATH . 'layout.php')) {
    include_once LAYOUT_PATH . 'layout.php';
}

// Funksione të dobishme
if (!function_exists('showFutureBlockBackground')) {
    function showFutureBlockBackground()
    {
        echo '<style>body{background-color:#f8f9fa;}</style>';
    }
}

// Opsionale: debug paths
/*
echo "<p>DB_PATH: " . DB_PATH . "</p>";
echo "<p>HELPER_PATH: " . HELPER_PATH . "</p>";
echo "<p>CONTROLLERS_PATH: " . CONTROLLERS_PATH . "</p>";
echo "<p>VIEWS_PATH: " . VIEWS_PATH . "</p>";
echo "<p>LAYOUT_PATH: " . LAYOUT_PATH . "</p>";
echo "<p>AUTH_PATH: " . AUTH_PATH . "</p>";
echo "<p>GENERAL_PATH: " . GENERAL_PATH . "</p>";
echo "<p>UPLOADS_PATH: " . UPLOADS_PATH . "</p>";
*/
