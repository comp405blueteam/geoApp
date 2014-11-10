<?php

    session_start();

    require_once("inc_functions.php");
    
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
			
		echo "<th align='left'>"."<p>Item Type</p>"."</th>";
		echo "<th align='left'>"."<p>Element</p>"."</th>";
		echo "<th align='left'>"."<p>Contaminant Level</p>"."</th>";
			
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
		
		echo "<table width='100%'>";
		
		echo "<tr>";
			
		echo "<th align='left'>"."<p>Item Type</p>"."</th>";
		echo "<th align='left'>"."<p>Element</p>"."</th>";
		echo "<th align='left'>"."<p>Contaminant Level</p>"."</th>";
			
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
        
        $title = "Quick Search";
        openHeader($title);
?>
	
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

<?php closeHeader($title); ?> 


    
    <div id="content">
        <form name="contentForm" id="contentForm">
    	   <div id="contentLeftWindow">
    		  <div id="contentLeftWindowContents">
    			 Element:<br/>
    			 <select name="elementSelect" id="elementSelect" onChange="searchChange()">
    				 <option value="">All</option>
                     <?php listChemicals($chemicals); ?>
    			 </select><br/><br/>
    			 Item type:<br/>
    			 <select name="objectSelect" id="objectSelect" onChange="searchChange()">
    				 <option value="">All</option>
                     <?php listObjects($objects); ?>
    			 </select><br/>
    		  </div>
    	   </div>
    	   <div id="contentRightWindow">
    		  <div id="contentRightWindowContents">
    			 <div name="resultsTextarea" id="resultsTextarea" style="resize:none; overflow:auto; overflow-x:hidden; width:100%; height:99%;";><?php initialContent(); ?></div>
    		  </div>
    	   </div>
        </form>
    </div>

<?php outputFooter(); ?>
