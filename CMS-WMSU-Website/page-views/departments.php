<?php
session_start();
require_once '../classes/pages.class.php';

$pagesObj = new Pages;
$departments = [];
$collegeOverview = [];
$genInfoImgs = [];

foreach ($_SESSION['collegeData'] as $data) {
    if ($data['indicator'] == "Departments"){
        $departments[] = $data;
    }
    if ($data['indicator'] == "College Overview"){
        $collegeOverview[] = $data;
    }
}

foreach ($collegeOverview as $imgs){
    if ($imgs['description'] == 'geninfo-front-img'){
        $genInfoImgs[] = $imgs;
    }
}

// Create default departments if none exist
if (empty($departments)) {
    $defaultDepartments = [
        ['content' => 'Department of Computer Science', 'sectionID' => 'temp_dept_1'],
        ['content' => 'Department of Engineering', 'sectionID' => 'temp_dept_2'],
        ['content' => 'Department of Business', 'sectionID' => 'temp_dept_3']
    ];
    $departments = $defaultDepartments;
}

// Create default images if none exist
if (empty($genInfoImgs)) {
    $defaultImages = [
        ['imagePath' => '', 'sectionID' => 'temp_img_1'],
        ['imagePath' => '', 'sectionID' => 'temp_img_2'],
        ['imagePath' => '', 'sectionID' => 'temp_img_3']
    ];
    $genInfoImgs = $defaultImages;
}

// Ensure we have enough images for all departments
while (count($genInfoImgs) < count($departments)) {
    $genInfoImgs[] = ['imagePath' => '', 'sectionID' => 'temp_img_' . (count($genInfoImgs) + 1)];
}
?>

