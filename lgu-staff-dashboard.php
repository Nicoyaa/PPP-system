<?php  
session_start();
if (!isset($_SESSION["role"])) {
    header("location: /login.php");
    exit();
}

if ($_SESSION["role"] == "user" || $_SESSION["role"] == "") {
    header("location: private-dashboard.php");
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
    <title>PPP Management System - LGU STAFF Dashboard</title>
    <link rel="stylesheet" href="css/proponent.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
<div class="app">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-inner">
            <div class="brand">
                <img src="assets/logo.jpg" alt="PPP Logo" class="logo" />
                <span>Lgu Staff Dashboard</span>
            </div>

            <!-- Profile Link -->
            <a href="#" class="nav-item" id="profileLink">
                <div class="icon"><i class="fas fa-user"></i></div>
                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
            </a>

            <nav> 
                <div class="nav-group">
                    <a href="lgu-staff-dashboard.php?page=lgu-staff-dashboard" class="nav-item <?php echo ($page == 'lgu-staff-dashboard') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-tachometer-alt"></i></div>
                        <span>Dashboard</span>
                    </a>
                    <a href="lgu-staff-dashboard.php?page=all-proposals" class="nav-item <?php echo ($page == 'all-proposals') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-file-alt"></i></div>
                        <span>All Proposals</span>
                    </a>
                    <a href="lgu-staff-dashboard.php?page=all-projects" class="nav-item <?php echo ($page == 'all-projects') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                        <span>All Projects</span>
                    </a>
                    <a href="lgu-staff-dashboard.php?page=viability-tool" class="nav-item <?php echo ($page == 'viability-tool') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                        <span>Viability Tool</span>
                    </a>
                    <a href="lgu-staff-dashboard.php?page=partnerships" class="nav-item <?php echo ($page == 'partnerships') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                        <span>Partnerships</span>
                    </a>
                </div>
                <a href="lgu-staff-dashboard.php?page=timeline" class="nav-item <?php echo ($page == 'timeline') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                        <span>Timeline</span>
                    </a>
                    <a href="lgu-staff-dashboard.php?page=reports" class="nav-item <?php echo ($page == 'reports') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                        <span>Reports</span>
                    </a>

                <div class="nav-group">
                    <a href="lgu-staff-dashboard.php?page=notifications" class="nav-item <?php echo ($page == 'notifications') ? 'active' : ''; ?>">
                        <div class="icon"><i class="fas fa-bell"></i></div>
                        <span>Notifications</span>
                    </a>
                    <a href="lgu-staff-dashboard.php?page=system-settings" class="nav-item <?php echo ($page == 'system-settings') ? 'active' : ''; ?>">
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
               <!-- 🔔 Notification Bell -->
<div class="dropdown">
  <button class="btn icon-btn position-relative" id="notifDropdownBtn">
    <i class="fas fa-bell"></i>
    <span id="notifCount" 
          class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill" 
          style="font-size: 0.7rem; display:none;">
    </span>
  </button>

  <!-- Dropdown Menu -->
  <ul id="notifDropdown" class="dropdown-menu dropdown-menu-end shadow" 
      style="width: 300px; max-height: 400px; overflow-y: auto;">
    <li class="dropdown-header">Notifications</li>
    <li><hr class="dropdown-divider"></li>
    <div id="notifDropdownList"></div>
    <li><a class="dropdown-item text-center text-primary" href="pages/notifications.php">View All</a></li>
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
                include "pages/lgu-staff-dashboard.php"; // fallback
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
</body>
</html>
