<?php
/**
 * Save All Changes Button Component
 * 
 * This component provides a fixed button at the bottom of the page
 * that allows users to save all changes at once.
 */
?>

<div class="fixed bottom-6 right-6 z-50" id="save-all-container">
    <button id="save-all-button" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-full shadow-lg flex items-center justify-center transition-all duration-300 transform hover:scale-105">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
        <span>Save All Changes</span>
    </button>
    
    <div id="save-all-spinner" class="hidden">
        <div class="bg-white p-3 rounded-full shadow-lg flex items-center justify-center">
            <svg class="animate-spin h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveAllButton = document.getElementById('save-all-button');
    const saveAllSpinner = document.getElementById('save-all-spinner');
    
    saveAllButton.addEventListener('click', function() {
        // Show spinner and hide button
        saveAllButton.classList.add('hidden');
        saveAllSpinner.classList.remove('hidden');
        
        // Collect all form data
        const formData = collectFormData();
        
        // Send data to server
        fetch('../page-functions/save-all-changes.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                page: window.location.pathname.split('/').pop(),
                form_data: JSON.stringify(formData)
            })
        })
        .then(response => response.json())
        .then(data => {
            // Hide spinner and show button
            saveAllSpinner.classList.add('hidden');
            saveAllButton.classList.remove('hidden');
            
            if (data.success) {
                // Show success message
                showNotification('success', 'All changes saved successfully!');
                
                // Mark changes as saved
                markChangesSaved();
                
                // Reload the page after a short delay to refresh data
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                // Show error message
                showNotification('error', data.message || 'Failed to save changes.');
                
                // Log errors
                if (data.errors && data.errors.length > 0) {
                    console.error('Errors:', data.errors);
                }
            }
        })
        .catch(error => {
            // Hide spinner and show button
            saveAllSpinner.classList.add('hidden');
            saveAllButton.classList.remove('hidden');
            
            // Show error message
            showNotification('error', 'An error occurred. Please try again.');
            console.error('Error:', error);
        });
    });
    
    // Function to show notification
    function showNotification(type, message) {
        const notificationContainer = document.createElement('div');
        notificationContainer.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${type === 'success' ? 'bg-green-100 border-l-4 border-green-500 text-green-700' : 'bg-red-100 border-l-4 border-red-500 text-red-700'}`;
        
        notificationContainer.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    ${type === 'success' 
                        ? '<svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>'
                        : '<svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>'
                    }
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
            </div>
        `;
        
        document.body.appendChild(notificationContainer);
        
        // Remove notification after 3 seconds
        setTimeout(() => {
            notificationContainer.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => {
                document.body.removeChild(notificationContainer);
            }, 500);
        }, 3000);
    }
});
</script>
