/**
 * Sidebar Preview System
 *
 * This script handles the sidebar-based live preview functionality.
 * It allows users to see a real-time preview of their content changes
 * before saving them to the database.
 */

document.addEventListener("DOMContentLoaded", () => {
  // Elements
  const previewSidebar = document.getElementById("preview-sidebar")
  const previewToggleBtn = document.getElementById("preview-toggle-btn")
  const togglePreviewSidebar = document.getElementById("toggle-preview-sidebar")
  const previewCloseBtn = document.querySelector(".preview-close-btn")
  const sidebarPageTypeSelector = document.getElementById("sidebar-page-type-selector")
  const sidebarRefreshPreviewBtn = document.getElementById("sidebar-refresh-preview-btn")
  const sidebarPreviewContent = document.getElementById("sidebar-preview-content")
  const sidebarEmptyPlaceholder = document.getElementById("sidebar-empty-preview-placeholder")

  // Initialize preview state
  let previewOpen = false
  const initialWidth = 400
  let currentWidth = initialWidth
  let isResizing = false
  let startX, startWidth

  // Try to detect the current page from URL
  const detectCurrentPage = () => {
    const pathParts = window.location.pathname.split("/")
    const availablePages = [
      "college-overview.php",
      "college-profile.php",
      "courses-offered.php",
      "departments.php",
      "shs.php",
    ]

    for (const part of pathParts) {
      if (availablePages.includes(part)) {
        return part
      }
    }

    // Check URL for specific keywords
    const url = window.location.href.toLowerCase()
    if (url.includes("college-profile")) {
      return "college-profile.php"
    } else if (url.includes("courses-offered")) {
      return "courses-offered.php"
    } else if (url.includes("departments")) {
      return "departments.php"
    } else if (url.includes("shs")) {
      return "shs.php"
    } else if (url.includes("college-overview")) {
      return "college-overview.php"
    }

    // Default to college overview
    return "college-overview.php"
  }

  // Set the initial page type in the selector
  const currentPage = detectCurrentPage()
  if (sidebarPageTypeSelector) {
    sidebarPageTypeSelector.value = currentPage
  }

  // Toggle preview sidebar
  const togglePreview = () => {
    previewOpen = !previewOpen

    if (previewOpen) {
      previewSidebar.classList.add("open")
      previewToggleBtn.classList.add("open")
      updateSidebarPreview()

      // Set the toggle button position based on current sidebar width
      previewToggleBtn.style.right = `${currentWidth}px`
    } else {
      previewSidebar.classList.remove("open")
      previewToggleBtn.classList.remove("open")
      previewToggleBtn.style.right = "0"
    }
  }

  // Event listeners for toggling the preview
  if (previewToggleBtn) {
    previewToggleBtn.addEventListener("click", togglePreview)
  }

  if (togglePreviewSidebar) {
    togglePreviewSidebar.addEventListener("click", togglePreview)
  }

  if (previewCloseBtn) {
    previewCloseBtn.addEventListener("click", togglePreview)
  }

  // Add event listeners to all form inputs to update preview on change
  document
    .querySelectorAll(
      'form[data-preview="true"] input, form[data-preview="true"] textarea, form[data-preview="true"] select',
    )
    .forEach((input) => {
      input.addEventListener("input", () => {
        markUnsavedChanges()
        if (previewOpen) {
          updateSidebarPreview()
        }
      })
    })

  // Add event listener for page type selector
  if (sidebarPageTypeSelector) {
    sidebarPageTypeSelector.addEventListener("change", () => {
      if (previewOpen) {
        updateSidebarPreview()
      }
    })
  }

  // Add event listener for refresh button
  if (sidebarRefreshPreviewBtn) {
    sidebarRefreshPreviewBtn.addEventListener("click", () => {
      if (previewOpen) {
        updateSidebarPreview()
      }
    })
  }

  // Add debug panel toggle (press Ctrl+Shift+D to toggle)
  document.addEventListener("keydown", (e) => {
    if (e.ctrlKey && e.shiftKey && e.key === "D") {
      const debugPanel = document.getElementById("sidebar-preview-debug-panel")
      if (debugPanel) {
        debugPanel.classList.toggle("hidden")
        e.preventDefault()
      }
    }
  })

  // Resize functionality
  if (previewSidebar) {
    // Mouse down on the resize handle (left edge of sidebar)
    previewSidebar.addEventListener("mousedown", (e) => {
      // Only trigger resize if clicking on the left 5px of the sidebar
      if (e.offsetX <= 5) {
        isResizing = true
        startX = e.clientX
        startWidth = Number.parseInt(getComputedStyle(previewSidebar).width, 10)

        document.body.style.cursor = "ew-resize"
        document.body.style.userSelect = "none"

        // Prevent text selection during resize
        e.preventDefault()
      }
    })

    // Mouse move to resize
    document.addEventListener("mousemove", (e) => {
      if (!isResizing) return

      const width = startWidth - (e.clientX - startX)

      // Apply min and max constraints
      if (width >= 300 && width <= 800) {
        currentWidth = width
        previewSidebar.style.width = `${width}px`

        // Update toggle button position
        if (previewOpen) {
          previewToggleBtn.style.right = `${width}px`
        }
      }
    })

    // Mouse up to stop resizing
    document.addEventListener("mouseup", () => {
      if (isResizing) {
        isResizing = false
        document.body.style.cursor = ""
        document.body.style.userSelect = ""
      }
    })
  }

  // Add JavaScript to handle the resizable preview sidebar
  // Add this to the document ready function or wherever appropriate

  // Update the toggle button position when the sidebar is resized
  if (previewSidebar && previewToggleBtn) {
    // Create a ResizeObserver to watch for changes to the sidebar width
    const resizeObserver = new ResizeObserver((entries) => {
      for (const entry of entries) {
        // When the sidebar is resized, update the toggle button position
        if (entry.target.classList.contains("open")) {
          const sidebarWidth = entry.contentRect.width
          previewToggleBtn.style.right = `${sidebarWidth}px`
        }
      }
    })

    // Start observing the sidebar
    resizeObserver.observe(previewSidebar)

    // Update toggle button behavior
    if (togglePreviewSidebar) {
      togglePreviewSidebar.addEventListener("click", () => {
        previewSidebar.classList.toggle("open")
        previewToggleBtn.classList.toggle("open")

        if (previewSidebar.classList.contains("open")) {
          const sidebarWidth = previewSidebar.offsetWidth
          previewToggleBtn.style.right = `${sidebarWidth}px`
        } else {
          previewToggleBtn.style.right = "0"
        }
      })
    }

    // Same for the toggle button itself
    previewToggleBtn.addEventListener("click", () => {
      previewSidebar.classList.toggle("open")
      previewToggleBtn.classList.toggle("open")

      if (previewSidebar.classList.contains("open")) {
        const sidebarWidth = previewSidebar.offsetWidth
        previewToggleBtn.style.right = `${sidebarWidth}px`
      } else {
        previewToggleBtn.style.right = "0"
      }
    })

    // And for the close button
    if (previewCloseBtn) {
      previewCloseBtn.addEventListener("click", () => {
        previewSidebar.classList.remove("open")
        previewToggleBtn.classList.remove("open")
        previewToggleBtn.style.right = "0"
      })
    }
  }

  // Add additional debugging for the sidebar preview
  function updateSidebarPreview() {
    if (!sidebarPreviewContent || !sidebarPageTypeSelector) return

    // Hide empty placeholder
    if (sidebarEmptyPlaceholder) {
      sidebarEmptyPlaceholder.style.display = "none"
    }

    // Get the selected page type
    const pageName = sidebarPageTypeSelector.value

    // Collect form data
    const formData = collectFormData()

    // Determine the correct path to generate-preview.php
    let previewEndpoint = "../page-functions/generate-preview.php"

    // If we're in a subdirectory, adjust the path
    if (window.location.pathname.includes("/page-views/")) {
      previewEndpoint = "../page-functions/generate-preview.php"
    } else if (window.location.pathname.includes("/pages/")) {
      previewEndpoint = "../page-functions/generate-preview.php"
    } else {
      // Try to determine the path based on the current URL
      const pathSegments = window.location.pathname.split("/").filter(Boolean)
      if (pathSegments.length > 0) {
        const adminIndex = pathSegments.indexOf("admin-side")
        if (adminIndex !== -1) {
          const depth = pathSegments.length - adminIndex - 1
          previewEndpoint = "../".repeat(depth) + "page-functions/generate-preview.php"
        }
      }
    }

    // Log debug information
    console.log("Preview endpoint:", previewEndpoint)
    console.log("Current path:", window.location.pathname)
    console.log("Selected page type:", pageName)
    console.log("Form data:", formData)

    // Show loading indicator
    sidebarPreviewContent.innerHTML = `
        <div class="flex justify-center items-center p-8">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary"></div>
            <span class="ml-3 text-gray-600">Loading preview...</span>
        </div>
    `

    // Request preview HTML from server
    fetch(previewEndpoint, {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        page: pageName,
        preview_data: JSON.stringify(formData),
      }),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Network response was not ok: ${response.status} ${response.statusText}`)
        }
        return response.json()
      })
      .then((data) => {
        // Update debug panel if it exists
        const debugContent = document.getElementById("sidebar-preview-debug-content")
        if (debugContent) {
          debugContent.innerHTML = "<pre>" + JSON.stringify(data.debug, null, 2) + "</pre>"
        }

        if (data.success) {
          // Update preview content
          sidebarPreviewContent.innerHTML = data.html
        } else {
          console.error("Error generating preview:", data.message)
          sidebarPreviewContent.innerHTML = `
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    <p>Error generating preview: ${data.message}</p>
                    <p class="mt-2 text-sm">Page requested: ${pageName}</p>
                    <p class="mt-2 text-sm">Preview endpoint: ${previewEndpoint}</p>
                    <p class="text-sm">Try pressing Ctrl+Shift+D to view debug information.</p>
                </div>
            `
        }
      })
      .catch((error) => {
        console.error("Error:", error)
        sidebarPreviewContent.innerHTML = `
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                <p>Error generating preview. Please try again.</p>
                <p class="mt-2 text-sm">Technical details: ${error.message}</p>
                <p class="mt-2 text-sm">Preview endpoint: ${previewEndpoint}</p>
            </div>
        `
      })
  }

  // Function to collect all form data
  function collectFormData() {
    const formData = {}
    const forms = document.querySelectorAll('form[data-preview="true"]')

    forms.forEach((form) => {
      const formId = form.id || form.getAttribute("name") || Math.random().toString(36).substring(7)
      formData[formId] = {}

      // Collect all input, select, and textarea values
      form.querySelectorAll("input, select, textarea").forEach((input) => {
        if (input.type === "file") return // Skip file inputs

        if (input.type === "checkbox" || input.type === "radio") {
          if (input.checked) {
            formData[formId][input.name] = input.value
          }
        } else {
          formData[formId][input.name] = input.value
        }
      })

      // Special handling for lists of items (like outcomes)
      const itemLists = form.querySelectorAll(".outcomes-list, .outcomes-container")
      itemLists.forEach((list) => {
        const items = []
        list.querySelectorAll('li input[type="text"]').forEach((input) => {
          items.push({
            content: input.value,
            sectionID: input.dataset.sectionid || "",
            isNew: input.dataset.isNew === "true" || false,
          })
        })

        if (items.length > 0) {
          formData[formId]["items"] = items
        }
      })
    })

    return formData
  }

  // Function to mark a form as having unsaved changes
  function markUnsavedChanges() {
    const previewStatus = document.getElementById("sidebar-preview-status")

    if (previewStatus) {
      previewStatus.innerHTML = `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Unsaved Changes
                </span>
            `
    }
  }

  // Function to mark all changes as saved
  window.markChangesSaved = () => {
    const previewStatus = document.getElementById("sidebar-preview-status")

    if (previewStatus) {
      previewStatus.innerHTML = `
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    All Changes Saved
                </span>
            `
    }
  }
})
