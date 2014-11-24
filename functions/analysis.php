<?php

/**
 * Handles all analysis functions
 * @author  Jeff and Zack
 */

class Analysis{
    
	//Query to list elements in dropdown
	public function listChemicals($chemicals) {
        foreach ($chemicals as $row) {
            foreach ($row as $chemical) {
                echo '<option value="' . $chemical . '">' . $chemical . '</option>';
            }
        }
    }

	//Query to list objects in dropdown
    public function listObjects($objects) {
        foreach ($objects as $row) {
            foreach ($row as $object) {
                echo '<option value="' . $object . '">' . $object . '</option>';
            }
        }
    }
	
	// Query for initial page setup
	public function initialContent(){
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
	
	// Build Results Table, echoes through to ajax query
	public function search($element, $object){
		$db = Db::getDbInstance();
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
	}
	
}
?>
