<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: welcome.php");
        exit;
    }
}

function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}
?>