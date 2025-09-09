<?php
require_once __DIR__ . '/../db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, project_title, category, status, created_at FROM proposals WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<header class="page-header d-flex justify-content-between align-items-center mb-4">
  <h1 class="h3">📑 My Proposals</h1>
  <a href="private-dashboard.php?page=submit-proposal" class="btn btn-primary"><i class="fas fa-plus"></i> New Proposal</a>
</header>

<section class="content">
  <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col">
          <div class="card h-100 shadow-sm rounded-3 p-3">
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <h5 class="card-title"><?php echo htmlspecialchars($row['project_title']); ?></h5>
                <p class="card-text"><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                <p class="card-text"><strong>Status:</strong> 
                  <span class="badge <?php echo $row['status']=='Approved'?'bg-success':($row['status']=='Rejected'?'bg-danger':'bg-secondary'); ?>">
                    <?php echo htmlspecialchars($row['status']); ?>
                  </span>
                </p>
                <p class="text-muted small">Submitted: <?php echo date("M d, Y", strtotime($row['created_at'])); ?></p>
              </div>
              <div class="d-flex justify-content-between mt-3">
                <a href="view-proposal.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-info">View</a>
                <a href="pages/cruds/edit-proposal.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-success">Edit</a>
                <a href="pages/cruds/delete-proposal.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this proposal?')" class="btn btn-sm btn-outline-danger">Delete</a>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info">📭 No proposals yet. <a href="private-dashboard.php?page=submit-proposal" class="alert-link">Submit one now</a>.</div>
      </div>
    <?php endif; ?>
  </div>
</section>
