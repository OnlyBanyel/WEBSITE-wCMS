<?php 
    function cleanInput($input){

        $input = trim($input);
        $input = strip_tags($input);
        $input = htmlspecialchars($input);
        $input = substr($input,0,255);

        return $input;
    }


    function validateInput($input, $type) {
        $input = cleanInput($input); 
        switch ($type) {
            case "text":
          
                return !empty($input) && is_string($input);
    
            case "email":
                return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
    
            case "number":
                return is_numeric($input);
    
            case "radio":
            case "select":
                return !empty($input);
    
            default:
                return false;
        }
    }
    
    
    // if (errText(validateInput($_POST[], "text")))
    // if (errText(validateInput($_POST[], "email")))
    // if (errText(validateInput($_POST[], text)))
    // if (errText(validateInput($_POST[], text)))
    function errText($bool) {
        return $bool ? "" : "Field should not be empty";
    }
    
    function errNum($bool) {
        return $bool ? "" : "Input must be a number";
    }
    
    function errEmail($bool) {
        return $bool ? "" : "Input must be a valid email address";
    }
    
    function errRadio($bool) {
        return $bool ? "" : "Please select one";
    }
    
    function errSelect($bool) {
        return $bool ? "" : "Please select at least one";
    }



?>