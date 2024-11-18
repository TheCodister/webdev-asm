<?php
require_once '../config/database.php';
require_once '../controllers/AppointmentController.php';

$database = new Database();
$db = $database->getConnection();

$action = $_GET['action'] ?? '';

if ($action == 'book_appointment') {
    $controller = new AppointmentController($db);
    $controller->bookAppointment();
}
?>
