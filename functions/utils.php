<?php
/**
 * Common utilites for all files
 * @author  Justin, George
 */

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
        $db=Db::getDbInstance();
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

//sends a file to the user
function start_send_file($filename){
    $now = gmdate("D, d M Y H:i:s");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: ".$now." GMT");

    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    header("Content-Disposition: attachment; filename=".$filename);
    header("Content-Transfer-Encoding: binary");
}

//gets and outputs a csv file given an array
function create_csv_file(array &$array){
    if (count($array) == 0) {
        return null;
    }
   
    ob_start();
    $df = fopen("php://output", 'w');
   
    foreach ($array as $fields) {
        fputcsv($df, $fields);
    }
   
    fclose($df);
    return ob_get_clean();
}


?>