<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include '../../db/db.php';
include '../layout/layout.php';



$token = $_GET['token'] ?? '';
$error = '';
$success = '';
$user_id = null;

if ($token) {
  $stmt = $mysqli->prepare("SELECT user_id, expires_at FROM password_resets WHERE token = ? LIMIT 1");
  $stmt->bind_param("s", $token);
  $stmt->execute();
  $row = $stmt->get_result()->fetch_assoc();

  if ($row && strtotime($row['expires_at']) > time()) {
    $user_id = $row['user_id'];
  } else {
    $error = "Linku nuk është valid ose ka skaduar.";
  }
} else {
  $error = "Token mungon.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user_id) {
  $password = $_POST['password'] ?? '';
  $confirm = $_POST['confirm_password'] ?? '';

  if (!$password || !$confirm) {
    $error = "Plotëso të gjitha fushat.";
  } elseif ($password !== $confirm) {
    $error = "Fjalëkalimet nuk përputhen.";
  } elseif (strlen($password) < 6) {
    $error = "Fjalëkalimi duhet të jetë të paktën 6 karaktere.";
  } else {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed, $user_id);
    $stmt->execute();

    $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    $success = "Fjalëkalimi  !";
    $user_id = null;
  }
}
?>

<!DOCTYPE html>
<html lang="sq">

<head>
  <meta charset="UTF-8">
  <title>Rivendos Fjalëkalimin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php showFutureBlockBackground(); ?>
  <div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card p-4 shadow-sm" style="max-width: 400px; width: 100%;">
      <h3 class="text-center text-primary mb-4">Vendos fjalëkalimin e ri</h3>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <a href="login.php" class="btn btn-success w-100 mt-2">Kyçu</a>
      <?php elseif ($user_id): ?>
        <form method="POST">
          <div class="mb-3">
            <label for="password" class="form-label">Fjalëkalimi i ri</label>
            <input type="password" name="password" id="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="confirm_password" class="form-label">Konfirmo fjalëkalimin</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Ndrysho fjalëkalimin</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>