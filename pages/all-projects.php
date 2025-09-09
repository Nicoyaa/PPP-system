<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . '/../db.php';

// ✅ Allow only LGU staff or admin
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'lgu-staff'])) {
    echo "<script>alert('❌ Access denied!'); window.location.href='/PPP-system/login.php';</script>";
    exit();
}

// ✅ Fetch all proposals/projects
$sql = "SELECT p.id, p.project_title, p.category, p.status, p.organization, 
               p.estimated_cost, p.created_at, u.username 
        FROM proposals p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);
?>

<div class="page">
  <h2>📂 All Projects</h2>
  <p class="text-muted">Complete list of PPP projects submitted by proponents.</p>

  <div class="grid">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card project-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3><?php echo htmlspecialchars($row['project_title']); ?></h3>
            <span class="badge 
              <?php echo $row['status']=='Approved'?'bg-success':
                         ($row['status']=='Rejected'?'bg-danger':
                         ($row['status']=='Ongoing'?'bg-warning text-dark':
                         ($row['status']=='Completed'?'bg-primary':'bg-secondary'))); ?>">
              <?php echo htmlspecialchars($row['status']); ?>
            </span>
          </div>
          <div class="card-body">
            <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
            <p><strong>Organization:</strong> <?php echo htmlspecialchars($row['organization']); ?></p>
            <p><strong>Estimated Cost:</strong> ₱<?php echo number_format($row['estimated_cost'], 2); ?></p>
            <p><strong>Submitted By:</strong> <?php echo htmlspecialchars($row['username']); ?></p>
            <p><small class="text-muted">📅 <?php echo date("M d, Y", strtotime($row['created_at'])); ?></small></p>
          </div>
          <div class="card-footer d-flex justify-content-end gap-2">
            <a href="lgu-staff-dashboard.php?page=view-proposal&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">👁 View</a>
            <a href="lgu-staff-dashboard.php?page=edit-proposal&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
            <a href="lgu-staff-dashboard.php?page=delete-proposal&id=<?php echo $row['id']; ?>" 
               onclick="return confirm('Are you sure you want to delete this project?')" 
               class="btn btn-sm btn-danger">🗑 Delete</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="alert alert-info">📌 No projects found.</div>
    <?php endif; ?>
  </div>
</div>

<style>
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1rem;
}
.project-card {
  border: 1px solid var(--border);
  border-radius: 12px;
  box-shadow: var(--shadow);
}
.card-header {
  border-bottom: 1px solid var(--border);
  padding-bottom: .5rem;
}
.card-footer {
  border-top: 1px solid var(--border);
  padding-top: .5rem;
}
</style>
