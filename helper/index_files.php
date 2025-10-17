<?php
$projectDir = $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk';
$mysqli = new mysqli('localhost', 'root', '', 'auto_future_block');
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

function indexFiles($dir, $mysqli)
{
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $fullPath = "$dir/$file";

        if (is_dir($fullPath)) {
            indexFiles($fullPath, $mysqli);
        } else {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if (!in_array($ext, ['php', 'html', 'js', 'css', 'txt'])) continue;

            $content = @file_get_contents($fullPath);
            if ($content === false) continue;

            $path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $fullPath);
            $title = basename($file);
            $category = $ext;

            $stmt = $mysqli->prepare("INSERT INTO files_index (path, title, category, content) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $path, $title, $category, $content);
            $stmt->execute();
        }
    }
}

$mysqli->query("TRUNCATE TABLE files_index");
indexFiles($projectDir, $mysqli);
echo "Indexing completed!";
