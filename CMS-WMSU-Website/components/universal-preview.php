<?php
/**
 * Universal Preview Component
 * 
 * This component provides a live preview of content being edited across
 * all admin pages before changes are committed to the database.
 */

// Initialize preview data from session if it exists
if (!isset($_SESSION['preview_data'])) {
    $_SESSION['preview_data'] = [];
}

// Get the current page identifier
$currentPage = isset($previewPage) ? $previewPage : 'default';
?>

<div class="bg-white rounded-xl shadow-md p-6 mb-8 preview-section" id="universal-preview-container">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-primary">Live Preview</h2>
        <div class="flex items-center">
            <span id="preview-status" class="text-sm text-gray-500 mr-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Unsaved Changes
                </span>
            </span>
            <button id="toggle-preview-btn" class="text-sm text-gray-500 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                <span>Toggle Preview</span>
            </button>
        </div>
    </div>
    
    <div class="preview-content overflow-auto max-h-[800px] border border-gray-200 rounded-lg" id="universal-preview-content">
        <!-- Preview content will be loaded here dynamically -->
        <div class="flex flex-col items-center justify-center p-8 bg-gray-100 rounded-lg" id="empty-preview-placeholder">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-600">Make changes to see a live preview</p>
        </div>
    </div>
    
    <div class="mt-4 text-center">
        <p class="text-sm text-gray-500">This preview shows how the content will appear on the actual website</p>
    </div>
    
    <!-- Debug Panel (hidden by default) -->
    <div id="preview-debug-panel" class="mt-4 p-4 bg-gray-100 rounded-lg hidden">
        <h3 class="text-sm font-semibold mb-2">Debug Information</h3>
        <div id="preview-debug-content" class="text-xs font-mono overflow-auto max-h-[200px]"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle preview visibility
    const togglePreviewBtn = document.getElementById('toggle-preview-btn');
    const previewContent = document.getElementById('universal-preview-content');
    
    togglePreviewBtn.addEventListener('click', function() {
        previewContent.classList.toggle('hidden');
        
        // Update button text
        const buttonText = togglePreviewBtn.querySelector('span');
        if (previewContent.classList.contains('hidden')) {
            buttonText.textContent = 'Show Preview';
        } else {
            buttonText.textContent = 'Hide Preview';
        }
    });
    
    // Initialize preview with current data
    updatePreview();
    
    // Add event listeners to all form inputs to update preview on change
    document.querySelectorAll('form[data-preview="true"] input, form[data-preview="true"] textarea, form[data-preview="true"] select').forEach(input => {
        input.addEventListener('input', function() {
            markUnsavedChanges();
        });
    });
    
    // Add debug panel toggle (press Ctrl+Shift+D to toggle)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'D') {
            const debugPanel = document.getElementById('preview-debug-panel');
            debugPanel.classList.toggle('hidden');
            e.preventDefault();
        }
    });
});

