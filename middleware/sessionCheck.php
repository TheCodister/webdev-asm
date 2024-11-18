<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// Check if session has expired (2 hours = 7200 seconds)
if (time() - $_SESSION['start_time'] > 7200) {
    session_destroy();
    header("Location: ../login.php?session_expired=true");
    exit;
}
?>
