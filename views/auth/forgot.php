<?php
session_start();
include '../../db/db.php';         
include '../layout/layout.php'; 

$message = $_SESSION['message'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <title>Keni harruar fjalëkalimin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<?php showFutureBlockBackground(); ?>

<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
      <h3 class="text-center mb-4 text-primary">Rivendos fjalëkalimin</h3>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <form action="../../controllers/forgot_process.php" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Emaili i përdoruesit</label>
          <input type="email" name="email" id="email" class="form-control" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary w-100">Vazhdo</button>
      </form>
    </div>
  </div>
</body>
</html>
