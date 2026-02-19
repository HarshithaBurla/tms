<?php
session_start();

// Ensure the user is logged in
if(!isset($_SESSION['user'])){
    // Redirect to login if not logged in
    header("Location: login.php");
    exit();
}

// Check if an ID is provided in the URL
if(isset($_GET['id'])){
    $booking_id = $_GET['id'];
    $username = $_SESSION['user'];

    try {
        // Database connection
        $dbPath = __DIR__ . "/travel.db";
        $db = new PDO("sqlite:" . $dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare a delete statement that ensures a user can only delete their own bookings
        $stmt = $db->prepare("DELETE FROM booking WHERE id = :id AND username = :user");
        $stmt->execute([':id' => $booking_id, ':user' => $username]);

    } catch (PDOException $e) {
        // You can log this error for debugging if you wish
        die("Database error: " . $e->getMessage());
    }
}

// Redirect back to the bookings page so the user sees the updated list
header("Location: my_bookings.php");
exit();
?>
