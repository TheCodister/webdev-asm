<?php
require_once '../models/AppointmentModel.php';

class DoctorController {
    private $model;

    public function __construct($db) {
        $this->model = new AppointmentModel($db);
    }

    // Load appointments for the logged-in doctor
    public function viewAppointments($doctorId) {
        return $this->model->getAppointmentsForDoctor($doctorId);
    }

    // Confirm an appointment
    public function confirmAppointment($appointmentId) {
        return $this->model->confirmAppointment($appointmentId);
    }
}
