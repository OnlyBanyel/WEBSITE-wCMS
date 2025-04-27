<link rel="stylesheet" href="../vendors/mdi/css/materialdesignicons.min.css">
<link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
<link rel="stylesheet" href="../vendors/coreui-5.3.1-dist/css/coreui.min.css">
<link rel="stylesheet" href="../vendors/datatable/datatables.min.css">
<link rel="stylesheet" href="../vendors/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="../css/tailwind.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins&family=Roboto&family=Merriweather&display=swap" rel="stylesheet">

<!-- Add this to the head.php file to include the necessary styles for the style editor -->
<style>
    /* Style editor specific styles */
    .style-editing {
        outline: 2px dashed #BD0F03 !important;
        position: relative;
        z-index: 10;
    }
    
    .style-edit-mode .styleable {
        cursor: pointer;
        transition: outline 0.2s ease;
    }
    
    .style-edit-mode .styleable:hover {
        outline: 2px dotted #BD0F03;
    }
    
    /* Prevent interaction with other elements when in edit mode */
    .style-edit-mode a, 
    .style-edit-mode button:not(#toggle-style-editor):not(#close-style-panel):not(.style-option):not(#reset-styles) {
        pointer-events: none;
    }
     /* Style for elements being edited */
        .style-editing {
            outline: 2px dashed #BD0F03 !important;
            position: relative;
        }
        
        .style-editing::after {
            content: "Editing";
            position: absolute;
            top: -20px;
            right: 0;
            background-color: #BD0F03;
            color: white;
            padding: 2px 6px;
            font-size: 10px;
            border-radius: 3px;
            z-index: 100;
        }
        
        /* Style for styleable elements */
        .styleable {
            cursor: pointer;
            position: relative;
        }
        
        .styleable:hover {
            outline: 2px dotted #BD0F03;
        }
        
        .styleable:hover::after {
            content: "Click to edit";
            position: absolute;
            top: -20px;
            right: 0;
            background-color: #BD0F03;
            color: white;
            padding: 2px 6px;
            font-size: 10px;
            border-radius: 3px;
            z-index: 100;
        }
</style>


<script src="../vendors/jquery-3.7.1/jquery-3.7.1.min.js"></script>
<script src="../vendors/coreui-5.3.1-dist/js/coreui.bundle.min.js"></script>
<script src="../vendors/datatable/datatables.min.js"></script>
<script src="../vendors/bootstrap/bootstrap.min.js"></script>
