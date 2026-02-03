<?php
$servername = "localhost";
$username = "root";
$psw = "root";
$database = "crud";
$port = "3307";

$conn = new mysqli($servername, $username, $psw, $database, $port);

if ($conn->connect_error) {
    die("Connect Failed : " . $conn->connect_error);
}

echo "<script>
console.log('Database Connected Successfully');
</script>";
