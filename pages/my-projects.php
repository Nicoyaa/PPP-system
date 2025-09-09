<?php
require_once __DIR__ . '/../db.php';

// Example: Projects table may not exist; fallback to approved proposals as "projects"
$stmt = $conn->prepare("SELECT id, project_title, category, status, created_at 
                        FROM proposals WHERE user_id = ? AND status='Approved'");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<div class="page">
  <h2>📂 My Projects</h2>
  <div class="row">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4">
          <div class="card shadow-sm p-3 mb-4">
            <h5><?php echo htmlspecialchars($row['project_title']); ?></h5>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
            <p><small>Approved on: <?php echo date("M d, Y", strtotime($row['created_at'])); ?></small></p>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No approved projects yet.</p>
    <?php endif; ?>
  </div>
</div>
