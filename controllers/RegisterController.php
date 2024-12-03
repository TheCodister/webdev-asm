<?php
require_once '../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = trim($_POST['role']); // Default value is 'patient'

    // Validate input
    if (empty($username) || empty($password) || empty($email) || empty($phone)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit();
    }

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.history.back();</script>";
        exit();
    }

    // Hash the password for security

    // Establish a database connection
    $database = new Database();
    $db = $database->getConnection();

    try {
        // Check if the username or email already exists
        $query = "SELECT * FROM users WHERE username = :username OR email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Username or email already exists.'); window.history.back();</script>";
            exit();
        }

        // Insert the new user into the database
        $insertQuery = "INSERT INTO users (username, password, email, phone, role) VALUES (:username, :password, :email, :phone, :role)";
        $insertStmt = $db->prepare($insertQuery);
        $insertStmt->bindParam(':username', $username);
        $insertStmt->bindParam(':password', $password);
        $insertStmt->bindParam(':email', $email);
        $insertStmt->bindParam(':phone', $phone);
        $insertStmt->bindParam(':role', $role);

        if ($insertStmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location.href = '../views/login.php';</script>";
        } else {
            echo "<script>alert('An error occurred while registering. Please try again.'); window.history.back();</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Database error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
