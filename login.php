<?php
session_start();

// Database setup - connecting to the original database configuration
$dbPath = __DIR__ . "/travel.db";
$db = new PDO("sqlite:" . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// When creating the project, the table was named customer. Let's create it if it doesn't exist.
$db->exec("CREATE TABLE IF NOT EXISTS customer (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    fname TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    email TEXT NOT NULL,
    city TEXT,
    phone TEXT
)");

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if(isset($_POST['login'])) {
    // CORRECTED LOGIC: Using the original 'customer' table and 'fname' column.
    // CORRECTED LOGIC: Checking password in plain text to match original functionality.
    $stmt = $db->prepare("SELECT * FROM customer WHERE fname = :name AND password = :password");
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':password', $_POST['pass']);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {
        // Login is successful, using 'fname' for the session.
        $_SESSION['user'] = $user['fname'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid Username or Password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Tourism Management System</title>
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
    <h2>User Login</h2>

    <?php if(!empty($error)):
        echo '<p style="color: red; text-align: center;">'.htmlspecialchars($error).'</p>';
    endif; ?>

    <form method="post" action="login.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="pass">Password:</label>
        <input type="password" id="pass" name="pass" required>
        
        <button type="submit" name="login">Login</button>
    </form>

    <p style="text-align: center; margin-top: 20px;">
        New user? <a href="register.php">Register here</a>
    </p>
</div>

</body>
</html>
