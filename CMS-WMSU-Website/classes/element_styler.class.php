<?php
require_once __DIR__ . "/db_connection.class.php";

class ElementStyler {
    protected $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    /**
     * Get all available style options from the styles table
     * 
     * @return array Associative array of style options grouped by type
     */
    public function getStyleOptions() {
        $sql = "SELECT * FROM styles ORDER BY type, className";
        $stmt = $this->db->connect()->prepare($sql);
        
        if ($stmt->execute()) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $options = [];
            
            foreach ($results as $row) {
                $options[$row['type']][] = $row;
            }
            
            return $options;
        }
        
        return [];
    }
    
    /**
     * Get element styles as a string
     * 
     * @param int $sectionID The section ID
     * @return string Space-separated class string or empty string if not found
     */
    public function getElementStyles($sectionID) {
        $sql = "SELECT styles FROM page_sections WHERE sectionID = :sectionID";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':sectionID', $sectionID, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result && $result['styles'] ? $result['styles'] : '';
        }
        
        return '';
    }
    
    /**
     * Get element styles as an array of class names
     * 
     * @param int $sectionID The section ID
     * @return array Array of class names
     */
    public function getElementStylesArray($sectionID) {
        $styles = $this->getElementStyles($sectionID);
        return $styles ? explode(' ', $styles) : [];
    }
    
    /**
     * Save element style
     * 
     * @param int $sectionID The section ID
     * @param string $category Style category
     * @param string $value Style value
     * @return bool True if successful, false otherwise
     */
    public function saveElementStyle($sectionID, $category, $value) {
        // Get current styles
        $currentStyles = $this->getElementStyles($sectionID);
        $stylesArray = $currentStyles ? explode(' ', $currentStyles) : [];
        
        // Remove any existing style from the same category
        $stylesArray = array_filter($stylesArray, function($style) use ($category) {
            // Check if the style starts with the category prefix
            $categoryPrefixes = [
                'font' => 'font-',
                'text-size' => 'text-',
                'text-weight' => 'font-',
                'text-color' => 'text-',
                'bg-color' => 'bg-',
                'padding' => 'p-',
                'margin' => 'm-',
                'border' => 'border',
                'border-color' => 'border-',
                'border-radius' => 'rounded'
            ];
            
            $prefix = isset($categoryPrefixes[$category]) ? $categoryPrefixes[$category] : $category;
            return strpos($style, $prefix) !== 0;
        });
        
        // Add the new style if it's not empty
        if (!empty($value)) {
            $stylesArray[] = $value;
        }
        
        // Convert back to space-separated string
        $stylesString = implode(' ', $stylesArray);
        
        // Save the updated styles
        $sql = "UPDATE page_sections SET styles = :styles WHERE sectionID = :sectionID";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':styles', $stylesString);
        $stmt->bindParam(':sectionID', $sectionID, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Remove all styles for an element
     * 
     * @param int $sectionID The section ID
     * @return bool True if successful, false otherwise
     */
    public function removeAllElementStyles($sectionID) {
        $sql = "UPDATE page_sections SET styles = NULL WHERE sectionID = :sectionID";
        $stmt = $this->db->connect()->prepare($sql);
        $stmt->bindParam(':sectionID', $sectionID, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    /**
     * Apply styles to an element for preview
     * 
     * @param int $sectionID The section ID
     * @return string Class string with all styles
     */
    public function getElementClassString($sectionID) {
        return $this->getElementStyles($sectionID);
    }
}
?>
