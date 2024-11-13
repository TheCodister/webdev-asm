<?php
include_once 'models/DoctorOffice.php';
include_once 'config/database.php';

class DoctorOfficeController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function showAvailableSlots($doctor_office_id) {
        $doctorOffice = new DoctorOffice($this->db);
        $doctorOffice->id = $doctor_office_id;
        return $doctorOffice->getAvailableSlots();
    }
}
