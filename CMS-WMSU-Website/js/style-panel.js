document.addEventListener("DOMContentLoaded", () => {
  // Elements
  const stylePanel = document.getElementById("style-panel");
  const closeStylePanelBtn = document.getElementById("close-style-panel");
  const toggleStyleEditorBtn = document.getElementById("toggle-style-editor");
  const currentElementName = document.getElementById("current-element-name");
  const currentSectionId = document.getElementById("current-section-id");
  const resetStylesBtn = document.getElementById("reset-styles");
  const styleOptions = document.querySelectorAll(".style-option");
  const fontSelect = document.getElementById("style-font");

  // State
  let selectedElement = null;
  let currentStyles = {};
  let isEditMode = false;

  // Functions
  function openPanel() {
    stylePanel.classList.remove("translate-x-full");
    stylePanel.classList.add("translate-x-0");
  }

  function closePanel() {
    stylePanel.classList.remove("translate-x-0");
    stylePanel.classList.add("translate-x-full");

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

  function toggleEditMode() {
    isEditMode = !isEditMode;

    // Show/hide edit mode indicator
    const editModeIndicator = document.getElementById("edit-mode-indicator");
    if (editModeIndicator) {
      if (isEditMode) {
        editModeIndicator.classList.remove("hidden");
      } else {
        editModeIndicator.classList.add("hidden");
        closePanel();
      }
    }

    // Add/remove edit mode class to body
    if (isEditMode) {
      document.body.classList.add("style-edit-mode");
    } else {
      document.body.classList.remove("style-edit-mode");
    }
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
          currentStyles = data.styles;

          // Apply current style selections in the panel
          applyStyleSelections(currentStyles);
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

  function applyStyleSelections(styles) {
    // Apply font selection
    if (fontSelect && styles["font"]) {
      fontSelect.value = styles["font"];
    }

    // Apply other style selections
    styleOptions.forEach((option) => {
      const category = option.dataset.category;
      const value = option.dataset.value;

      if (styles[category] === value) {
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
          // Update current styles
          if (value) {
            currentStyles[category] = value;
          } else {
            delete currentStyles[category];
          }

          // Update element classes
          updateElementClasses();
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

          // Update element classes
          updateElementClasses();
        } else {
          console.error("Error resetting styles:", data.message);
        }
      })
      .catch((error) => {
        console.error("Error resetting styles:", error);
      });
  }

  function updateElementClasses() {
    if (!selectedElement) {
      return;
    }

    // Get all style categories
    const styleCategories = [
      "font",
      "text-size",
      "text-weight",
      "text-color",
      "bg-color",
      "padding",
      "margin",
      "border",
      "border-color",
      "border-radius",
    ];

    // Remove all style classes
    styleCategories.forEach((category) => {
      // Find classes that match this category pattern
      const regex = new RegExp(
        `(^|\\s)(${category}|${category}-[\\w-]+)(\\s|$)`,
        "g"
      );
      selectedElement.className = selectedElement.className.replace(regex, " ");
    });

    // Add current style classes
    Object.values(currentStyles).forEach((className) => {
      selectedElement.classList.add(className);
    });

    // Clean up extra spaces
    selectedElement.className = selectedElement.className
      .replace(/\s+/g, " ")
      .trim();
  }

  // Toggle edit mode with Alt+S key combination
  document.addEventListener("keydown", (e) => {
    if (e.altKey && e.key === "s") {
      e.preventDefault();
      toggleEditMode();
    }
  });

  // Event Listeners
  closeStylePanelBtn.addEventListener("click", closePanel);
  toggleStyleEditorBtn.addEventListener("click", () => {
    toggleEditMode();
    if (!isEditMode) {
      closePanel();
    }
  });

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
  resetStylesBtn.addEventListener("click", resetAllStyles);

  // Make elements styleable when in edit mode
  document.addEventListener("click", (e) => {
    if (!isEditMode) return;

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
    if (stylePanel.classList.contains("translate-x-full")) {
      return;
    }

    if (
      !stylePanel.contains(e.target) &&
      !e.target.closest(".styleable") &&
      e.target !== toggleStyleEditorBtn
    ) {
      closePanel();
    }
  });

  // Add edit mode indicator
  const editModeIndicator = document.createElement("div");
  editModeIndicator.id = "edit-mode-indicator";
  editModeIndicator.className =
    "fixed top-4 right-4 bg-primary text-white px-3 py-1 rounded-md shadow-lg z-50 hidden";
  editModeIndicator.textContent = "Style Edit Mode";
  document.body.appendChild(editModeIndicator);
});
