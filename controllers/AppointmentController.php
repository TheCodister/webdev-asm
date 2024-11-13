<?php
require 'vendor/autoload.php';
require_once 'models/AppointmentModel.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AppointmentController {
    private $model;

    public function __construct($db) {
        $this->model = new AppointmentModel($db);
    }

    public function bookAppointment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $patientId = $_POST['patient_id'];
            $doctorId = $_POST['doctor_id'];
            $timeSlot = $_POST['time_slot'];
            $success = $this->model->bookAppointment($patientId, $doctorId, $timeSlot);

            if ($success) {
                echo "Appointment booked successfully!";
                $this->sendConfirmationEmail("JohnDoe@example.com");
            } else {
                echo "Failed to book appointment.";
            }
        }
    }
    public function markTimeSlotUnavailable() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $doctorId = $_POST['doctor_id'];
            $timeSlot = $_POST['time_slot'];
            $this->model->markTimeSlotUnavailable($doctorId, $timeSlot);
            echo "Time slot marked as unavailable!";
        }
    }

    private function sendConfirmationEmail($to) {
        $mail = new PHPMailer(true);
        try {
            // SMTP server configuration for Mercury Mail
            $mail->isSMTP();
            $mail->Host = '127.0.0.1';  // Mercury server is on localhost
            $mail->SMTPAuth = true;
            $mail->Username = 'user@example.com'; // Use the local user created in Mercury
            $mail->Password = '123456';    // Password for that Mercury user
            $mail->Port = 25;                     // Default SMTP port for Mercury
            
            // Email content
            $mail->setFrom('user@example.com', 'Doctor Appointment');
            $mail->addAddress($to);
            $mail->Subject = 'Appointment Confirmation';
            $mail->Body = "Your appointment has been confirmed. Please arrive 10 minutes before your appointment time.";
            
            $mail->send();
            echo "Confirmation email sent!";
        } catch (Exception $e) {
            echo "Error sending email: {$mail->ErrorInfo}";
        }
    }
    
}
