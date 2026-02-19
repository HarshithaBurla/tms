<?php
session_start();

// Database setup
$dbPath = __DIR__ . "/travel.db";
$db = new PDO("sqlite:" . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create admin table and default admin if they don't exist, using plaintext password
$db->exec("CREATE TABLE IF NOT EXISTS admin (username TEXT PRIMARY KEY, password TEXT)");
$count = $db->query("SELECT COUNT(*) FROM admin")->fetchColumn();
if ($count == 0) {
    $defaultUser = 'admin';
    $defaultPass = 'admin123'; // Storing plaintext password to match login logic
    $stmt = $db->prepare("INSERT INTO admin (username, password) VALUES (:user, :pass)");
    $stmt->bindParam(':user', $defaultUser);
    $stmt->bindParam(':pass', $defaultPass);
    $stmt->execute();
}

$error = '';
if(isset($_POST['login'])) {
    // CORRECTED LOGIC: Using plain text password check against the 'admin' table
    $stmt = $db->prepare("SELECT * FROM admin WHERE username = :user AND password = :pass");
    $stmt->bindParam(':user', $_POST['user']);
    $stmt->bindParam(':pass', $_POST['pass']);
    $stmt->execute();

    if($stmt->fetch()){
        $_SESSION['admin'] = $_POST['user'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid Admin Login!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Tourism Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php">Home</a>
    <a href="login.php">User Login</a>
    <a href="admin_login.php">Admin Login</a>
</div>

<div class="container">
    <h2>Admin Login</h2>

    <?php if(!empty($error)): ?>
        <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="user">Username:</label>
        <input type="text" id="user" name="user" required value="admin">
        
        <label for="pass">Password:</label>
        <input type="password" id="pass" name="pass" required value="admin123">
        
        <button type="submit" name="login">Login</button>
    </form>

    <p style="text-align: center; margin-top: 20px;">
        <a href="index.php">Back to Homepage</a>
    </p>
</div>

</body>
</html>
