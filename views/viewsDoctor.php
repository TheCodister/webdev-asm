<?php
require_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Query to fetch doctors and their available time slots
$query = "
    SELECT d.id AS doctor_id, d.name AS doctor_name, d.specialty, 
           ts.slot_time, ts.available 
    FROM doctors d
    LEFT JOIN time_slots ts ON d.id = ts.doctor_id
    WHERE ts.available = 1
    ORDER BY d.id, ts.slot_time
";

$stmt = $db->prepare($query);
$stmt->execute();
$doctorData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Doctors and Available Time Slots</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Doctors and Available Time Slots</h1>

        <?php
        if (!empty($doctorData)) {
            $currentDoctorId = null;
            foreach ($doctorData as $data) {
                // Check if we're moving to a new doctor
                if ($currentDoctorId !== $data['doctor_id']) {
                    if ($currentDoctorId !== null) {
                        echo '</ul></div>'; // Close previous doctor card if not the first
                    }
                    // New doctor card
                    echo '
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2>' . htmlspecialchars($data['doctor_name']) . '</h2>
                            <p>Specialty: ' . htmlspecialchars($data['specialty']) . '</p>
                        </div>
                        <ul class="list-group list-group-flush">';
                    
                    $currentDoctorId = $data['doctor_id'];
                }
                
                // Display each available time slot for the current doctor
                echo '
                    <li class="list-group-item">
                        Available Time: ' . date("F j, Y, g:i a", strtotime($data['slot_time'])) . '
                    </li>';
            }
            echo '</ul></div>'; // Close the last doctor's card
        } else {
            echo '<p class="text-center">No available time slots at the moment.</p>';
        }
        ?>

    </div>
</body>
</html>
