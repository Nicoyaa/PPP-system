document.addEventListener("DOMContentLoaded", () => {
  const content = document.querySelector(".content");
  const crumb = document.getElementById("crumb");
  const pageTitle = document.getElementById("pageTitle");

  // Sidebar toggle
  const menuBtn = document.querySelector(".menu-btn");
  const sidebar = document.querySelector(".sidebar");
  const overlay = document.querySelector(".overlay");

  if (menuBtn && sidebar && overlay) {
    menuBtn.addEventListener("click", () => {
      sidebar.classList.toggle("open");
      overlay.classList.toggle("show");
    });

    overlay.addEventListener("click", () => {
      sidebar.classList.remove("open");
      overlay.classList.remove("show");
    });
  }

  // Function to load a page
  function loadPage(page) {
    fetch(`pages/${page}.html`)
      .then(res => {
        if (!res.ok) throw new Error("Page not found");
        return res.text();
      })
      .then(data => {
        content.innerHTML = data;
        crumb.textContent = page.replace("-", " ").toUpperCase();
        pageTitle.textContent = page.replace("-", " ").toUpperCase();

        // Close sidebar on mobile after navigation
        sidebar.classList.remove("open");
        overlay.classList.remove("show");
      })
      .catch(() => {
        content.innerHTML = "<p class='text-danger'>Page not found.</p>";
      });
  }

  // Default load (Dashboard)
  loadPage("dashboard");

  // Sidebar click
  document.querySelectorAll(".nav-item[data-route]").forEach(item => {
    item.addEventListener("click", (e) => {
      e.preventDefault();

      const page = item.getAttribute("data-route");

      loadPage(page);

      // Active highlight
      document.querySelectorAll(".nav-item").forEach(nav => nav.classList.remove("active"));
      item.classList.add("active");
    });
  });
});
