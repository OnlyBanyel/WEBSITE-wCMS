$(document).ready(function() {
    // Open the first modal when clicking "Add Elements"
    $(document).on("click", ".add-modal", function(e) {
        e.preventDefault();

        var sectionID = $(this).data("section-id"); 
        var sectionName = $(this).data("section");
        var allowedElements = $(this).data("allowed-elements");

        if (typeof allowedElements === "string") {
            allowedElements = JSON.parse(allowedElements);
        }

        $.ajax({
            url: "../modals/add_modal.php",
            type: "POST",
            data: { sectionID: sectionID, section: sectionName, allowedElements: allowedElements },
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
        var sectionID = $(this).data("section-id");
        var elementType = $(this).data("type");
        var elementDesc = $(this).data("desc");

        console.log("Clicked Element Type:", elementType);
        console.log("Clicked Element Description:", elementDesc);
        console.log("Section ID:", sectionID);

        $.ajax({
            url: "../modals/add_content_modal.php",
            type: "POST",
            data: { 
                sectionID: sectionID, 
                elementType: elementType, 
                elementDesc: elementDesc 
            },
            success: function(response) {
                $("#modalContent").html(response); // Replace modal content
            },
            error: function() {
                alert("Error loading second modal.");
            }
        });
    });

    // Save element (either content or imagePath) when clicking "Save"
    $(document).on("click", "#saveElement", function() {
        var sectionID = $("#elementForm").data("section-id");
        var elementType = $("#elementForm").data("type");
        var value = $("#elementInput").val();

        $.ajax({
            url: "../functions/save_element.php",
            type: "POST",
            data: {
                sectionID: sectionID,
                elementType: elementType,
                value: value
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    alert("Element updated successfully.");
                    $("#addModal").modal("hide");
                } else {
                    alert("Error: " + result.message);
                }
            },
            error: function() {
                alert("Error saving data.");
            }
        });
    });
});
