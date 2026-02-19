<?php
session_start();

// Redirect to login if user is not logged in
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Tourism Management System</title>
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
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>! 🎉</h2>
    <p>You are logged in successfully.</p>
    
    <div class="dashboard-links">
        <a href="booking.php" class="dashboard-link">Book a New Tour</a>
        <a href="my_bookings.php" class="dashboard-link">View My Bookings</a>
    </div>
</div>

</body>
</html>
