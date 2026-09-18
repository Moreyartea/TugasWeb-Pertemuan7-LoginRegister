<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$file = 'users.json';
$users = [];

if (!file_exists($file)) {
    $_SESSION['error'] = 'Data user tidak ditemukan.';
    header('Location: dashboard.php');
    exit;
}

$json = file_get_contents($file);
$users = json_decode($json, true);

if (!is_array($users)) {
    $_SESSION['error'] = 'Data user tidak valid.';
    header('Location: dashboard.php');
    exit;
}

$currentIndex = null;

foreach ($users as $index => $user) {
    if (isset($user['id']) && $user['id'] == $_SESSION['user_id']) {
        $currentIndex = $index;
        break;
    }
}

if ($currentIndex === null) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nama === '' || $email === '') {
        $error = 'Nama dan email wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } else {
        $emailExists = false;

        foreach ($users as $index => $user) {
            if (
                $index !== $currentIndex &&
                isset($user['email']) &&
                strtolower($user['email']) === strtolower($email)
            ) {
                $emailExists = true;
                break;
            }
        }

        if ($emailExists) {
            $error = 'Email sudah digunakan oleh akun lain.';
        } else {
            $users[$currentIndex]['nama'] = $nama;
            $users[$currentIndex]['email'] = $email;

            file_put_contents(
                $file,
                json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                LOCK_EX
            );

            $_SESSION['username'] = $nama;
            $_SESSION['email'] = $email;

            $success = 'Profile berhasil diperbarui.';
        }
    }
}

$currentUser = $users[$currentIndex];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Login System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="dashboard.php" class="nav-brand">Login System</a>

        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php" class="nav-logout">Logout</a>
        </div>
    </div>
</nav>

<main class="main-container">
    <section class="form-card">
        <div class="section-heading">
            <span class="eyebrow">PROFILE</span>
            <h1>Edit Profile</h1>
            <p>Perbarui informasi akun Anda.</p>
        </div>

        <?php if ($error !== ''): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="<?= htmlspecialchars($currentUser['nama'], ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($currentUser['email'], ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="profile.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </section>
</main>

</body>
</html>