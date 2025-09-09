<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection
require __DIR__ . '/../db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id       = $_SESSION['user_id'];
    $title         = trim($_POST['projectTitle']);
    $category      = trim($_POST['category']);
    $description   = trim($_POST['description']);
    $address       = trim($_POST['address']);
    $estimatedCost = trim($_POST['estimatedCost']);
    $roi           = trim($_POST['roi']);
    $organization  = trim($_POST['organization']);
    $contactPerson = trim($_POST['contactPerson']);
    $email         = trim($_POST['email']);
    $phone         = trim($_POST['phone']);

    $uploadDir = __DIR__ . '/../uploads/';
    $filePath = null;

    // Handle file upload
    if (!empty($_FILES['attachments']['name'][0])) {
        $fileName = time() . "_" . basename($_FILES["attachments"]["name"][0]);
        $targetFile = $uploadDir . $fileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // create directory if not exists
        }

        if (move_uploaded_file($_FILES["attachments"]["tmp_name"][0], $targetFile)) {
            $filePath = 'uploads/' . $fileName;
        } else {
            $errorMessage = "❌ Failed to upload file.";
        }
    }

    // Insert into database if no errors
    if (empty($errorMessage)) {
        $stmt = $conn->prepare("INSERT INTO proposals 
            (user_id, project_title, category, description, address, estimated_cost, roi, attachments, organization, contact_person, email, phone) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        if ($stmt) {
            $stmt->bind_param(
                "issssddsssss",
                $user_id, $title, $category, $description, $address,
                $estimatedCost, $roi, $filePath, $organization, $contactPerson, $email, $phone
            );

            if ($stmt->execute()) {
                $successMessage = "✅ Proposal submitted successfully!";
            } else {
                // Show detailed DB error
                $errorMessage = "❌ Database error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errorMessage = "❌ Failed to prepare SQL: " . $conn->error;
        }
    }
}
?>

<!-- Proposal Form -->
<div class="form-page">
  <div class="form-card">
    <h2 class="form-title">Submit New Proposal</h2>
    <p class="form-subtitle">Fill in the details below to submit your PPP project proposal.</p>

    <!-- Alerts -->
    <?php if (!empty($errorMessage)) : ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php elseif (!empty($successMessage)) : ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="form-grid">

        <div class="form-group">
            <label>Project Title</label>
            <input type="text" name="projectTitle" required placeholder="Enter project title">
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category" required>
                <option value="">-- Select Category --</option>
                <option value="infrastructure">Infrastructure</option>
                <option value="transportation">Transportation</option>
                <option value="health">Health</option>
                <option value="education">Education</option>
                <option value="technology">Technology</option>
                <option value="others">Others</option>
            </select>
        </div>

        <div class="form-group full">
            <label>Project Description</label>
            <textarea name="description" rows="4" required placeholder="Briefly describe the project..."></textarea>
        </div>

        <div class="form-group full">
            <label>Project Address</label>
            <input type="text" name="address" required placeholder="Enter full address">
        </div>

        <div class="form-group">
            <label>Estimated Cost (₱)</label>
            <input type="number" name="estimatedCost" required placeholder="5000000">
        </div>

        <div class="form-group">
            <label>Expected ROI (%)</label>
            <input type="number" name="roi" placeholder="12">
        </div>

        <div class="form-group full">
            <label>Supporting Documents</label>
            <input type="file" name="attachments[]" multiple required>
            <small>Upload feasibility studies, financial plans, designs, etc.</small>
        </div>

        <div class="form-group">
            <label>Organization / Company</label>
            <input type="text" name="organization" required placeholder="ABC Corp.">
        </div>

        <div class="form-group">
            <label>Contact Person</label>
            <input type="text" name="contactPerson" required placeholder="Juan Dela Cruz">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="you@example.com">
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" required placeholder="09123456789">
        </div>

        <div class="form-actions full">
            <button type="reset" class="btn-secondary">Clear</button>
            <button type="submit" class="btn-primary">Submit</button>
        </div>
    </form>
  </div>
</div>

<!-- Styles -->
<style>
.form-page {
  display: flex;
  justify-content: center;
  padding: 40px;
  min-height: 100vh;
  
}

.form-card {
  background: #fff;
  color: #222;
  border-radius: 12px;
  padding: 32px;
  width: 100%;
  max-width: 750px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.form-title { font-size: 1.8rem; font-weight: 600; margin-bottom: 6px; }
.form-subtitle { font-size: 0.95rem; margin-bottom: 24px; color: #666; }

.form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }
.form-group { display: flex; flex-direction: column; }
.form-group.full { grid-column: span 2; }
.form-group label { margin-bottom: 6px; font-size: 0.9rem; color: #444; }

.form-group input,
.form-group select,
.form-group textarea {
  background: #fff;
  border: 1px solid #ccc;
  border-radius: 8px;
  padding: 12px;
  font-size: 0.95rem;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus { outline: none; border-color: #007bff; background: #f9f9f9; }

.form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 16px; }

.btn-primary { background: #007bff; border: none; padding: 12px 28px; border-radius: 8px; color: #fff; cursor: pointer; transition: 0.2s; }
.btn-primary:hover { background: #0056b3; }

.btn-secondary { background: #e0e0e0; color: #222; border: none; padding: 12px 28px; border-radius: 8px; cursor: pointer; }
.btn-secondary:hover { background: #d6d6d6; }

.alert { padding: 10px; border-radius: 6px; margin-bottom: 15px; }
.alert-danger { background: #f8d7da; color: #721c24; }
.alert-success { background: #d4edda; color: #155724; }

@media (max-width: 768px) {
  .form-grid { grid-template-columns: 1fr; }
}
</style>
