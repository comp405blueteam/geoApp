<?php
    require_once("inc_functions.php");

    session_start();
    
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
?>

<?php

    function listChemicals($chemicals) {
        foreach ($chemicals as $row) {
            foreach ($row as $chemical) {
                echo '<option value="' . $chemical . '">' . $chemical . '</option>';
            }
        }
    }

    function listObjects($objects) {
        foreach ($objects as $row) {
            foreach ($row as $object) {
                echo '<option value="' . $object . '">' . $object . '</option>';
            }
        }
    }
    
    $title = "Full Analysis";
    openHeader($title);
    closeHeader($title);

?>
   
    <div id="content">
        <form name="contentForm" id="contentForm">
    	   <div id="contentLeftWindow">
    		  <div id="contentLeftWindowContents">
    			 Element:<br/>
    			 <select name="elementSelect" id="elementSelect">
    				     <option value="temp">Select an element...</option>
                         <?php listChemicals($chemicals); ?>
    			 </select><br/><br/>
    			 Item type:<br/>
    			 <select name="objectSelect" id="objectSelect">
    				     <option value="temp2">Select an item type...</option>
                         <?php listObjects($objects); ?>
    			 </select><br/><br/>
    			 PPM:<br/>
    			 <input name="ppmInput" id="ppmInput"/><br/><br/>
    			 <button name="addButton">Add</button><br/><br/>
    			 <textarea disabled name="addTextarea" id="addTextarea" style="resize:none; overflow-y:auto; overflow-x:auto; width:100%; height:30%;";>RESULTS - USERS CANNOT EDIT TEXTFIELD</textarea>
    			 <div id="itemContainer">
    				    <button name="runAnalysisButton" id="runAnalysisButton">Run Analysis</button>
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

<?php outputFooter(); ?>