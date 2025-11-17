<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400,
        'cookie_path' => '/',
        'cookie_secure' => false,
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax'
    ]);
}

header('Content-Type: application/json');
include_once __DIR__ . '/../db/db.php';


if (!isset($_SESSION['user_id']) && !isset($_SESSION['guest_id'])) {
    $_SESSION['guest_id'] = time() . rand(1000, 9999);
}

$user_id  = $_SESSION['user_id'] ?? 0;
$is_admin = $_SESSION['is_admin'] ?? 0;
$guest_id = $_SESSION['guest_id'] ?? 0;

if ($user_id > 0) {
    $user_type  = $is_admin ? 'admin' : 'user';
    $current_id = $user_id;
} else {
    $user_type  = 'guest';
    $current_id = $guest_id;
}


$action = $_POST['action'] ?? '';


if ($action === 'send') {
    $receiver_id   = intval($_POST['receiver_id'] ?? 0);
    $receiver_type = $_POST['receiver_type'] ?? '';
    $message       = trim($_POST['message'] ?? '');
    $file_path     = '';

    if ($message === '' && empty($_FILES['file'])) {
        echo json_encode(['success' => false, 'message' => 'Mesazhi është bosh']);
        exit;
    }

    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
        if (!in_array($_FILES['file']['type'], $allowed)) {
            echo json_encode(['success' => false, 'message' => 'File i palejuar']);
            exit;
        }

        $uploadDir = __DIR__ . '/../uploads/chat_files/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $fileName = time() . '_' . preg_replace("/[^A-Za-z0-9.\-_]/", "", $_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fileName);
        $file_path = $fileName;
    }

    $stmt = $mysqli->prepare("
        INSERT INTO messages
        (sender_id,sender_type,receiver_id,receiver_type,message,file_path,is_read,created_at)
        VALUES (?,?,?,?,?,?,0,NOW())
    ");
    $stmt->bind_param("isisss", $current_id, $user_type, $receiver_id, $receiver_type, $message, $file_path);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'fetch_contacts') {
    $contacts = [];

    if ($is_admin) {
        $stmt = $mysqli->prepare("
            SELECT u.id AS contact_id, u.username AS contact_name,
                   'user' AS contact_type,
                   (SELECT message FROM messages
                    WHERE (sender_id=u.id AND sender_type='user' AND receiver_type='admin')
                       OR (receiver_id=u.id AND receiver_type='user' AND sender_type='admin')
                    ORDER BY created_at DESC LIMIT 1) AS last_message,
                   (SELECT COUNT(*) FROM messages m2
                    WHERE m2.receiver_id=? AND m2.receiver_type='admin'
                      AND m2.sender_id=u.id AND m2.sender_type='user' AND m2.is_read=0
                   ) AS unread_count
            FROM users u WHERE u.is_admin=0 ORDER BY u.username ASC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $contacts[] = $row;
        $stmt->close();

        $stmt = $mysqli->prepare("
            SELECT x.guest_id AS contact_id, CONCAT('Guest #',x.guest_id) AS contact_name,
                   'guest' AS contact_type,
                   (SELECT message FROM messages
                    WHERE (sender_id=x.guest_id AND sender_type='guest')
                       OR (receiver_id=x.guest_id AND receiver_type='guest')
                    ORDER BY created_at DESC LIMIT 1) AS last_message,
                   (SELECT COUNT(*) FROM messages m3
                    WHERE m3.sender_type='guest' AND m3.sender_id=x.guest_id
                      AND m3.receiver_type='admin' AND m3.is_read=0) AS unread_count
            FROM (SELECT DISTINCT sender_id AS guest_id FROM messages WHERE sender_type='guest'
                  UNION
                  SELECT DISTINCT receiver_id AS guest_id FROM messages WHERE receiver_type='guest') AS x
            ORDER BY x.guest_id DESC
        ");
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $contacts[] = $row;
        $stmt->close();
    } else {
        $stmt = $mysqli->prepare("
            SELECT id AS contact_id, username AS contact_name,
                   'admin' AS contact_type,'' AS last_message,0 AS unread_count
            FROM users WHERE is_admin=1 LIMIT 1
        ");
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) $contacts[] = $row;
        $stmt->close();
    }

    echo json_encode(['success' => true, 'contacts' => $contacts]);
    exit;
}

if ($action === 'search_contacts') {
    $query = trim($_POST['query'] ?? '');
    $contacts = [];

    if ($query === '') {
        echo json_encode(['success' => true, 'contacts' => []]);
        exit;
    }

    if ($is_admin) {
        $stmt = $mysqli->prepare("
            SELECT id AS contact_id, username AS contact_name, 'user' AS contact_type
            FROM users
            WHERE is_admin=0 AND username LIKE CONCAT('%',?,'%')
        ");
        $stmt->bind_param("s", $query);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $contacts[] = $row;
        $stmt->close();

        $stmt = $mysqli->prepare("
            SELECT DISTINCT sender_id AS contact_id, CONCAT('Guest #', sender_id) AS contact_name, 'guest' AS contact_type
            FROM messages
            WHERE sender_type='guest' AND sender_id LIKE CONCAT('%',?,'%')
        ");
        $stmt->bind_param("s", $query);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $contacts[] = $row;
        $stmt->close();
    } else {
        $stmt = $mysqli->prepare("
            SELECT id AS contact_id, username AS contact_name, 'admin' AS contact_type
            FROM users
            WHERE is_admin=1 AND username LIKE CONCAT('%',?,'%') LIMIT 1
        ");
        $stmt->bind_param("s", $query);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) $contacts[] = $row;
        $stmt->close();
    }

    echo json_encode(['success' => true, 'contacts' => $contacts]);
    exit;
}

if ($action === 'fetch_messages') {
    $receiver_id   = intval($_POST['receiver_id'] ?? 0);
    $receiver_type = $_POST['receiver_type'] ?? '';

    $stmt = $mysqli->prepare("
        UPDATE messages
        SET is_read=1
        WHERE sender_id=? AND sender_type=? AND receiver_id=? AND receiver_type=? AND is_read=0
    ");
    $stmt->bind_param("isis", $receiver_id, $receiver_type, $current_id, $user_type);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("
        SELECT * FROM messages
        WHERE (sender_id=? AND sender_type=? AND receiver_id=? AND receiver_type=?)
           OR (sender_id=? AND sender_type=? AND receiver_id=? AND receiver_type=?)
        ORDER BY created_at ASC
    ");
    $stmt->bind_param(
        "isisisis",
        $current_id,
        $user_type,
        $receiver_id,
        $receiver_type,
        $receiver_id,
        $receiver_type,
        $current_id,
        $user_type
    );

    $stmt->execute();
    $res = $stmt->get_result();
    $messages = [];
    while ($row = $res->fetch_assoc()) $messages[] = $row;
    $stmt->close();

    echo json_encode(['success' => true, 'messages' => $messages]);
    exit;
}


echo json_encode(['success' => false, 'message' => 'Veprim i panjohur']);
exit;
