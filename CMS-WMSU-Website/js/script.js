$(document).ready(() => {
  // Your existing page loading code
  var savedPage = localStorage.getItem("activePage")

  // Only try to load the saved page if it exists
  if (savedPage) {
    loadPage(savedPage)
  }
  // Don't add an else clause here - let the specific sections handle their default pages

  $(".dynamic-load").click(function (e) {
    e.preventDefault()
    var file = $(this).data("file")
    localStorage.setItem("activePage", file)
    loadPage(file)
  })

  function loadPage(file) {
    $(".dynamic-load").removeClass("active")
    $(".dynamic-load[data-file='" + file + "']").addClass("active")

    $.ajax({
      url: file,
      type: "GET",
      success: (response) => {
        $("#main-content-section").html(response)

        // Reinitialize carousel if exists
        if ($("#main-content-section").find("#carouselExampleSlidesOnly").length > 0) {
          $("#carouselExampleSlidesOnly").carousel()
        }

        // Initialize form handlers for the new content
        initFormHandlers()
      },
      error: () => {
        $("#main-content-section").html("<p style='color:red;'>Failed to load content.</p>")
      },
    })
  }
  initFormHandlers()

  function initFormHandlers() {
    // Check if jQuery is loaded
    if (typeof jQuery == "undefined") {
      console.error("jQuery is not loaded. Please ensure jQuery is included in your HTML.")
      return // Exit the function if jQuery is not loaded
    }

    $(document)
      .off("submit", "form.status-form")
      .on("submit", "form.status-form", function (e) {
        e.preventDefault()
        var form = $(this)
        var isSuspend = form.find('[name="suspend_account"]').length > 0
        var managerId = form.find('[name="manager_id"]').val()

        if (!managerId) {
          alert("Error: Invalid account ID")
          return false
        }

        // Confirm before taking action
        if (isSuspend && !confirm("Are you sure you want to suspend this account?")) {
          return false
        }

        if (!isSuspend && !confirm("Are you sure you want to reactivate this account?")) {
          return false
        }

        // Create FormData object
        var formData = new FormData(form[0])

        // Log what we're sending for debugging
        console.log("Submitting form with manager_id:", managerId)
        console.log("Action:", isSuspend ? "suspend" : "reactivate")

        // Show a loading indicator on the button
        var button = form.find('button[type="submit"]')
        var originalButtonText = button.html()
        button.html("Processing...").prop("disabled", true)

        $.ajax({
          url: "../page-functions/update-account-status.php", // Use the dedicated script
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          dataType: "json",
          headers: {
            "X-Requested-With": "XMLHttpRequest",
          },
          success: (response) => {
            // Reset button state
            button.html(originalButtonText).prop("disabled", false)

            console.log("Server Response:", response)
            if (response.success) {
              var card = form.closest(".bg-white")
              var statusBadge = card.find(".inline-flex.items-center")

              if (response.newStatus === 0) {
                // Update to suspended state
                statusBadge
                  .removeClass("bg-green-100 text-green-800")
                  .addClass("bg-red-100 text-red-800")
                  .html(`
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Suspended
                `)

                // Update form to show reactivate button
                form.html(`
                <input type="hidden" name="manager_id" value="${managerId}">
                <input type="hidden" name="reactivate_account" value="1">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Reactivate Account
                </button>
              `)
              } else {
                // Update to active state
                statusBadge
                  .removeClass("bg-red-100 text-red-800")
                  .addClass("bg-green-100 text-green-800")
                  .html(`
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Active
                `)

                // Update form to show suspend button
                form.html(`
                <input type="hidden" name="manager_id" value="${managerId}">
                <input type="hidden" name="suspend_account" value="1">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                  </svg>
                  Suspend Account
                </button>
              `)
              }

              // Show success message
              alert(response.message)
            } else {
              // Show error message
              alert("Error: " + (response.message || "Failed to update account status"))
            }
          },
          error: (xhr, status, error) => {
            // Reset button state
            button.html(originalButtonText).prop("disabled", false)

            console.error("AJAX Error:", status, error)
            console.log("Response Text:", xhr.responseText)

            // Check if the response is HTML (which might indicate a session timeout)
            if (xhr.responseText && xhr.responseText.indexOf("<!DOCTYPE html>") !== -1) {
              console.error("Server returned HTML instead of JSON. Possible session timeout.")

              if (xhr.responseText.indexOf("login-form.php") !== -1) {
                alert("Your session has expired. Please refresh the page and log in again.")
              } else {
                alert("Server error: The request could not be processed. Please refresh the page and try again.")
              }
            } else {
              // Try to parse the response as JSON
              try {
                var responseObj = JSON.parse(xhr.responseText)
                alert("Error: " + (responseObj.message || "Failed to update account status"))
              } catch (e) {
                alert("An error occurred while updating the account status: " + error)
              }
            }
          },
        })
      })
  }

  // Function to handle dynamic page loading
  // function loadPage(file) {  // Removed duplicate function declaration
  //   $(".dynamic-load").removeClass("active");
  //   $(".dynamic-load[data-file='" + file + "']").addClass("active");

  //   $.ajax({
  //     url: file,
  //     type: "GET",
  //     success: function (response) {
  //       $("#main-content-section").html(response);

  //       // Reinitialize carousel if exists
  //       if (
  //         $("#main-content-section").find("#carouselExampleSlidesOnly").length >
  //         0
  //       ) {
  //         $("#carouselExampleSlidesOnly").carousel();
  //       }

  //       // Initialize form handlers for the new content
  //       initFormHandlers();
  //     },
  //     error: function () {
  //       $("#main-content-section").html(
  //         "<p style='color:red;'>Failed to load content.</p>"
  //       );
  //     },
  //   });
  // }

  // Initialize form handlers when page first loads
  //initFormHandlers();

  // Open the first modal when clicking "Add Elements"
  $(document).on("click", ".add-modal", function (e) {
    e.preventDefault()
    var sectionName = $(this).data("section")
    var allowedElements = $(this).data("allowed-elements")
    if (typeof allowedElements === "string") {
      allowedElements = JSON.parse(allowedElements)
    }
    $.ajax({
      url: "../modals/add_modal.php",
      type: "POST",
      data: {
        section: sectionName,
        allowedElements: JSON.stringify(allowedElements),
      }, // Fix: Proper JSON encoding
      success: (response) => {
        $("#modalContent").html(response)
        $("#addModal").modal("show") // Open first modal
      },
      error: () => {
        alert("Error loading first modal.")
      },
    })
  })

  // Open the second modal inside the first modal
  $(document).on("click", ".open-second-modal", function () {
    var sectionName = $(this).data("section")
    var elementType = $(this).data("type")
    var elementDesc = $(this).data("desc")

    $.ajax({
      url: "../modals/add_content_modal.php",
      type: "POST",
      data: {
        section: sectionName,
        elementType: elementType,
        elementDesc: elementDesc,
      }, // Fix: Matching PHP variable names
      success: (response) => {
        $("#modalContent").html(response) // Replace modal content
      },
      error: () => {
        alert("Error loading second modal.")
      },
    })
  })

  // Save element (either content or imagePath) when clicking "Save"
  $(document)
    .off("click", "#saveElement")
    .on("click", "#saveElement", () => {
      const elementType = $("#elementForm").data("type")
      const value = $("#elementInput").val()
      const indicator = $("#indicator").val()
      const description = $("#description").val()
      const pageID = $("#pageID").val()
      const subpageID = $("#subpageID").val()

      if (!indicator || !description) {
        console.error("Missing indicator or description!")
        return
      }

      $.ajax({
        url: "../functions/save_content.php",
        type: "POST",
        data: {
          elementType: elementType,
          value: value.toString(),
          indicator: indicator,
          description: description,
          pageID: pageID,
          subpageID: subpageID,
        },
        dataType: "json",
        success: (response) => {
          console.log("Server Response:", response)
          if (response.status === "success") {
            alert("Element saved successfully!")

            // Get current active page and force reload
            var currentPage = $(".dynamic-load.active").data("file")
            if (currentPage) {
              loadPage(currentPage)
            } else {
              location.reload()
            }
          } else {
            alert("Error: " + response.message)
          }
        },
        error: (xhr, status, error) => {
          console.error("AJAX Error:", status, error, xhr.responseText)
          alert("An error occurred while saving the element.")
        },
      })
    })

  $(document).on("submit", "#editLogoForm", function (e) {
    e.preventDefault() // Prevent default form submission

    var formData = new FormData(this) // Get form data
    var currentPage = $(".dynamic-load.active").data("file") // Get the currently loaded page

    $.ajax({
      url: "../page-functions/uploadLogo.php", // PHP file handling upload
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json", // Expect JSON response
      success: (response) => {
        if (response.success) {
          alert("Logo updated successfully!")

          // Reload the current dynamic page
          if (currentPage) {
            loadPage(currentPage)
          }
        } else {
          alert("Error: " + response.message)
        }
      },
      error: () => {
        alert("Upload failed. Try again.")
      },
    })
  })
  $(document).on("submit", "form[id^='profileImgForm-']", function (e) {
    e.preventDefault()

    var formData = new FormData(this)
    var currentPage = $(".dynamic-load.active").data("file")
    var form = $(this) // Store reference to the form
    formData.append("imageIndex", form.find("input[name='imageIndex']").val())

    $.ajax({
      url: "../page-functions/uploadProfileImgs.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: (response) => {
        if (response.success) {
          alert("Image updated successfully!")

          // Update the image **inside the same form**
          form.find("img").attr("src", response.newPath)

          // Reload the dynamic page (optional, only if necessary)
          if (currentPage) {
            loadPage(currentPage)
          }
        } else {
          alert("Error: " + response.message)
        }
      },
      error: () => {
        alert("Upload failed. Try again.")
      },
    })
  })

  $(document).on("submit", "form[id^='departmentForm-']", function (e) {
    e.preventDefault()

    var formData = new FormData(this)
    var form = $(this)
    var sectionID = form.attr("id").split("-")[1]
    var textID = $(this).find("input[name='deptName']").data("textid")

    formData.append("sectionID", sectionID)
    formData.append("textID", textID)

    // Check if a file has been selected for upload
    var fileInput = form.find("input[name='deptImg']")
    var fileSelected = fileInput[0].files.length > 0

    // If no file is selected, don't append the file to the FormData
    if (!fileSelected) {
      formData.delete("deptImg")
    }

    var currentPage = $(".dynamic-load.active").data("file")

    $.ajax({
      url: "../page-functions/uploadDeptImgs.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: (response) => {
        if (response.success) {
          // If the response contains a new image path
          if (response.newPath) {
            alert("update successful!")
          } else {
            // If only the department name is updated
            alert("Department name updated successfully!")
          }

          // Reload the dynamic page (optional)
          if (currentPage) {
            loadPage(currentPage)
          }
        } else {
          alert("Error: " + response.message)
        }
      },
      error: () => {
        alert("Upload failed. Try again.")
      },
    })
  })

  $(document).on("submit", "#editnameForm", function (e) {
    e.preventDefault() // Prevent default form submission

    console.log("Form Submitted!") // Add a log to check if the event triggers

    var formData = new FormData(this) // Create FormData from the form
    var textID = $(this).find("input[name='collegeName']").data("textid") // Get textID from the form
    console.log("TextID:", textID) // Log textID to check if it's correct

    formData.append("textID", textID) // Append textID to the form data

    var currentPage = $(".dynamic-load.active").data("file") // Get the current active page from the dynamic content

    $.ajax({
      url: "../page-functions/updateCollegeName.php", // PHP file handling the form submission
      type: "POST",
      data: formData,
      contentType: false, // Don't set content-type header, as we are sending FormData
      processData: false, // Prevent jQuery from transforming the data into a query string
      dataType: "json", // Expecting JSON response
      success: (response) => {
        console.log("Response:", response) // Log server response to check if it worked

        if (response.success) {
          alert("College Name updated successfully!") // Success message

          // Reload the current dynamic page content to reflect the changes
          if (currentPage) {
            loadPage(currentPage)
          }
        } else {
          alert("Error: " + response.message) // Show error if something went wrong
        }
      },
      error: () => {
        alert("Update Failed. Try again.") // Handle errors
      },
    })
  })

  $(document).on("submit", "form[id$='-items']", function (e) {
    e.preventDefault()

    var formData = new FormData(this)
    var courseTitle = $(this).find("input.courseTitle").val()
    var titleSectionID = $(this).find("input.courseTitle").data("titlesectionid")
    var currentPage = $(".dynamic-load.active").data("file")

    formData.append("courseTitle", courseTitle)
    formData.append("titleSectionID", titleSectionID)

    // Collect all outcomes
    $(this)
      .find(".outcomes-container input[type='text']")
      .each(function (index) {
        var outcomeValue = $(this).val()
        var isNew = $(this).data("is-new") || false
        var sectionID = $(this).data("sectionid") || null

        formData.append("outcomes[" + index + "][content]", outcomeValue)
        formData.append("outcomes[" + index + "][isNew]", isNew)
        if (sectionID) {
          formData.append("outcomes[" + index + "][sectionID]", sectionID)
        }
      })

    $.ajax({
      url: "../page-functions/updateCourse.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: (response) => {
        if (response.success) {
          alert("Course updated successfully!")
          // Force a complete reload of the current page
          if (currentPage) {
            loadPage(currentPage)
          } else {
            location.reload()
          }
        } else {
          alert(
            "Error: " +
              (response.message || "Update failed") +
              (response.errors ? "\nDetails: " + response.errors.join(", ") : ""),
          )
        }
      },
      error: (xhr, status, error) => {
        alert("Update Failed: " + error)
      },
    })
  })

  $(document).on("submit", "form[id^='overviewImg-']", function (e) {
    e.preventDefault()

    var formData = new FormData(this)
    var currentPage = $(".dynamic-load.active").data("file")
    var form = $(this) // Store reference to the form
    formData.append("imageIndex", form.find("input[name='overviewImgIndex']").val())

    $.ajax({
      url: "../page-functions/uploadOverviewImg.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: (response) => {
        if (response.success) {
          alert("Image updated successfully!")

          // Update the image **inside the same form**
          form.find("img").attr("src", response.newPath)

          // Reload the dynamic page (optional, only if necessary)
          if (currentPage) {
            loadPage(currentPage)
          }
        } else {
          alert("Error: " + response.message)
        }
      },
      error: () => {
        alert("Upload failed. Try again.")
      },
    })
  })

  $(document).on("submit", "form[id$='-overviewItems']", function (e) {
    e.preventDefault()

    console.log("Form Submitted!")

    var formData = new FormData(this)
    var form = $(this)
    var overviewTitle = form.find("input.overviewTitle").val()
    var overviewSectionID = form.find("input.overviewTitle").data("overviewsectionid")

    formData.append("overviewTitle", overviewTitle)
    formData.append("overviewSectionID", overviewSectionID)

    // Collect overview top content
    form.find(".overview-top-content").each(function () {
      var topContent = $(this).val()
      var sectionID = $(this).data("sectionid")
      formData.append("overviewTopContent", topContent)
      formData.append("topContentSectionID", sectionID)
    })

    // Collect outcomes with new items marked
    const outcomesArray = []
    form.find("input[type='text'][name$='-outcomes']").each(function () {
      outcomesArray.push({
        content: $(this).val(),
        sectionID: $(this).data("sectionid"),
        isNew: $(this).data("sectionid") < 0, // Mark if negative (new item)
      })
    })

    formData.append("outcomes", JSON.stringify(outcomesArray))

    console.log("Final FormData:")
    for (const [key, value] of formData.entries()) {
      console.log(`${key}: ${value}`)
    }

    var currentPage = $(".dynamic-load.active").data("file")

    $.ajax({
      url: "../page-functions/updateOverviewItem.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: (response) => {
        console.log("Server Response:", response)

        if (response.success) {
          alert("Overview updated successfully!")
          if (currentPage) {
            loadPage(currentPage)
          }
        } else {
          console.error("Update failed:", response.errors || response.message)
          alert("Error: " + (response.message || "Something went wrong. Check console for details."))
        }
      },
      error: (xhr, status, error) => {
        console.error("AJAX error:", error, "Response Text:", xhr.responseText)
        alert("Update Failed. Try again.")
      },
    })
  })

  $(document).on("click", ".remove-outcome", function () {
    const button = $(this)
    const listItem = button.closest("li")
    const currentPage = $(".dynamic-load.active").data("file")

    // For new items (not yet saved to DB)
    if (button.data("is-new") || !button.data("sectionid")) {
      listItem.remove()
      return
    }

    // Existing items need server deletion
    if (confirm("Are you sure you want to delete this outcome permanently?")) {
      $.ajax({
        url: "../page-functions/removeItem.php",
        type: "POST",
        data: {
          sectionID: button.data("sectionid"),
          currentPage: currentPage,
        },
        success: (response) => {
          console.log("Server Response:", response)

          if (response.success) {
            alert("Deleted successfully!")
            if (currentPage) {
              loadPage(currentPage)
            }
          } else {
            console.error("Update failed:", response.errors || response.message)
            alert("Error: " + (response.message || "Something went wrong. Check console for details."))
          }
        },
      })
    }
  })

  $(document).on("click", ".add-outcome", function (e) {
    e.preventDefault()
    const form = $(this).closest(".overview-form")
    const outcomesList = form.find(".outcomes-list")
    const formName = form.attr("name")
    const nextIndex = outcomesList.find("li").length + 1

    // Generate a temporary ID for new items (negative number)
    const tempSectionID = -Math.floor(Math.random() * 1000000)

    const newOutcome = $(`
            <li>
                <input type="text" 
                       name="${formName}-${nextIndex}-outcomes" 
                       id="${formName}-${nextIndex}-outcomes" 
                       data-sectionid="${tempSectionID}" 
                       value="">
                <button type="button" class="remove-outcome btn btn-danger" data-sectionid="${tempSectionID}">×</button>
            </li>
        `)

    outcomesList.append(newOutcome)
  })

  $(document).on("click", ".courses-item-container .add-outcome", function (e) {
    e.preventDefault()
    const form = $(this).closest("form")
    const outcomesContainer = form.find(".outcomes-container")
    const formName = form.attr("name")
    const nextIndex = outcomesContainer.find("li").length + 1

    const newOutcome = $(`
            <li>
                <input type="text" 
                       name="${formName}-outcomes-${nextIndex}" 
                       id="${formName}-outcomes-${nextIndex}" 
                       data-is-new="true" 
                       value="">
                <button type="button" class="remove-outcome btn btn-danger">×</button>
            </li>
        `)

    outcomesContainer.append(newOutcome)
  })

  // Account Management Form Submissions

  // Profile Picture Form
  $(document).on("submit", "#profilePictureForm", function (e) {
    e.preventDefault()

    var formData = new FormData(this)
    formData.append("formType", "profilePicture")

    $.ajax({
      url: "../page-functions/updateAccount.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: (response) => {
        if (response.success) {
          alert(response.message)
          // Update the profile picture preview
          if (response.newPath) {
            $("#profilePreview").attr("src", response.newPath)
          }
        } else {
          alert("Error: " + response.message)
        }
      },
      error: (xhr, status, error) => {
        console.error("AJAX Error:", status, error, xhr.responseText)
        alert("An error occurred while updating your profile picture.")
      },
    })
  })

  // Personal Information Form
  $(document).on("submit", "#personalInfoForm", function (e) {
    e.preventDefault()

    var formData = new FormData(this)
    formData.append("formType", "personalInfo")

    $.ajax({
      url: "../page-functions/updateAccount.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: (response) => {
        if (response.success) {
          alert(response.message)
        } else {
          alert("Error: " + response.message)
        }
      },
      error: (xhr, status, error) => {
        console.error("AJAX Error:", status, error, xhr.responseText)
        alert("An error occurred while updating your personal information.")
      },
    })
  })

  // Email Form
  $(document).on("submit", "#emailForm", function (e) {
    e.preventDefault()

    var formData = new FormData(this)
    formData.append("formType", "email")

    $.ajax({
      url: "../page-functions/updateAccount.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: (response) => {
        if (response.success) {
          alert(response.message)
        } else {
          alert("Error: " + response.message)
        }
      },
      error: (xhr, status, error) => {
        console.error("AJAX Error:", status, error, xhr.responseText)
        alert("An error occurred while updating your email.")
      },
    })
  })

  // Password Form
  $(document).on("submit", "#passwordForm", function (e) {
    e.preventDefault()

    var formData = new FormData(this)
    formData.append("formType", "password")

    $.ajax({
      url: "../page-functions/updateAccount.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: (response) => {
        if (response.success) {
          alert(response.message)
          // Clear password fields on success
          $("#currentPassword, #newPassword, #confirmPassword").val("")
        } else {
          alert("Error: " + response.message)
        }
      },
      error: (xhr, status, error) => {
        console.error("AJAX Error:", status, error, xhr.responseText)
        alert("An error occurred while updating your password.")
      },
    })
  })

  // Add this code after the existing event handlers for adding new departments and courses

  // Handle "Add New Department" button click with AJAX
  $(document).on("click", "#addNewDepartment", function (e) {
    e.preventDefault()

    // Show loading indicator
    $(this).html(
      '<div class="spinner-border spinner-border-sm text-light" role="status"><span class="visually-hidden">Loading...</span></div> Adding...',
    )
    $(this).prop("disabled", true)

    var currentPage = $(".dynamic-load.active").data("file")

    $.ajax({
      url: "../page-functions/addDepartment.php",
      type: "POST",
      data: { addNewDepartment: 1 },
      dataType: "json",
      success: (response) => {
        if (response.success) {
          alert(response.message)
          // Load the departments page without full page reload
          if (currentPage) {
            loadPage(currentPage)
          } else if (response.redirect) {
            loadPage(response.redirect)
          }
        } else {
          alert("Error: " + response.message)
          // Reset button
          $("#addNewDepartment").html(
            '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg><span class="font-medium">Add New Department</span>',
          )
          $("#addNewDepartment").prop("disabled", false)
        }
      },
      error: () => {
        alert("An error occurred. Please try again.")
        // Reset button
        $("#addNewDepartment").html(
          '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg><span class="font-medium">Add New Department</span>',
        )
        $("#addNewDepartment").prop("disabled", false)
      },
    })
  })

  // Handle "Add New Course" buttons with AJAX
  $(document).on("click", "#addNewUndergradCourse, #addNewGradCourse", function (e) {
    e.preventDefault()

    // Show loading indicator
    $(this).html(
      '<div class="spinner-border spinner-border-sm text-light" role="status"><span class="visually-hidden">Loading...</span></div> Adding...',
    )
    $(this).prop("disabled", true)

    var courseType = $(this).attr("id") === "addNewUndergradCourse" ? "undergrad" : "grad"
    var currentPage = $(".dynamic-load.active").data("file")

    $.ajax({
      url: "../page-functions/addCourse.php",
      type: "POST",
      data: { courseType: courseType },
      dataType: "json",
      success: (response) => {
        if (response.success) {
          alert(response.message)
          // Load the courses page without full page reload
          if (currentPage) {
            loadPage(currentPage)
          } else if (response.redirect) {
            loadPage(response.redirect)
          }
        } else {
          alert("Error: " + response.message)
          // Reset button
          var buttonHtml =
            '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg><span class="font-medium">Add New ' +
            (courseType === "undergrad" ? "Undergraduate" : "Graduate") +
            " Course</span>"
          $(e.target).html(buttonHtml)
          $(e.target).prop("disabled", false)
        }
      },
      error: () => {
        alert("An error occurred. Please try again.")
        // Reset button
        var buttonHtml =
          '<svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg><span class="font-medium">Add New ' +
          (courseType === "undergrad" ? "Undergraduate" : "Graduate") +
          " Course</span>"
        $(e.target).html(buttonHtml)
        $(e.target).prop("disabled", false)
      },
    })
  })
})
