<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <!-- Tailwind CSS - Using CDN for now to ensure styles work -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/tailwind.config.js"></script>
    
    <!-- Local CSS files -->
    <link rel="stylesheet" href="../vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../vendors/coreui-5.3.1-dist/css/coreui.min.css">
    <link rel="stylesheet" href="../vendors/datatable/datatables.min.css">
    <link rel="stylesheet" href="../vendors/bootstrap/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&family=Roboto&family=Merriweather&display=swap" rel="stylesheet">

    <!-- Style Editor Specific Styles -->
    <style>
        /* Style editor specific styles */
        .style-editing {
            outline: 2px dashed #BD0F03 !important;
            position: relative;
            z-index: 10;
        }
        
        body.style-edit-mode .styleable {
            cursor: pointer;
            transition: outline 0.2s ease;
        }
        
        body.style-edit-mode .styleable:hover {
            outline: 2px dotted #BD0F03;
        }
        
        /* Prevent interaction with other elements when in edit mode */
        body.style-edit-mode a:not(.allowed-click), 
        body.style-edit-mode button:not(#toggle-style-editor):not(#close-style-panel):not(.style-option):not(#reset-styles):not([type="submit"]):not(#saveElement):not(.save-btn):not(.submit-btn) {
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
        body.style-edit-mode .styleable {
            cursor: pointer;
            position: relative;
        }
        
        body.style-edit-mode .styleable:hover {
            outline: 2px dotted #BD0F03;
        }
        
        body.style-edit-mode .styleable:hover::after {
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
        
        /* Style panel positioning */
        .main-content {
            transition: margin-right 0.3s ease;
        }
        
        /* Keyboard shortcut info */
        .keyboard-shortcut-info {
            position: fixed;
            bottom: 10px;
            right: 10px;
            background-color: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            z-index: 1000;
        }
        
        /* Toggle button for style edit mode */
        #toggle-style-editor {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #BD0F03;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        #toggle-style-editor:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        
        #toggle-style-editor svg {
            width: 24px;
            height: 24px;
        }
        
        /* Style edit mode indicator */
        .edit-mode-indicator {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #BD0F03;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            z-index: 1000;
            display: none;
        }
        
        body.style-edit-mode .edit-mode-indicator {
            display: block;
        }
    </style>

    <!-- JavaScript Libraries -->
    <script src="../vendors/jquery-3.7.1/jquery-3.7.1.min.js"></script>
    <script src="../vendors/coreui-5.3.1-dist/js/coreui.bundle.min.js"></script>
    <script src="../vendors/datatable/datatables.min.js"></script>
    <script src="../vendors/bootstrap/bootstrap.min.js"></script>
</head>
<body>
<!-- Edit Mode Toggle Button -->
<button id="toggle-style-editor" title="Toggle Style Editor">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 20h9"></path>
        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
    </svg>
</button>

<!-- Edit Mode Indicator -->
<div class="edit-mode-indicator">Style Edit Mode</div>

