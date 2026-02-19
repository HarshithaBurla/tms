<?php
session_start();

$message = '';
$error = '';

if(isset($_POST['register'])) {
    $dbPath = __DIR__ . "/travel.db";
    $db = new PDO("sqlite:" . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // CORRECTED LOGIC: Check if username exists in the 'customer' table using 'fname'.
    $stmt = $db->prepare("SELECT COUNT(*) FROM customer WHERE fname = :name");
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $error = "Username already exists!";
    } else {
        $name = $_POST['name'];
        $pass = $_POST['pass']; // CORRECTED LOGIC: Storing password in plain text.
        $email = $_POST['email'];
        $city = $_POST['city'];
        $phone = $_POST['phone'];

        try {
            // CORRECTED LOGIC: Inserting into 'customer' table with 'fname' for the name.
            $stmt = $db->prepare("INSERT INTO customer (fname, password, email, city, phone) VALUES (:name, :password, :email, :city, :phone)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':password', $pass);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':phone', $phone);
            $stmt->execute();
            $message = "Registration Successful! You can now login.";
        } catch(PDOException $e) {
            // Use a more specific error message if possible, but this is a safe default.
            $error = "An error occurred during registration. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Tourism Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php">Home</a>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
    <a href="admin_login.php">Admin</a>
</div>

<div class="container">
    <h2>Create Your Account</h2>

    <?php if(!empty($message)): ?>
        <p style="color: green; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if(!empty($error)): ?>
        <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post" action="register.php">
        <label for="name">Username</label>
        <input type="text" id="name" name="name" required>

        <label for="pass">Password</label>
        <input type="password" id="pass" name="pass" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="city">City</label>
        <input type="text" id="city" name="city" required>

        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" required>

        <button type="submit" name="register">Register</button>
    </form>

    <p style="text-align: center; margin-top: 20px;">
        Already have an account? <a href="login.php">Login here</a>
    </p>
</div>

</body>
</html>
