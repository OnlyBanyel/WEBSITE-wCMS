<?php
require_once __DIR__ . "/db_connection.class.php";

class ElementStyler {
    protected $db;

    function __construct() {
        $this->db = new Database;
    }

    /**
     * Get all available style options by category
     * 
     * @return array Array of style options grouped by category
     */
    public function getStyleOptions() {
        $sql = "SELECT * FROM styles ORDER BY type, className";
        $qry = $this->db->connect()->prepare($sql);
        
        if ($qry->execute()) {
            $styles = $qry->fetchAll(PDO::FETCH_ASSOC);
            $grouped = [];
            
            foreach ($styles as $style) {
                if (!isset($grouped[$style['type']])) {
                    $grouped[$style['type']] = [];
                }
                $grouped[$style['type']][] = [
                    'id' => $style['styleID'],
                    'className' => $style['className']
                ];
            }
            
            return $grouped;
        }
        
        return [];
    }

    /**
     * Get styles for a specific element
     * 
     * @param string $elementType The type of element (e.g., 'page_sections')
     * @param int $elementId The ID of the element
     * @return array Array of styles for the element
     */
    public function getElementStyles($elementType, $elementId) {
        $sql = "SELECT * FROM element_styles 
                WHERE element_type = :elementType 
                AND element_id = :elementId";
        
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':elementType', $elementType);
        $qry->bindParam(':elementId', $elementId);
        
        if ($qry->execute()) {
            $styles = $qry->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            
            foreach ($styles as $style) {
                $result[$style['style_category']] = $style['style_value'];
            }
            
            return $result;
        }
        
        return [];
    }

    /**
     * Save a style for an element
     * 
     * @param string $elementType The type of element
     * @param int $elementId The ID of the element
     * @param string $category The style category
     * @param string $value The style value (Tailwind class)
     * @return bool Success or failure
     */
    public function saveElementStyle($elementType, $elementId, $category, $value) {
        // First check if this style category already exists for this element
        $checkSql = "SELECT id FROM element_styles 
                    WHERE element_type = :elementType 
                    AND element_id = :elementId 
                    AND style_category = :category";
        
        $checkQry = $this->db->connect()->prepare($checkSql);
        $checkQry->bindParam(':elementType', $elementType);
        $checkQry->bindParam(':elementId', $elementId);
        $checkQry->bindParam(':category', $category);
        $checkQry->execute();
        
        $existingId = $checkQry->fetchColumn();
        
        if ($existingId) {
            // Update existing style
            $sql = "UPDATE element_styles 
                    SET style_value = :value 
                    WHERE id = :id";
            
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':value', $value);
            $qry->bindParam(':id', $existingId);
        } else {
            // Insert new style
            $sql = "INSERT INTO element_styles 
                    (element_type, element_id, style_category, style_value) 
                    VALUES 
                    (:elementType, :elementId, :category, :value)";
            
            $qry = $this->db->connect()->prepare($sql);
            $qry->bindParam(':elementType', $elementType);
            $qry->bindParam(':elementId', $elementId);
            $qry->bindParam(':category', $category);
            $qry->bindParam(':value', $value);
        }
        
        return $qry->execute();
    }

    /**
     * Get the combined Tailwind classes for an element
     * 
     * @param string $elementType The type of element
     * @param int $elementId The ID of the element
     * @return string Space-separated Tailwind classes
     */
    public function getElementClassString($elementType, $elementId) {
        $styles = $this->getElementStyles($elementType, $elementId);
        return implode(' ', $styles);
    }

    /**
     * Remove a style from an element
     * 
     * @param string $elementType The type of element
     * @param int $elementId The ID of the element
     * @param string $category The style category to remove
     * @return bool Success or failure
     */
    public function removeElementStyle($elementType, $elementId, $category) {
        $sql = "DELETE FROM element_styles 
                WHERE element_type = :elementType 
                AND element_id = :elementId 
                AND style_category = :category";
        
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':elementType', $elementType);
        $qry->bindParam(':elementId', $elementId);
        $qry->bindParam(':category', $category);
        
        return $qry->execute();
    }
}
?>
