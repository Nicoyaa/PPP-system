<?php
require_once __DIR__ . '/../db.php';

// Total proposals of this user
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM proposals WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$totalProposals = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

// Approved proposals
$stmt = $conn->prepare("SELECT COUNT(*) as approved FROM proposals WHERE user_id = ? AND status='Approved'");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$approved = $stmt->get_result()->fetch_assoc()['approved'] ?? 0;

// Pending proposals
$stmt = $conn->prepare("SELECT COUNT(*) as pending FROM proposals WHERE user_id = ? AND status='Pending'");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$pending = $stmt->get_result()->fetch_assoc()['pending'] ?? 0;
?>
<div class="dashboard">
  <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

  <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top:20px;">
    <div class="card"><h3>Total Proposals</h3><p><?php echo $totalProposals; ?></p></div>
    <div class="card"><h3>Approved</h3><p><?php echo $approved; ?></p></div>
    <div class="card"><h3>Pending</h3><p><?php echo $pending; ?></p></div>
  </div>
</div>
