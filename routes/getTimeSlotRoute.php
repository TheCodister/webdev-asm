<?php
require_once '../config/database.php';

if (isset($_GET['doctor_id'])) {
    $doctorId = $_GET['doctor_id'];

    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT id, slot_time FROM time_slots WHERE doctor_id = :doctor_id AND available = 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':doctor_id', $doctorId, PDO::PARAM_INT);
    $stmt->execute();

    $timeSlots = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($timeSlots);
}
?>
