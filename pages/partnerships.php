<?php
require __DIR__ . '/../db.php';

// Fetch approved proposals with projects
$sql = "
    SELECT 
        p.id AS proposal_id,
        p.project_title,
        p.category,
        p.organization,
        p.contact_person,
        p.email,
        p.phone,
        p.status AS proposal_status,
        pr.id AS project_id,
        pr.status AS project_status,
        pr.created_at AS project_created
    FROM proposals p
    LEFT JOIN projects pr ON p.id = pr.proposal_id
    WHERE p.status = 'Approved'
    ORDER BY p.created_at DESC
";
$result = $conn->query($sql);
?>

<div class="page">
  <h2>🤝 Partnerships</h2>
  <p class="text-muted">Active partnerships with private companies.</p>

  <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.2rem; margin-top: 20px;">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="card" style="border:1px solid var(--border); border-radius:16px; padding:1rem; position:relative;">
          
          <!-- Header with Company Name -->
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
            <h3 style="margin:0; font-size:1.2rem; font-weight:600;">
              <?= htmlspecialchars($row['organization']) ?>
            </h3>
            <?php if ($row['project_status'] == "Ongoing" || $row['proposal_status'] == "Approved"): ?>
              <span class="badge bg-success">🟢 Active</span>
            <?php elseif ($row['project_status'] == "Completed"): ?>
              <span class="badge bg-primary">✅ Completed</span>
            <?php elseif ($row['proposal_status'] == "Rejected"): ?>
              <span class="badge bg-danger">❌ Rejected</span>
            <?php else: ?>
              <span class="badge bg-warning">⏳ Pending</span>
            <?php endif; ?>
          </div>

          <!-- Project Details -->
          <p><strong>Project:</strong> <?= htmlspecialchars($row['project_title']) ?></p>
          <p><strong>Category:</strong> <?= htmlspecialchars($row['category'] ?: 'N/A') ?></p>
          <p><strong>Contact:</strong> <?= htmlspecialchars($row['contact_person']) ?> 
             (📧 <a href="mailto:<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></a>
             <?= $row['phone'] ? "📞 " . htmlspecialchars($row['phone']) : '' ?>)
          </p>

          <!-- Footer -->
          <div style="display:flex; justify-content:space-between; margin-top:12px; font-size:.85rem; color:gray;">
            <span>Proposal: <em><?= htmlspecialchars($row['proposal_status']) ?></em></span>
            <span><?= $row['project_created'] ? date("M d, Y", strtotime($row['project_created'])) : '—' ?></span>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="card text-center" style="padding:2rem;">
        <p class="text-muted">No active partnerships yet.</p>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php
require __DIR__ . '/../db.php';

// Fetch approved proposals with projects
$sql = "
    SELECT 
        p.id AS proposal_id,
        p.project_title,
        p.category,
        p.organization,
        p.contact_person,
        p.email,
        p.phone,
        p.status AS proposal_status,
        pr.id AS project_id,
        pr.status AS project_status,
        pr.created_at AS project_created
    FROM proposals p
    LEFT JOIN projects pr ON p.id = pr.proposal_id
    WHERE p.status = 'Approved'
    ORDER BY p.created_at DESC
";
$result = $conn->query($sql);
?>

<div class="page">
  <h2>🤝 Partnerships</h2>
  <p class="text-muted">Active partnerships with private companies.</p>

  <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.2rem; margin-top: 20px;">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="card" style="border:1px solid var(--border); border-radius:16px; padding:1rem; position:relative;">
          
          <!-- Header with Company Name -->
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
            <h3 style="margin:0; font-size:1.2rem; font-weight:600;">
              <?= htmlspecialchars($row['organization']) ?>
            </h3>
            <?php if ($row['project_status'] == "Ongoing" || $row['proposal_status'] == "Approved"): ?>
              <span class="badge bg-success">🟢 Active</span>
            <?php elseif ($row['project_status'] == "Completed"): ?>
              <span class="badge bg-primary">✅ Completed</span>
            <?php elseif ($row['proposal_status'] == "Rejected"): ?>
              <span class="badge bg-danger">❌ Rejected</span>
            <?php else: ?>
              <span class="badge bg-warning">⏳ Pending</span>
            <?php endif; ?>
          </div>

          <!-- Project Details -->
          <p><strong>Project:</strong> <?= htmlspecialchars($row['project_title']) ?></p>
          <p><strong>Category:</strong> <?= htmlspecialchars($row['category'] ?: 'N/A') ?></p>
          <p><strong>Contact:</strong> <?= htmlspecialchars($row['contact_person']) ?> 
             (📧 <a href="mailto:<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></a>
             <?= $row['phone'] ? "📞 " . htmlspecialchars($row['phone']) : '' ?>)
          </p>

          <!-- Footer -->
          <div style="display:flex; justify-content:space-between; margin-top:12px; font-size:.85rem; color:gray;">
            <span>Proposal: <em><?= htmlspecialchars($row['proposal_status']) ?></em></span>
            <span><?= $row['project_created'] ? date("M d, Y", strtotime($row['project_created'])) : '—' ?></span>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="card text-center" style="padding:2rem;">
        <p class="text-muted">No active partnerships yet.</p>
      </div>
    <?php endif; ?>
  </div>
</div>
