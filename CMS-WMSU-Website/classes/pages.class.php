<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/WEBSITE-WCMS/CMS-WMSU-Website/tools/functions.php";
require_once __DIR__ . "/db_connection.class.php";

    class Pages{
        protected $db;

        public $pageData;


        function __construct()
        {
            $this->db = new Database;
        }

        function fetchGenElements(){
            $sql = "SELECT * from generalelements";
        }

        function fetchPageData($pageID, $subpageID = null){

            if ($subpageID == null){
            $sql = "SELECT * from page_sections 
                    LEFT JOIN pages ON page_sections.pageID = pages.ID 
                    WHERE pageID = :pageID";

            $qry = $this->db->connect()->prepare($sql);

            $qry->bindParam(":pageID", $pageID);
            }
            else{
            $sql = "SELECT * from page_sections 
                    LEFT JOIN pages ON page_sections.pageID = pages.ID 
                    LEFT JOIN subpages ON page_sections.subpage = subpages.subpageID 
                    WHERE pageID = :pageID AND subpages.subpageID = :subpageID";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(":subpageID", $subpageID);
            $qry->bindParam(":pageID", $pageID);
            }
            

            if ($qry->execute())
            {
            $this->pageData = $qry->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $this->pageData = [];
            }
        return $this->pageData;
        }

        function fetchCollegeSubpages($pageID){
            $sql = "SELECT * from subpages WHERE pagesID = :pageID AND isCollege = 1";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':pageID', $pageID);

            if ($qry->execute()){
                $data = $qry->fetchAll(PDO::FETCH_ASSOC);
            }
            else{
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
        

        function editValue($newValue, $ID, $column){
            $sql0 = "SELECT * from page_sections WHERE sectionID = :ID";
            $qry0 = $this->db->connect()->prepare($sql0);
            $qry0->bindParam(":ID", $ID);
            if ($qry0->execute()){
                $data = $qry0->fetch(PDO::FETCH_ASSOC);
            }
            
            if(!$data){
                return False;
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
            if ($qry->execute())
            {
            $data = $qry->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $data = Null;
            }
        return $data;
        }

// Define the updateSectionContent function
        public function beginTransaction() {
            return $this->db->connect()->beginTransaction();
        }
    
        // Helper function to commit a transaction
        public function commit() {
            return $this->db->connect()->commit();
        }
    
        // Helper function to rollback a transaction
        public function rollback() {
            return $this->db->connect()->rollBack();
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
        
        function deleteVal($sectionID){
            $sql = "DELETE FROM page_sections WHERE sectionID = :sectionID";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':sectionID', $sectionID);
            return $qry->execute();
        }

        function fetchColleges(){
            $sql = "SELECT subpageID, subPageName FROM subpages WHERE isCollege = 1";
            $qry = $this->db->connect()->prepare($sql);

            if ($qry->execute()){
                $data = $qry->fetchAll(PDO::FETCH_ASSOC);
            }
            else{
                $data = null;
            }
            return $data;
        }

        function uploadLogo($destPath, $subpage){
            $sql = "UPDATE page_sections SET imagePath = :destPath WHERE subpage = :subpage AND description = 'carousel-logo'";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':destPath', $destPath);
            $qry->bindParam(':subpage', $subpage);

            return $qry->execute();
        }

        function uploadProfileImgs($destPath, $sectionID, $subpage){
            $sql = "UPDATE page_sections 
            SET imagePath = :destPath 
            WHERE subpage = :subpage 
              AND sectionID = :sectionID 
              AND description = 'carousel-img'";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':destPath', $destPath);
            $qry->bindParam(':sectionID', $sectionID);
            $qry->bindParam(':subpage', $subpage);

            return $qry->execute();
        }
        function uploadImgs($destPath, $sectionID, $subpage){
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

        public function changeContent($sectionID, $subpage, $value, $indicator = null, $description = null) {
            $sql = "UPDATE page_sections SET content = :value 
                    WHERE subpage = :subpage
                    AND sectionID = :sectionID";
            
            if ($indicator !== null) {
                $sql .= " AND indicator = :indicator";
            }
            if ($description !== null) {
                $sql .= " AND description = :description";
            }
            
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':value', $value);
            $qry->bindParam(':subpage', $subpage);
            $qry->bindParam(':sectionID', $sectionID);
            
            if ($indicator !== null) {
                $qry->bindParam(':indicator', $indicator);
            }
            if ($description !== null) {
                $qry->bindParam(':description', $description);
            }
            
            return $qry->execute();
        }

        function deleteItem($sectionID, $subpage){
            $sql = "DELETE FROM page_sections WHERE sectionID = :sectionID AND subpage = :subpage";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':sectionID', $sectionID);
            $qry->bindParam(':subpage', $subpage);
        }

        public function addContent($subpage, $indicator, $elemType, $content, $imagePath, $description) {
    try {
        $sql = "INSERT INTO page_sections 
                (pageID, subpage, indicator, elemType, content, imagePath, description, createdAt, updatedAt) 
                VALUES 
                (3, :subpage, :indicator, :elemType, :content, :imagePath, :description, NOW(), NOW())";
        
        $db = $this->db->connect();
        $qry = $db->prepare($sql);
        
        $qry->bindParam(':subpage', $subpage);
        $qry->bindParam(':indicator', $indicator);
        $qry->bindParam(':elemType', $elemType);
        $qry->bindParam(':content', $content);
        $qry->bindParam(':imagePath', $imagePath);
        $qry->bindParam(':description', $description);
        
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

        public function deleteContent($sectionID, $subpage) {
            try {
                $db = $this->db->connect();
                
                $sql = "DELETE FROM page_sections 
                        WHERE sectionID = :sectionID 
                        AND subpage = :subpage";
                
                $qry = $db->prepare($sql);
                $qry->bindParam(':sectionID', $sectionID);
                $qry->bindParam(':subpage', $subpage);
                
                return $qry->execute();
                
            } catch (PDOException $e) {
                error_log("Database Error: " . $e->getMessage());
                return false;
            }
        }

        function fetchContentManagers(){
            $sql = "SELECT * 
            FROM accounts 
            LEFT JOIN subpages ON accounts.subpage_assigned = subpages.subpageID 
            LEFT JOIN roles ON accounts.role_id = roles.roleID
            WHERE accounts.role_id = 2";

            $qry = $this->db->connect()->prepare($sql);

            if ($qry->execute()){
                $data = $qry->fetchAll(PDO::FETCH_ASSOC);
            }
            else{
                $data = null;
            }
            return $data;

        }

       // This is just the updateAccountStatus method from the Pages class
// Add this to your existing Pages class if it's different

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
        
        // Now update the status
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

/**
 * Fetch all users for messaging
 * 
 * @return array The users
 */
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
                LEFT JOIN roles r ON a.role_id = r.id 
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

    function addCollege($collegeName, $logoPath, $pageID){
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

    function addNewCollegeName($pageID, $indicator, $elemType, $content, $desc){

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

    /**
     * Fetch all strands for Senior High School
     * 
     * @return array The strands data
     */
    function fetchSHSStrands() {
        $sql = "SELECT * FROM page_sections 
                WHERE subpage = 31 
                AND indicator = 'Strand'
                ORDER BY sectionID ASC";
        
        $qry = $this->db->connect()->prepare($sql);
        
        if ($qry->execute()) {
            return $qry->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }
    
    /**
     * Update an existing strand or create a new one
     * 
     * @param int $subpage The subpage ID (31 for SHS)
     * @param string $strandID The section ID of the strand name
     * @param string $descID The section ID of the strand description
     * @param string $endDescID The section ID of the strand end description
     * @param string $strandName The name of the strand
     * @param string $strandDesc The description of the strand
     * @param string $strandEndDesc The end description of the strand
     * @param bool $isNew Whether this is a new strand
     * @return array Result with success status and message
     */
    function updateStrand($subpage, $strandID, $descID, $endDescID, $strandName, $strandDesc, $strandEndDesc, $isNew = false) {
        try {
            $db = $this->db->connect();
            $db->beginTransaction();
            
            // If it's a new strand, insert new records
            if ($isNew) {
                // Insert strand name
                $sql = "INSERT INTO page_sections (pageID, subpage, indicator, elemType, content, description) 
                        VALUES (3, :subpage, 'Strand', 'text', :content, 'strand-name')";
                $qry = $db->prepare($sql);
                $qry->bindParam(':subpage', $subpage);
                $qry->bindParam(':content', $strandName);
                $qry->execute();
                $newStrandID = $db->lastInsertId();
                
                // Insert strand description
                $sql = "INSERT INTO page_sections (pageID, subpage, indicator, elemType, content, description) 
                        VALUES (3, :subpage, 'Strand', 'text', :content, 'strand-desc')";
                $qry = $db->prepare($sql);
                $qry->bindParam(':subpage', $subpage);
                $qry->bindParam(':content', $strandDesc);
                $qry->execute();
                
                // Insert strand end description
                $sql = "INSERT INTO page_sections (pageID, subpage, indicator, elemType, content, description) 
                        VALUES (3, :subpage, 'Strand', 'text', :content, 'strand-desc-end')";
                $qry = $db->prepare($sql);
                $qry->bindParam(':subpage', $subpage);
                $qry->bindParam(':content', $strandEndDesc);
                $qry->execute();
                
                $db->commit();
                return [
                    'success' => true, 
                    'message' => 'Strand added successfully',
                    'strandID' => $newStrandID
                ];
            } else {
                // Update existing strand
                // Update strand name
                $sql = "UPDATE page_sections SET content = :content WHERE sectionID = :sectionID";
                $qry = $db->prepare($sql);
                $qry->bindParam(':content', $strandName);
                $qry->bindParam(':sectionID', $strandID);
                $qry->execute();
                
                // Update strand description
                $sql = "UPDATE page_sections SET content = :content WHERE sectionID = :sectionID";
                $qry = $db->prepare($sql);
                $qry->bindParam(':content', $strandDesc);
                $qry->bindParam(':sectionID', $descID);
                $qry->execute();
                
                // Update strand end description
                $sql = "UPDATE page_sections SET content = :content WHERE sectionID = :sectionID";
                $qry = $db->prepare($sql);
                $qry->bindParam(':content', $strandEndDesc);
                $qry->bindParam(':sectionID', $endDescID);
                $qry->execute();
                
                $db->commit();
                return [
                    'success' => true, 
                    'message' => 'Strand updated successfully'
                ];
            }
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            error_log("Error in updateStrand: " . $e->getMessage());
            return [
                'success' => false, 
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Add or update a strand outcome/subject
     * 
     * @param int $subpage The subpage ID
     * @param string $outcomeID The section ID of the outcome
     * @param string $content The content of the outcome
     * @param int $index The index of the outcome (for new items)
     * @param bool $isNew Whether this is a new outcome
     * @return array Result with success status and message
     */
    function updateStrandOutcome($subpage, $outcomeID, $content, $index = 1, $isNew = false) {
        try {
            if ($isNew) {
                // Insert new outcome
                $description = 'strand-item-' . $index;
                $sql = "INSERT INTO page_sections (pageID, subpage, indicator, elemType, content, description) 
                        VALUES (3, :subpage, 'Strand', 'text', :content, :description)";
                $qry = $this->db->connect()->prepare($sql);
                $qry->bindParam(':subpage', $subpage);
                $qry->bindParam(':content', $content);
                $qry->bindParam(':description', $description);
                $qry->execute();
                
                return [
                    'success' => true, 
                    'message' => 'Subject added successfully',
                    'outcomeID' => $this->db->connect()->lastInsertId()
                ];
            } else {
                // Update existing outcome
                $sql = "UPDATE page_sections SET content = :content WHERE sectionID = :sectionID";
                $qry = $this->db->connect()->prepare($sql);
                $qry->bindParam(':content', $content);
                $qry->bindParam(':sectionID', $outcomeID);
                $qry->execute();
                
                return [
                    'success' => true, 
                    'message' => 'Subject updated successfully'
                ];
            }
        } catch (PDOException $e) {
            error_log("Error in updateStrandOutcome: " . $e->getMessage());
            return [
                'success' => false, 
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete a strand and all its related content
     * 
     * @param string $strandID The section ID of the strand name
     * @return array Result with success status and message
     */
    function deleteStrand($strandID) {
        try {
            $db = $this->db->connect();
            
            // First, get the strand name to identify related items
            $sql = "SELECT content FROM page_sections WHERE sectionID = :sectionID";
            $qry = $db->prepare($sql);
            $qry->bindParam(':sectionID', $strandID);
            $qry->execute();
            $strandName = $qry->fetchColumn();
            
            if (!$strandName) {
                return [
                    'success' => false, 
                    'message' => 'Strand not found'
                ];
            }
            
            // Begin transaction
            $db->beginTransaction();
            
            // Delete the strand name entry
            $sql = "DELETE FROM page_sections WHERE sectionID = :sectionID";
            $qry = $db->prepare($sql);
            $qry->bindParam(':sectionID', $strandID);
            $qry->execute();
            
            // Delete all related entries (description, end description, and outcomes)
            $sql = "DELETE FROM page_sections 
                    WHERE subpage = 31 
                    AND indicator = 'Strand' 
                    AND (description = 'strand-desc' 
                         OR description = 'strand-desc-end' 
                         OR description LIKE 'strand-item-%')";
            $qry = $db->prepare($sql);
            $qry->execute();
            
            $db->commit();
            return [
                'success' => true, 
                'message' => 'Strand deleted successfully'
            ];
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            error_log("Error in deleteStrand: " . $e->getMessage());
            return [
                'success' => false, 
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete a strand outcome/subject
     * 
     * @param string $outcomeID The section ID of the outcome
     * @return array Result with success status and message
     */
    function deleteStrandOutcome($outcomeID) {
        try {
            $sql = "DELETE FROM page_sections WHERE sectionID = :sectionID";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':sectionID', $outcomeID);
            $qry->execute();
            
            return [
                'success' => true, 
                'message' => 'Subject deleted successfully'
            ];
        } catch (PDOException $e) {
            error_log("Error in deleteStrandOutcome: " . $e->getMessage());
            return [
                'success' => false, 
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
}

?>
