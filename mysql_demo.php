<?php


    session_start();
    
    require_once("../constants.php");
    require_once("functions/gui.php");
    require_once("functions/db.php");
    $db = Db::getDbInstance();
    
    header_out("PHP Script Testing");
    
    echo "<h2>Running MySQL Scripts</h2>";
    
    if(!empty($_GET['data']) && (filter_input(INPUT_GET, "data", FILTER_SANITIZE_SPECIAL_CHARS) == 'reset')){
        echo "<br/>Data Reset<br/>";
        $db->wipeDB(MYSQL_DATABASE, true);
        $db->insertTestData(true);
    }    
    
    $sql = 
    "
    SELECT *
    FROM user
    ";
    
    $res = $db->getRset($sql);
    
    echo "<br/>";
    
    print_r($res);
    
    echo "<br/><br/>";
    
    echo "<table border='1'>";
    echo 
    "
    <th>user_id</th>
    <th>email</th>
    <th>first_name</th>
    <th>last_name</th>
    <th>password</th>
    <th>auth_level</th>
    <th>active</th>
    ";
    
    for($i = 0;$i<count($res);$i++){
        echo "<tr>";
        
        echo 
        "
        <td>".$res[$i]['user_id']."</td>
        <td>".$res[$i]['email']."</td>
        <td>".$res[$i]['first_name']."</td>
        <td>".$res[$i]['last_name']."</td>
        <td>".$res[$i]['password']."</td>
        <td>".$res[$i]['auth_level']."</td>
        <td>".$res[$i]['active']."</td>
        ";
        
        echo "</tr>";
    }
    
    echo "</table>";
    
    $sql = 
    "
    SELECT *
    FROM analysis
    WHERE analysis_id = 200
    ";
    
    $res = $db->getRset($sql);
    
    echo "<br/><br/>";
    
    echo "<table border='1'>";
    echo 
    "
    <th>analysis_id</th>
    <th>analysis_name</th>
    <th>user_id</th>
    <th>notes</th>
    <th>timestamp</th>
    ";
    
    for($i = 0;$i<count($res);$i++){
        echo "<tr>";
        
        echo 
        "
        <td>".$res[$i]['analysis_id']."</td>
        <td>".$res[$i]['analysis_name']."</td>
        <td>".$res[$i]['user_id']."</td>
        <td>".$res[$i]['notes']."</td>
        <td>".$res[$i]['timestamp']."</td>
        ";
        
        echo "</tr>";
    }
    
    echo "</table>";
    
    echo "<br/><br/>";
    
    $chemical = "Lead";
    $object = "Paint";
    
    $sql = 
    "
    SELECT danger_level
    FROM chemical
    JOIN contaminant USING(chemical_id)
    JOIN object USING(object_id)
    WHERE chemical_name = '".$chemical."'
    AND object_name = '".$object."'
    ";
    
    $danger_level = $db->getVal($sql);
    
    echo "The dangerous level of ".$chemical." in ".$object." is ".$danger_level." ppm.";
            
    $db->closeDb();
    
    footer_out();

?>
