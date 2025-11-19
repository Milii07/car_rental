<?php
require __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../db/db.php';

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

$user_id = intval($_SESSION['user_id'] ?? 0);
$is_admin = intval($_SESSION['is_admin'] ?? 0);
$guest_id = intval($_SESSION['guest_id'] ?? 0);

$current_id = $user_id > 0 ? $user_id : $guest_id;
$user_type = $user_id > 0 ? ($is_admin ? 'admin' : 'user') : 'guest';

$action = $_POST['action'] ?? '';

$pusher = new Pusher\Pusher(
    'd0652d5ed102a0e6056c',
    '90c034773a5a9e225f20',
    '2079882',
    ['cluster' => 'eu', 'useTLS' => true]
);

if ($action === 'send') {
    $sender_id = strval($current_id);
    $sender_type = $user_type;
    $receiver_id = strval($_POST['receiver_id'] ?? '');
    $receiver_type = $_POST['receiver_type'] ?? '';
    $message = trim($_POST['message'] ?? '');

    if (empty($receiver_id) || empty($receiver_type)) {
        echo json_encode(['success' => false, 'message' => 'Invalid receiver']);
        exit;
    }

    if (empty($message) && empty($_FILES['file'])) {
        echo json_encode(['success' => false, 'message' => 'Empty message']);
        exit;
    }

    $file_path = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $uploadDir = __DIR__ . '/../uploads/chat_files/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $fileName = time() . '_' . preg_replace("/[^A-Za-z0-9.\-_]/", "", basename($_FILES['file']['name']));
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadDir . $fileName)) {
            $file_path = $fileName;
        }
    }

    $stmt = $mysqli->prepare("INSERT INTO messages 
        (sender_id, sender_type, receiver_id, receiver_type, message, file_path, is_read, created_at)
        VALUES (?, ?, ?, ?, ?, ?, 0, NOW())
    ");
    $stmt->bind_param("ssssss", $sender_id, $sender_type, $receiver_id, $receiver_type, $message, $file_path);

    if ($stmt->execute()) {
        $insert_id = $stmt->insert_id;
        $timestamp = date('Y-m-d H:i:s');

        $data = [
            'id' => $insert_id,
            'sender_id' => $sender_id,
            'sender_type' => $sender_type,
            'receiver_id' => $receiver_id,
            'receiver_type' => $receiver_type,
            'message' => $message,
            'file_path' => $file_path,
            'is_read' => 0,
            'created_at' => $timestamp
        ];

        $receiverChannel = "chat-{$receiver_type}-{$receiver_id}";
        $senderChannel = "chat-{$sender_type}-{$sender_id}";

        error_log("=== PUSHER DEBUG ===");
        error_log("Sender: {$sender_type} #{$sender_id}");
        error_log("Receiver: {$receiver_type} #{$receiver_id}");
        error_log("Receiver Channel: " . $receiverChannel);
        error_log("Sender Channel: " . $senderChannel);
        error_log("Message: " . $message);

        try {
            $result1 = $pusher->trigger($receiverChannel, 'new-message', $data);
            error_log("Pusher to receiver: " . ($result1 ? "SUCCESS" : "FAILED"));

            if ($receiverChannel !== $senderChannel) {
                $result2 = $pusher->trigger($senderChannel, 'new-message', $data);
                error_log("Pusher to sender: " . ($result2 ? "SUCCESS" : "FAILED"));
            }

            echo json_encode([
                'success' => true,
                'id' => $insert_id,
                'receiver_channel' => $receiverChannel,
                'sender_channel' => $senderChannel
            ]);
        } catch (Exception $e) {
            error_log("PUSHER ERROR: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Pusher error: ' . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
    exit;
}


if ($action === 'fetch_contacts') {
    $contacts = [];
    if ($is_admin) {
        $stmt = $mysqli->prepare("
            SELECT u.id AS contact_id, u.username AS contact_name, 'user' AS contact_type,
            (SELECT message FROM messages
             WHERE (sender_id=u.id AND sender_type='user' AND receiver_type='admin')
                OR (receiver_id=u.id AND receiver_type='user' AND sender_type='admin')
             ORDER BY created_at DESC LIMIT 1) AS last_message,
            (SELECT COUNT(*) FROM messages
             WHERE sender_type='user' AND sender_id=u.id AND receiver_type='admin' AND is_read=0) AS unread_count
            FROM users u WHERE is_admin=0 ORDER BY u.username ASC
        ");
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $contacts[] = $row;
        $stmt->close();

        $stmt = $mysqli->prepare("
            SELECT x.guest_id AS contact_id, CONCAT('Guest #', x.guest_id) AS contact_name, 'guest' AS contact_type,
            (SELECT message FROM messages
             WHERE (sender_id=x.guest_id AND sender_type='guest')
                OR (receiver_id=x.guest_id AND receiver_type='guest')
             ORDER BY created_at DESC LIMIT 1) AS last_message,
            (SELECT COUNT(*) FROM messages
             WHERE sender_type='guest' AND sender_id=x.guest_id AND receiver_type='admin' AND is_read=0) AS unread_count
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
            SELECT id AS contact_id, username AS contact_name, 'admin' AS contact_type,
            '' AS last_message,
            (SELECT COUNT(*) FROM messages
             WHERE sender_type='admin' AND receiver_id=? AND receiver_type=? AND is_read=0) AS unread_count
            FROM users WHERE is_admin=1 LIMIT 1
        ");
        $stmt->bind_param("is", $current_id, $user_type);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) $contacts[] = $row;
        $stmt->close();
    }

    echo json_encode(['success' => true, 'contacts' => $contacts]);
    exit;
}

if ($action === 'fetch_messages') {
    $receiver_id = $_POST['receiver_id'] ?? '';
    $receiver_type = $_POST['receiver_type'] ?? '';

    $stmt = $mysqli->prepare("
        UPDATE messages SET is_read=1
        WHERE sender_id=? AND sender_type=? AND receiver_id=? AND receiver_type=? AND is_read=0
    ");
    $stmt->bind_param("ssss", $receiver_id, $receiver_type, $current_id, $user_type);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("
        SELECT * FROM messages
        WHERE (sender_id=? AND sender_type=? AND receiver_id=? AND receiver_type=?)
           OR (sender_id=? AND sender_type=? AND receiver_id=? AND receiver_type=?)
        ORDER BY created_at ASC 
    ");
    $stmt->bind_param("ssssssss", $current_id, $user_type, $receiver_id, $receiver_type, $receiver_id, $receiver_type, $current_id, $user_type);
    $stmt->execute();
    $res = $stmt->get_result();
    $messages = [];
    while ($row = $res->fetch_assoc()) $messages[] = $row;
    $stmt->close();

    echo json_encode(['success' => true, 'messages' => $messages]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
