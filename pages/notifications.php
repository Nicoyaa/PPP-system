<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../db.php';

// Only allow logged in users
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='../login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all notifications (latest first)
$stmt = $conn->prepare("SELECT message, is_read, created_at 
                        FROM notifications 
                        WHERE user_id = ? 
                        ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="page">
  <h2>🔔 Notifications</h2>
  <p class="text-muted">Here’s the history of your system and proposal updates.</p>

  <ul class="list-group mt-3">
    <?php if ($result->num_rows === 0): ?>
      <li class="list-group-item text-muted">No notifications found.</li>
    <?php else: ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center 
                   <?php echo $row['is_read'] ? '' : 'fw-bold'; ?>">
          <div>
            <span><?php echo htmlspecialchars($row['message']); ?></span><br>
            <small class="text-muted"><?php echo date("M d, Y h:i A", strtotime($row['created_at'])); ?></small>
          </div>
          <?php if (!$row['is_read']): ?>
            <span class="badge bg-primary">New</span>
          <?php endif; ?>
        </li>
      <?php endwhile; ?>
    <?php endif; ?>
  </ul>
</div>
