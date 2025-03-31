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

        function fetchSubpages($pageID){
            $sql = "SELECT * from subpages WHERE pagesID = :pageID";
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
            $sql = "SELECT subpageID, subPageName FROM subpages";
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
    }

?>