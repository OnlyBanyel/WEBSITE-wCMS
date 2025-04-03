$(document).ready(function() {

       // Restore active page from localStorage on page load
       var savedPage = localStorage.getItem("activePage");
       if (savedPage) {
           loadPage(savedPage);
       }
   
       $(".dynamic-load").click(function (e) {
           e.preventDefault(); // Prevent default link behavior
           var file = $(this).data("file");
   
           // Save active page in localStorage
           localStorage.setItem("activePage", file);
   
           // Load the page
           loadPage(file);
       });
   
       function loadPage(file) {
           $(".dynamic-load").removeClass("active"); // Remove active class from all
           $(".dynamic-load[data-file='" + file + "']").addClass("active"); // Add active class
   
           $.ajax({
               url: file,
               type: "GET",
               success: function (response) {
                   $("#main-content-section").html(response);
                   // If carousel exists, reinitialize Bootstrap's carousel
                   if ($("#main-content-section").find("#carouselExampleSlidesOnly").length > 0) {
                       $("#carouselExampleSlidesOnly").carousel();
                   }
               },
               error: function () {
                   $("#main-content-section").html("<p style='color:red;'>Failed to load content.</p>");
               },
           });
       }
    

    // Open the first modal when clicking "Add Elements"
    $(document).on("click", ".add-modal", function(e) {
        e.preventDefault();
        var sectionName = $(this).data("section");
        var allowedElements = $(this).data("allowed-elements");
        if (typeof allowedElements === "string") {
            allowedElements = JSON.parse(allowedElements);
        }
        $.ajax({
            url: "../modals/add_modal.php",
            type: "POST",
            data: { section: sectionName, allowedElements: JSON.stringify(allowedElements) }, // Fix: Proper JSON encoding
            success: function(response) {
                $("#modalContent").html(response);
                $("#addModal").modal("show"); // Open first modal
            },
            error: function() {
                alert("Error loading first modal.");
            }
        });
    });

    // Open the second modal inside the first modal
    $(document).on("click", ".open-second-modal", function() {
        var sectionName = $(this).data("section");
        var elementType = $(this).data("type");
        var elementDesc = $(this).data("desc");

        $.ajax({
            url: "../modals/add_content_modal.php",
            type: "POST",
            data: { section: sectionName, elementType: elementType, elementDesc: elementDesc }, // Fix: Matching PHP variable names
            success: function(response) {
                $("#modalContent").html(response); // Replace modal content
            },
            error: function() {
                alert("Error loading second modal.");
            }
        });
    });

    // Save element (either content or imagePath) when clicking "Save"
    $(document).off("click", "#saveElement").on("click", "#saveElement", function () {
        let elementType = $("#elementForm").data("type");
        let value = $("#elementInput").val();
        let indicator = $("#indicator").val();
        let description = $("#description").val();
        let pageID = $('#pageID').val();
        let subpageID = $('#subpageID').val();
    
        if (!indicator || !description) {
            console.error("Missing indicator or description!");
            return;
        }
    
        console.log("Element Type:", elementType);
        console.log("Value:", value);
        console.log("Indicator:", indicator);
        console.log("Description:", description);
    
        $.ajax({
            url: "../functions/save_content.php",
            type: "POST",
            data: {
                
                elementType: elementType,
                value: value.toString(),
                indicator: indicator,
                description: description,
                pageID: pageID,
                subpageID: subpageID
            },
            dataType: "json",
            success: function (response) {
                console.log("Server Response:", response);
                if (response.status === "success") {
                    alert("Element saved successfully!");
                    location.reload();  
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error, xhr.responseText);
                alert("An error occurred while saving the element.");
            }
        });
    });

    $(document).on("submit", "#editLogoForm", function (e) {
        e.preventDefault(); // Prevent default form submission

        var formData = new FormData(this); // Get form data
        var currentPage = $(".dynamic-load.active").data("file"); // Get the currently loaded page
    
        $.ajax({
            url: "../page-functions/uploadLogo.php", // PHP file handling upload
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json", // Expect JSON response
            success: function (response) {
                if (response.success) {
                    alert("Logo updated successfully!");
    
                    // Reload the current dynamic page
                    if (currentPage) {
                        loadPage(currentPage);
                    }
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function () {
                alert("Upload failed. Try again.");
            },
        });
    });
    $(document).on("submit", "form[id^='profileImgForm-']", function (e) {
        e.preventDefault();
    
        var formData = new FormData(this);
        var currentPage = $(".dynamic-load.active").data("file");
        var form = $(this); // Store reference to the form
        formData.append("imageIndex", form.find("input[name='imageIndex']").val());
    
        $.ajax({
            url: "../page-functions/uploadProfileImgs.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert("Image updated successfully!");
    
                    // Update the image **inside the same form**
                    form.find("img").attr("src", response.newPath);
    
                    // Reload the dynamic page (optional, only if necessary)
                    if (currentPage) {
                        loadPage(currentPage);
                    }
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function () {
                alert("Upload failed. Try again.");
            },
        });
    });

    $(document).on("submit", "form[id^='departmentForm-']", function (e) {
        e.preventDefault();
    
        var formData = new FormData(this);
        var form = $(this);
        var sectionID = form.attr("id").split("-")[1];
        var textID = $(this).find("input[name='deptName']").data("textid");
    
        formData.append("sectionID", sectionID);
        formData.append("textID", textID);
    
        // Check if a file has been selected for upload
        var fileInput = form.find("input[name='deptImg']");
        var fileSelected = fileInput[0].files.length > 0;
    
        // If no file is selected, don't append the file to the FormData
        if (!fileSelected) {
            formData.delete("deptImg");
        }
    
        var currentPage = $(".dynamic-load.active").data("file");
    
        $.ajax({
            url: "../page-functions/uploadDeptImgs.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // If the response contains a new image path
                    if (response.newPath) {
                        alert("update successful!");
                    } else {
                        // If only the department name is updated
                        alert("Department name updated successfully!");
                    }
    
                    // Reload the dynamic page (optional)
                    if (currentPage) {
                        loadPage(currentPage);
                    }
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function () {
                alert("Upload failed. Try again.");
            },
        });
    });

    $(document).on('submit', "#editnameForm", function(e) {
        e.preventDefault(); // Prevent default form submission
    
        console.log("Form Submitted!"); // Add a log to check if the event triggers
        
        var formData = new FormData(this); // Create FormData from the form
        var textID = $(this).find("input[name='collegeName']").data("textid"); // Get textID from the form
        console.log("TextID:", textID); // Log textID to check if it's correct
    
        formData.append("textID", textID); // Append textID to the form data
    
        var currentPage = $(".dynamic-load.active").data("file"); // Get the current active page from the dynamic content
    
        $.ajax({
            url: "../page-functions/updateCollegeName.php", // PHP file handling the form submission
            type: 'POST',
            data: formData,
            contentType: false, // Don't set content-type header, as we are sending FormData
            processData: false, // Prevent jQuery from transforming the data into a query string
            dataType: "json", // Expecting JSON response
            success: function(response) {
                console.log("Response:", response); // Log server response to check if it worked
    
                if (response.success) {
                    alert("College Name updated successfully!"); // Success message
    
                    // Reload the current dynamic page content to reflect the changes
                    if (currentPage) {
                        loadPage(currentPage);
                    }
                } else {
                    alert("Error: " + response.message); // Show error if something went wrong
                }
            },
            error: function() {
                alert("Update Failed. Try again."); // Handle errors
            },
        });
    });

    $(document).on('submit', "form[id$='-items']", function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var courseTitle = $(this).find("input.courseTitle").val();
        var titleSectionID = $(this).find("input.courseTitle").data("titlesectionid");
        
        formData.append("courseTitle", courseTitle);
        formData.append("titleSectionID", titleSectionID);
        
        // Collect all outcomes
        $(this).find(".outcomes-container input[type='text']").each(function(index) {
            var outcomeValue = $(this).val();
            var isNew = $(this).data("is-new") || false;
            var sectionID = $(this).data("sectionid") || null;
            
            formData.append("outcomes[" + index + "][content]", outcomeValue);
            formData.append("outcomes[" + index + "][isNew]", isNew);
            if (sectionID) {
                formData.append("outcomes[" + index + "][sectionID]", sectionID);
            }
        });
        
        var currentPage = $(".dynamic-load.active").data("file");
        
        $.ajax({
            url: "../page-functions/updateCourse.php",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    alert("Course updated successfully!");
                    if (currentPage) {
                        loadPage(currentPage);
                    }
                } else {
                    alert("Error: " + (response.message || "Update failed") + 
                          (response.errors ? "\nDetails: " + response.errors.join(", ") : ""));
                }
            },
            error: function(xhr, status, error) {
                alert("Update Failed: " + error);
            },
        });
    });
    
    $(document).on("submit", "form[id^='overviewImg-']", function (e) {
        e.preventDefault();
    
        var formData = new FormData(this);
        var currentPage = $(".dynamic-load.active").data("file");
        var form = $(this); // Store reference to the form
        formData.append("imageIndex", form.find("input[name='overviewImgIndex']").val());
    
        $.ajax({
            url: "../page-functions/uploadOverviewImg.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    alert("Image updated successfully!");
    
                    // Update the image **inside the same form**
                    form.find("img").attr("src", response.newPath);
    
                    // Reload the dynamic page (optional, only if necessary)
                    if (currentPage) {
                        loadPage(currentPage);
                    }
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function () {
                alert("Upload failed. Try again.");
            },
        });
    });


    $(document).on('submit', "form[id$='-overviewItems']", function (e) {
        e.preventDefault();
        
        console.log("Form Submitted!");
        
        var formData = new FormData(this);
        var form = $(this);
        var overviewTitle = form.find("input.overviewTitle").val();
        var overviewSectionID = form.find("input.overviewTitle").data("overviewsectionid");
        
        formData.append("overviewTitle", overviewTitle);
        formData.append("overviewSectionID", overviewSectionID);
        
        // Collect overview top content
        form.find(".overview-top-content").each(function () {
            var topContent = $(this).val();
            var sectionID = $(this).data("sectionid");
            formData.append("overviewTopContent", topContent);
            formData.append("topContentSectionID", sectionID);
        });
        
        // Collect outcomes with new items marked
        let outcomesArray = [];
        form.find("input[type='text'][name$='-outcomes']").each(function () {
            outcomesArray.push({
                content: $(this).val(),
                sectionID: $(this).data("sectionid"),
                isNew: $(this).data("sectionid") < 0 // Mark if negative (new item)
            });
        });
        
        formData.append("outcomes", JSON.stringify(outcomesArray));
        
        console.log("Final FormData:");
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        
        var currentPage = $(".dynamic-load.active").data("file");
        
        $.ajax({
            url: "../page-functions/updateOverviewItem.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                console.log("Server Response:", response);
                
                if (response.success) {
                    alert("Overview updated successfully!");
                    if (currentPage) {
                        loadPage(currentPage);
                    }
                } else {
                    console.error("Update failed:", response.errors || response.message);
                    alert("Error: " + (response.message || "Something went wrong. Check console for details."));
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", error, "Response Text:", xhr.responseText);
                alert("Update Failed. Try again.");
            },
        });
    });

    $(document).on('click', '.remove-outcome', function() {
        const button = $(this);
        const listItem = button.closest('li');
        const currentPage = $(".dynamic-load.active").data("file");
        
        // For new items (not yet saved to DB)
        if (button.data("is-new") || !button.data("sectionid")) {
            listItem.remove();
            return;
        }
        
        // Existing items need server deletion
        if (confirm('Are you sure you want to delete this outcome permanently?')) {
            $.ajax({
                url: '../page-functions/removeItem.php',
                type: 'POST',
                data: { 
                    sectionID: button.data("sectionid"),
                    currentPage: currentPage
                },
                success: function (response) {
                    console.log("Server Response:", response);
                    
                    if (response.success) {
                        alert("Deleted successfully!");
                        if (currentPage) {
                            loadPage(currentPage);
                        }
                    } else {
                        console.error("Update failed:", response.errors || response.message);
                        alert("Error: " + (response.message || "Something went wrong. Check console for details."));
                    }
                },
            });
        }
    });

    $(document).on('click', '.add-outcome', function(e) {
        e.preventDefault();
        const form = $(this).closest('.overview-form');
        const outcomesList = form.find('.outcomes-list');
        const formName = form.attr('name');
        const nextIndex = outcomesList.find('li').length + 1;
        
        // Generate a temporary ID for new items (negative number)
        const tempSectionID = -Math.floor(Math.random() * 1000000);
        
        const newOutcome = $(`
            <li>
                <input type="text" 
                       name="${formName}-${nextIndex}-outcomes" 
                       id="${formName}-${nextIndex}-outcomes" 
                       data-sectionid="${tempSectionID}" 
                       value="">
                <button type="button" class="remove-outcome btn btn-danger" data-sectionid="${tempSectionID}">×</button>
            </li>
        `);
        
        outcomesList.append(newOutcome);
    });
    
    $(document).on('click', '.courses-item-container .add-outcome', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const outcomesContainer = form.find('.outcomes-container');
        const formName = form.attr('name');
        const nextIndex = outcomesContainer.find('li').length + 1;
        
        const newOutcome = $(`
            <li>
                <input type="text" 
                       name="${formName}-outcomes-${nextIndex}" 
                       id="${formName}-outcomes-${nextIndex}" 
                       data-is-new="true" 
                       value="">
                <button type="button" class="remove-outcome btn btn-danger">×</button>
            </li>
        `);
        
        outcomesContainer.append(newOutcome);
    });

});
