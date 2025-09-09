<?php 
require_once __DIR__ . '/../db.php';
$stmt = $conn->prepare("SELECT project_title, status, created_at FROM proposals WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="page">
  <h2 style="margin-bottom:20px;">📊 Track Proposal Status</h2>

  <?php if ($result->num_rows > 0): ?>
    <div class="track-list">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="track-item">
          <div class="track-header">
            <h3><?php echo htmlspecialchars($row['project_title']); ?></h3>
            <small>📅 <?php echo date("M d, Y", strtotime($row['created_at'])); ?></small>
          </div>
          <div class="track-progress">
            <div class="step <?php echo ($row['status'] != 'Pending') ? 'active' : ''; ?>">Submitted</div>
            <div class="step <?php echo ($row['status']=='Approved' || $row['status']=='Ongoing' || $row['status']=='Completed') ? 'active' : ''; ?>">Reviewed</div>
            <div class="step <?php echo ($row['status']=='Ongoing' || $row['status']=='Completed') ? 'active' : ''; ?>">Ongoing</div>
            <div class="step <?php echo ($row['status']=='Completed') ? 'active' : ''; ?>">Completed</div>
          </div>
          <div class="track-status <?php echo strtolower($row['status']); ?>">
            Current Status: <?php echo htmlspecialchars($row['status']); ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p>No proposals submitted yet.</p>
  <?php endif; ?>
</div>

<style>
/* ===== Default Light Mode ===== */
.track-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}
.track-item {
  background: #fff;
  padding: 18px;
  border-radius: 12px;
  box-shadow: 0 3px 8px rgba(0,0,0,0.1);
  transition: background .3s, color .3s;
}
.track-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}
.track-header h3 {
  margin: 0;
  font-size: 1.1rem;
}
.track-progress {
  display: flex;
  justify-content: space-between;
  margin: 15px 0;
  position: relative;
}
.track-progress::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 4px;
  background: #e0e0e0;
  z-index: 0;
}
.step {
  position: relative;
  z-index: 1;
  background: #e0e0e0;
  color: #555;
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.85rem;
  transition: all .3s ease;
}
.step.active {
  background: #7384e2ff;
  color: #fff;
}
.track-status {
  margin-top: 8px;
  font-weight: 600;
  font-size: 0.9rem;
}
.track-status.pending { color: #6c757d; }
.track-status.approved { color: #28a745; }
.track-status.rejected { color: #dc3545; }
.track-status.ongoing { color: #007bff; }
.track-status.completed { color: #ff9800; }

/* ===== Dark Mode Support ===== */
body.dark .track-item {
  background: #1e1e1e;
  color: #f1f1f1;
  box-shadow: 0 3px 8px rgba(255,255,255,0.05);
}
body.dark .track-progress::before {
  background: #444;
}
body.dark .step {
  background: #444;
  color: #aaa;
}
body.dark .step.active {
  background: #7179f1ff;
  color: #fff;
}
body.dark .track-status.pending { color: #aaa; }
body.dark .track-status.approved { color: #66ff99; }
body.dark .track-status.rejected { color: #ff6666; }
body.dark .track-status.ongoing { color: #66aaff; }
body.dark .track-status.completed { color: #ffb74d; }
</style>
