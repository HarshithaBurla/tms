<?php
// This is a one-time script to clean up duplicate bookings.

$dbPath = __DIR__ . "/travel.db";
$db = new PDO("sqlite:" . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Starting cleanup of duplicate bookings...\n";

try {
    // This query deletes all rows from the booking table that are not the first instance 
    // (i.e., the one with the minimum ID) of a unique combination of user and booking details.
    $sql = "DELETE FROM booking WHERE id NOT IN (
                SELECT MIN(id) 
                FROM booking 
                GROUP BY username, ffirst, flast, femail, city, fphone, fdesti, current_location
            )";
    
    $affectedRows = $db->exec($sql);
    
echo "Cleanup complete. Removed {$affectedRows} duplicate booking(s).\n";

} catch (PDOException $e) {
    echo "An error occurred during cleanup: " . $e->getMessage() . "\n";
}

?>
