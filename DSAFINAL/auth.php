<?php

require_once __DIR__ . '/db.php';

$createSql = "CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
$conn->query($createSql);

$action = isset($_GET['action']) ? strtolower(trim($_GET['action'])) : '';

function redirect_with($location) {
    header('Location: ' . $location);
    exit;
}

if ($action === 'register') {
    $fullName = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? (string)$_POST['password'] : '';

    if ($fullName === '' || $email === '' || $password === '') {
        redirect_with('login.html?mode=register&error=missing_fields');
    }

    // Check duplicate email
    $check = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $check->bind_param('s', $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $check->close();
        redirect_with('login.html?mode=register&error=email_taken');
    }
    $check->close();

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $fullName, $email, $hash);
    if ($stmt->execute()) {
        $stmt->close();
        redirect_with('dashboard.html?authed=1');
    } else {
        $stmt->close();
        redirect_with('login.html?mode=register&error=server');
    }
}

if ($action === 'login') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? (string)$_POST['password'] : '';

    if ($email === '' || $password === '') {
        redirect_with('login.html?mode=login&error=missing_fields');
    }

    $stmt = $conn->prepare('SELECT id, password_hash FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($userId, $passwordHash);
        $stmt->fetch();
        if (password_verify($password, $passwordHash)) {
            $stmt->close();
            redirect_with('dashboard.html?authed=1');
        }
    }
    $stmt->close();
    redirect_with('login.html?mode=login&error=invalid_credentials');
}

// Fallback
redirect_with('login.html');
?>


