<?php
require __DIR__ . '/../db.php';

// Fetch timeline events with user info
$stmt = $conn->prepare("
  SELECT t.*, u.username, u.role AS user_role
  FROM timeline_events t
  JOIN users u ON t.user_id = u.id
  ORDER BY t.created_at DESC
  LIMIT 30
");
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
/* ===== TIMELINE CSS ===== */
.timeline {
  position: relative;
  margin: 2rem 0;
  padding-left: 30px;
  border-left: 3px solid var(--blue);
}

.timeline-item {
  position: relative;
  margin-bottom: 2rem;
}

.timeline-item .timeline-icon {
  position: absolute;
  left: -20px;
  top: 0;
  width: 36px;
  height: 36px;
  background: var(--blue);
  color: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: var(--shadow);
}

.timeline-item .timeline-content {
  background: var(--white);
  padding: 1rem;
  border-radius: 12px;
  border: 1px solid var(--border);
  box-shadow: var(--shadow);
}

.timeline-item .time {
  font-size: 0.8rem;
  color: gray;
  display: block;
  margin-bottom: 0.5rem;
}

/* Status colors */
.timeline-item.pending .timeline-icon { background: orange; }
.timeline-item.review .timeline-icon { background: dodgerblue; }
.timeline-item.approved .timeline-icon,
.timeline-item.success .timeline-icon { background: green; }
.timeline-item.rejected .timeline-icon { background: red; }
.timeline-item.update .timeline-icon { background: gray; }

/* Dark Mode Support */
body.dark-mode .timeline {
  border-left: 3px solid var(--dark-border);
}
body.dark-mode .timeline-item .timeline-content {
  background: var(--dark-card);
  border: 1px solid var(--dark-border);
  color: var(--dark-text);
}
</style>

<div class="page">
  <h2>📜 Activity Timeline</h2>
  <div class="timeline">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="timeline-item <?php echo $row['status']; ?>">
          <div class="timeline-icon"><i class="<?php echo $row['icon']; ?>"></i></div>
          <div class="timeline-content">
            <span class="time"><?php echo date("M d, Y h:i A", strtotime($row['created_at'])); ?></span>
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <small class="text-muted">
              👤 <?php echo htmlspecialchars($row['username']); ?> (<?php echo $row['user_role']; ?>)
            </small>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-muted">No activities yet.</p>
    <?php endif; ?>
  </div>
</div>
