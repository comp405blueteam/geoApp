<?php

    session_start();

    require_once("../geoApp/functions/db.php");
    require_once("../constants.php");
    
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

<html>

<head><link href="StyleSheet.css" rel="stylesheet" type="text/css"></head>

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
    			 <select name="elementSelect" id="elementSelect">
    				 <option value="temp">Select an element...</option>
                     <?php listChemicals($chemicals); ?>
    			 </select></br></br>
    			 Item type:</br>
    			 <select name="objectSelect" id="objectSelect">
    				 <option value="temp2">Select an item type...</option>
                     <?php listObjects($objects); ?>
    			 </select></br>
    			 <div id="itemContainer">
    				<button name="runSearchButton" id="runSearchButton">Run Search</button>
    			 </div>
    		  </div>
    	   </div>
    	   <div id="contentRightWindow">
    		  <div id="contentRightWindowContents">
    			 <textarea disabled name="resultsTextarea" id="resultsTextarea" style="resize:none; overflow-y:auto; overflow-x:auto; width:100%; height:99%;";>RESULTS - USERS CANNOT EDIT TEXTFIELD</textarea>
    		  </div>
    	   </div>
        </form>
    </div>

</div>

</html>