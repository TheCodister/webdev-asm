<?php
class DoctorOffice {
    private $conn;
    private $table_name = "doctor_offices";

    public $id;
    public $name;
    public $available_slots;
    public $email;
    public $phone;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAvailableSlots() {
        $query = "SELECT available_slots FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
