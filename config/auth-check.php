<?php

$timeout = 1800; // 30 minutes

// Check login
if (!isset($_SESSION['id'])) {
    header("Location: index.php?route=login&error=You_are_not_logged_in");
    exit();
}

// Check idle timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: /login.php?msg=Session expired");
    exit();
}

// Update activity timestamp
$_SESSION['last_activity'] = time();
