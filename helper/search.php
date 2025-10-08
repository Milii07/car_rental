<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$mysqli = new mysqli('localhost', 'root', '', 'auto_future_block');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$tables = ['brands', 'cars', 'categories', 'clients', 'menu_items', 'password_resets', 'reservations', 'users'];

$projectDir = $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk';


function getTextColumns($table, $mysqli)
{
    $db = $mysqli->query("SELECT DATABASE()")->fetch_row()[0];
    $sql = "SELECT column_name, data_type 
            FROM information_schema.columns 
            WHERE table_schema = '{$db}' 
              AND table_name = '{$table}'";
    $res = $mysqli->query($sql);
    $columns = [];
    while ($row = $res->fetch_assoc()) {
        if (in_array($row['data_type'], ['varchar', 'char', 'text', 'longtext'])) {
            $columns[] = $row['column_name'];
        }
    }
    return $columns;
}

function searchDatabase($query, $tables, $mysqli)
{
    $results = [];
    $query = $mysqli->real_escape_string($query);

    foreach ($tables as $table) {
        $columns = getTextColumns($table, $mysqli);
        if (empty($columns)) continue;

        $where = [];
        foreach ($columns as $col) {
            $where[] = "$col LIKE '%$query%'";
        }

        $sql = "SELECT " . implode(',', $columns) . " FROM $table WHERE " . implode(' OR ', $where) . " LIMIT 20";
        $res = $mysqli->query($sql);

        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $results[] = [
                    'type' => 'db',
                    'table' => $table,
                    'columns' => $row,
                    'url' => "/new_project_bk/views/general/$table/list.php"
                ];
            }
        }
    }
    return $results;
}

function scanFiles($dir, &$results = [])
{
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($fullPath)) {
            scanFiles($fullPath, $results);
        } else {
            $results[] = $fullPath;
        }
    }
    return $results;
}

function searchFiles($query, $projectDir)
{
    $query = strtolower($query);
    $allFiles = scanFiles($projectDir);
    $matches = [];

    foreach ($allFiles as $file) {
        $filename = strtolower(basename($file));
        if (strpos($filename, $query) !== false) {
            $matches[] = [
                'type' => 'file',
                'name' => basename($file),
                'path' => str_replace($_SERVER['DOCUMENT_ROOT'], '', $file)
            ];
            continue;
        }

        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if (in_array($ext, ['php', 'html', 'js', 'css', 'txt'])) {
            $content = strtolower(file_get_contents($file));
            if (strpos($content, $query) !== false) {
                $matches[] = [
                    'type' => 'content',
                    'name' => basename($file),
                    'path' => str_replace($_SERVER['DOCUMENT_ROOT'], '', $file)
                ];
            }
        }
    }

    return $matches;
}


if (!empty($_GET['q'])) {
    header('Content-Type: application/json; charset=utf-8');
    $query = trim($_GET['q']);

    $dbResults = searchDatabase($query, $tables, $mysqli);
    $fileResults = searchFiles($query, $projectDir);

    $results = array_merge($dbResults, $fileResults);

    echo json_encode(array_slice($results, 0, 50));
    exit;
}
