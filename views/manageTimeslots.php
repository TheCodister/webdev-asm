<?php
require_once '../middleware/doctorCheck.php';
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['slot_time'])) {
        $slotTime = $_POST['slot_time'];
        $doctorId = $_SESSION['user_id']; // Assuming user_id is stored in session

        // Insert time slot
        $query = "INSERT INTO time_slots (doctor_id, slot_time, available) VALUES (:doctor_id, :slot_time, 1)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':doctor_id', $doctorId);
        $stmt->bindParam(':slot_time', $slotTime);

        if ($stmt->execute()) {
            $message = "Time slot added successfully!";
        } else {
            $message = "Error adding time slot.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Time Slots</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Manage Time Slots</h2>
    <a class="btn btn-primary w-100" href="viewOfficeAppointments.php">View Appointment</a>
    <?php if (isset($message)) : ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="slot_time" class="form-label">Time Slot:</label>
            <input type="datetime-local" id="slot_time" name="slot_time" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Time Slot</button>
    </form>

    <h3>Your Time Slots</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Slot Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $doctorId = $_SESSION['user_id'];
            $query = "SELECT id, slot_time, available FROM time_slots WHERE doctor_id = :doctor_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':doctor_id', $doctorId);
            $stmt->execute();
            $timeSlots = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($timeSlots as $slot) :
            ?>
                <tr>
                    <td><?= htmlspecialchars($slot['slot_time']) ?></td>
                    <td><?= $slot['available'] ? 'Available' : 'Unavailable' ?></td>
                    <td>
                        <form method="POST" action="deleteTimeslot.php" style="display: inline;">
                            <input type="hidden" name="slot_id" value="<?= $slot['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
