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
                        $("#main-content-section").load(currentPage + " #main-content-section > *");
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
                        $("#main-content-section").load(currentPage + " #main-content-section > *");
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
    

});
