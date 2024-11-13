<?php
class AppointmentModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function bookAppointment($patientId, $doctorId, $timeSlot) {
        try {
            // Begin a transaction
            $this->conn->beginTransaction();

            // Insert the appointment record
            $query = "INSERT INTO appointments (patient_id, doctor_id, time_slot) VALUES (:patient_id, :doctor_id, :time_slot)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":patient_id", $patientId);
            $stmt->bindParam(":doctor_id", $doctorId);
            $stmt->bindParam(":time_slot", $timeSlot);
            $stmt->execute();

            // Update the time slot to mark it as unavailable
            $this->markTimeSlotUnavailable($doctorId, $timeSlot);

            // Commit the transaction
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Roll back if there’s an error
            $this->conn->rollBack();
            echo "Failed to book appointment: " . $e->getMessage();
            return false;
        }
    }

    private function markTimeSlotUnavailable($doctorId, $timeSlot) {
        $query = "UPDATE time_slots SET available = 0 WHERE doctor_id = :doctor_id AND slot_time = :slot_time";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":doctor_id", $doctorId);
        $stmt->bindParam(":slot_time", $timeSlot);
        $stmt->execute();
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
