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
        public function insertSectionContent($column, $value, $indicator, $description, $elemType, $pageID, $subpageID = null) {
            // 🔹 Ensure spaces between words without leading space
            $indicator = preg_replace('/(?<!^)([A-Z])/', ' $1', $indicator);
            
            $sql = "INSERT INTO page_sections (pageID, subpage, indicator, description, elemType, $column) 
                    VALUES (:pageID, :subpageID, :indicator, :description, :elemType, :value)";
        
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(":value", $value);
            $qry->bindParam(":indicator", $indicator);
            $qry->bindParam(":description", $description);
            $qry->bindParam(":elemType", $elemType);
            $qry->bindParam(":pageID", $pageID);
            $qry->bindParam(":subpageID", $subpageID);
        
            return $qry->execute();
        }
        
        

        function getRowByIndicator($indicator) {
            $sql = "SELECT * FROM page_sections WHERE indicator = :indicator LIMIT 1";
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(":indicator", $indicator);
            $qry->execute();
            return $qry->fetch(PDO::FETCH_ASSOC);
        }
    }

?>