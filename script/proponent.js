// ===== Sidebar toggle =====
const menuBtn = document.getElementById("menuBtn");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");

menuBtn?.addEventListener("click", () => {
  sidebar?.classList.toggle("open");
  overlay?.classList.toggle("show");
});

overlay?.addEventListener("click", () => {
  sidebar?.classList.remove("open");
  overlay?.classList.remove("show");
});

// ===== Dark mode =====
const darkModeToggle = document.getElementById("darkModeToggle");
if (localStorage.getItem("darkMode") === "enabled") {
  document.body.classList.add("dark-mode");
}
darkModeToggle?.addEventListener("click", () => {
  document.body.classList.toggle("dark-mode");
  localStorage.setItem(
    "darkMode",
    document.body.classList.contains("dark-mode") ? "enabled" : "disabled"
  );
});

// ===== Help & Support Modal =====
const helpLink = document.getElementById("helpLink");
const helpModal = document.getElementById("helpModal");
const closeModalBtn = document.getElementById("closeModalBtn");

if (helpLink && helpModal && closeModalBtn) {
  helpLink.addEventListener("click", (e) => {
    e.preventDefault();
    helpModal.classList.add("show");
  });

  closeModalBtn.addEventListener("click", () => {
    helpModal.classList.remove("show");
  });

  window.addEventListener("click", (e) => {
    if (e.target === helpModal) {
      helpModal.classList.remove("show");
    }
  });
}

// ===== SPA Loader & Page Navigation =====
document.addEventListener("DOMContentLoaded", () => {
  const content = document.querySelector(".content");
  const crumb = document.getElementById("crumb");
  const pageTitle = document.getElementById("pageTitle");

  const titles = {
    "dashboard": "Dashboard",
    "my-proposals": "My Proposals",
    "my-projects": "My Projects",
    "submit-proposal": "Submit Proposal",
    "track-status": "Track Status",
    "notifications": "Notifications",
    "help": "Help & Support",
    "contacts": "Contacts"
  };

  function setTitle(route) {
    const t =
      titles[route] ||
      route.replace(/-/g, " ").replace(/\b\w/g, (m) => m.toUpperCase());
    crumb.textContent = t;
    pageTitle.textContent = t;
  }

  async function loadPage(route) {
    if (route === "dashboard") {
      setTitle("dashboard");
      return;
    }

    try {
      const res = await fetch(`pages/${route}.php`, { cache: "no-store" });
      if (!res.ok) throw new Error(`${route}.php not found`);
      const html = await res.text();

      content.innerHTML = html;
      setTitle(route);

      document
        .querySelectorAll(".nav-item")
        .forEach((i) => i.classList.remove("active"));
      document
        .querySelector(`.nav-item[data-route="${route}"]`)
        ?.classList.add("active");

      sidebar?.classList.remove("open");
      overlay?.classList.remove("show");
    } catch (err) {
      content.innerHTML = `<div class="card"><div class="card-body"><p class="text-danger">Error loading page: ${err.message}</p></div></div>`;
    }
  }

  document.querySelectorAll(".nav-item[data-route]").forEach((item) => {
    item.addEventListener("click", (e) => {
      e.preventDefault();
      const route = item.getAttribute("data-route");
      if (route) loadPage(route);
    });
  });

  setTitle("dashboard");
});

// ===== Refresh demo KPIs =====
document.getElementById("refreshBtn")?.addEventListener("click", () => {
  const r = () => Math.floor(Math.random() * 400 + 100);
  const bump = (el) => {
    if (!el) return;
    el.style.transition = "transform .15s";
    el.style.transform = "scale(1.05)";
    setTimeout(() => {
      el.style.transform = "";
    }, 160);
  };
  const s = (id) => document.getElementById(id);

  s("kpiSubmitted") && (s("kpiSubmitted").textContent = r(), bump(s("kpiSubmitted")));
  s("kpiPending") &&
    (s("kpiPending").textContent = Math.floor(Math.random() * 120 + 40),
    bump(s("kpiPending")));
  s("kpiApproved") &&
    (s("kpiApproved").textContent = Math.floor(Math.random() * 40 + 10) + "%",
    bump(s("kpiApproved")));
  s("kpiRejected") &&
    (s("kpiRejected").textContent =
      "₱" + (Math.random() * 90 + 10).toFixed(1) + "k",
    bump(s("kpiRejected")));
});

// ===== Realtime Notifications =====
async function loadNotifications() {
  try {
    const res = await fetch("notifications_api.php", { cache: "no-store" });
    if (!res.ok) throw new Error("Failed to load notifications");

    const data = await res.json();

    const notifList = document.getElementById("notifDropdownList");
    const notifCount = document.getElementById("notifCount");

    if (!notifList || !notifCount) return;

    notifList.innerHTML = "";

    if (data.length === 0) {
      notifList.innerHTML = `<li class="dropdown-item text-muted">No new notifications</li>`;
      notifCount.style.display = "none";
    } else {
      data.forEach((n) => {
        const item = document.createElement("li");
        item.className = "dropdown-item";
        item.textContent = n.message;
        notifList.appendChild(item);
      });

      notifCount.textContent = data.length;
      notifCount.style.display = "inline-block";
    }
  } catch (err) {
    console.error("Notif fetch error:", err);
  }
}

// Initial load + auto-refresh every 15s
document.addEventListener("DOMContentLoaded", () => {
  loadNotifications();
  setInterval(loadNotifications, 15000);
});

// ===== Notification Dropdown Toggle + Mark as Read =====
const notifBtn = document.getElementById("notifDropdownBtn");
const notifMenu = document.getElementById("notifDropdown");
const notifCount = document.getElementById("notifCount");

notifBtn?.addEventListener("click", async (e) => {
  e.stopPropagation();
  notifMenu.classList.toggle("show");

  // ✅ mark as read
  try {
    const res = await fetch("mark_read.php", { method: "POST" });
    if (res.ok) {
      notifCount.textContent = "0";
      notifCount.style.display = "none";
    }
  } catch (err) {
    console.error("Failed to mark notifications as read:", err);
  }
});

document.addEventListener("click", (e) => {
  if (!notifMenu.contains(e.target) && e.target !== notifBtn) {
    notifMenu.classList.remove("show");
  }
});
