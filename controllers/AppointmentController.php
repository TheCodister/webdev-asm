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
                $this->sendConfirmationEmail("quangcuber002@gmail.com");
            } else {
                echo "Failed to book appointment.";
            }
        }
    }

    private function sendConfirmationEmail($to) {
        $mail = new PHPMailer(true);
        try {
            // SMTP server configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com';
            $mail->Password = 'your_email_password';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Email content
            $mail->setFrom('your_email@gmail.com', 'Doctor Appointment');
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
