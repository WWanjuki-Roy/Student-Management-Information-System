<?php
$servername = "localhost";
$username = "root";          // default for XAMPP/WAMP
$password = "";              // default for XAMPP/WAMP
$dbname = "sims";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
?>
