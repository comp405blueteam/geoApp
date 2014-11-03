<?php

    session_start();

    require_once("../constants.php");
    require_once("functions/utils.php");
    require_once("functions/db.php");
    require_once("functions/gui.php");   
    
    
    $db = Db::getDbInstance();
    
    if(isset($_POST['listContaminants'])){
        listContaminants();
        exit;
    }
    
    if(isset($_POST['element']) && isset($_POST['object']) && isset($_POST['ppm'])){
        $element = trim(sanitize($_POST['element']));
        $object = trim(sanitize($_POST['object']));
        $ppm = trim(sanitize($_POST['ppm']));
        
        if(!empty($element) && !empty($object) && !empty($ppm)){
            ini_set('display_errors',1); error_reporting(E_ALL);
            
            $sql = 
            "
            UPDATE contaminant
            SET danger_level = ".$ppm."
            WHERE chemical_id = ".$element."
            AND object_id = ".$object."
            ";
            
            $db->update($sql);
            
            echo $sql;
            
            exit;
        }
        
        echo "Invalid parameters";
        exit;
    }
    
    $chemicals = array();
    $objects = array();

    $sql =
    "
    SELECT chemical_id AS id, chemical_name AS name
    FROM chemical
    ";

    $chemicals = $db->getRset($sql);

    $sql =
    "
    SELECT object_id AS id, object_name AS name
    FROM object
    ";

    $objects = $db->getRset($sql);

    function outputOptionsById($items){
        for($i = 0;$i<count($items);$i++){
            echo '<option value="'.$items[$i]['id'].'">'.$items[$i]['name'].'</option>';                    
        }
    }
    
    function listContaminants(){
        global $db;
        $sql = 
        "
        SELECT chemical_name, object_name, danger_level
        FROM contaminant
        JOIN chemical USING (chemical_id)
        JOIN object USING (object_id)
        ";
                                
        $contams = $db->getRset($sql);

        echo "<table width = '100%'>";

        echo "<tr>";
        echo "<th>Element Name</th>";
        echo "<th>Object Name</th>";
        echo "<th>Danger Level</th>";
        echo "</tr>";

        for($i = 0;$i < count($contams);$i++){
            echo "<tr>";
            echo "<td>".$contams[$i]['chemical_name']."</td>";
            echo "<td>".$contams[$i]['object_name']."</td>";
            echo "<td>".$contams[$i]['danger_level']."</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    
    $title = 'Edit Database';
    openHeader($title);
    
?>
    <script>
    
        var databaseUpdates = [];
    
    
        function addContamLevel(){
            var element = document.getElementById('elementSelect').value;
            var object = document.getElementById('objectSelect').value;
            var regexp = /^\d*\.?\d+$/;
            var ppm = document.getElementById('ppmInput').value
            var ppmMatch = regexp.exec(ppm);
            
            if(element == ""){
                alert('A valid element has not been selected.');
                return;
            }
            
            if(object == ""){
                alert('A valid object has not been selected.');
                return;
            }
            
            if(!ppmMatch){
                alert('A valid PPM has not been entered.');
                document.getElementById('ppmInput').value = "";
                return;
            }
            
            $("#addTable tbody").append("<tr><td>"+$('#elementSelect option:selected').text()+"</td><td>"+$('#objectSelect option:selected').text()+"</td><td>"+ppm+"</td></tr>");
            databaseUpdates.push(queueUpdate(element, object, ppm));
		
            return;	
        }
        
        function queueUpdate(element, object, ppm){
            return function(){
                var dataString = {element:element, object:object, ppm:ppm};
                $.ajax({        
                    type: "POST",
                    url: <?php echo "'".BASE_URL."edit_database.php'" ?>,
                    data: dataString,
                    async: false,
                    cache: false,
                    success: function(html)
                    {
                        return;
                        alert(html);
                    }
                    
                });
            }
        }
        
        function updateDatabase(){
            while (databaseUpdates.length > 0) {
                (databaseUpdates.shift())();   
            }
            
            var list = "list";
            var dataString = {listContaminants:list};
                $.ajax({        
                    type: "POST",
                    url: <?php echo "'".BASE_URL."edit_database.php'" ?>,
                    data: dataString,
                    cache: false,
                    success: function(html)
                    {
                        document.getElementById("resultsTextarea").innerHTML = html;
                    }
                    
                });
                
            document.getElementById("addTextarea").innerHTML = "<table width='100%' name='addTable' id='addTable'> \
                                                                        <tr> \
                                                                            <th>Element</th> \
                                                                            <th>Object</th> \
                                                                            <th>New PPM</th> \
                                                                        </tr> \
                                                                    </table>";
            
        }

        
    </script>
    
<?php closeHeader($title); ?> 
    
    <div id="content">
        <form name="contentForm" id="contentForm">
    	   <div id="contentLeftWindow">
    		  <div id="contentLeftWindowContents">
    			Element:<br/>
    			<select name="elementSelect" id="elementSelect">
    			      <option value="">Select an element...</option>
                      <?php outputOptionsById($chemicals); ?>
    			 </select><br/><br/>
    			 Item type:<br/>
    			 <select name="objectSelect" id="objectSelect">
    			      <option value="">Select an item type...</option>
                      <?php outputOptionsById($objects); ?>
    			 </select><br/><br/>
    			 New PPM:<br/>
    			 <input name="ppmInput" id="ppmInput"/><br/><br/>
                         <button type="button" name="addButton" id="addButton" onclick="addContamLevel();" >Add</button><br/><br/>
    			 <div disabled name="addTextarea" id="addTextarea" style="resize:none; overflow-y:auto; overflow-x:auto; width:100%; height:30%;">
                    <table width="100%" name="addTable" id="addTable">
                        <tr>
                            <th>Element</th>
                            <th>Object</th>
                            <th>New PPM</th>
                        </tr>
                    </table>
                 </div>
    			 <div id="itemContainer">
    				    <button type="button" name="confirmChangesButton" id="confirmChangesButton" onclick="updateDatabase();">Confirm Changes</button><br/><br/>
                        Caution: Changes made to the database are permanent
    			 </div>
    		  </div>
    	   </div>
    	   <div id="contentRightWindow">
    		  <div id="contentRightWindowContents">
    			 <div disabled name="resultsTextarea" id="resultsTextarea" style="resize:none; overflow:auto; width:100%; height:99%;";>
                             <?php listContaminants(); ?>
                         </div>
    		  </div>
    	   </div>
        </form>
    </div>

<?php outputFooter(); ?>
