<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ROOT_PATH', realpath(__DIR__) . '/');
define('DB_PATH', ROOT_PATH . 'db/');
define('HELPER_PATH', ROOT_PATH . 'helper/');
define('CONTROLLERS_PATH', ROOT_PATH . 'controllers/');
define('VIEWS_PATH', ROOT_PATH . 'views/');
define('LAYOUT_PATH', VIEWS_PATH . 'layout/');
define('GENERAL_PATH', VIEWS_PATH . 'general/');
define('UPLOADS_PATH', ROOT_PATH . 'uploads/');

define('BASE_URL', '/new_project_bk/');
define('GENERAL_URL', BASE_URL . 'views/general/');
define('UPLOADS_URL', BASE_URL . 'uploads/');

if (file_exists(DB_PATH . 'db.php')) include_once DB_PATH . 'db.php';
