<?php
require 'config.php';

$con->exec("
CREATE TABLE IF NOT EXISTS booking (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    ffirst TEXT NOT NULL,
    flast TEXT NOT NULL,
    femail TEXT NOT NULL,
    city TEXT NOT NULL,
    fphone TEXT NOT NULL,
    fdesti TEXT NOT NULL
);
");

echo "Booking table created successfully!";
?>
