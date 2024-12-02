<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../styles.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand d-flex justify-content-center align-items-center" href="../index.php">
                <img src="../01_logobachkhoatoi.png" alt="Hospital Logo" width="100" class="d-inline-block align-top">
                <h2 class="fw-bold">MEDCARE</h2>
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li> 
                     <!-- Dropdown for Contact -->
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Contact
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="mailto:contact@hospital.com">Email: contact@hospital.com</a></li>
                            <li><a class="dropdown-item" href="tel:+123456789">Phone: +123 456 789</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="doctors.php">Doctors</a></li> 
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li> 
                </ul>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Departments Section -->
    <div class="container mt-5">
        <h1 class="mb-4">Our Hospital Locations</h1>
        <div class="list-group">
            <!-- Add clickable hospital addresses -->
            <a href="#" class="list-group-item list-group-item-action" onclick="showMap('21.028511,105.804817')">
                Hanoi - 123 Le Loi Street, Hoan Kiem District
            </a>
            <a href="#" class="list-group-item list-group-item-action" onclick="showMap('10.823099,106.629662')">
                Ho Chi Minh City - 456 Nguyen Trai Street, District 5
            </a>
            <a href="#" class="list-group-item list-group-item-action" onclick="showMap('16.047079,108.206230')">
                Da Nang - 789 Bach Dang Street, Hai Chau District
            </a>
        </div>

        <!-- Google Map -->
        <div id="map" class="mt-4" style="height: 500px; width: 100%;"></div>
    </div>

    <!-- Footer -->
    <div class="footer text-center mt-5">
        &copy; 2024 MEDCARE. All rights reserved.
    </div>

    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANtP7PGtwI_IDUlbHQIt-ZAgK4AKOXfnw"></script>
    <script>
        let map;
        function initMap() {
            const defaultLocation = { lat: 21.028511, lng: 105.804817 }; // Default to Hanoi
            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLocation,
                zoom: 12,
            });
        }

        function showMap(latlng) {
            const [lat, lng] = latlng.split(',');
            const location = { lat: parseFloat(lat), lng: parseFloat(lng) };
            map.setCenter(location);
            new google.maps.Marker({
                position: location,
                map: map,
            });
        }

        // Initialize the map
        window.onload = initMap;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>