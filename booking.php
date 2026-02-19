<?php
session_start();

// Redirect to login if user is not logged in
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// Database setup
$dbPath = __DIR__ . "/travel.db";
$db = new PDO("sqlite:" . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$message = '';
$booking_successful = false;
$places_to_visit = [];
$last_booking = null;

// Predefined famous places for routes
$famous_places = [
    'delhi-jaipur' => ['India Gate', 'Qutub Minar', 'Neemrana Fort', 'Hawa Mahal', 'Amber Palace'],
    'delhi-manali' => ['Chandigarh Rock Garden', 'Sukhna Lake', 'Kullu Valley', 'Solang Valley', 'Hidimba Devi Temple'],
    'mumbai-goa'   => ['Lonavala', 'Khandala', 'Panjim City', 'Calangute Beach', 'Baga Beach'],
    'jaipur-delhi' => ['Amber Palace', 'Hawa Mahal', 'Neemrana Fort', 'Qutub Minar', 'India Gate']
];

// Handle form submission (POST request)
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])){
    // Anti-duplication token check
    if (!isset($_POST['form_token']) || !isset($_SESSION['form_token']) || $_POST['form_token'] !== $_SESSION['form_token']) {
        // Token mismatch, likely a resubmission. Redirect to the booking form to prevent duplicates.
        header("Location: booking.php");
        exit();
    }
    
    // Invalidate the token to prevent re-submission
    unset($_SESSION['form_token']);

    try {
        $stmt = $db->prepare("INSERT INTO booking (username, ffirst, flast, femail, city, fphone, fdesti, current_location) VALUES (:user, :first, :last, :email, :city, :phone, :desti, :current_loc)");
        $stmt->execute([
            ':user' => $_SESSION['user'], ':first' => $_POST['fname'], ':last' => $_POST['lname'], ':email' => $_POST['email'], ':city' => $_POST['city'], ':phone' => $_POST['phone'], ':desti' => $_POST['destination'], ':current_loc' => $_POST['current_location']
        ]);

        $_SESSION['last_booking_id'] = $db->lastInsertId();
        
        // Redirect to a success page using PRG pattern
        header("Location: booking.php?success=1");
        exit();

    } catch(PDOException $e) {
        $message = "An error occurred during booking. Please try again.";
        // For debugging: error_log($e->getMessage());
    }
}

// Handle display of success message (GET request)
if(isset($_GET['success'])) {
    $booking_successful = true;
    $message = "Booking Successful! We will contact you shortly.";

    if (isset($_SESSION['last_booking_id'])) {
        $stmt = $db->prepare("SELECT * FROM booking WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['last_booking_id']]);
        $last_booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if($last_booking){
            $start = strtolower(trim($last_booking['current_location']));
            $end = strtolower(trim($last_booking['fdesti']));
            $route_key = "{$start}-{$end}";

            if(array_key_exists($route_key, $famous_places)){
                $places_to_visit = $famous_places[$route_key];
            }
        }
        // Unset the session variable so it's only used once for displaying details
        unset($_SESSION['last_booking_id']);
    }
}

// Generate a new token if we are displaying the form
if (!$booking_successful) {
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book a Tour - Tourism Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="dashboard.php">Home</a>
    <a href="booking.php">Book Tour</a>
    <a href="my_bookings.php">My Bookings</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <?php if($booking_successful): ?>
        <h2>Booking Confirmed!</h2>
        <p class="success-message"><?php echo htmlspecialchars($message); ?></p>

        <?php if($last_booking && !empty($places_to_visit)): ?>
            <div class="places-section">
                <h3>Famous Places and Attractions on Your Route:</h3>
                <ul>
                    <?php foreach($places_to_visit as $place): ?>
                        <li><?php echo htmlspecialchars($place); ?></li>
                    <?php endforeach; ?>
                </ul>
                <p>You can use this list to plan your hotel stays and sightseeing stops.</p>
            </div>
        <?php elseif ($last_booking): ?>
             <p style="text-align: center; margin-top: 20px;">While we couldn't generate a specific list of attractions for your route, we recommend searching online for points of interest between <?php echo htmlspecialchars($last_booking['current_location']); ?> and <?php echo htmlspecialchars($last_booking['fdesti']); ?>.</p>
        <?php else: ?>
            <p style="text-align: center; margin-top: 20px;">You can view your latest booking on the 'My Bookings' page.</p>
        <?php endif; ?>

        <div class="confirmation-links">
            <a href="my_bookings.php" class="btn-secondary">View My Bookings</a>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>

    <?php else: ?>
        <h2>Book Your Tour</h2>
        <p>Plan your trip by telling us your starting point and final destination.</p>
        
        <?php if(!empty($message)): ?>
            <p style="color: red; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="post" action="booking.php">
            <input type="hidden" name="form_token" value="<?php if(isset($_SESSION['form_token'])) echo $_SESSION['form_token']; ?>">

            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" required>

            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="city">Home City:</label>
            <input type="text" id="city" name="city" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="current_location">Current Location (e.g., Delhi, Mumbai):</label>
            <input type="text" id="current_location" name="current_location" required>

            <label for="destination">Final Destination:</label>
            <select id="destination" name="destination">
                <option>Goa</option>
                <option>Manali</option>
                <option>Delhi</option>
                <option>Jaipur</option>
            </select>

            <button type="submit" name="book">Book Now</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
