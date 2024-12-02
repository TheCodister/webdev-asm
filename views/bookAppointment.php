<?php
require_once '../config/database.php';
require_once '../middleware/sessionCheck.php';

$database = new Database();
$db = $database->getConnection();

// Fetch doctors
$query = "SELECT id, name FROM doctors";
$stmt = $db->prepare($query);
$stmt->execute();
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch available time slots for each doctor
$timeSlotsQuery = "SELECT * FROM time_slots WHERE available = 1";
$timeStmt = $db->prepare($timeSlotsQuery);
$timeStmt->execute();
$timeSlots = $timeStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow-lg p-4" style="max-width: 500px; width: 100%;">
        <?php if (empty($doctors)) : ?>
            <div class="alert alert-warning text-center">
                No doctors available for appointments at this time.
            </div>
        <?php else : ?>
            <h3 class="text-center mb-4">Book an Appointment</h3>
            <form action="/assignment/routes/bookAppointmentRoutes.php?action=book_appointment" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone:</label>
                    <input type="text" name="phone" id="phone" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="doctor_id" class="form-label">Select Doctor:</label>
                    <select name="doctor_id" id="doctor_id" class="form-select" required>
                        <option value="">Select a doctor</option>
                        <?php foreach ($doctors as $doctor) : ?>
                            <option value="<?= $doctor['id'] ?>"><?= htmlspecialchars($doctor['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="time_slot" class="form-label">Select Time Slot:</label>
                    <select name="time_slot" id="time_slot" class="form-select" required>
                        <option value="">Select a time slot</option>
                    </select>
                </div>

                <script>
                document.getElementById('doctor_id').addEventListener('change', function () {
                    const doctorId = this.value;
                    const timeSlotSelect = document.getElementById('time_slot');

                    if (doctorId) {
                        fetch(`/assignment/routes/getTimeSlotRoute.php?doctor_id=${doctorId}`)
                            .then(response => response.json())
                            .then(data => {
                                timeSlotSelect.innerHTML = '<option value="">Select a time slot</option>';
                                data.forEach(slot => {
                                    const option = document.createElement('option');
                                    option.value = slot.slot_time;
                                    option.textContent = slot.slot_time;
                                    timeSlotSelect.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error fetching time slots:', error));
                    } else {
                        timeSlotSelect.innerHTML = '<option value="">Select a time slot</option>';
                    }
                });
                </script>

                <div class="d-flex flex-column gap-3">
                    <button type="submit" class="btn btn-primary w-100">Book Appointment</button>
                    <a class="btn btn-secondary w-100" href="../controllers/LogoutController.php">Log out</a> 
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
