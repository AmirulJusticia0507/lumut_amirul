<?php
$host = 'localhost';
$dbname = 'db_lumut_amirul';
$username = 'root'; // sesuaikan dengan username MySQL Anda
$password = ''; // sesuaikan dengan password MySQL Anda

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
