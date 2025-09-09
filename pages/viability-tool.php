<?php
require __DIR__ . '/../db.php';

// Fetch proposals pending AI evaluation
$proposals = $conn->query("SELECT id, project_title, description FROM proposals WHERE status = 'Pending'");
?>

<div class="card">
  <h2>AI Viability Tool</h2>
  <p>Select a proposal and let the AI predict its viability automatically.</p>

  <form method="POST" action="run-viability-ai.php">
    <label for="proposal_id">Select Proposal</label>
    <select name="proposal_id" required>
      <option value="">-- Choose Proposal --</option>
      <?php while($row = $proposals->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['project_title']) ?></option>
      <?php endwhile; ?>
    </select>
    <button type="submit" class="btn-primary" style="margin-top:10px;">Run AI Evaluation</button>
  </form>
</div>
