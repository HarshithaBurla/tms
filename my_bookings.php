<?php
session_start();

// Ensure the user is logged in
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// Database connection
$dbPath = __DIR__ . "/travel.db";
$db = new PDO("sqlite:" . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ONE-TIME AUTOMATIC CLEANUP
// Check if the cleanup has been run in this session to prevent loops
if (!isset($_SESSION['cleanup_run'])) {
    try {
        // This SQL query keeps only the first instance of each unique booking and deletes the rest
        $sql = "DELETE FROM booking WHERE id NOT IN (
                    SELECT MIN(id) 
                    FROM booking 
                    GROUP BY username, ffirst, flast, femail, city, fphone, fdesti, current_location
                )";
        
        $db->exec($sql);
        
        // Mark that the cleanup has been run for this session
        $_SESSION['cleanup_run'] = true;

    } catch (PDOException $e) {
        // If there's an error, it's best to stop and show it.
        die("A critical error occurred during database cleanup: " . $e->getMessage());
    }
}

// Fetch the user's bookings (now cleaned)
$stmt = $db->prepare("SELECT * FROM booking WHERE username = :user");
$stmt->execute(['user' => $_SESSION['user']]);
$bookings = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
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
    <h2>My Bookings</h2>
    <p>Here are all the tours you have booked with us.</p>

    <?php if (count($bookings) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>City</th>
                    <th>Phone</th>
                    <th>Destination</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['ffirst']) . ' ' . htmlspecialchars($booking['flast']); ?></td>
                        <td><?php echo htmlspecialchars($booking['femail']); ?></td>
                        <td><?php echo htmlspecialchars($booking['city']); ?></td>
                        <td><?php echo htmlspecialchars($booking['fphone']); ?></td>
                        <td><?php echo htmlspecialchars($booking['fdesti']); ?></td>
                        <td><a href="delete_booking.php?id=<?php echo $booking['id']; ?>" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no bookings yet.</p>
    <?php endif; ?>

     <p class="dashboard-link-container">
        <a href="dashboard.php">Back to Dashboard</a>
    </p>
</div>

</body>
</html>
