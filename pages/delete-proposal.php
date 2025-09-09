<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require __DIR__ . '/../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $sql = "DELETE FROM proposals WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../lgu-staff-dashboard.php?page=all-proposals&deleted=1");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error deleting proposal.</div>";
    }
} else {
    echo "<div class='alert alert-warning'>Invalid request.</div>";
}
?>
