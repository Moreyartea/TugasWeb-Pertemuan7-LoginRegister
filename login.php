<?php

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $tokenHash = hash('sha256', $token);
    $file = 'users.json';

    if (file_exists($file)) {
        $json = file_get_contents($file);
        $users = json_decode($json, true);

        if (is_array($users)) {
            foreach ($users as $user) {
                if (
                    isset($user['remember_token_hash']) &&
                    $user['remember_token_hash'] !== null &&
                    hash_equals($user['remember_token_hash'], $tokenHash)
                ) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['nama'];
                    $_SESSION['email'] = $user['email'];

                    header('Location: dashboard.php');
                    exit;
                }
            }
        }
    }
}

$error = '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } else {
        $file = 'users.json';
        $users = [];

        if (file_exists($file)) {
            $json = file_get_contents($file);
            $users = json_decode($json, true);

            if (!is_array($users)) {
                $users = [];
            }
        }

        $foundUser = null;
        $foundIndex = null;

        foreach ($users as $index => $user) {
            if (
                isset($user['email']) &&
                strtolower($user['email']) === strtolower($email)
            ) {
                $foundUser = $user;
                $foundIndex = $index;
                break;
            }
        }

        if ($foundUser && password_verify($password, $foundUser['password'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $foundUser['id'];
            $_SESSION['username'] = $foundUser['nama'];
            $_SESSION['email'] = $foundUser['email'];

            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $tokenHash = hash('sha256', $token);

                $users[$foundIndex]['remember_token_hash'] = $tokenHash;

                file_put_contents(
                    $file,
                    json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                    LOCK_EX
                );

                setcookie(
                    'remember_token',
                    $token,
                    [
                        'expires' => time() + (30 * 24 * 60 * 60),
                        'path' => '/',
                        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]
                );
            }

            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email atau password salah.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Login System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="page-wrapper">
    <div class="auth-card">
        <div class="brand">
            <div class="brand-icon">🔐</div>
            <h1>Selamat Datang</h1>
            <p>Login ke akun Anda</p>
        </div>

        <?php if ($success !== ''): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Masukkan email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                >
            </div>

            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                <span>Remember Me</span>
            </label>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <div class="auth-footer">
            <p>Belum punya akun?</p>
            <a href="register.php">Buat akun baru</a>
        </div>
    </div>
</div>

</body>
</html>