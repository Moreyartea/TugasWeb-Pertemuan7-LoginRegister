<?php

session_start();

$file = 'users.json';

if (isset($_SESSION['user_id']) && file_exists($file)) {
    $json = file_get_contents($file);
    $users = json_decode($json, true);

    if (is_array($users)) {
        foreach ($users as $index => $user) {
            if (isset($user['id']) && $user['id'] == $_SESSION['user_id']) {
                $users[$index]['remember_token_hash'] = null;
                break;
            }
        }

        file_put_contents(
            $file,
            json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
    }
}

setcookie(
    'remember_token',
    '',
    [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]
);

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        [
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => 'Lax'
        ]
    );
}

session_destroy();

header('Location: login.php');
exit;