<?php
require_once '../config/database.php';
require_once '../controllers/DoctorController.php';

// Initialize database and controller
$database = new Database();
$db = $database->getConnection();
$doctorController = new DoctorController($db);

// Mock doctor ID (Replace with session or authentication logic in production)
$doctorId = 1; // Replace with actual logged-in doctor ID
$appointments = $doctorController->viewAppointments($doctorId);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'])) {
    $appointmentId = $_POST['appointment_id'];
    $doctorController->confirmAppointment($appointmentId);
    header("Location: viewAppointments.php"); // Refresh to show updated status
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2 class="mt-5">Your Appointments</h2>

    <?php if (empty($appointments)) : ?>
        <p>No appointments at the moment.</p>
    <?php else : ?>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient ID</th>
                    <th>Time Slot</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment) : ?>
                    <tr>
                        <td><?= $appointment['id'] ?></td>
                        <td><?= $appointment['patient_id'] ?></td>
                        <td><?= $appointment['time_slot'] ?></td>
                        <td><?= $appointment['status'] ?></td>
                        <td>
                            <?php if ($appointment['status'] == 'pending') : ?>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Confirm</button>
                                </form>
                            <?php else : ?>
                                <span class="text-success">Confirmed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
