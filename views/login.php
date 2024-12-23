<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $database = new Database();
    $db = $database->getConnection();

    // Verify user credentials
    $query = "SELECT id, role FROM users WHERE username = :username AND password = :password";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password); // In production, use hashed passwords
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        $_SESSION['start_time'] = time(); // Track session start time

        if ($user['role'] === 'doctor') {
            // Fetch office_id for doctor
            $query = "SELECT office_id FROM doctors WHERE id = :user_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $user['id']);
            $stmt->execute();
            $office = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($office) {
                $_SESSION['office_id'] = $office['office_id'];
            }
            header('Location: viewOfficeAppointments.php');
        } else if($user['role'] === 'patient'){
            header('Location: bookAppointment.php');
        } else if($user['role'] === 'staff'){
            header('Location: viewAppointments.php');
        }
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-4">Login</h3>
        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
            <a href="../" class="btn btn-secondary w-100">Return to Homepage</a>
        </form>
    </div>
</div>
</body>
</html>
