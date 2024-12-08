<?php
$servername = "localhost";
$username = "root"; // Replace with your MAMP username
$password = "root"; // Replace with your MAMP password
$dbname = "PayTrack";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>