document.addEventListener("DOMContentLoaded", () => {
  // Elements
  const stylePanel = document.getElementById("style-panel");
  const closeStylePanelBtn = document.getElementById("close-style-panel");
  const currentElementName = document.getElementById("current-element-name");
  const currentSectionId = document.getElementById("current-section-id");
  const resetStylesBtn = document.getElementById("reset-styles");
  const styleOptions = document.querySelectorAll(".style-option");
  const fontSelect = document.getElementById("style-font");
  const mainContent = document.querySelector(".main-content");

  // State
  let selectedElement = null;
  let isPanelOpen = false;
  let currentStyles = {}; // Declare currentStyles

  // Functions
  function openPanel() {
    stylePanel.classList.remove("translate-x-full");
    stylePanel.classList.add("translate-x-0");

    // Push content to the left
    mainContent.style.marginRight = "320px"; // 80px (panel width) + padding
    isPanelOpen = true;
  }

  function closePanel() {
    stylePanel.classList.remove("translate-x-0");
    stylePanel.classList.add("translate-x-full");

    // Reset content margin
    mainContent.style.marginRight = "0";
    isPanelOpen = false;

    // Deselect element
    if (selectedElement) {
      selectedElement.classList.remove("style-editing");
      selectedElement = null;
    }

    // Reset state
    currentElementName.textContent = "None selected";
    currentSectionId.value = "";
    currentStyles = {};
  }

  function selectElement(element, sectionId, elementName) {
    // Deselect previously selected element
    if (selectedElement) {
      selectedElement.classList.remove("style-editing");
    }

    // Select new element
    selectedElement = element;
    selectedElement.classList.add("style-editing");

    // Update panel info
    currentElementName.textContent = elementName || "Element #" + sectionId;
    currentSectionId.value = sectionId;

    // Load current styles
    loadElementStyles(sectionId);

    // Open panel
    openPanel();
  }

  function loadElementStyles(sectionId) {
    // Reset style selections
    resetStyleSelections();

    // Fetch current styles from server
    fetch("../page-functions/update-element-style.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        section_id: sectionId,
        action: "get",
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success && data.styles) {
          // Apply current style selections in the panel
          applyStyleSelections(data.styles);
        }
      })
      .catch((error) => {
        console.error("Error loading styles:", error);
      });
  }

  function resetStyleSelections() {
    // Reset font select
    if (fontSelect) {
      fontSelect.value = "";
    }

    // Reset style options
    styleOptions.forEach((option) => {
      option.classList.remove("ring-2", "ring-primary");
    });

    currentStyles = {};
  }

  function applyStyleSelections(stylesString) {
    if (!stylesString) return;

    const styles = stylesString.split(" ");

    // Apply font selection
    if (fontSelect) {
      const fontStyle = styles.find((style) => style.startsWith("font-["));
      if (fontStyle) {
        fontSelect.value = fontStyle;
      }
    }

    // Apply other style selections
    styleOptions.forEach((option) => {
      const value = option.dataset.value;
      if (styles.includes(value)) {
        option.classList.add("ring-2", "ring-primary");
      }
    });
  }

  function saveElementStyle(sectionId, category, value) {
    fetch("../page-functions/update-element-style.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        section_id: sectionId,
        style_category: category,
        style_value: value,
        action: "save",
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Update element preview classes
          updateElementPreview(sectionId);
        } else {
          console.error("Error saving style:", data.message);
        }
      })
      .catch((error) => {
        console.error("Error saving style:", error);
      });
  }

  function resetAllStyles() {
    const sectionId = currentSectionId.value;

    if (!sectionId) {
      return;
    }

    fetch("../page-functions/update-element-style.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        section_id: sectionId,
        action: "reset",
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Reset style selections
          resetStyleSelections();

          // Update element preview
          updateElementPreview(sectionId);
        } else {
          console.error("Error resetting styles:", data.message);
        }
      })
      .catch((error) => {
        console.error("Error resetting styles:", error);
      });
  }

  function updateElementPreview(sectionId) {
    // Fetch updated styles
    fetch("../page-functions/update-element-style.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        section_id: sectionId,
        action: "get",
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Find all preview elements for this section
          const previewElements = document.querySelectorAll(
            `.preview-element[data-section-id="${sectionId}"]`
          );

          previewElements.forEach((element) => {
            // Remove all style classes
            const classesToRemove = element.className
              .split(" ")
              .filter(
                (cls) =>
                  cls.startsWith("font-") ||
                  cls.startsWith("text-") ||
                  cls.startsWith("bg-") ||
                  cls.startsWith("p-") ||
                  cls.startsWith("m-") ||
                  cls.startsWith("border") ||
                  cls.startsWith("rounded")
              );

            classesToRemove.forEach((cls) => {
              element.classList.remove(cls);
            });

            // Add new style classes
            if (data.styles) {
              data.styles.split(" ").forEach((cls) => {
                if (cls) element.classList.add(cls);
              });
            }
          });
        }
      })
      .catch((error) => {
        console.error("Error updating preview:", error);
      });
  }

  // Event Listeners
  if (closeStylePanelBtn) {
    closeStylePanelBtn.addEventListener("click", closePanel);
  }

  // Style option click events
  styleOptions.forEach((option) => {
    option.addEventListener("click", function () {
      const category = this.dataset.category;
      const value = this.dataset.value;
      const sectionId = currentSectionId.value;

      if (!sectionId) {
        return;
      }

      // Toggle selection
      const isSelected = this.classList.contains("ring-2");

      // Remove selection from other options in the same category
      document
        .querySelectorAll(`.style-option[data-category="${category}"]`)
        .forEach((opt) => {
          opt.classList.remove("ring-2", "ring-primary");
        });

      // If not already selected, select this option and save
      if (!isSelected) {
        this.classList.add("ring-2", "ring-primary");
        saveElementStyle(sectionId, category, value);
      } else {
        // If already selected, deselect and remove the style
        saveElementStyle(sectionId, category, "");
      }
    });
  });

  // Font select change event
  if (fontSelect) {
    fontSelect.addEventListener("change", function () {
      const value = this.value;
      const sectionId = currentSectionId.value;

      if (!sectionId) {
        return;
      }

      saveElementStyle(sectionId, "font", value);
    });
  }

  // Reset styles button
  if (resetStylesBtn) {
    resetStylesBtn.addEventListener("click", resetAllStyles);
  }

  // Make elements styleable
  document.addEventListener("click", (e) => {
    // Check if the clicked element or its parent has the 'styleable' class
    const styleableElement = e.target.closest(".styleable");

    if (styleableElement) {
      e.preventDefault();
      e.stopPropagation();

      const sectionId = styleableElement.dataset.sectionId;
      const elementName = styleableElement.dataset.elementName;

      if (sectionId) {
        selectElement(styleableElement, sectionId, elementName);
      }
    }
  });

  // Close panel when clicking outside
  document.addEventListener("click", (e) => {
    if (!stylePanel || stylePanel.classList.contains("translate-x-full")) {
      return;
    }

    if (
      !stylePanel.contains(e.target) &&
      !e.target.closest(".styleable") &&
      e.target !== closeStylePanelBtn
    ) {
      closePanel();
    }
  });

  // Add keyboard shortcut (Alt+S) to toggle style panel
  document.addEventListener("keydown", (e) => {
    if (e.altKey && e.key === "s") {
      e.preventDefault();
      if (isPanelOpen) {
        closePanel();
      } else if (selectedElement) {
        openPanel();
      }
    }
  });
});
