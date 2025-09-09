<?php 
if (session_status() == PHP_SESSION_NONE) session_start();
require __DIR__ . '/../db.php';

// ✅ Allow only LGU staff or admin
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'lgu-staff'])) {
    echo "<script>window.location.href='/PPP-system/login.php';</script>";
    exit();
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<script>window.location.href='/PPP-system/lgu-staff-dashboard.php?page=all-proposals';</script>";
    exit();
}

// ✅ Fetch proposal details (needed for notifications)
$stmt = $conn->prepare("SELECT * FROM proposals WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$proposal = $result->fetch_assoc();

if (!$proposal) {
    echo "<script>
            alert('❌ Proposal not found!');
            window.location.href='/PPP-system/lgu-staff-dashboard.php?page=all-proposals';
          </script>";
    exit();
}

// ✅ Handle form submission first
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['project_title']);
    $category    = trim($_POST['category']);
    $description = trim($_POST['description']);
    $status      = trim($_POST['status']);

    $sql = "UPDATE proposals SET project_title=?, category=?, description=?, status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $category, $description, $status, $id);

    if ($stmt->execute()) {
        // ✅ Insert notification for proposal owner
        $proposalUserId = $proposal['user_id'];
        $msg = "Your proposal '{$title}' has been updated. Status: {$status}.";

        $stmtNotif = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmtNotif->bind_param("is", $proposalUserId, $msg);
        $stmtNotif->execute();

        // ✅ Notify all LGU Staff
        $sqlStaff = "SELECT id FROM users WHERE role='lgu-staff'";
        $staffResult = $conn->query($sqlStaff);
        while ($staff = $staffResult->fetch_assoc()) {
            $staffMsg = "Proposal '{$title}' updated by LGU. Status: {$status}.";
            $stmtNotif->bind_param("is", $staff['id'], $staffMsg);
            $stmtNotif->execute();
        }

        // ✅ Notify all Brgy Captains
        $sqlCaptain = "SELECT id FROM users WHERE role='brgy-captain'";
        $captainResult = $conn->query($sqlCaptain);
        while ($captain = $captainResult->fetch_assoc()) {
            $captainMsg = "Proposal '{$title}' updated. Status: {$status}.";
            $stmtNotif->bind_param("is", $captain['id'], $captainMsg);
            $stmtNotif->execute();
        }

        // ✅ Redirect back
        echo "<script>
                alert('✅ Proposal updated successfully!');
                window.location.href='/PPP-system/lgu-staff-dashboard.php?page=all-proposals';
              </script>";
        exit();
    } else {
        $error = "❌ Update failed: " . $conn->error;
    }
}
?>

<div class="form-page">
  <div class="form-card">
    <h2 class="form-title">✏️ Edit Proposal</h2>
    <p class="form-subtitle">Update the details of the selected proposal.</p>

    <?php if (!empty($error)) : ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="form-grid">
      <div class="form-group full">
        <label for="project_title">Project Title</label>
        <input type="text" id="project_title" name="project_title" 
               value="<?php echo htmlspecialchars($proposal['project_title']); ?>" required>
      </div>

      <div class="form-group">
        <label for="category">Category</label>
        <select id="category" name="category" required>
          <option value="Infrastructure" <?php if ($proposal['category']=='Infrastructure') echo 'selected'; ?>>Infrastructure</option>
          <option value="Transportation" <?php if ($proposal['category']=='Transportation') echo 'selected'; ?>>Transportation</option>
          <option value="Health" <?php if ($proposal['category']=='Health') echo 'selected'; ?>>Health</option>
          <option value="Education" <?php if ($proposal['category']=='Education') echo 'selected'; ?>>Education</option>
          <option value="Technology" <?php if ($proposal['category']=='Technology') echo 'selected'; ?>>Technology</option>
          <option value="Others" <?php if ($proposal['category']=='Others') echo 'selected'; ?>>Others</option>
        </select>
      </div>

      <div class="form-group full">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($proposal['description']); ?></textarea>
      </div>

      <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" required>
          <option value="Pending"   <?php if ($proposal['status']=='Pending') echo 'selected'; ?>>Pending</option>
          <option value="Approved"  <?php if ($proposal['status']=='Approved') echo 'selected'; ?>>Approved</option>
          <option value="Rejected"  <?php if ($proposal['status']=='Rejected') echo 'selected'; ?>>Rejected</option>
          <option value="Ongoing"   <?php if ($proposal['status']=='Ongoing') echo 'selected'; ?>>Ongoing</option>
          <option value="Completed" <?php if ($proposal['status']=='Completed') echo 'selected'; ?>>Completed</option>
        </select>
      </div>

      <div class="form-actions full">
        <a href="/PPP-system/lgu-staff-dashboard.php?page=all-proposals" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>
</div>
