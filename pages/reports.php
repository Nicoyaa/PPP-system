<?php
require __DIR__ . '/../db.php';

// ====== Aggregate Data ======

// Total proposals by status
$statusData = $conn->query("
  SELECT status, COUNT(*) as count 
  FROM proposals 
  GROUP BY status
")->fetch_all(MYSQLI_ASSOC);

// Proposals per category
$categoryData = $conn->query("
  SELECT category, COUNT(*) as count 
  FROM proposals 
  GROUP BY category
")->fetch_all(MYSQLI_ASSOC);

// Proposals per month (for line chart)
$monthlyData = $conn->query("
  SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count 
  FROM proposals 
  GROUP BY month
  ORDER BY month
")->fetch_all(MYSQLI_ASSOC);
?>

<div class="page">
  <h2>📊 Reports & Analytics</h2>

  <!-- KPI Cards -->
  <div class="grid" style="grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:1rem; margin:1rem 0;">
    <div class="card"><h3>Total Proposals</h3><p><?= array_sum(array_column($statusData, 'count')) ?></p></div>
    <div class="card"><h3>Approved</h3><p><?= array_sum(array_column(array_filter($statusData, fn($r)=>$r['status']=='Approved'), 'count')) ?></p></div>
    <div class="card"><h3>Pending</h3><p><?= array_sum(array_column(array_filter($statusData, fn($r)=>$r['status']=='Pending'), 'count')) ?></p></div>
    <div class="card"><h3>Rejected</h3><p><?= array_sum(array_column(array_filter($statusData, fn($r)=>$r['status']=='Rejected'), 'count')) ?></p></div>
  </div>

  <!-- Charts -->
  <div class="grid" style="grid-template-columns: repeat(auto-fit,minmax(300px,1fr)); gap:1rem; margin-top:1rem;">
    <div class="card">
      <h3>Proposals by Status</h3>
      <canvas id="statusChart"></canvas>
    </div>
    <div class="card">
      <h3>Proposals by Category</h3>
      <canvas id="categoryChart"></canvas>
    </div>
    <div class="card" style="grid-column: span 2;">
      <h3>Proposals Over Time</h3>
      <canvas id="monthlyChart"></canvas>
    </div>
  </div>

  <!-- Data Table -->
  <div class="card" style="margin-top:1.5rem;">
    <h3>📑 Detailed Proposals Report</h3>
    <table class="table" style="width:100%; border-collapse:collapse; margin-top:1rem;">
      <thead>
        <tr>
          <th style="padding:.5rem; border-bottom:1px solid var(--border);">Title</th>
          <th style="padding:.5rem; border-bottom:1px solid var(--border);">Category</th>
          <th style="padding:.5rem; border-bottom:1px solid var(--border);">Status</th>
          <th style="padding:.5rem; border-bottom:1px solid var(--border);">Submitted</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $allProposals = $conn->query("SELECT project_title, category, status, created_at FROM proposals ORDER BY created_at DESC LIMIT 50");
        while ($p = $allProposals->fetch_assoc()):
        ?>
          <tr>
            <td style="padding:.5rem; border-bottom:1px solid var(--border);"><?= htmlspecialchars($p['project_title']) ?></td>
            <td style="padding:.5rem; border-bottom:1px solid var(--border);"><?= htmlspecialchars($p['category']) ?></td>
            <td style="padding:.5rem; border-bottom:1px solid var(--border);"><?= htmlspecialchars($p['status']) ?></td>
            <td style="padding:.5rem; border-bottom:1px solid var(--border);"><?= date("M d, Y", strtotime($p['created_at'])) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ====== Data for Charts ======
const statusData = <?= json_encode($statusData) ?>;
const categoryData = <?= json_encode($categoryData) ?>;
const monthlyData = <?= json_encode($monthlyData) ?>;

// ===== Status Chart =====
new Chart(document.getElementById("statusChart"), {
  type: "doughnut",
  data: {
    labels: statusData.map(r => r.status),
    datasets: [{
      data: statusData.map(r => r.count),
      backgroundColor: ["#0d6efd","#198754","#ffc107","#dc3545"]
    }]
  }
});

// ===== Category Chart =====
new Chart(document.getElementById("categoryChart"), {
  type: "bar",
  data: {
    labels: categoryData.map(r => r.category),
    datasets: [{
      label: "Proposals",
      data: categoryData.map(r => r.count),
      backgroundColor: "#0d6efd"
    }]
  },
  options: { responsive:true, scales: { y: { beginAtZero: true } } }
});

// ===== Monthly Chart =====
new Chart(document.getElementById("monthlyChart"), {
  type: "line",
  data: {
    labels: monthlyData.map(r => r.month),
    datasets: [{
      label: "Proposals Submitted",
      data: monthlyData.map(r => r.count),
      borderColor: "#0d6efd",
      fill: false,
      tension: 0.3
    }]
  },
  options: { responsive:true }
});
</script>
