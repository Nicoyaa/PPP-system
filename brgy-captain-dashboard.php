<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PPP Management System - Brgy. Captain Dashboard</title>
  <link rel="stylesheet" href="css/proponent.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
      <div class="brand">
        <img src="assets/logo.jpg" alt="PPP Logo" class="logo" />
        <span>Captain Dashboard</span>
      </div>

      <!-- Profile -->
      <a href="#" class="nav-item modal-trigger" id="profileLink">
        <div class="icon"><i class="fas fa-user-circle"></i></div>
        <span>My Profile</span>
      </a>

      <nav>
        <div class="nav-group">
          <!-- Dashboard -->
          <a href="#" class="nav-item active" data-route="dashboard">
            <div class="icon"><i class="fas fa-tachometer-alt"></i></div>
            <span>Dashboard</span>
          </a>

          <!-- Final Approvals -->
          <a href="captain-approvals.html" class="nav-item" data-route="approvals">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <span>Final Approvals</span>
          </a>

          <!-- All Proposals -->
          <a href="captain-proposals.html" class="nav-item" data-route="proposals">
            <div class="icon"><i class="fas fa-file-alt"></i></div>
            <span>All Proposals</span>
          </a>

          <!-- Projects Oversight -->
          <a href="captain-projects.html" class="nav-item" data-route="projects">
            <div class="icon"><i class="fas fa-project-diagram"></i></div>
            <span>Project Oversight</span>
          </a>

          <!-- Reports -->
          <a href="captain-reports.html" class="nav-item" data-route="reports">
            <div class="icon"><i class="fas fa-chart-bar"></i></div>
            <span>Reports</span>
          </a>
        </div>

        <div class="nav-group">
          <!-- Notifications -->
          <a href="#" class="nav-item" data-route="notifications">
            <div class="icon"><i class="fas fa-bell"></i></div>
            <span>Notifications</span>
          </a>

          <!-- Timeline -->
          <a href="#" class="nav-item" data-route="timeline">
            <div class="icon"><i class="fas fa-stream"></i></div>
            <span>Project Timeline</span>
          </a>

          <!-- Analytics -->
          <a href="#" class="nav-item" data-route="analytics">
            <div class="icon"><i class="fas fa-chart-pie"></i></div>
            <span>Analytics</span>
          </a>

          <!-- Settings -->
          <a href="#" class="nav-item" data-route="settings">
            <div class="icon"><i class="fas fa-cog"></i></div>
            <span>System Settings</span>
          </a>
        </div>
      </nav>
    </aside>

    <!-- Main Section -->
    <section class="main">
      <header class="topbar">
        <button class="btn menu-btn" id="menuBtn" aria-label="Open menu">☰</button>
        <div class="search">
          <span aria-hidden="true">🔎</span>
          <input id="searchInput" type="search" placeholder="Search…" />
        </div>
        <div class="actions">
          <button class="btn" id="exportBtn">Export Reports</button>
          <button class="btn icon-btn" id="notificationBtn" aria-label="Notifications">
            <i class="fas fa-bell"></i>
          </button>
          <button class="btn" id="darkModeToggle">🌙</button>
        </div>
      </header>

      <div class="content">
        <div class="breadcrumbs">Home / <span id="crumb">Dashboard</span></div>
        <div class="page-title">
          <h1 id="pageTitle">Brgy. Captain Dashboard</h1>
          <div class="actions">
            <button class="btn" id="refreshBtn">Refresh</button>
          </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid" style="grid-template-columns: repeat(12, 1fr);">
          <div class="card anim-pop" style="grid-column: span 3;">
            <h3>Pending Approvals</h3>
            <div class="kpi"><span class="value" id="kpiApprovals">12</span></div>
          </div>
          <div class="card anim-pop" style="grid-column: span 3;">
            <h3>Ongoing Projects</h3>
            <div class="kpi"><span class="value" id="kpiOngoing">18</span></div>
          </div>
          <div class="card anim-pop" style="grid-column: span 3;">
            <h3>Completed Projects</h3>
            <div class="kpi"><span class="value" id="kpiCompleted">55</span></div>
          </div>
          <div class="card anim-pop" style="grid-column: span 3;">
            <h3>Total Partnerships</h3>
            <div class="kpi"><span class="value" id="kpiPartnerships">22</span></div>
          </div>
        </div>

        <!-- Tables + Notifications -->
        <div class="grid" style="grid-template-columns: repeat(12, 1fr); margin-top:1rem;">
          <div class="card anim-fade" style="grid-column: span 8;">
            <h3>Proposals Awaiting Final Approval</h3>
            <table class="table" id="approvalTable">
              <thead>
                <tr><th>Title</th><th>Proponent</th><th>Status</th><th>Date Submitted</th></tr>
              </thead>
              <tbody>
                <tr><td>Barangay Hall Renovation</td><td>BuildCo</td><td>For Approval</td><td>Aug 25</td></tr>
                <tr><td>Drainage Improvement</td><td>InfraTech</td><td>For Approval</td><td>Aug 22</td></tr>
                <tr><td>Waste Management Facility</td><td>EcoWaste</td><td>For Approval</td><td>Aug 20</td></tr>
              </tbody>
            </table>
          </div>
          <div class="card anim-fade" style="grid-column: span 4;">
            <h3>System Notifications</h3>
            <ul id="notificationList" class="notification-list">
              <li>3 new proposals require your final approval.</li>
              <li>Ongoing project “Drainage Improvement” reached 75% completion.</li>
              <li>Monthly report is ready for review.</li>
            </ul>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Help Modal -->
  <div class="overlay" id="overlay"></div>
  <div class="modal" id="helpModal">
    <div class="modal-content">
      <span class="close-btn" id="closeModalBtn">&times;</span>
      <div class="help-support-page">
        <h2>Help & Support</h2>
        <div class="faq-section">
          <h3>Frequently Asked Questions (FAQs)</h3>
          <ul>
            <li><strong>How do I give final approval?</strong><br>Go to "Final Approvals" and review each pending proposal.</li>
            <li><strong>How do I monitor projects?</strong><br>Check "Project Oversight" for detailed tracking of project phases.</li>
            <li><strong>Can I generate summary reports?</strong><br>Yes, go to "Reports" to export summaries and analytics.</li>
          </ul>
        </div>
        <div class="contact-section">
          <h3>Contact Information</h3>
          <p>If you need further assistance, please reach out to us:</p>
          <ul>
            <li><strong>Email:</strong> support@ppp-system.com</li>
            <li><strong>Phone:</strong> +63 912 345 6789</li>
            <li><strong>Office Address:</strong> Barangay Proper Novaliches, Quezon City</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <script src="script/proponent.js"></script>
</body>
</html>
