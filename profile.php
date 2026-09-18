<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$file = 'users.json';
$users = [];
$currentUser = null;

if (file_exists($file)) {
    $json = file_get_contents($file);
    $users = json_decode($json, true);

    if (is_array($users)) {
        foreach ($users as $user) {
            if (isset($user['id']) && $user['id'] == $_SESSION['user_id']) {
                $currentUser = $user;
                break;
            }
        }
    }
}

if (!$currentUser) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Login System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="dashboard.php" class="nav-brand">Login System</a>

        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="profile.php" class="active">Profile</a>
            <a href="logout.php" class="nav-logout">Logout</a>
        </div>
    </div>
</nav>

<main class="main-container">
    <section class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <?= strtoupper(substr($currentUser['nama'], 0, 1)) ?>
            </div>

            <div>
                <span class="eyebrow">PROFILE</span>
                <h1><?= htmlspecialchars($currentUser['nama'], ENT_QUOTES, 'UTF-8') ?></h1>
                <p><?= htmlspecialchars($currentUser['email'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        </div>

        <div class="profile-details">
            <div class="info-item">
                <span>ID User</span>
                <strong><?= htmlspecialchars((string) $currentUser['id'], ENT_QUOTES, 'UTF-8') ?></strong>
            </div>

            <div class="info-item">
                <span>Nama Lengkap</span>
                <strong><?= htmlspecialchars($currentUser['nama'], ENT_QUOTES, 'UTF-8') ?></strong>
            </div>

            <div class="info-item">
                <span>Email</span>
                <strong><?= htmlspecialchars($currentUser['email'], ENT_QUOTES, 'UTF-8') ?></strong>
            </div>

            <div class="info-item">
                <span>Terdaftar Pada</span>
                <strong><?= htmlspecialchars($currentUser['created_at'], ENT_QUOTES, 'UTF-8') ?></strong>
            </div>
        </div>

        <div class="profile-actions">
            <a href="edit-profile.php" class="btn btn-primary">Edit Profile</a>
            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
        </div>
    </section>
</main>

</body>
</html>