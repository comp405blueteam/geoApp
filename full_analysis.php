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
	
	// IF ajax query for adding item to analysis
	if (isset($_POST['element']) & isset($_POST['object']) & isset($_POST['ppm']) & !isset($_POST['flag'])){
		
		// Sanitize values for
		$element = trim(sanitize(cleanInput($_POST['element'])));
		$object = trim(sanitize(cleanInput($_POST['object'])));
		$PPM = trim(sanitize(cleanInput($_POST['ppm'])));
		
		// return values
		echo json_encode(array("element" => $element, "object" => $object, "ppm" => $PPM));
		
		exit();
	}
        
        // If ajax query for results
        if (isset($_POST['element']) & isset($_POST['object']) & isset($_POST['ppm']) & isset($_POST['flag'])){
            
            $element = $_POST['element'];
            $object = $_POST['object'];
            $ppm = $_POST['ppm'];
            $maxPPM;
            
            $sql =
            "
            SELECT contaminant.danger_level
            FROM contaminant
            JOIN object
            USING ( object_id ) 
            JOIN chemical
            USING ( chemical_id ) 
            WHERE chemical.chemical_name = chemical.chemical_name
            AND chemical.chemical_name =  '$element'
            AND object.object_name =  '$object'
            ";
            
            $maxPPM = $db->getVal($sql);
            
            if ($ppm > $maxPPM){
                echo "<tr style='color:red'>";
                
                
            }
            
            if ($ppm <= $maxPPM){
                echo "<tr style='color:lime'>";                
            }
            
            echo "<td align='left'>".$object."</td>";
            echo "<td align='left'>".$element."</td>";
            echo "<td align='left'>".$ppm." PPM"."</td>";
            echo "<td align='left'>".$maxPPM." PPM"."</td>";
            echo "</tr>";
            
            exit();
            
        }
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

<script>

// Variables to hold list of items to be analyzed
var addElement = new Array;
var addObject = new Array;
var addPPM = new Array;
var results = new Array;

//Runs when user clicks add button
function add(){
    var element = document.getElementById("elementSelect").value;
    var object = document.getElementById("objectSelect").value;
    var ppm = document.getElementById("ppmInput").value;
    var dataString = {element:element, object:object, ppm:ppm};

    $.ajax({        
                type: "POST",
                url: <?php echo "'".BASE_URL."full_analysis.php'" ?>,
                dataType: "json",
                data: dataString,
                cache: false,
                success: function(results)
                {                   
				        // Add sanitized results to arrays
					addElement[addElement.length] = results.element;
					addObject[addObject.length] = results.object;
					addPPM[addPPM.length] = results.ppm;
					// var html holds the output for the addTextarea
					var html = "<table width='100%'>";
					html += "<tr>";
					html += "<th align='left'>";
                                        html += "<p>Item Type</p>";
                                        html += "</th>";
					html += "<th align='left'>";
                                        html += "<p>Element</p>";
                                        html += "</th>";
					html += "<th align='left'>";
                                        html += "<p>Sample's Contaminant Level</p>";
                                        html += "</th>";
					html += "</tr>";
					
					// Add all items in list to the output
					for(var i = 0; i < addElement.length; i++){
			
						html += "<tr>";
			
						html += "<td align='left'>";
						html += addObject[i];
						html += "</td>";
						
						html += "<td align='left'>";
						html += addElement[i];
						html += "</td>";
						
						html += "<td align='left'>";
						html += addPPM[i];
						html += " PPM";
						html += "</td>";
			
						html += "</tr>";
					}
					html += "</table>";
					document.getElementById('addTextarea').innerHTML = html;
                }
            });
		return;	
}

// Runs when user clicks analyze results button
function analyze(){
    var flag = 1;
    
    var html = "<table width='100%'>";
    html += "<tr>";
    html += "<th align='left'>";
    html += "<p>Item Type</p>";
    html += "</th>";
    html += "<th align='left'>";
    html += "<p>Element</p>";
    html += "</th>";
    html += "<th align='left'>";
    html += "<p>Sample's Contaminant Level</p>";
    html += "</th>";
    html += "<th align='left'>";
    html += "<p>Maximum Contaminant Level</p>";
    html += "</th>";
    html += "</tr>";

    for(var i = 0; i < addElement.length; i++){
        
        var dataString = {element:addElement[i], object:addObject[i], ppm:addPPM[i], flag:flag};
        
        $.ajax({        
                type: "POST",
                url: <?php echo "'".BASE_URL."full_analysis.php'" ?>,
                data: dataString,
                cache: false,
                async: false,
                success: function(results)
                {                    
                    html += results;
                }
            });
    }
    html += "</table>";
    document.getElementById('resultsTextarea').innerHTML = html;
}

</script>
   
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
    			 <button name="addButton" type="button" onclick="add()">Add</button><br/><br/>
    			 <div disabled name="addTextarea" id="addTextarea" style="resize:none; overflow-y:auto; overflow-x:auto; width:100%; height:30%;">RESULTS - USERS CANNOT EDIT TEXTFIELD</div>
    			 <div id="itemContainer">
    				    <button name="runAnalysisButton" type="button" id="runAnalysisButton" onclick="analyze()">Run Analysis</button>
    			 </div>
    		  </div>
    	   </div>
    	   <div id="contentRightWindow">
    		  <div id="contentRightWindowContents">
    		  	   <div disabled name="resultsTextarea" id="resultsTextarea" style="resize:none; overflow:auto; overflow-x:hidden; width:100%; height:99%;">RESULTS - USERS CANNOT EDIT TEXTFIELD</div>
    		  </div>
    	   </div>
        </form>
    </div>

<?php outputFooter(); ?>