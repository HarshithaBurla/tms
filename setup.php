<?php
try {
    $con = new PDO("sqlite:travel.db");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $con->exec("
        CREATE TABLE IF NOT EXISTS customer (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            fname TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            email TEXT NOT NULL,
            city TEXT NOT NULL,
            phone TEXT NOT NULL
        );
    ");

    echo "Database and table created successfully!";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
