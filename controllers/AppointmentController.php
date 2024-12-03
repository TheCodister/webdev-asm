<?php
require '../vendor/autoload.php';
require_once '../models/AppointmentModel.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AppointmentController {
    private $model;

    public function __construct($db) {
        $this->model = new AppointmentModel($db);
    }

    public function bookAppointment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Capture patient information
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $doctorId = $_POST['doctor_id'];
            $timeSlot = $_POST['time_slot'];

            // Create a new patient if not already in the database
            $patientId = $this->model->getPatientId($name, $email, $phone);

            // If the patient does not exist, insert a new patient
            if (!$patientId) {
                $patientId = $this->model->addPatient($name, $phone, $email);
            }

            // Save the appointment
            $success = $this->model->bookAppointment($patientId, $doctorId, $timeSlot);

            if ($success) {
                $this->sendConfirmationEmail($email);
                echo "<script>alert('Appointment booked successfully!');</script>";
                echo "<script>window.location.href = '/assignment/views/bookAppointment.php';</script>";
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
            // SMTP server configuration for sending email
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Use gmail smtp server
            $mail->SMTPAuth = true;
            $mail->Username = 'quangcuber002@gmail.com'; // Use the gmail address
            $mail->Password = 'ktfnpjsstwukfhrx';    // Password for that app password
            $mail->SMTPSecure = 'ssl';  
            $mail->Port = 465;                     // Default SMTP port for gmail
            
            // Email content
            $mail->setFrom('quangcuber002@gmail.com', 'Doctor Appointment');
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = 'Appointment Confirmation';
            $mail->Body = "Your appointment has been confirmed. Please arrive 10 minutes before your appointment time.";
            
            $mail->send();
            echo "Confirmation email sent!";
        } catch (Exception $e) {
            echo "Error sending email: {$mail->ErrorInfo}";
        }
    }
    
}
