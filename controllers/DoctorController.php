<?php
class DoctorController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function viewAppointments() {
        try {
            $query = "
                SELECT 
                    a.id AS appointment_id, 
                    a.patient_id, 
                    a.time_slot, 
                    a.status, 
                    d.name AS doctor_name
                FROM 
                    appointments a
                INNER JOIN 
                    doctors d ON a.doctor_id = d.id
                ORDER BY 
                    a.time_slot ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Failed to fetch appointments: " . $e->getMessage();
            return [];
        }
    }

    public function confirmAppointment($appointmentId) {
        try {
            $query = "UPDATE appointments SET status = 'confirmed' WHERE id = :appointment_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":appointment_id", $appointmentId);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Failed to confirm appointment: " . $e->getMessage();
        }
    }
}
