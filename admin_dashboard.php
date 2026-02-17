<?php
session_start();
require 'config.php';

if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}

$stmt = $con->query("SELECT * FROM booking");
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Admin Dashboard</h2>

<p>Welcome Admin 🎉</p>

<?php if(count($bookings) > 0): ?>

<table border="1" cellpadding="10">
    <tr>
    <td><?php echo $row['username']; ?></td>
    <td><?php echo $row['ffirst']; ?></td>
    <td><?php echo $row['flast']; ?></td>
    <td><?php echo $row['femail']; ?></td>
    <td><?php echo $row['city']; ?></td>
    <td><?php echo $row['fphone']; ?></td>
    <td><?php echo $row['fdesti']; ?></td>

    <td>
        <a href="delete_booking.php?id=<?php echo $row['id']; ?>" 
           onclick="return confirm('Are you sure?')">
           Delete
        </a>
    </td>
</tr>


    <?php foreach($bookings as $row): ?>
    <tr>
        <td><?php echo $row['username']; ?></td>
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

<br><br>
<a href="admin_logout.php">Logout</a>