<style>
    /* Override Bootstrap's primary color with our red theme */
    .bg-primary,
    .bg-primary.active,
    .bg-primary:not([class*="bg-opacity"]) {
        --tw-bg-opacity: 1 !important;
        --bs-bg-opacity: 1 !important;
        background-color: rgb(189 15 3 / var(--tw-bg-opacity)) !important;
    }
    
    .btn-primary,
    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active {
        background-color: rgb(189 15 3 / var(--tw-bg-opacity)) !important;
        border-color: rgb(189 15 3 / var(--tw-bg-opacity)) !important;
    }
    
    :root {
        --bs-primary: #BD0F03 !important;
        --bs-primary-rgb: 189, 15, 3 !important;
    }
    
    /* Department card hover effect */
    .dept-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
    }
    
    .dept-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 25px -5px rgba(189, 15, 3, 0.2), 0 10px 10px -5px rgba(189, 15, 3, 0.1);
    }
    
    /* Tab styling */
    .tab-nav {
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }
    
    .tab-button {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        color: #6b7280;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }
    
    .tab-button:hover {
        color: #111827;
    }
    
    .tab-button.active {
        color: #BD0F03;
        border-bottom-color: #BD0F03;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    /* Table styling */
    .dept-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .dept-table th {
        text-align: left;
        padding: 0.75rem 1rem;
        background-color: #f9fafb;
        font-weight: 600;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .dept-table td {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    
    .dept-table tr:last-child td {
        border-bottom: none;
    }
    
    .dept-table tr:hover {
        background-color: #f9fafb;
    }
    
    /* Image preview */
    .img-preview {
        width: 120px;
        height: 80px;
        object-fit: cover;
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
    }
    
    .img-placeholder {
        width: 120px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f3f4f6;
        border-radius: 0.375rem;
        border: 1px solid #e5e7eb;
    }
</style>

<div class="bg-gray-50 min-h-screen p-4 md:p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Departments Management</h1>
        <p class="text-gray-600 mt-2">Edit and manage the departments in your college</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <div class="tab-nav">
            <button class="tab-button active" data-tab="preview">Preview</button>
            <button class="tab-button" data-tab="departments">Departments Management</button>
        </div>
        
        <!-- Preview Tab Content -->
        <div id="preview-tab" class="tab-content active">
            <?php if (!empty($departments)) { ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php 
                    $i = 0;
                    foreach ($departments as $items) { ?>
                        <div class="dept-card rounded-lg overflow-hidden shadow-md h-48 relative">
                            <div class="absolute inset-0 bg-primary/70" style="background: linear-gradient(rgba(189, 15, 3, 0.7), rgba(189, 15, 3, 0.7)), url('<?php echo !empty($genInfoImgs[$i]['imagePath']) ? $genInfoImgs[$i]['imagePath'] : ''; ?>') no-repeat center center; background-size: cover;"></div>
                            <div class="absolute inset-0 flex items-center justify-center p-4">
                                <h3 class="text-xl font-bold text-white text-center"><?php echo $items['content']; ?></h3>
                            </div>
                        </div>
                    <?php $i++; } ?>
                </div>
            <?php } else { ?>
                <div class="flex flex-col items-center justify-center p-8 bg-gray-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="text-gray-600">No departments available. Add departments below to see preview.</p>
                </div>
            <?php } ?>
        </div>
        
        <!-- Departments Management Tab Content -->
        <div id="departments-tab" class="tab-content">
            <div class="mb-4 flex justify-end">
                <button id="addNewDepartment" class="bg-primary hover:bg-red-700 text-white px-4 py-2 rounded-md flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Department
                </button>
            </div>
            
            <table class="dept-table">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="30%">Department Name</th>
                        <th width="25%">Image</th>
                        <th width="25%">Upload New Image</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
$i = 0;
foreach ($departments as $items) { ?>
    <tr>
        <td><?php echo $i + 1; ?></td>
        <td>
            <!-- Department Name Form -->
            <form action="../page-functions/uploadDeptImgs.php" method="POST" class="departmentNameForm" id="departmentNameForm-<?php echo $items['sectionID']; ?>" enctype="multipart/form-data">
                <input type="text" name="deptName" class="deptName w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" id="deptName" data-textid="<?php echo $items['sectionID']?>" value="<?php echo $items['content']?>">
                <input type="hidden" name="textID" value="<?php echo $items['sectionID']?>">
                <input type="hidden" name="isNew" value="<?php echo strpos($items['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                <input type="hidden" name="updateType" value="name">
                
                <div class="mt-2">
                    <button type="submit" class="saveDeptName bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Name
                    </button>
                </div>
            </form>
        </td>
        <td>
            <?php if (!empty($genInfoImgs[$i]['imagePath'])) { ?>
                <img src="<?php echo $genInfoImgs[$i]['imagePath'] ?>" alt="Department Image" class="img-preview">
            <?php } else { ?>
                <div class="img-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            <?php } ?>
        </td>
        <td>
            <!-- Department Image Form -->
            <form action="../page-functions/uploadDeptImgs.php" method="POST" class="departmentImageForm" id="departmentImageForm-<?php echo isset($genInfoImgs[$i]) ? $genInfoImgs[$i]['sectionID'] : 'temp_'.$i; ?>" enctype="multipart/form-data">
                <input type="hidden" name="textID" value="<?php echo $items['sectionID']?>">
                <input type="hidden" name="sectionID" value="<?php echo isset($genInfoImgs[$i]) ? $genInfoImgs[$i]['sectionID'] : 'temp_img_'.$i; ?>">
                <input type="hidden" name="imgIsNew" value="<?php echo (!isset($genInfoImgs[$i]) || empty($genInfoImgs[$i]['imagePath'])) ? '1' : '0'; ?>">
                <input type="hidden" name="updateType" value="image">
                
                <div class="relative">
                    <input type="file" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-10" name="deptImg" id="deptImg-<?php echo isset($genInfoImgs[$i]) ? $genInfoImgs[$i]['sectionID'] : 'temp_'.$i; ?>" accept="image/*">
                    <div class="bg-gray-100 border border-gray-300 rounded-md px-4 py-2 text-gray-700 flex items-center justify-between">
                        <span class="file-name">Choose a new image...</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                
                <div class="mt-2">
                    <button type="submit" class="saveDeptImg bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Save Image
                    </button>
                </div>
            </form>
        </td>
        <td>
            <button type="button" class="deleteDeptBtn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm" 
                    data-textid="<?php echo $items['sectionID']?>"
                    data-isnew="<?php echo strpos($items['sectionID'], 'temp_') === 0 ? '1' : '0'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete
            </button>
        </td>
    </tr>
<?php $i++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Tab functionality
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Add active class to clicked tab
            button.classList.add('active');
            document.getElementById(`${button.dataset.tab}-tab`).classList.add('active');
        });
    });

    // File input display
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'Choose a file...';
            this.parentElement.querySelector('.file-name').textContent = fileName;
        });
    });
    
    // Add new department functionality with AJAX
    document.getElementById('addNewDepartment').addEventListener('click', function() {
        const button = this;
        const originalHTML = button.innerHTML;
        
        // Show loading state
        button.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Adding Department...
        `;
        button.disabled = true;

        // Make AJAX request
        fetch('../page-functions/addDepartment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'addNewDepartment=1'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Reload the page to show the new department
                location.reload();
            } else {
                // Show error message
                const errorMsg = document.createElement('div');
                errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                errorMsg.textContent = data.message || 'Failed to add department';
                document.body.appendChild(errorMsg);
                
                setTimeout(() => {
                    errorMsg.remove();
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMsg = document.createElement('div');
            errorMsg.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
            errorMsg.textContent = 'An error occurred while adding the department';
            document.body.appendChild(errorMsg);
            
            setTimeout(() => {
                errorMsg.remove();
            }, 3000);
        })
        .finally(() => {
            // Restore button state
            button.innerHTML = originalHTML;
            button.disabled = false;
        });
    });

    // Department deletion functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('deleteDeptBtn')) {
            const button = e.target;
            const row = button.closest('tr');
            const textID = button.dataset.textid;
            const isNew = button.dataset.isnew === '1';
            
            if (confirm('Are you sure you want to delete this department?')) {
                // Show loading state
                const originalText = button.textContent;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;
                
                // Make AJAX request
                fetch('../page-functions/uploadDeptImgs.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `deleteDepartment=1&textID=${textID}&isNew=${isNew}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Remove the row with animation
                        row.style.opacity = '0';
                        row.style.transition = 'opacity 0.3s ease';
                        setTimeout(() => {
                            row.remove();
                            
                            // Show success message
                            const successMsg = document.createElement('div');
                            successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                            successMsg.textContent = 'Department deleted successfully!';
                            document.body.appendChild(successMsg);
                            
                            setTimeout(() => {
                                successMsg.remove();
                            }, 3000);
                        }, 300);
                    } else {
                        alert(data.message || 'Failed to delete department');
                        button.textContent = originalText;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the department');
                    button.textContent = originalText;
                    button.disabled = false;
                });
            }
        }
    });
</script>
