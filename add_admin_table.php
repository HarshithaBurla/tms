<?php
require 'config.php';

$con->exec("
CREATE TABLE IF NOT EXISTS admin (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password TEXT
);
");

# Insert default admin (only once)
$con->exec("
INSERT OR IGNORE INTO admin (username, password) 
VALUES ('admin', 'admin123');
");

echo "Admin table ready!";
?>
