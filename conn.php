<?php
// test_connection.php
echo "Testing database connection...<br>";

// Test with PDO
try {
    $pdo = new PDO('mysql:host=124.158.161.188;port=33127;dbname=eregulasi_dev;charset=utf8', 'eregulasi', 'Db3regulasi@2025!');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ PDO Connection: SUCCESS<br>";
    $pdo = null;
} catch (PDOException $e) {
    echo "❌ PDO Connection: FAILED - " . $e->getMessage() . "<br>";
}

// Test with MySQLi
try {
    $mysqli = new mysqli('124.158.161.188', 'eregulasi', 'Db3regulasi@2025!', 'eregulasi_dev', 33127);
    if ($mysqli->connect_error) {
        echo "❌ MySQLi Connection: FAILED - " . $mysqli->connect_error . "<br>";
    } else {
        echo "✅ MySQLi Connection: SUCCESS<br>";
        $mysqli->close();
    }
} catch (Exception $e) {
    echo "❌ MySQLi Connection: FAILED - " . $e->getMessage() . "<br>";
}

echo "<br>PHP Version: " . phpversion() . "<br>";
echo "MySQL Client Version: " . mysqli_get_client_info() . "<br>";
