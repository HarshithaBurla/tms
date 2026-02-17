<?php
session_start();
require 'config.php';

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$stmt = $con->prepare("SELECT * FROM booking WHERE username = ?");
$stmt->execute([$_SESSION['user']]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h2>My Bookings</h2>

    <?php if(count($bookings) > 0): ?>

        <table>
            <tr>
                <th>First</th>
                <th>Last</th>
                <th>Email</th>
                <th>City</th>
                <th>Phone</th>
                <th>Destination</th>
            </tr>

            <?php foreach($bookings as $row): ?>
            <tr>
                <td><?php echo $row['ffirst']; ?></td>
                <td><?php echo $row['flast']; ?></td>
                <td><?php echo $row['femail']; ?></td>
                <td><?php echo $row['city']; ?></td>
                <td><?php echo $row['fphone']; ?></td>
                <td><?php echo $row['fdesti']; ?></td>
            </tr>
            <?php endforeach; ?>

        </table>

    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>

</div>

</body>
</html>
