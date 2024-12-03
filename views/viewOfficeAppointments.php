<?php
require_once '../middleware/doctorCheck.php';
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$officeId = $_SESSION['office_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'])) {
    $appointmentId = $_POST['appointment_id'];
    $query = "UPDATE appointments SET status = 'confirmed' WHERE id = :appointment_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':appointment_id', $appointmentId);

    if ($stmt->execute()) {
        $message = "Appointment confirmed!";
    } else {
        $message = "Error confirming appointment.";
    }
}

// Fetch appointments for the office
$query = "
    SELECT a.id, a.patient_id, a.time_slot, a.status, d.name AS doctor_name
    FROM appointments a
    INNER JOIN doctors d ON a.doctor_id = d.id
    WHERE d.office_id = :office_id
";
$stmt = $db->prepare($query);
$stmt->bindParam(':office_id', $officeId);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Office Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Appointments for Office ID: <?= htmlspecialchars($officeId) ?></h2>
    <a class="btn btn-primary w-100" href="manageTimeslots.php">Manage Time Slots</a>
    <?php if (isset($message)) : ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (empty($appointments)) : ?>
        <p>No appointments at the moment.</p>
    <?php else : ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient ID</th>
                    <th>Time Slot</th>
                    <th>Status</th>
                    <th>Doctor</th>
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
                        <td><?= $appointment['doctor_name'] ?></td>
                        <td>
                            <?php if ($appointment['status'] == 'pending') : ?>
                                <form method="POST">
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
    <a class="btn btn-secondary w-100" href="../controllers/LogoutController.php">Log out</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
