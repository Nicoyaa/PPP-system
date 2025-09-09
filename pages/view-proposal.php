<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require __DIR__ . '/../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET['id'] ?? 0);
$sql = "SELECT p.*, u.username, u.company, u.email, u.contact_number 
        FROM proposals p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$proposal = $result->fetch_assoc();

if (!$proposal) {
    echo "<div class='alert alert-danger'>Proposal not found.</div>";
    exit();
}
?>

<div class="card shadow-sm p-4">
  <h2 class="mb-3"><?php echo htmlspecialchars($proposal['project_title']); ?></h2>
  <p><strong>Category:</strong> <?php echo htmlspecialchars($proposal['category']); ?></p>
  <p><strong>Status:</strong> 
    <span class="badge 
      <?php echo $proposal['status']=='Approved'?'bg-success':($proposal['status']=='Rejected'?'bg-danger':'bg-secondary'); ?>">
      <?php echo htmlspecialchars($proposal['status']); ?>
    </span>
  </p>
  <p><strong>Description:</strong></p>
  <div class="border rounded p-3 mb-3 bg-light">
    <?php echo nl2br(htmlspecialchars($proposal['description'])); ?>
  </div>

  <h4>📌 Proponent Information</h4>
  <ul>
    <li><strong>Name:</strong> <?php echo htmlspecialchars($proposal['username']); ?></li>
    <li><strong>Company:</strong> <?php echo htmlspecialchars($proposal['company']); ?></li>
    <li><strong>Email:</strong> <?php echo htmlspecialchars($proposal['email']); ?></li>
    <li><strong>Contact:</strong> <?php echo htmlspecialchars($proposal['contact_number']); ?></li>
  </ul>

  <div class="mt-3">
    <a href="lgu-staff-dashboard.php?page=edit-proposal&id=<?php echo $proposal['id']; ?>" class="btn btn-success">✏️ Edit</a>
    <a href="pages/delete-proposal.php?id=<?php echo $proposal['id']; ?>" 
       class="btn btn-danger" onclick="return confirm('Delete this proposal?');">🗑 Delete</a>
    <a href="lgu-staff-dashboard.php?page=all-proposals" class="btn btn-secondary">⬅ Back</a>
  </div>
</div>
