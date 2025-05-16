<?php
require_once __DIR__ . "../../../tools/functions.php";
require_once __DIR__ . "/db_connection.class.php";

class Pages {
    protected $db;
    public $pageData;

    function __construct() {
        $this->db = new Database;
    }

    function fetchGenElements() {
        $sql = "SELECT * from generalelements";
    }

    function fetchPageData($pageID, $subpageID = null) {
        if ($subpageID == null) {
            $sql = "SELECT * from page_sections 
                    LEFT JOIN pages ON page_sections.pageID = pages.ID 
                    WHERE pageID = :pageID";

            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(":pageID", $pageID);
        } else {
            $sql = "SELECT * from page_sections 
                    LEFT JOIN pages ON page_sections.pageID = pages.ID 
                    LEFT JOIN subpages ON page_sections.subpage = subpages.subpageID 
                    WHERE pageID = :pageID AND subpages.subpageID = :subpageID";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(":subpageID", $subpageID);
            $qry->bindParam(":pageID", $pageID);
        }
        
        if ($qry->execute()) {
            $this->pageData = $qry->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $this->pageData = [];
        }
        return $this->pageData;
    }

    function fetchCollegeSubpages($pageID) {
        $sql = "SELECT * from subpages WHERE pagesID = :pageID AND isCollege = 1";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':pageID', $pageID);

