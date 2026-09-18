<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8');

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Login System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="dashboard.php" class="nav-brand">Login System</a>

        <div class="nav-links">
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php" class="nav-logout">Logout</a>
        </div>
    </div>
</nav>

<main class="main-container">
    <section class="dashboard-card">
        <div class="dashboard-icon">🏠</div>

        <div class="dashboard-content">
            <span class="eyebrow">DASHBOARD</span>
            <h1>Selamat datang, <?= $username ?>!</h1>
            <p>Anda berhasil login ke sistem.</p>

            <div class="user-info">
                <div class="info-item">
                    <span>Nama</span>
                    <strong><?= $username ?></strong>
                </div>

                <div class="info-item">
                    <span>Email</span>
                    <strong><?= $email ?></strong>
                </div>
            </div>

            <div class="dashboard-actions">
                <a href="profile.php" class="btn btn-primary">Lihat Profile</a>
                <a href="edit-profile.php" class="btn btn-secondary">Edit Profile</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </section>
</main>

</body>
</html>