<?php

    session_start();

    require_once("../geoApp/functions/db.php");
    require_once("../constants.php");
	require_once("functions/utils.php");
	$db = Db::getDbInstance();
    
    $chemicals = array();
    $objects = array();

    $sql =
    "
    SELECT chemical_name
    FROM chemical
    ";

    $chemicals = $db->getRset($sql);

    $sql =
    "
    SELECT object_name
    FROM object
    ";

    $objects = $db->getRset($sql);
	
	if (isset($_POST['element']) || isset($_POST['object'])){
		$element = trim(sanitize(cleanInput($_POST['element'])));
		$object = trim(sanitize(cleanInput($_POST['object'])));
		$sql =
		"
		SELECT contaminant.danger_level, object.object_name, chemical_name
		FROM contaminant
		JOIN object USING(object_id)
		JOIN chemical USING(chemical_id)
		WHERE chemical.chemical_name = chemical.chemical_name
		";
		
		if (!empty($element)){
		
			$sql .= "AND chemical.chemical_name = '$element'";
		}
		
		if (!empty($object)){
		
			$sql .= "AND object.object_name = '$object'";
		}
		
		$results = $db->getRset($sql);		
		
		echo "<table width='100%'>";
		
		echo "<tr>";
			
		echo "<td>"."<p>Item Type</p>"."</td>";
		echo "<td>"."<p>Element</p>"."</td>";
		echo "<td>"."<p>Contaminant Level</p>"."</td>";
			
		echo "</tr>";
		
		for($i = 0;$i < count($results);$i++){
			
			echo "<tr>";
			
			echo "<td>".$results[$i]['object_name']."</td>";
			echo "<td>".$results[$i]['chemical_name']."</td>";
			echo "<td>".$results[$i]['danger_level']."</td>";
			
			echo "</tr>";
		}
		
		echo "</table>";
		
		exit();
	}
?>

<?php

    function listChemicals($chemicals) {
        foreach($chemicals as $row) {
	    foreach($row as $chemical) {
		echo '<option value="' . $chemical . '">' . $chemical . '</option>';
	    }
        }
    }

    function listObjects($objects) {
        foreach($objects as $row) {
	    foreach($row as $object) {
		echo '<option value="' . $object . '">' . $object . '</option>';
	    }
        }
    }

?>

<?php
	
	function initialContent(){
	$db = Db::getDbInstance();
	
		$sql =
		"
		SELECT contaminant.danger_level, object.object_name, chemical.chemical_name
		FROM contaminant
		JOIN object USING(object_id)
		JOIN chemical USING(chemical_id)
		";
		
		$results = $db->getRset($sql);		
		
		echo "<table>";
		
		echo "<tr>";
			
		echo "<td>"."<p>Item Type</p>"."</td>";
		echo "<td>"."<p>Element</p>"."</td>";
		echo "<td>"."<p>Contaminant Level</p>"."</td>";
			
		echo "</tr>";
		
		for($i = 0;$i < count($results);$i++){
			
			echo "<tr>";
			
			echo "<td>".$results[$i]['object_name']."</td>";
			echo "<td>".$results[$i]['chemical_name']."</td>";
			echo "<td>".$results[$i]['danger_level']."</td>";
			
			echo "</tr>";
		
		}
		
		echo "</table>";
	}
?>
	
	
<html>

<head>

<link href="StyleSheet.css" rel="stylesheet" type="text/css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script>

function searchChange(){

var element = document.getElementById("elementSelect").value;
var object = document.getElementById("objectSelect").value;
var dataString = {element:element, object:object};



$.ajax({        
                type: "POST",
                url: <?php echo "'".BASE_URL."quick_search.php'" ?>,
                data: dataString,
                cache: false,
                success: function(html)
                {
                    document.getElementById('resultsTextarea').innerHTML = html;
                }
            });
		return;	
  }

</script>

</head>

<div class="container">
    
    <div id="sidebar">
        <div id="sidebarQuickSearch">
            <a href="quick_search.php"> <img style="max-width:100%; max-height:100%;" src="images/quick_search_label.png" /></a>
        </div>
        <div id="sidebarFullAnalysis">
            <a href="full_analysis.php"> <img style="max-width:100%; max-height:100%;" src="images/full_analysis_label.png" /></a>
        </div>
        <div id="sidebarReportsLogs">
            <a href="reports_logs.php"> <img style="max-width:100%; max-height:100%;" src="images/reports_logs_label.png" /></a>
        </div>
        <div id="sidebarUpperBlankSpace"></div>
        <div id="sidebarManageAccounts">
            <a href="manage_accounts.php"> <img style="max-width:100%; max-height:100%;" src="images/manage_accounts_label.png" /></a>
        </div>
        <div id="sidebarEditDatabase">
            <a href="edit_database.php"> <img style="max-width:100%; max-height:100%;" src="images/edit_database_label.png" /></a>
        </div>
        <div id="sidebarLowerBlankSpace"></div>
        <div id="sidebarUserInfo">CURRENT USER INFO</div>
    </div>
    
    <div id="applicationLogo">IMAGE</div>
    
    <div id="header">
    	<div id="headerTitle">Quick Search</div>
    	<div id="headerLogin">
            <a href="login.php">Login/Logout</a>
        </div>
    </div>
    
    <div id="content">
        <form name="contentForm" id="contentForm">
    	   <div id="contentLeftWindow">
    		  <div id="contentLeftWindowContents">
    			 Element:</br>
    			 <select name="elementSelect" id="elementSelect" onChange="searchChange()">
    				 <option value="">All</option>
                     <?php listChemicals($chemicals); ?>
    			 </select></br></br>
    			 Item type:</br>
    			 <select name="objectSelect" id="objectSelect" onChange="searchChange()">
    				 <option value="">All</option>
                     <?php listObjects($objects); ?>
    			 </select></br>
    		  </div>
    	   </div>
    	   <div id="contentRightWindow">
    		  <div id="contentRightWindowContents">
    			 <div name="resultsTextarea" id="resultsTextarea" style="resize:none; overflow:auto; overflow-x:hidden; width:100%; height:99%;";><?php initialContent(); ?></div>
    		  </div>
    	   </div>
        </form>
    </div>

</div>

</html>
