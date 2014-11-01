<?php

function header_out($title = "PHP Test"){
    echo "<html>";

    echo "<head>";
    
    echo "<title>".$title."</title>";
    
    echo "</head>";
    
    echo "<body bgcolor = 'gray'>";
}

function footer_out(){
    echo "</body>";
    
    echo "</html>";
}


?>