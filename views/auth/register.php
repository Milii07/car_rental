<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include $_SERVER['DOCUMENT_ROOT'] . '/new_project_bk/views/layout/layout.php';
?>

<!DOCTYPE html>
<html lang="sq">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Rregjistrohu - FutureBlock</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f9;
    }

    .login-card {
      background: #fff;
      border-radius: 25px;
      padding: 100px 120px;
      /* më shumë hapësirë */
      box-shadow: 0 12px 60px rgba(0, 0, 0, 0.18);
      max-width: 900px;
      /* më e gjerë */
      margin: 70px auto;
      text-align: center;
    }

    .form-control {
      border-radius: 18px;
      height: 70px;
      padding-left: 95px;
      font-size: 1.15rem;
    }

    .input-group-text {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      background: transparent;
      border: none;
      color: #2575fc;
      font-size: 1.7rem;
      z-index: 2;
    }

    .btn {
      text-transform: uppercase;
      font-weight: 700;
      letter-spacing: 1px;
      cursor: pointer;
      font-size: 1.2rem;
      padding: 15px 35px;
    }

    h3 {
      font-size: 40px;
      font-weight: 700;
      color: #2575fc;
      margin-bottom: 40px;
    }
  </style>
</head>

<body>
  <?php showFutureBlockBackground(); ?>

  <div class="login-card">
    <h3 class="mb-4">Rregjistrohu</h3>

    <?php if (!empty($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']);
                                      unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']);
                                        unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form action="/new_project_bk/controllers/register_progress.php" method="POST">
      <div class="input-group mb-3 position-relative">
        <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
        <input type="text" class="form-control" name="username" placeholder="Përdoruesi" required>
      </div>
      <div class="input-group mb-3 position-relative">
        <span class="input-group-text"><i class="bi bi-envelope-at-fill"></i></span>
        <input type="email" class="form-control" name="email" placeholder="Email" required>
      </div>
      <div class="input-group mb-3 position-relative">
        <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
        <input type="password" class="form-control" name="password" placeholder="Fjalëkalimi" required>
      </div>
      <div class="input-group mb-4 position-relative">
        <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
        <input type="password" class="form-control" name="confirm_password" placeholder="Konfirmo fjalëkalimin" required>
      </div>

      <button type="submit" class="btn btn-primary w-100">Rregjistrohu</button>
    </form>

    <p class="mt-3">Ke llogari? <a href="/new_project_bk/views/auth/login.php">Kyçu këtu</a></p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>