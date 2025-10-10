<?php
header('Content-Type: application/json');
include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/db/db.php';

$q = strtolower($_GET['q'] ?? '');
$q = $mysqli->real_escape_string($q);

$results = [];

if ($q !== '') {
    $query = "SELECT c.id, c.model, c.phone, b.name AS brand_name, cat.name AS category_name
              FROM cars c
              LEFT JOIN brands b ON c.brand_id = b.id
              LEFT JOIN categories cat ON c.category_id = cat.id
              WHERE LOWER(c.model) LIKE '%$q%' OR LOWER(c.phone) LIKE '%$q%' 
                 OR LOWER(b.name) LIKE '%$q%' OR LOWER(cat.name) LIKE '%$q%'
              LIMIT 20";
    $res = $mysqli->query($query);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $columns = [
                'name' => $row['model'],
                'phone' => $row['phone']
            ];
            if (!empty($row['brand_name'])) $columns['brand'] = $row['brand_name'];
            if (!empty($row['category_name'])) $columns['category'] = $row['category_name'];

            $results[] = [
                'columns' => $columns,
                'table' => 'Makina',
                'url' => "/new_project_bk/views/general/cars/list.php?id=" . $row['id'],
                'id' => $row['id']
            ];
        }
    }

    $res = $mysqli->query("SELECT id, name FROM brands WHERE LOWER(name) LIKE '%$q%' LIMIT 10");
    while ($row = $res->fetch_assoc()) {
        $results[] = [
            'columns' => ['name' => $row['name']],
            'table' => 'Brands',
            'url' => "/new_project_bk/views/general/brands/list.php?id=" . $row['id'],
            'id' => $row['id']
        ];
    }

    $res = $mysqli->query("SELECT id, name FROM categories WHERE LOWER(name) LIKE '%$q%' LIMIT 10");
    while ($row = $res->fetch_assoc()) {
        $results[] = [
            'columns' => ['name' => $row['name']],
            'table' => 'Categories',
            'url' => "/new_project_bk/views/general/categories/list.php?id=" . $row['id'],
            'id' => $row['id']
        ];
    }

    $res = $mysqli->query("SELECT id, full_name, company_name, phone, email FROM clients 
                           WHERE LOWER(full_name) LIKE '%$q%' 
                              OR LOWER(company_name) LIKE '%$q%' 
                              OR LOWER(email) LIKE '%$q%' 
                              OR LOWER(phone) LIKE '%$q%'
                           LIMIT 20");
    while ($row = $res->fetch_assoc()) {
        $columns = [
            'name' => $row['full_name'] ?: $row['company_name'],
            'phone' => $row['phone'],
            'email' => $row['email']
        ];
        $results[] = [
            'columns' => $columns,
            'table' => 'Klient',
            'url' => "/new_project_bk/views/general/client_management/list.php?id=" . $row['id'],
            'id' => $row['id']
        ];
    }

    $res = $mysqli->query("SELECT id, title, content FROM files_index 
                           WHERE LOWER(title) LIKE '%$q%' OR LOWER(content) LIKE '%$q%' LIMIT 10");
    while ($row = $res->fetch_assoc()) {
        $results[] = [
            'columns' => ['name' => $row['title']],
            'table' => 'Files',
            'url' => "/new_project_bk/views/general/files/list.php?id=" . $row['id'],
            'id' => $row['id']
        ];
    }

    $res = $mysqli->query("SELECT id, username, email FROM users 
                           WHERE LOWER(username) LIKE '%$q%' OR LOWER(email) LIKE '%$q%' LIMIT 10");
    while ($row = $res->fetch_assoc()) {
        $results[] = [
            'columns' => ['name' => $row['username'], 'email' => $row['email']],
            'table' => 'Users',
            'url' => "/new_project_bk/views/general/users/list.php?id=" . $row['id'],
            'id' => $row['id']
        ];
    }

    $res = $mysqli->query("SELECT id, client_name, display_name FROM reservations 
                           WHERE LOWER(client_name) LIKE '%$q%' OR LOWER(display_name) LIKE '%$q%' LIMIT 10");
    while ($row = $res->fetch_assoc()) {
        $results[] = [
            'columns' => ['name' => $row['client_name'], 'display' => $row['display_name']],
            'table' => 'Rezervime',
            'url' => "/new_project_bk/views/general/reservations/list.php?id=" . $row['id'],
            'id' => $row['id']
        ];
    }

    $res = $mysqli->query("SELECT id, name FROM menu_items WHERE LOWER(name) LIKE '%$q%' LIMIT 10");
    while ($row = $res->fetch_assoc()) {
        $results[] = [
            'columns' => ['name' => $row['name']],
            'table' => 'Menu',
            'url' => "/new_project_bk/views/general/menu_items/list.php?id=" . $row['id'],
            'id' => $row['id']
        ];
    }
}

$generalPages = [
    [
        'title' => 'Home',
        'url' => '/new_project_bk/views/general/home/list.php',
        'keywords' => ['home', 'ballina', 'faqja kryesore']
    ],
    [
        'title' => 'Order Status',
        'url' => '/new_project_bk/views/general/order_status/list.php',
        'keywords' => ['orders', 'porosi', 'status', 'gjendja e makines']
    ],
];

foreach ($generalPages as $page) {
    foreach ($page['keywords'] as $keyword) {
        if (strpos(strtolower($keyword), $q) !== false) {
            $results[] = [
                'columns' => ['name' => $page['title']],
                'table' => 'Faqe',
                'url' => $page['url'],
                'keyword' => $keyword
            ];
            break;
        }
    }
}

echo json_encode($results);
exit;
