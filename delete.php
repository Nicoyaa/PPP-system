<?php
session_start();
if (!isset($_SESSION["email"]) || $_SESSION["role"] !== "admin") {
    header("Location: /login.php");
    exit();
}

include 'db.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: user_management.php");
exit();
?>