        if ($qry->execute()) {
            $data = $qry->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data = null;
        }
        return $data;
    }

    public function fetchSectionsByIndicator($indicator, $pageID, $subpageID = null): array {
        $sql = "SELECT * FROM page_sections WHERE pageID = :pageID AND indicator = :indicator";
        
        if ($subpageID !== null) {
            $sql .= " AND subpage = :subpageID";
        }
    
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(":pageID", $pageID);
        $qry->bindParam(":indicator", $indicator);
        
        if ($subpageID !== null) {
            $qry->bindParam(":subpageID", $subpageID);
        }
    
        if ($qry->execute()) {
            return $qry->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    function editValue($newValue, $ID, $column) {
        $sql0 = "SELECT * from page_sections WHERE sectionID = :ID";
        $qry0 = $this->db->connect()->prepare($sql0);
        $qry0->bindParam(":ID", $ID);
        if ($qry0->execute()) {
            $data = $qry0->fetch(PDO::FETCH_ASSOC);
        }
        
        if (!$data) {
            return false;
        }
        
        $sql = "UPDATE page_sections SET $column = :newValue WHERE sectionID = :ID";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(":newValue", $newValue);
        $qry->bindParam(":ID", $ID);
        
        return $qry->execute();
    }

    function getRowById($ID) {
        $sql = "SELECT * FROM page_sections WHERE sectionID = :ID";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(":ID", $ID);
        $qry->execute();
        return $qry->fetch(PDO::FETCH_ASSOC);
    }

    function execQuery($sql) {
        $qry = $this->db->connect()->prepare($sql);
        if ($qry->execute()) {
            $data = $qry->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data = null;
        }
        return $data;
    }

    // Helper function to add a page section
    public function addPageSection($data) {
        $sql = "INSERT INTO page_sections (pageID, subpage, indicator, description, elemType, content, imagePath, createdAt, updatedAt) 
                VALUES (3, :subpage, :indicator, :description, :elemType, :content, :imagePath, NOW(), NOW())";
        
        $qry = $this->db->connect()->prepare($sql);
        
        $qry->bindParam(':subpage', $data['subpage']);
        $qry->bindParam(':indicator', $data['indicator']);
        $qry->bindParam(':description', $data['description']);
        $qry->bindParam(':elemType', $data['elemType']);
        $qry->bindParam(':content', $data['content']);
        $qry->bindParam(':imagePath', $data['imagePath']);
        
        if ($qry->execute()) {
            return $this->db->connect()->lastInsertId();
        } else {
            throw new Exception("Failed to add page section");
        }
    }

    // Helper function to update a page section
    public function updatePageSection($sectionID, $data) {
        $sql = "UPDATE page_sections SET content = :content WHERE sectionID = :sectionID";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':content', $data['content']);
        $qry->bindParam(':sectionID', $sectionID);
        
        if (!$qry->execute()) {
            throw new Exception("Failed to update page section");
        }
    }

    // Helper function to delete a page section
    public function deletePageSection($sectionID) {
        $sql = "DELETE FROM page_sections WHERE sectionID = :sectionID";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':sectionID', $sectionID);
        
        if (!$qry->execute()) {
            throw new Exception("Failed to delete page section");
        }
    }

    public function updateSectionContent($sectionID, $column, $value, $indicator, $description, $elemType) {
        $sql = "UPDATE page_sections SET $column = :value, indicator = :indicator, description = :description, elemType = :elemType WHERE sectionID = :sectionID";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(":value", $value);
        $qry->bindParam(":indicator", $indicator);
        $qry->bindParam(":description", $description);
        $qry->bindParam(":elemType", $elemType);
        $qry->bindParam(":sectionID", $sectionID);
        return $qry->execute();
    }

    // Define the insertSectionContent function without using sectionID
    public function insertSectionContent($value, $indicator, $description, $pageID, $subpageID = null) {
        // Determine element type based on indicator
        if (stripos($indicator, 'img') !== false) {
            $elemType = 'image';
            $column = 'imagePath';
        } else {
            $elemType = 'text';
            $column = 'content';
        }
    
        // Indicators that should not be modified
        $exceptions = ['onlinereg-section', 'General-Info-Back', 'General-Info'];
    
        // Special case: If indicator is "GeneralInfoItems", change it to "General-Info-Back"
        if ($indicator === 'GeneralInfoItems') {
            $indicator = 'General-Info-Back';
        } 
        // Only modify if NOT in the exceptions list
        elseif (!in_array($indicator, $exceptions, true)) {
            $indicator = preg_replace('/(?<!^)([A-Z])/', ' $1', $indicator); // Add spaces before uppercase letters
        }
    
        // Check if pageID exists to avoid foreign key constraint error
        $checkSql = "SELECT COUNT(*) FROM subpages WHERE subpageID = :pageID";
        $checkQry = $this->db->connect()->prepare($checkSql);
        $checkQry->bindParam(":pageID", $pageID);
        $checkQry->execute();
        $pageExists = $checkQry->fetchColumn();
    
        if (!$pageExists) {
            die(json_encode(['status' => 'error', 'message' => 'pageID does not exist in pages table']));
        }
    
        // Insert into the correct column dynamically
        $sql = "INSERT INTO page_sections (pageID, subpage, indicator, description, elemType, $column) 
                VALUES (:pageID, :subpageID, :indicator, :description, :elemType, :value)";
    
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(":value", $value);
        $qry->bindParam(":indicator", $indicator);
        $qry->bindParam(":description", $description);
        $qry->bindParam(":elemType", $elemType);
        $qry->bindParam(":pageID", $pageID);
    
        // Ensure subpageID is bound properly (NULL-safe)
        if ($subpageID === null) {
            $qry->bindValue(":subpageID", null, PDO::PARAM_NULL);
        } else {
            $qry->bindParam(":subpageID", $subpageID);
        }
    
        if (!$qry->execute()) {
            error_log("SQL Error: " . json_encode($qry->errorInfo()));
            die(json_encode(['status' => 'error', 'message' => 'Database error occurred']));
        }
    
        return true;
    }
    
    function getRowByIndicator($indicator) {
        $sql = "SELECT * FROM page_sections WHERE indicator = :indicator LIMIT 1";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(":indicator", $indicator);
        $qry->execute();
        return $qry->fetch(PDO::FETCH_ASSOC);
    }
    
    function deleteVal($sectionID) {
        $sql = "DELETE FROM page_sections WHERE sectionID = :sectionID";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':sectionID', $sectionID);
        return $qry->execute();
    }

    function fetchColleges() {
        $sql = "SELECT subpageID, subPageName, established_year FROM subpages WHERE isCollege = 1";
        $qry = $this->db->connect()->prepare($sql);

        if ($qry->execute()) {
            $data = $qry->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data = null;
        }
        return $data;
    }

    function uploadLogo($destPath, $subpage) {
        $sql = "UPDATE page_sections SET imagePath = :destPath WHERE subpage = :subpage AND description = 'carousel-logo'";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':destPath', $destPath);
        $qry->bindParam(':subpage', $subpage);

        return $qry->execute();
    }

    // Find the uploadProfileImgs method and replace it with this improved version:

