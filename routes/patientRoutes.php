<?php
include_once '../controllers/AppointmentController.php';

$appointmentController = new AppointmentController();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_appointment'])) {
    $appointmentController->createAppointment($_POST['patient_id'], $_POST['doctor_office_id'], $_POST['appointment_date']);
}
