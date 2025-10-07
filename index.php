<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}




define('BASE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/new_project');

define('DB_PATH', BASE_PATH . '/db');
define('HELPER_PATH', BASE_PATH . '/helper');

define('CONTROLLERS_PATH', BASE_PATH . '/controllers');
define('VIEWS_PATH', BASE_PATH . '/views');
define('PUBLIC_PATH', BASE_PATH . '/public');


define('HEADER_PATH', VIEWS_PATH . '/layout/header.php');

define('FOOTER_PATH', VIEWS_PATH . '/layout/layout.php');

// function includeAllPHP($dir)
// {
//     if (!is_dir($dir)) return;

//     $iterator = new RecursiveIteratorIterator(
//         new RecursiveDirectoryIterator($dir)
//     );

//     foreach ($iterator as $file) {
//         if ($file->isFile() && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
//             include_once $file->getPathname();
//         }
//     }
// }


// includeAllPHP(DB_PATH);
// includeAllPHP(HELPER_PATH);
// includeAllPHP(CONTROLLERS_PATH);


include HEADER_PATH;
include FOOTER_PATH;
