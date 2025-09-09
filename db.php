<?php
// General database connection for the PPP Management System

$host = "localhost:3307";      // Host and port
$user = "root";                // Database username
$pass = "";                    // Database password
$dbname = "ppp_system";    // Database name (change as needed)

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset to utf8mb4 for better compatibility
$conn->set_charset("utf8mb4");