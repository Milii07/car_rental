<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../db/db.php';
include '../layout/layout.php';
?>

<!DOCTYPE html>
<html lang="sq">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kyçu - FutureBlock</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            width: 520px;
            max-width: 95vw;
            text-align: center;
        }

        .form-control {
            border-radius: 10px;
            padding-left: 55px;
            height: 55px;
            font-size: 1.1rem;
        }

        .input-group-text {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            color: #2575fc;
            font-size: 1.5rem;
        }

        .rounded-input {
            border-radius: 10px !important;
        }

        .btn {
            font-size: 1.1rem;
            padding: 12px 30px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
        }

        .role-buttons {
            margin-bottom: 30px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
    </style>
</head>

<body>
    <?php showFutureBlockBackground(); ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger w-50 mx-auto mt-3"><?= htmlspecialchars($_SESSION['error']);
                                                            unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="login-card mx-auto mt-5">
        <div class="role-buttons">
            <a href="login.php?role=user" class="btn btn-outline-primary">Login / Register User</a>
            <a href="login.php?role=client" class="btn btn-outline-success">Login / Register Klient</a>
        </div>

        <h3><?= isset($_GET['role']) && $_GET['role'] == 'client' ? 'Kyçu si Klient' : 'Kyçu si User'; ?></h3>

        <form action="<?= isset($_GET['role']) && $_GET['role'] == 'client' ? '../../controllers/client_login_progress.php' : '../../controllers/login_progress.php'; ?>" method="POST">

            <div class="input-group mb-4 position-relative">
                <span class="input-group-text position-absolute top-50 translate-middle-y" style="left: 15px; background: transparent; border: none; color: #2575fc;">
                    <i class="bi bi-person-circle fs-4"></i>
                </span>
                <input type="email" class="form-control rounded-input ps-6" name="email" placeholder="Email" required autofocus />
            </div>

            <?php if (!isset($_GET['role']) || $_GET['role'] != 'client'): ?>
                <div class="input-group mb-3 position-relative">
                    <span class="input-group-text position-absolute top-50 translate-middle-y" style="left: 15px; background: transparent; border: none; color: #2575fc;">
                        <i class="bi bi-lock-fill fs-4"></i>
                    </span>
                    <input type="password" class="form-control rounded-input ps-6" name="password" placeholder="Fjalëkalimi" required />
                </div>

                <div class="form-check mb-3 text-start">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" />
                    <label class="form-check-label" for="remember"> Më mbaj mend </label>
                </div>
            <?php endif; ?>

            <div class="mb-3 text-end">
                <a href="forgot.php" class="text-decoration-none small">Ke harruar fjalëkalimin?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <?= isset($_GET['role']) && $_GET['role'] == 'client' ? 'Kyçu si Klient' : 'Kyçu si User'; ?>
            </button>
        </form>

        <p class="mt-3">Nuk ke llogari?
            <?php if (isset($_GET['role']) && $_GET['role'] == 'client'): ?>
                <a href="client_register.php">Regjistrohu si Klient</a>
            <?php else: ?>
                <a href="register.php">Regjistrohu si User</a>
            <?php endif; ?>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>