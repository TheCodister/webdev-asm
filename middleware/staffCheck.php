<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'staff') {
    // Redirect to login page if not a doctor
    header('Location: ../views/login.php');
    exit();
}
?>