<?php
// Include database configuration file
require_once __DIR__ . '/../config/database.php';

// Instantiate database and get connection
$database = new Database();
$db = $database->getConnection();

// Check if the 'page' parameter exists in the URL
$page = isset($_GET['page']) ? $_GET['page'] : 'home'; // Default to 'home' if no page is set

// Define the base path for your views folder
$base_path = __DIR__ . '/../views/';

// Determine the file to include based on the page
switch ($page) {
    case 'login':
        $file_path = $base_path . 'login.php';
        break;
    case 'bookAppointment':
        $file_path = $base_path . 'bookAppointment.php';
        break;
    case 'department':
        $file_path = $base_path . 'department.php';
        break;
    case 'doctors':
        $file_path = $base_path . 'doctors.php';
        break;
    case 'manageTimeslots':
        $file_path = $base_path . 'manageTimeslots.php';
        break;
    case 'viewAppointments':
        $file_path = $base_path . 'viewAppointments.php';
        break;
    case 'viewOfficeAppointments':
        $file_path = $base_path . 'viewOfficeAppointments.php';
        break;
    default:
        $file_path = $base_path . 'home.php';
        break;
}

// Check if the file exists before including it
if (file_exists($file_path)) {
    include $file_path;
} else {
    // Display a user-friendly error message if the file is missing
    echo "<h2>404 - Page not found</h2>";
    echo "<p>The page you are looking for does not exist.</p>";
}
?>
