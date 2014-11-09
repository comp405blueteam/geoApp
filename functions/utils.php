<?php
    // Function for stripping out malicious bits
    function cleanInput($input) {
        $search = array(
            '@<script[^>]*?>.*?</script>@si', // Strip out javascript
            '@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
            '@<style[^>]*?>.*?</style>@siU', // Strip style tags
            '@<![\s\S]*?--[ \t\n\r]*>@' // Strip multi-line comments
        );
        $output = preg_replace($search, '', $input);
        return $output;
    }
 
    // Sanitization function
    function sanitize($input) {
        if (is_array($input)) {
            foreach($input as $var=>$val) {
                $output[$var] = sanitize($val);
            }
        }
        else {
            if (get_magic_quotes_gpc()) {
                $input = stripslashes($input);
            }
            $input = cleanInput($input);
            $output = $db->realEscapeString($input);
        }
        return $output;
}


?>