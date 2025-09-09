<?php
session_start();
require __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
    http_response_code(403);
    exit;
}

$userId = $_SESSION['user_id'];
$role   = $_SESSION['role'];

// ✅ Mark as read only those notifications for this user/role
$stmt = $conn->prepare("
  UPDATE notifications 
  SET is_read = 1 
  WHERE (user_id = ? OR role = ? OR (user_id IS NULL AND role IS NULL))
");
$stmt->bind_param("is", $userId, $role);
$stmt->execute();

echo "OK";
