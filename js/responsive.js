// Responsive JavaScript for WMSU Website

document.addEventListener("DOMContentLoaded", () => {
  // Add responsive class to tables
  const tables = document.querySelectorAll("table");
  tables.forEach((table) => {
    table.classList.add("responsive-table");

    // Create a wrapper div for the table to enable horizontal scrolling on mobile
    const wrapper = document.createElement("div");
    wrapper.classList.add("table-responsive");
    table.parentNode.insertBefore(wrapper, table);
    wrapper.appendChild(table);
  });

  // Add responsive class to images
  const images = document.querySelectorAll("img:not(.logo):not(.icon)");
  images.forEach((img) => {
    img.classList.add("responsive-img");
    if (!img.hasAttribute("loading")) {
      img.setAttribute("loading", "lazy");
    }
  });

  // Handle responsive navigation
  const mobileMenuToggle = document.querySelector(".mobile-menu-toggle");
  const navMenu = document.querySelector(".nav-menu");

  if (mobileMenuToggle && navMenu) {
    mobileMenuToggle.addEventListener("click", function () {
      navMenu.classList.toggle("active");
      this.classList.toggle("active");
    });
  }

  // Handle responsive tabs in academics pages
  const tabButtons = document.querySelectorAll(".tab-button");
  const tabContents = document.querySelectorAll(".tab-content");

  tabButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const target = this.getAttribute("data-target");

      // Hide all tab contents
      tabContents.forEach((content) => {
        content.classList.remove("active");
      });

      // Deactivate all tab buttons
      tabButtons.forEach((btn) => {
        btn.classList.remove("active");
      });

      // Activate clicked tab button and its content
      this.classList.add("active");
      document.getElementById(target).classList.add("active");
    });
  });

  // Add responsive behavior to ESU campus cards
  const campusCards = document.querySelectorAll(".campus-card");
  if (campusCards.length > 0) {
    if (window.innerWidth < 768) {
      campusCards.forEach((card) => {
        card.addEventListener("click", function () {
          this.classList.toggle("expanded");
        });
      });
    }
  }

  // Make admission forms responsive
  const formGroups = document.querySelectorAll(".form-group");
  if (formGroups.length > 0 && window.innerWidth < 768) {
    formGroups.forEach((group) => {
      group.classList.add("mobile-form-group");
    });
  }

  // Handle window resize events
  window.addEventListener("resize", () => {
    if (window.innerWidth < 768) {
      document.body.classList.add("mobile-view");
    } else {
      document.body.classList.remove("mobile-view");
    }
  });

  // Trigger resize event once on load
  if (window.innerWidth < 768) {
    document.body.classList.add("mobile-view");
  }
});