function uploadProfileImgs($destPath, $sectionID, $subpage) {
    try {
        // First check if the section exists
        $checkSql = "SELECT COUNT(*) FROM page_sections WHERE sectionID = :sectionID AND subpage = :subpage";
        $checkQry = $this->db->connect()->prepare($checkSql);
        $checkQry->bindParam(':sectionID', $sectionID);
        $checkQry->bindParam(':subpage', $subpage);
        $checkQry->execute();
        
        $exists = $checkQry->fetchColumn();
        
        if (!$exists) {
            error_log("Section ID $sectionID does not exist for subpage $subpage");
            return false;
        }
        
        $sql = "UPDATE page_sections 
                SET imagePath = :destPath 
                WHERE sectionID = :sectionID 
                AND subpage = :subpage";
        
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':destPath', $destPath);
        $qry->bindParam(':sectionID', $sectionID);
        $qry->bindParam(':subpage', $subpage);
        
        $result = $qry->execute();
        
        if (!$result) {
            error_log("Failed to update image path for section ID $sectionID: " . print_r($qry->errorInfo(), true));
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("Database error in uploadProfileImgs: " . $e->getMessage());
        return false;
    }
}
    
    function uploadImgs($destPath, $sectionID, $subpage) {
        $sql = "UPDATE page_sections 
        SET imagePath = :destPath 
        WHERE subpage = :subpage 
          AND sectionID = :sectionID 
          AND description = 'geninfo-front-img'";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':destPath', $destPath);
        $qry->bindParam(':sectionID', $sectionID);
        $qry->bindParam(':subpage', $subpage);

        return $qry->execute();
    }

    // Find the changeContent method and replace it with this improved version:

