<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/../db.php';

// ✅ Security check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// ✅ Fetch all proposals with proponent info
$sql = "SELECT p.*, u.username, u.company 
        FROM proposals p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);
?>

<div class="page-header d-flex justify-content-between align-items-center mb-4">
  <h1 class="h3">📑 All Proposals</h1>
</div>

<section class="content">
  <div class="card shadow-sm rounded-3 p-3">

    <!-- 🔎 Filters -->
    <div class="row mb-3">
      <div class="col-md-3">
        <label for="filterCategory" class="form-label">Filter by Category</label>
        <select id="filterCategory" class="form-select">
          <option value="">All Categories</option>
          <option value="Infrastructure">Infrastructure</option>
          <option value="Transportation">Transportation</option>
          <option value="Health">Health</option>
          <option value="Education">Education</option>
          <option value="Technology">Technology</option>
          <option value="Others">Others</option>
        </select>
      </div>
      <div class="col-md-3">
        <label for="filterStatus" class="form-label">Filter by Status</label>
        <select id="filterStatus" class="form-select">
          <option value="">All Status</option>
          <option value="Pending">Pending</option>
          <option value="Approved">Approved</option>
          <option value="Rejected">Rejected</option>
        </select>
      </div>
    </div>

    <!-- 📊 Table -->
    <table id="proposalsTable" class="table table-striped table-hover" style="width:100%">
      <thead>
        <tr>
          <th>Project Title</th>
          <th>Category</th>
          <th>Proponent</th>
          <th>Status</th>
          <th>Submitted</th>
          <th style="width:150px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['project_title']); ?></td>
              <td><?php echo htmlspecialchars($row['category']); ?></td>
              <td><?php echo htmlspecialchars($row['organization'] ?: $row['username']); ?></td>
              <td>
                <span class="badge 
                  <?php echo $row['status']=='Approved'?'bg-success':($row['status']=='Rejected'?'bg-danger':'bg-secondary'); ?>">
                  <?php echo htmlspecialchars($row['status']); ?>
                </span>
              </td>
              <td><?php echo date("M d, Y", strtotime($row['created_at'])); ?></td>
              <td>
                <!-- ✅ Actions -->
                <a href="lgu-staff-dashboard.php?page=view-proposal&id=<?php echo $row['id']; ?>" 
                   class="btn btn-sm btn-outline-info me-1">
                  <i class="fas fa-eye"></i>
                </a>
                <a href="lgu-staff-dashboard.php?page=edit-proposal&id=<?php echo $row['id']; ?>" 
                   class="btn btn-sm btn-outline-success me-1">
                  <i class="fas fa-edit"></i>
                </a>
                <a href="pages/delete-proposal.php?id=<?php echo $row['id']; ?>" 
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Are you sure you want to delete this proposal?')">
                  <i class="fas fa-trash"></i>
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<!-- ✅ DataTables + jQuery CDN -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#proposalsTable').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 20, 50],
        order: [[4, 'desc']], // sort by Submitted
    });

    // ✅ Category filter
    $('#filterCategory').on('change', function () {
        table.column(1).search(this.value).draw();
    });

    // ✅ Status filter
    $('#filterStatus').on('change', function () {
        table.column(3).search(this.value).draw();
    });
});
</script>