// Function to update the preview with current form data
function updatePreview() {
    const previewContainer = document.getElementById('universal-preview-content');
    const emptyPlaceholder = document.getElementById('empty-preview-placeholder');
    const debugContent = document.getElementById('preview-debug-content');
    
    // Collect all form data
    const forms = document.querySelectorAll('form[data-preview="true"]');
    if (forms.length === 0) {
        return; // No previewable forms found
    }
    
    // Hide empty placeholder
    if (emptyPlaceholder) {
        emptyPlaceholder.style.display = 'none';
    }
    
    // Determine the current page
    let pageName = 'college-overview.php'; // Default to college overview
    
    // Try to get the page name from various sources
    const metaTag = document.querySelector('meta[name="current-page"]');
    if (metaTag && metaTag.getAttribute('content')) {
        pageName = metaTag.getAttribute('content');
    } else {
        // Try to extract from URL
        const pathParts = window.location.pathname.split('/');
        for (let i = 0; i < pathParts.length; i++) {
            if (pathParts[i].endsWith('.php')) {
                pageName = pathParts[i];
                break;
            }
        }
    }
    
    // Collect form data
    const formData = collectFormData();
    
    // Determine the correct path to generate-preview.php
    let previewEndpoint = '../page-functions/generate-preview.php';

    // If we're in a subdirectory, adjust the path
    if (window.location.pathname.includes('/page-views/')) {
        previewEndpoint = '../page-functions/generate-preview.php';
    } else if (window.location.pathname.includes('/pages/')) {
        previewEndpoint = '../page-functions/generate-preview.php';
    } else {
        // Try to determine the path based on the current URL
        const pathSegments = window.location.pathname.split('/').filter(Boolean);
        if (pathSegments.length > 0) {
            const adminIndex = pathSegments.indexOf('admin-side');
            if (adminIndex !== -1) {
                const depth = pathSegments.length - adminIndex - 1;
                previewEndpoint = '../'.repeat(depth) + 'page-functions/generate-preview.php';
            }
        }
    }

    // Add this debug information to the page
    console.log('Preview endpoint:', previewEndpoint);
    console.log('Current path:', window.location.pathname);

    // Request preview HTML from server
    fetch(previewEndpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            page: pageName,
            preview_data: JSON.stringify(formData)
        })
    })
    .then(response => response.json())
    .then(data => {
        // Update debug panel if it exists
        if (debugContent) {
            debugContent.innerHTML = '<pre>' + JSON.stringify(data.debug, null, 2) + '</pre>';
        }
        
        if (data.success) {
            // Update preview content
            previewContainer.innerHTML = data.html;
        } else {
            console.error('Error generating preview:', data.message);
            previewContainer.innerHTML = `
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    <p>Error generating preview: ${data.message}</p>
                    <p class="mt-2 text-sm">Page requested: ${pageName}</p>
                    <p class="mt-2 text-sm">Preview endpoint: ${previewEndpoint}</p>
                    <p class="text-sm">Try pressing Ctrl+Shift+D to view debug information.</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        previewContainer.innerHTML = `
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                <p>Error generating preview. Please try again.</p>
                <p class="mt-2 text-sm">Technical details: ${error.message}</p>
                <p class="mt-2 text-sm">Preview endpoint: ${previewEndpoint}</p>
            </div>
        `;
    });
}

// Function to collect all form data
function collectFormData() {
    const formData = {};
    const forms = document.querySelectorAll('form[data-preview="true"]');
    
    forms.forEach(form => {
        const formId = form.id || form.getAttribute('name') || Math.random().toString(36).substring(7);
        formData[formId] = {};
        
        // Collect all input, select, and textarea values
        form.querySelectorAll('input, select, textarea').forEach(input => {
            if (input.type === 'file') return; // Skip file inputs
            
            if (input.type === 'checkbox' || input.type === 'radio') {
                if (input.checked) {
                    formData[formId][input.name] = input.value;
                }
            } else {
                formData[formId][input.name] = input.value;
            }
        });
        
        // Special handling for lists of items (like outcomes)
        const itemLists = form.querySelectorAll('.outcomes-list, .outcomes-container');
        itemLists.forEach(list => {
            const items = [];
            list.querySelectorAll('li input[type="text"]').forEach(input => {
                items.push({
                    content: input.value,
                    sectionID: input.dataset.sectionid || '',
                    isNew: input.dataset.isNew === 'true' || false
                });
            });
            
            if (items.length > 0) {
                formData[formId]['items'] = items;
            }
        });
    });
    
    return formData;
}

// Function to mark a form as having unsaved changes
function markUnsavedChanges() {
    const previewStatus = document.getElementById('preview-status');
    
    previewStatus.innerHTML = `
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Unsaved Changes
        </span>
    `;
    
    // Update preview after a short delay to avoid too many requests
    clearTimeout(window.previewUpdateTimeout);
    window.previewUpdateTimeout = setTimeout(updatePreview, 500);
}

// Function to mark all changes as saved
function markChangesSaved() {
    const previewStatus = document.getElementById('preview-status');
    
    previewStatus.innerHTML = `
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            All Changes Saved
        </span>
    `;
}
</script>
