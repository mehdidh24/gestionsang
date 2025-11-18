<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAuth() {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header("Location: login.php");
        exit();
    }
}

function checkRole($allowed_roles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
        header("Location: dashboard.php");
        exit();
    }
}

function canAccess($allowed_roles) {
    return isset($_SESSION['role']) && in_array($_SESSION['role'], $allowed_roles);
}
