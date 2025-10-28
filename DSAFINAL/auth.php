<?php
function requireAuth() {
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    
    // Regenerate session ID periodically for security
    if (!isset($_SESSION['last_regeneration']) || time() - $_SESSION['last_regeneration'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return $_SESSION['user_role'] ?? 'member';
}

function requirePremium() {
    if (getUserRole() !== 'premium') {
        header('Location: upgrade-membership.php');
        exit;
    }
}
?>