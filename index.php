<?php
session_start();

$dbPath = __DIR__ . "/travel.db";
$db = new PDO("sqlite:" . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



/* Insert default data if table is empty */
$count = $db->query("SELECT COUNT(*) FROM destinations")->fetchColumn();

if ($count == 0) {
    $db->exec("
        INSERT INTO destinations (name, description) VALUES
        ('Goa', 'Beautiful beaches & nightlife'),
        ('Manali', 'Snow mountains & adventure'),
        ('Jaipur', 'Royal forts & heritage'),
        ('Delhi', 'Capital city & rich history');
    ");
}

$destination_images = [
    'Goa' => 'images/goa.jpg',
    'Manali' => 'images/manali.jpg',
    'Jaipur' => 'images/jaipur.jpg',
    'Delhi' => 'images/delhi.jpg'
];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tourism Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <a href="index.php">Home</a>

    <?php if(isset($_SESSION['user'])): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
        <a href="admin_login.php">Admin</a>
    <?php endif; ?>
</div>

<div class="hero">
    <h1>Explore The World With Us 🌍</h1>
</div>

<div class="container">
    <h2>Popular Destinations</h2>

    <div class="destinations">
        <?php
        $result = $db->query("SELECT * FROM destinations");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $destinationName = trim($row['name']);
            $image_url = isset($destination_images[$destinationName]) ? $destination_images[$destinationName] : 'images/default.jpg';
            $name = htmlspecialchars($row['name']);
            $description = htmlspecialchars($row['description']);

            echo <<<CARD
            <div class="card" style="background-image: url('$image_url')">
                <div class="card-content">
                    <h3>$name</h3>
                    <p>$description</p>
                </div>
            </div>
CARD;
        }
        ?>
    </div>

    <div style="text-align:center; margin-top:40px;">
        <?php if(!isset($_SESSION['user'])): ?>
            <a href="register.php">
                <button>Start Booking Now</button>
            </a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
