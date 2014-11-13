<?php

    session_start();

    require_once("inc_functions.php");

    openHeader();
    echo "<title>Edit Database - CIT</title>";
    closeHeader();
    
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
    
    outputFooter();

?>

