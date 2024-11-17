# Doctor Appointment Booking Application

A web-based application where patients can make appointments with doctors' offices online. The app supports multiple user roles: administrators, staff, doctor offices, and patients. Built with PHP, HTML, CSS, and JavaScript, following the MVC model.

## Prerequisites

- HTML, CSS, JavaScript, PHP
- [XAMPP](https://www.apachefriends.org/index.html) (or any web server environment that supports PHP and MySQL)

## Setup Instructions

### Step 1: Start Apache and MySQL Server

1. Open XAMPP Control Panel.
2. Start the Apache and MySQL servers.

### Step 2: Create the Database and Tables

1. Open phpMyAdmin (usually available at `http://localhost/phpmyadmin`).
2. Run the following SQL query to set up the database and tables:

   ```sql
   CREATE DATABASE doctor_appointment;
   USE doctor_appointment;

   -- Users table
   CREATE TABLE users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       username VARCHAR(50) NOT NULL,
       password VARCHAR(255) NOT NULL,
       role ENUM('admin', 'staff', 'doctor', 'patient') NOT NULL,
       email VARCHAR(100),
       phone VARCHAR(15)
   );

   -- Doctors table
   CREATE TABLE doctors (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(100) NOT NULL,
       specialty VARCHAR(100),
       office_id INT,
       FOREIGN KEY (office_id) REFERENCES users(id) ON DELETE CASCADE
   );
    CREATE TABLE patients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        phone VARCHAR(20),
        email VARCHAR(255)
   );

   -- Appointments table
   CREATE TABLE appointments (
       id INT AUTO_INCREMENT PRIMARY KEY,
       patient_id INT,
       doctor_id INT,
       time_slot DATETIME,
       status ENUM('pending', 'confirmed') DEFAULT 'pending',
       FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
       FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
   );

   -- Time slots table
   CREATE TABLE time_slots (
       id INT AUTO_INCREMENT PRIMARY KEY,
       doctor_id INT,
       slot_time DATETIME,
       available BOOLEAN DEFAULT TRUE,
       FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
   );
   ```

3. Add some doctor data, for example:

```sql
INSERT INTO users (username, password, role, email, phone)
VALUES ('doctor_office_1', 'password123', 'doctor', 'doctor1@example.com', '1234567890');

INSERT INTO doctors (name, specialty, office_id)
VALUES ('Dr. John Doe', 'Cardiology', 1);  -- replace 1 with the actual user ID from step 1

```

4. Download PHPMailer

- Download this repository: [PHPMailer](https://github.com/PHPMailer/PHPMailer)
- Extract the content of the file and put the content of the PHPMailer file in to this directory: vendor/phpmailer
- After extracting, it should look like this. Then you good to go.
  ![image](https://github.com/user-attachments/assets/b2c28ac2-9dda-4daa-a6d7-7a755e8ff18f)

### Step 3: Run the Application

1. Place the project files in the `htdocs` folder of your XAMPP installation (e.g., `C:/xampp/htdocs/doctor_appointment`).
2. Open a web browser and go to the following URL to view the booking appointment page:

   ```
   http://localhost/doctor_appointment/views/bookAppointment.php
   ```

## Features

- **Patient Role**: View available doctors, select a doctor and time slot, and book an appointment.
- **Doctor Office Role**: Manage time slots, view appointments, and confirm appointments.
- **Staff Role**: View all appointment information.
- **Administrator Role**: Manage all user accounts and roles.

## Notes

- Remember to add initial data (users, doctors, patients) in the `users` and `doctors` tables before attempting to book appointments.
- Ensure that `time_slots` are set up for each doctor, as patients will only be able to book available slots.

## License

This project is for educational purposes and is not intended for commercial use.
