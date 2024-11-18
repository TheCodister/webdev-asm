<?php
class AppointmentModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getPatientId($name, $email, $phone) {
        $query = "SELECT id FROM patients WHERE email = :email AND phone = :phone";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->execute();
        
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
        return $patient ? $patient['id'] : null;
    }

    // Add a new patient if they do not exist
    public function addPatient($name, $phone, $email) {
        $query = "INSERT INTO patients (name, phone, email) VALUES (:name, :phone, :email)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        
        return $this->conn->lastInsertId(); // Return the new patient's ID
    }

    // Book the appointment
    public function bookAppointment($patientId, $doctorId, $timeSlot) {
        try {
            // Begin a transaction
            $this->conn->beginTransaction();

            // Insert the appointment record
            $appointmentQuery = "INSERT INTO appointments (patient_id, doctor_id, time_slot) VALUES (:patient_id, :doctor_id, :time_slot)";
            $appointmentStmt = $this->conn->prepare($appointmentQuery);
            $appointmentStmt->bindParam(":patient_id", $patientId);
            $appointmentStmt->bindParam(":doctor_id", $doctorId);
            $appointmentStmt->bindParam(":time_slot", $timeSlot);
            $appointmentStmt->execute();

            // Mark the time slot as unavailable
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

    // Mark the time slot as unavailable after booking the appointment
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

    public function getAppointmentsForDoctor($doctorId) {
        $query = "SELECT * FROM appointments WHERE doctor_id = :doctor_id ORDER BY time_slot ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":doctor_id", $doctorId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function confirmAppointment($appointmentId) {
        $query = "UPDATE appointments SET status = 'confirmed' WHERE id = :appointment_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":appointment_id", $appointmentId);
        return $stmt->execute();
    }
}
?>
