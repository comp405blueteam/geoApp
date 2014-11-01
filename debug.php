<?php

    session_start();

    require_once("../constants.php");
    require_once("functions/gui.php");
    require_once("functions/db.php");

    header_out("Debug Messages");
    
    $sql = 
    "
    SELECT timestamp, tag, message
    FROM debug
    ";
    
    $res = $db->getRset($sql);
    
    echo "<br/>";
    
    echo "<br/><br/>";
    
    echo "<table border='1'>";
    echo 
    "
    <th>timestamp</th>
    <th>tag</th>
    <th>message</th>
    ";
    
    for($i = 0;$i<count($res);$i++){
        echo "<tr>";
        
        echo 
        "
        <td>".$res[$i]['timestamp']."</td>
        <td>".$res[$i]['tag']."</td>
        <td>".$res[$i]['message']."</td>
        ";
        
        echo "</tr>";
    }
    
    echo "</table>";
    
    $db->closeDb();
    
    footer_out();

?>

