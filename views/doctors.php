<?php
session_start(); 
require_once '../config/database.php'; 

try {
    // Establish database connection
    $database = new Database();
    $db = $database->getConnection();

    // Fetch all doctors
    $query = "SELECT id, name, specialty FROM doctors";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../styles.css" rel="stylesheet"> 
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">MEDCARE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li> 
                    <li class="nav-item"><a class="nav-link" href="../index.php#contact">Contact</a></li> 
                    <li class="nav-item"><a class="nav-link" href="../index.php#department">Department</a></li> 
                    <li class="nav-item"><a class="nav-link" href="doctors.php">Doctors</a></li> 
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li> 
                </ul>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Doctors List -->
    <div class="container mt-5">
        <h1 class="mb-4">Our Doctors</h1>
        <?php if (!empty($doctors)): ?>
            <div class="row">
                <?php foreach ($doctors as $doctor): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($doctor['name']) ?></h5>
                                <p class="card-text"><strong>Specialty:</strong> <?= htmlspecialchars($doctor['specialty']) ?></p>
                                <?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true): ?>
                                    <!-- Redirect to login if not logged in -->
                                    <a href="login.php?redirect=doctors.php" class="btn btn-primary">Make Appointment</a>
                                <?php else: ?>
                                    <!-- Proceed to appointment page if logged in -->
                                    <a href="bookAppointment.php?doctor_id=<?= $doctor['id'] ?>" class="btn btn-primary">Make Appointment</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No doctors found.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>