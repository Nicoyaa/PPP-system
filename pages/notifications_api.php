<?php
session_start();
require __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$userId = $_SESSION['user_id'];
$role   = $_SESSION['role'];

// ✅ Get notifications for this user or role or global
$stmt = $conn->prepare("
  SELECT id, message, is_read, created_at
  FROM notifications
  WHERE (user_id = ? OR role = ? OR (user_id IS NULL AND role IS NULL))
  ORDER BY created_at DESC
  LIMIT 10
");
$stmt->bind_param("is", $userId, $role);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

header('Content-Type: application/json');
echo json_encode($notifications);
