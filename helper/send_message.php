<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include_once __DIR__ . '/../db/db.php';
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? 0;
$is_admin = $_SESSION['is_admin'] ?? 0;
$user_type = $is_admin ? 'admin' : 'user';

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Nuk jeni të kyçur']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'send') {
    $receiver_id = intval($_POST['receiver_id'] ?? 0);
    $receiver_type = $_POST['receiver_type'] ?? ($is_admin ? 'user' : 'admin');
    $message = trim($_POST['message'] ?? '');
    $file_path = '';

    // Validimi i file-ve
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        if (in_array($_FILES['file']['type'], $allowedTypes)) {
            $uploadDir = __DIR__ . '/../uploads/chat_files/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $fileName = time() . '_' . basename($_FILES['file']['name']);
            $filePath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                $file_path = $fileName;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'File i palejuar']);
            exit;
        }
    }

    if ($message === '' && $file_path === '') {
        echo json_encode(['success' => false, 'message' => 'Mesazhi është bosh']);
        exit;
    }

    $stmt = $mysqli->prepare("
        INSERT INTO messages 
        (sender_id, sender_type, receiver_id, receiver_type, message, file_path, is_read, created_at)
        VALUES (?,?,?,?,?,?,0,NOW())
    ");
    $stmt->bind_param("isssss", $user_id, $user_type, $receiver_id, $receiver_type, $message, $file_path);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => true, 'message' => 'Mesazhi u dërgua']);
    exit;
}

if ($action === 'fetch_contacts') {
    $contacts = [];
    if ($is_admin) {
        $stmt = $mysqli->prepare("
            SELECT 
                u.id AS contact_id, 
                u.username AS contact_name,
                (SELECT message FROM messages 
                 WHERE (sender_id=u.id AND sender_type='user') 
                    OR (receiver_id=u.id AND receiver_type='user')
                 ORDER BY created_at DESC LIMIT 1) AS last_message,
                (SELECT COUNT(*) FROM messages m2 
                 WHERE m2.receiver_id=? 
                   AND m2.receiver_type='admin' 
                   AND m2.sender_id=u.id 
                   AND m2.sender_type='user' 
                   AND m2.is_read=0
                ) AS unread_count
            FROM users u WHERE u.is_admin=0
        ");
        $stmt->bind_param("i", $user_id);
    } else {
        $stmt = $mysqli->prepare("
            SELECT 
                id AS contact_id, 
                username AS contact_name
            FROM users 
            WHERE is_admin=1 LIMIT 1
        ");
    }

    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) $contacts[] = $row;
    $stmt->close();

    echo json_encode(['success' => true, 'contacts' => $contacts]);
    exit;
}

if ($action === 'fetch_messages') {
    $receiver_id = intval($_POST['receiver_id'] ?? 0);
    $receiver_type = $_POST['receiver_type'] ?? ($is_admin ? 'user' : 'admin');

    $user_type = $is_admin ? 'admin' : 'user';
    $other_type = $user_type === 'admin' ? 'user' : 'admin';

    $stmt = $mysqli->prepare("
        UPDATE messages 
        SET is_read = 1
        WHERE sender_id = ? 
          AND sender_type = ? 
          AND receiver_id = ? 
          AND receiver_type = ?
          AND is_read = 0
    ");
    $stmt->bind_param("isis", $receiver_id, $other_type, $user_id, $user_type);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("
        SELECT * FROM messages
        WHERE 
            (sender_id = ? AND sender_type = ? AND receiver_id = ? AND receiver_type = ?)
         OR (sender_id = ? AND sender_type = ? AND receiver_id = ? AND receiver_type = ?)
        ORDER BY created_at ASC
    ");
    $stmt->bind_param(
        "isisisis",
        $user_id,
        $user_type,
        $receiver_id,
        $receiver_type,
        $receiver_id,
        $receiver_type,
        $user_id,
        $user_type
    );

    $stmt->execute();
    $res = $stmt->get_result();

    $messages = [];
    while ($row = $res->fetch_assoc()) {
        $row['message'] = htmlspecialchars_decode($row['message']);
        $messages[] = $row;
    }
    $stmt->close();

    echo json_encode(['success' => true, 'messages' => $messages]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Veprim i panjohur']);
exit;
