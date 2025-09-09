<?php  
session_start();
if (!isset($_SESSION["role"])) {
    header("location: /login.php");
    exit();
}

if ($_SESSION["role"] == "admin") {
    header("location: lgu-staff-dashboard.php");
    exit();
}
if ($_SESSION["role"] == "brgy-captain") {
    header("location: brgy-captain-dashboard.php");
    exit();
}

// ✅ Capture current page (default: dashboard)
$page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PPP Management System - Proponent Dashboard</title>
    <link rel="stylesheet" href="css/proponent.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
<div class="app">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-inner">
            <div class="brand">
                <img src="assets/logo.jpg" alt="PPP Logo" class="logo" />
                <span>Proponent Dashboard</span>
            </div>

            <!-- Profile Link -->
            <a href="#" class="nav-item" id="profileLink">
                <div class="icon"><i class="fas fa-user"></i></div>
                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </a>

            <nav> 
                <div class="nav-group">
                    <a href="private-dashboard.php?page=dashboard" class="nav-item <?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-tachometer-alt"></i></div>
                        <span>Dashboard</span>
                    </a>
                    <a href="private-dashboard.php?page=my-proposals" class="nav-item <?php echo ($page == 'my-proposals') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-file-alt"></i></div>
                        <span>My Proposals</span>
                    </a>
                    <a href="private-dashboard.php?page=my-projects" class="nav-item <?php echo ($page == 'my-projects') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                        <span>My Projects</span>
                    </a>
                    <a href="private-dashboard.php?page=submit-proposal" class="nav-item <?php echo ($page == 'submit-proposal') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-plus"></i></div>
                        <span>Submit Proposal</span>
                    </a>
                    <a href="private-dashboard.php?page=track-status" class="nav-item <?php echo ($page == 'track-status') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                        <span>Track Status</span>
                    </a>
                </div>

                <div class="nav-group">
                    <a href="private-dashboard.php?page=notifications" class="nav-item <?php echo ($page == 'notifications') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-bell"></i></div>
                        <span>Notifications</span>
                    </a>
                    <a href="private-dashboard.php?page=settings" class="nav-item <?php echo ($page == 'settings') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-cog"></i></div>
                        <span>Settings</span>
                    </a>
                </div>
            </nav>

            <!-- Sidebar footer -->
            <div class="sidebar-footer">
                <a href="logout.php" class="nav-item logout">
                    <div class="icon"><i class="fas fa-sign-out-alt"></i></div>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <section class="main">
        <header class="topbar">
            <button class="btn menu-btn" id="menuBtn" aria-label="Open menu">☰</button>
            <div class="search">
                <span aria-hidden="true">🔎</span>
                <input id="searchInput" type="search" placeholder="Search…" />
            </div>
            <div class="actions">
    <a href="private-dashboard.php?page=submit-proposal" class="btn">+ New Proposal</a>
    <a href="export.php" class="btn">Export</a>
<!-- 🔔 Bell Dropdown -->
<!-- 🔔 Bell Dropdown -->
<div class="dropdown" id="notifWrapper">
  <button class="btn icon-btn position-relative" id="notifDropdownBtn">
    <i class="fas fa-bell"></i>
    <span id="notifCount" 
          class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill" 
          style="font-size: 0.7rem; display:none;">
      0
    </span>
  </button>

  <!-- Custom Dropdown -->
  <ul class="dropdown-menu shadow" id="notifDropdown" 
      style="width: 300px; max-height: 400px; overflow-y: auto; display: none;">
    <li class="dropdown-header">Notifications</li>
    <li><hr class="dropdown-divider"></li>
    <div id="notifDropdownList"></div>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item text-center text-primary" href="private-dashboard.php?page=notifications">View All</a></li>
  </ul>
</div>

 <button class="btn" id="darkModeToggle">🌙</button>
</div>
        </header>

        <!-- ✅ Load PHP pages directly -->
        <div class="content">
            <?php
            $file = "pages/" . $page . ".php";
            if (file_exists($file)) {
                include $file;
            } else {
                include "pages/dashboard-content.php"; // fallback
            }
            ?>
        </div>
    </section>
</div>

<!-- User Profile Modal -->
<div class="modal" id="profileModal">
    <div class="modal-content">
        <span class="close-btn" id="closeProfileModal">&times;</span>
        <div class="profile-page">
            <h2>My Profile</h2>
            <div style="text-align:center; margin-bottom:1rem;">
                <img src="<?php echo isset($_SESSION['profile_pic']) && $_SESSION['profile_pic'] !== '' 
                                ? htmlspecialchars($_SESSION['profile_pic']) 
                                : 'assets/default-profile.png'; ?>" 
                     alt="Profile Picture" 
                     style="width:120px; height:120px; border-radius:50%; object-fit:cover; border:2px solid #ccc;">
                <form action="upload_profile_pic.php" method="POST" enctype="multipart/form-data" style="margin-top:10px;">
                    <input type="file" name="profile_pic" accept="image/*" required>
                    <button type="submit" class="btn">Upload</button>
                </form>
            </div>
            <table class="table" style="width:100%;">
                <tr><th>Username:</th><td><?php echo htmlspecialchars($_SESSION['username']); ?></td></tr>
                <tr><th>Email:</th><td><?php echo htmlspecialchars($_SESSION['email']); ?></td></tr>
                <tr><th>Company:</th><td><?php echo htmlspecialchars($_SESSION['company']); ?></td></tr>
                <tr><th>Contact Number:</th><td><?php echo htmlspecialchars($_SESSION['contact_number']); ?></td></tr>
            </table>
        </div>
    </div>
</div>

<script src="script/proponent.js"></script>

<script>
// Profile Modal JS
const profileLink = document.getElementById("profileLink");
const profileModal = document.getElementById("profileModal");
const closeProfileModal = document.getElementById("closeProfileModal");

profileLink?.addEventListener("click", (e) => {
    e.preventDefault();
    profileModal.style.display = "block";
});
closeProfileModal?.addEventListener("click", () => {
    profileModal.style.display = "none";
});
window.addEventListener("click", (e) => {
    if (e.target === profileModal) {
        profileModal.style.display = "none";
    }
});
</script>
<!-- Add this before your closing </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
