$(document).ready(function() {
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
                value: value,
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
});