public function changeContent($sectionID, $subpage, $value, $indicator = null, $description = null) {
    try {
        // First check if the section exists
        $checkSql = "SELECT COUNT(*) FROM page_sections WHERE sectionID = :sectionID AND subpage = :subpage";
        $checkQry = $this->db->connect()->prepare($checkSql);
        $checkQry->bindParam(':sectionID', $sectionID);
        $checkQry->bindParam(':subpage', $subpage);
        $checkQry->execute();
        
        $exists = $checkQry->fetchColumn();
        if (!$exists) {
            error_log("Section ID $sectionID does not exist for subpage $subpage");
            return false;
        }
        
        // Build the SQL query
        $sql = "UPDATE page_sections SET content = :value WHERE sectionID = :sectionID AND subpage = :subpage";
        
        if ($indicator !== null) {
            $sql .= " AND indicator = :indicator";
        }
        if ($description !== null) {
            $sql .= " AND description = :description";
        }
        
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':value', $value);
        $qry->bindParam(':sectionID', $sectionID);
        $qry->bindParam(':subpage', $subpage);
        
        if ($indicator !== null) {
            $qry->bindParam(':indicator', $indicator);
        }
        if ($description !== null) {
            $qry->bindParam(':description', $description);
        }
        
        $result = $qry->execute();
        
        if (!$result) {
            error_log("Failed to update content for section ID $sectionID: " . print_r($qry->errorInfo(), true));
        }
        
        return $result;
    } catch (PDOException $e) {
        error_log("Database error in changeContent: " . $e->getMessage());
        return false;
    }
}

    function deleteItem($sectionID, $subpage) {
        $sql = "DELETE FROM page_sections WHERE sectionID = :sectionID AND subpage = :subpage";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':sectionID', $sectionID);
        $qry->bindParam(':subpage', $subpage);
        return $qry->execute();
    }

    public function addContent($subpage, $indicator, $elemType, $content, $imagePath, $description, $groupId = null) {
        try {
            // Check if the page_sections table has a group_id column
            $checkColumnSql = "SHOW COLUMNS FROM page_sections LIKE 'group_id'";
            $checkColumnStmt = $this->db->connect()->prepare($checkColumnSql);
            $checkColumnStmt->execute();
            
            // If the column doesn't exist, add it
            if ($checkColumnStmt->rowCount() == 0) {
                $addColumnSql = "ALTER TABLE page_sections ADD COLUMN group_id VARCHAR(50) NULL";
                $addColumnStmt = $this->db->connect()->prepare($addColumnSql);
                $addColumnStmt->execute();
                error_log("Added group_id column to page_sections table");
            }
            
            $sql = "INSERT INTO page_sections 
                    (pageID, subpage, indicator, elemType, content, imagePath, description, group_id, createdAt, updatedAt) 
                    VALUES 
                    (3, :subpage, :indicator, :elemType, :content, :imagePath, :description, :groupId, NOW(), NOW())";
            
            $db = $this->db->connect();
            $qry = $db->prepare($sql);
            
            $qry->bindParam(':subpage', $subpage);
            $qry->bindParam(':indicator', $indicator);
            $qry->bindParam(':elemType', $elemType);
            $qry->bindParam(':content', $content);
            $qry->bindParam(':imagePath', $imagePath);
            $qry->bindParam(':description', $description);
            $qry->bindParam(':groupId', $groupId);
            
            $success = $qry->execute();
            
            if ($success) {
                // Return the last inserted ID
                return $db->lastInsertId();
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Error in addContent: " . $e->getMessage());
            return false;
        }
    }

    public function deleteContent($sectionID) {
        try {
            $db = $this->db->connect();
            
            $sql = "DELETE FROM page_sections WHERE sectionID = :sectionID";
            
            $qry = $db->prepare($sql);
            $qry->bindParam(':sectionID', $sectionID);
            
            return $qry->execute();
            
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    function fetchContentManagers() {
        $sql = "SELECT accounts.*, subpages.subPageName, subpages.established_year, roles.roleName, pages.pageName 
                FROM accounts 
                LEFT JOIN subpages ON accounts.subpage_assigned = subpages.subpageID 
                LEFT JOIN roles ON accounts.role_id = roles.roleID
                LEFT JOIN pages ON subpages.pagesID = pages.ID
                WHERE accounts.role_id = 2";

        $qry = $this->db->connect()->prepare($sql);

        if ($qry->execute()) {
            $data = $qry->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $data = null;
        }
        return $data;
    }

    public function updateAccountStatus($managerId, $status) {
    try {
        // First, check if the manager ID exists
        $checkSql = "SELECT COUNT(*) FROM accounts WHERE id = :id";
        $checkQry = $this->db->connect()->prepare($checkSql);
        $checkQry->bindParam(':id', $managerId, PDO::PARAM_INT);
        $checkQry->execute();
        
        $managerExists = $checkQry->fetchColumn();
        
        if (!$managerExists) {
            error_log("Manager ID does not exist: $managerId");
            return false;
        }
        
        // Now update the status with a specific WHERE clause for the ID
        $sql = "UPDATE accounts SET status = :status WHERE id = :id";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':status', $status, PDO::PARAM_INT);
        $qry->bindParam(':id', $managerId, PDO::PARAM_INT);
        
        $result = $qry->execute();
        
        if (!$result) {
            $errorInfo = $qry->errorInfo();
            error_log("SQL Error: " . print_r($errorInfo, true));
            return false;
        }
        
        // Check if any rows were affected
        $rowCount = $qry->rowCount();
        if ($rowCount === 0) {
            error_log("No rows affected when updating account status for manager ID: $managerId");
            // Still return true if the query executed successfully but no rows were affected
            // This can happen if the status is already set to the requested value
            return true;
        }
        
        return true;
    } catch (PDOException $e) {
        error_log("PDO Exception in updateAccountStatus: " . $e->getMessage());
        return false;
    }
}

    public function fetchAllUsers() {
        try {
            $sql = "SELECT id, firstName, lastName, profileImg, role_id FROM accounts ORDER BY firstName, lastName";
            $qry = $this->db->connect()->prepare($sql);
            $qry->execute();
            
            return $qry->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching users: " . $e->getMessage());
            return [];
        }
    }

    public function fetchAdminUsers() {
        try {
            $sql = "SELECT a.id, a.firstName, a.lastName, a.profileImg, a.role_id, r.roleName 
                    FROM accounts a 
                    LEFT JOIN roles r ON a.role_id = r.roleID 
                    WHERE a.role_id = 1 OR a.role_id = 2 
                    ORDER BY a.firstName, a.lastName";
            $qry = $this->db->connect()->prepare($sql);
            $qry->execute();
            
            return $qry->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching admin users: " . $e->getMessage());
            return [];
        }
    }

    public function fetchContentManagersBySubpage($subpage_id) {
        try {
            $sql = "SELECT *
                    FROM accounts a 
                    WHERE a.subpage_assigned = :subpage_id";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':subpage_id', $subpage_id, PDO::PARAM_INT);
            $qry->execute();
            
            $managers = $qry->fetchAll(PDO::FETCH_ASSOC);
            
            // If no specific managers found for this subpage, fall back to admin users
            if (empty($managers)) {
                $sql = "SELECT *
                        FROM accounts a 
                        WHERE a.role_id = 1";
                        
                $qry = $this->db->connect()->prepare($sql);
                $qry->execute();
                $managers = $qry->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $managers;
        } catch (PDOException $e) {
            error_log("Error fetching content managers by subpage: " . $e->getMessage());
            return [];
        }   
    }

    function addCollege($collegeName, $logoPath, $pageID) {
        $sql = "INSERT INTO subpages
        (pagesID, subPageName, subPagePath, imagePath, isCollege) 
        VALUES 
        (:pageID, :collegeName, '#', :logoPath, 1);";
        $qry = $this->db->connect()->prepare($sql);

        $qry->bindParam(':pageID', $pageID);
        $qry->bindParam(':collegeName', $collegeName);
        $qry->bindParam(':logoPath', $logoPath);

        return ($qry->execute());
    }

    function addNewCollegeName($pageID, $indicator, $elemType, $content, $desc) {
        $sql0 = "SELECT subpageID from subpages WHERE subPageName = :content";
        $qry0 = $this->db->connect()->prepare($sql0);
        $qry0->bindParam(':content', $content);
        $qry0->execute();
        $data = $qry0->fetchColumn();
        
        $sql = "INSERT INTO page_sections (pageID, subpage, indicator, elemType, content, description)
        VALUES (:pageID, :subpage, :indicator, :elemType, :content, :desc);";
        $qry = $this->db->connect()->prepare($sql);

        $qry->bindParam(':pageID', $pageID);
        $qry->bindParam(':subpage', $data);
        $qry->bindParam(':elemType', $elemType);
        $qry->bindParam(':indicator', $indicator);
        $qry->bindParam(':content', $content);
        $qry->bindParam(':desc', $desc);

        return ($qry->execute());
    }

    function fetchSHSStrands() {
        $sql = "SELECT * FROM page_sections 
                WHERE subpage = 31 
                AND indicator = 'Strand' ";        
        $qry = $this->db->connect()->prepare($sql);
        
        if ($qry->execute()) {
            return $qry->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }
    
    // Get strand-related items
    public function getStrandRelatedItems($strandID) {
        try {
            // First get the group_id for this strand
            $groupSql = "SELECT group_id FROM page_sections WHERE sectionID = :sectionID";
            $groupStmt = $this->db->connect()->prepare($groupSql);
            $groupStmt->bindParam(':sectionID', $strandID);
            $groupStmt->execute();
            $groupId = $groupStmt->fetchColumn();
            
            // If we have a group_id, use it to fetch related items
            if ($groupId) {
                $sql = "SELECT * FROM page_sections 
                        WHERE group_id = :groupId
                        AND indicator = 'Strand'";
                
                $stmt = $this->db->connect()->prepare($sql);
                $stmt->bindParam(':groupId', $groupId);
                $stmt->execute();
                
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Fallback to the old method if no group_id
                $sql = "SELECT * FROM page_sections 
                        WHERE subpage = 31 
                        AND indicator = 'Strand' 
                        AND (description = 'strand-desc' 
                             OR description = 'strand-desc-end' 
                             OR description LIKE 'strand-item-%')";
                
                $stmt = $this->db->connect()->prepare($sql);
                $stmt->execute();
                
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }
    function getLastInsertedCollegeId($collegeName) {
    $sql = "SELECT subpageID FROM subpages WHERE subPageName = ? ORDER BY subpageID DESC LIMIT 1";
    $qry = $this->db->connect()->prepare($sql);
    $qry->execute([$collegeName]);
    $result = $qry->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['subpageID'] : false;
}

function updateEstablishedYear($subpageId, $year) {
    $sql = "UPDATE subpages SET established_year = ? WHERE subpageID = ?";
    $qry = $this->db->connect()->prepare($sql);
    return $qry->execute([$year, $subpageId]);
}
}
?>
