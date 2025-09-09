<?php 
require __DIR__ . '/../db.php';

// ✅ KPI Queries
$totalProposals   = $conn->query("SELECT COUNT(*) AS c FROM proposals")->fetch_assoc()['c'] ?? 0;
$ongoingProjects  = $conn->query("SELECT COUNT(*) AS c FROM projects WHERE status='Ongoing'")->fetch_assoc()['c'] ?? 0;
$completedProjects = $conn->query("SELECT COUNT(*) AS c FROM projects WHERE status='Completed'")->fetch_assoc()['c'] ?? 0;
$registeredUsers  = $conn->query("SELECT COUNT(*) AS c FROM users WHERE role='user'")->fetch_assoc()['c'] ?? 0;

// ✅ Status counts for chart
$statusCounts = [];
$res = $conn->query("SELECT status, COUNT(*) AS cnt FROM proposals GROUP BY status");
while ($row = $res->fetch_assoc()) {
  $statusCounts[$row['status']] = $row['cnt'];
}

// ✅ Proposals by category
$categoryData = [];
$res = $conn->query("SELECT category, COUNT(*) as cnt FROM proposals GROUP BY category");
while ($row = $res->fetch_assoc()) {
  $categoryData[] = $row;
}

// ✅ Recent proposals
$recentProposals = $conn->query("SELECT project_title, category, status, updated_at 
                                FROM proposals ORDER BY updated_at DESC LIMIT 5");
?>

<!-- KPI Section -->
<div class="grid" style="grid-template-columns: repeat(12, 1fr); gap:1rem;">
  <div class="card anim-pop" style="grid-column: span 3;">
    <h3>Total Proposals</h3>
    <div class="kpi"><span class="value"><?= $totalProposals ?></span></div>
  </div>
  <div class="card anim-pop" style="grid-column: span 3;">
    <h3>Ongoing Projects</h3>
    <div class="kpi"><span class="value"><?= $ongoingProjects ?></span></div>
  </div>
  <div class="card anim-pop" style="grid-column: span 3;">
    <h3>Completed Projects</h3>
    <div class="kpi"><span class="value"><?= $completedProjects ?></span></div>
  </div>
  <div class="card anim-pop" style="grid-column: span 3;">
    <h3>Registered Proponents</h3>
    <div class="kpi"><span class="value"><?= $registeredUsers ?></span></div>
  </div>
</div>

<!-- ✅ Ensure Chart.js is loaded -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-top: 20px;">
  <div class="card">
    <h3>Proposal Status Overview</h3>
    <canvas id="proposalChart" style="height:250px;"></canvas>
  </div>

  <div class="card">
    <h3>Projects Overview</h3>
    <canvas id="projectsChart" style="height:250px;"></canvas>
  </div>
</div>
<!-- Recent Proposals Table -->
<div class="card anim-fade" style="margin-top:1.5rem;">
  <h3>📝 Recent Proposals</h3>
  <table class="table">
    <thead>
      <tr><th>Title</th><th>Category</th><th>Status</th><th>Last Updated</th></tr>
    </thead>
    <tbody>
      <?php while ($p = $recentProposals->fetch_assoc()) : ?>
        <tr>
          <td><?= htmlspecialchars($p['project_title']) ?></td>
          <td><?= htmlspecialchars($p['category']) ?></td>
          <td><?= htmlspecialchars($p['status']) ?></td>
          <td><?= htmlspecialchars(date("M d, Y", strtotime($p['updated_at']))) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // ✅ Status Chart
  const ctx1 = document.getElementById('statusChart');
  new Chart(ctx1, {
    type: 'doughnut',
    data: {
      labels: <?= json_encode(array_keys($statusCounts)) ?>,
      datasets: [{
        data: <?= json_encode(array_values($statusCounts)) ?>,
        backgroundColor: ['#28a745','#ffc107','#dc3545','#17a2b8','#6f42c1']
      }]
    },
    options: { responsive: true }
  });

  // ✅ Category Chart
  const ctx2 = document.getElementById('categoryChart');
  new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_column($categoryData, 'category')) ?>,
      datasets: [{
        label: 'Proposals',
        data: <?= json_encode(array_column($categoryData, 'cnt')) ?>,
        backgroundColor: '#007bff'
      }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
  });
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const proposalData = {
    total: <?= $totalProposals ?>,
    ongoing: <?= $ongoingProjects ?>,
    completed: <?= $completedProjects ?>,
    users: <?= $registeredUsers ?>
  };

  const ctx1 = document.getElementById('proposalChart').getContext('2d');
  new Chart(ctx1, {
    type: 'doughnut',
    data: {
      labels: ['Total Proposals', 'Registered Proponents'],
      datasets: [{
        data: [proposalData.total, proposalData.users],
        backgroundColor: ['#0c60db', '#f4b400']
      }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
  });

  const ctx2 = document.getElementById('projectsChart').getContext('2d');
  new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: ['Ongoing Projects', 'Completed Projects'],
      datasets: [{
        label: 'Projects',
        data: [proposalData.ongoing, proposalData.completed],
        backgroundColor: ['#34a853', '#db4437']
      }]
    },
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
  });
});
</script>
