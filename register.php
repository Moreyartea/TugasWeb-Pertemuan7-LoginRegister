<?php

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nama === '' || $email === '' || $password === '') {
        $error = 'Semua field wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
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

        $emailExists = false;

        foreach ($users as $user) {
            if (isset($user['email']) && strtolower($user['email']) === strtolower($email)) {
                $emailExists = true;
                break;
            }
        }

        if ($emailExists) {
            $error = 'Email sudah terdaftar.';
        } else {
            $ids = array_column($users, 'id');
            $newId = empty($ids) ? 1 : max($ids) + 1;

            $users[] = [
                'id' => $newId,
                'nama' => $nama,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'remember_token_hash' => null,
                'created_at' => date('Y-m-d H:i:s')
            ];

            file_put_contents(
                $file,
                json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                LOCK_EX
            );

            $_SESSION['success'] = 'Registrasi berhasil. Silakan login.';
            header('Location: login.php');
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Login System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="page-wrapper">
    <div class="auth-card">
        <div class="brand">
            <div class="brand-icon">🔐</div>
            <h1>Buat Akun</h1>
            <p>Registrasi akun baru</p>
        </div>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="Masukkan nama lengkap"
                    value="<?= htmlspecialchars($_POST['nama'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

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
                    placeholder="Minimal 6 karakter"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>

        <div class="auth-footer">
            <p>Sudah punya akun?</p>
            <a href="login.php">Login sekarang</a>
        </div>
    </div>
</div>

</body>
</html>