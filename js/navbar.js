const navbar = document.getElementById("navbar");

window.addEventListener("scroll", () => {
  if (window.scrollY > 0) {
    navbar.classList.remove("opacity-0");
    navbar.classList.add("opacity-100", "pointer-events-auto");
  }
});

navbar.addEventListener("mouseenter", () => {
  navbar.classList.remove("opacity-0");
  navbar.classList.add("opacity-100");
});

setTimeout(() => {
  navbar.classList.add(
    "opacity-100",
    "pointer-events-auto",
    "permanently-visible"
  );
  navbar.classList.remove("opacity-0");
}, 3000); // 3 second delay

// Toggle menu function
function toggleMenu() {
  const hamburgerButton = document.getElementById("hamburger-button");
  const drawer = document.getElementById("drawer-navigation");

  hamburgerButton.classList.toggle("opened");
  drawer.classList.toggle("show");

  // Set aria-expanded attribute
  const isExpanded = hamburgerButton.classList.contains("opened");
  hamburgerButton.setAttribute("aria-expanded", isExpanded);
}

// Close menu function
function closeMenu() {
  const hamburgerButton = document.getElementById("hamburger-button");
  const drawer = document.getElementById("drawer-navigation");

  hamburgerButton.classList.remove("opened");
  drawer.classList.remove("show");
  hamburgerButton.setAttribute("aria-expanded", "false");
}

// Close menu when clicking outside
document.addEventListener("click", (event) => {
  const drawer = document.getElementById("drawer-navigation");
  const hamburgerButton = document.getElementById("hamburger-button");

  if (
    !drawer.contains(event.target) &&
    !hamburgerButton.contains(event.target) &&
    drawer.classList.contains("show")
  ) {
    closeMenu();
  }
});

// Handle window resize
window.addEventListener("resize", () => {
  if (window.innerWidth > 1280) {
    // xl breakpoint
    closeMenu();
  }
});
