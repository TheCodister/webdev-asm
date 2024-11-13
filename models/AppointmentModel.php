<?php
class AppointmentModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function bookAppointment($patientId, $doctorId, $timeSlot) {
        $query = "INSERT INTO appointments (patient_id, doctor_id, time_slot) VALUES (:patient_id, :doctor_id, :time_slot)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":patient_id", $patientId);
        $stmt->bindParam(":doctor_id", $doctorId);
        $stmt->bindParam(":time_slot", $timeSlot);
        return $stmt->execute();
    }

    public function getAppointments($doctorId) {
        $query = "SELECT * FROM appointments WHERE doctor_id = :doctor_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":doctor_id", $doctorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
