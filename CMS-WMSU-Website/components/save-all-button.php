<?php
/**
 * Save All Changes Button Component
 * 
 * This component provides a button to save all changes made to the current page.
 * It works with the preview system to ensure changes are only committed to the database
 * when the user explicitly chooses to save them.
 */

// Get the current page from the URL
$currentPage = basename($_SERVER['REQUEST_URI']);
if (strpos($currentPage, '?') !== false) {
    $currentPage = substr($currentPage, 0, strpos($currentPage, '?'));
}

// If the current page is empty, try to get it from the referer
if (empty($currentPage) && isset($_SERVER['HTTP_REFERER'])) {
    $refererParts = parse_url($_SERVER['HTTP_REFERER']);
    if (isset($refererParts['path'])) {
        $pathParts = explode('/', $refererParts['path']);
        $currentPage = end($pathParts);
    }
}

// Default to college-overview.php if we still can't determine the page
if (empty($currentPage) || $currentPage === 'index.php') {
    $currentPage = 'college-overview.php';
}
?>

<div class="fixed bottom-4 right-4 z-50">
    <button id="save-all-changes-btn" class="bg-primary hover:bg-red-700 text-white font-bold py-2 px-4 rounded-full shadow-lg flex items-center transition-all duration-300 transform hover:scale-105">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        Save All Changes
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveAllBtn = document.getElementById('save-all-changes-btn');
    
    if (saveAllBtn) {
        saveAllBtn.addEventListener('click', function() {
            // Show loading state
            saveAllBtn.disabled = true;
            saveAllBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
            `;
            
            // Collect all form data
            const forms = document.querySelectorAll('form[data-preview="true"]');
            const formData = {};
            
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
            
            // Send data to server
            fetch('../page-functions/save-all-changes.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    page: '<?php echo $currentPage; ?>',
                    form_data: JSON.stringify(formData)
                })
            })
            .then(response => response.json())
            .then(data => {
                // Reset button state
                saveAllBtn.disabled = false;
                
                if (data.success) {
                    // Show success state
                    saveAllBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save All Changes
                    `;
                    
                    // Show success message
                    alert('All changes saved successfully!');
                    
                    // Mark changes as saved in the preview
                    if (typeof window.markChangesSaved === 'function') {
                        window.markChangesSaved();
                    }
                    
                    // Reload the page to reflect the changes
                    // We use a small delay to ensure the success message is seen
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    // Show error state
                    saveAllBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Save All Changes
                    `;
                    
                    // Show error message
                    alert('Error: ' + (data.message || 'Failed to save changes'));
                    
                    // Log detailed errors if available
                    if (data.errors && data.errors.length > 0) {
                        console.error('Save errors:', data.errors);
                    }
                }
            })
            .catch(error => {
                // Reset button state
                saveAllBtn.disabled = false;
                saveAllBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Save All Changes
                `;
                
                // Show error message
                alert('Error: ' + error.message);
                console.error('Save error:', error);
            });
        });
    }
});
</script>
