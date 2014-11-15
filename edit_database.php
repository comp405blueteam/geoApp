<?php

    session_start();

    require_once("inc_functions.php");
        
    $db = Db::getDbInstance();
    
    if(isset($_POST['listContaminants'])){
        listContaminants();
        exit;
    }
    
    if(isset($_POST['elementInput'])){
        $cm = ContaminantManager::getCMInstance();
        
        $element = trim(sanitize($_POST['elementInput']));
        if(!empty($element)){
            $cm->addChemical($element);
        }
        
        outputOptionsById("chemical","chemical_id","chemical_name");
        exit;
    }
    
    if(isset($_POST['objectInput'])){
        $cm = ContaminantManager::getCMInstance();
        
        $object = trim(sanitize($_POST['objectInput']));
        if(!empty($object)){
            $cm->addObject($object);
        }
        
        outputOptionsById("object","object_id","object_name");
        exit;
    }
    
    if(isset($_POST['element']) && isset($_POST['object']) && isset($_POST['ppm'])){
        $cm = ContaminantManager::getCMInstance();
        
        $element = trim(sanitize($_POST['element']));
        $object = trim(sanitize($_POST['object']));
        $ppm = trim(sanitize($_POST['ppm']));
        
        if(!empty($element) && !empty($object) && !empty($ppm)){
            
            $cm->updateContaminant($element, $object, $ppm);
            
            echo 'Updated';
            
            exit;
        }
        
        echo "Invalid parameters";
        exit;
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
        
        function addElement(){
            var element = prompt("Enter the name of the new element: ");
            if(!element|| element == ""){
                alert('Invalid element');
                return;
            }
            
            var dataString = {elementInput:element};
            $.ajax({        
                type: "POST",
                url: <?php echo "'".BASE_URL."edit_database.php'" ?>,
                data: dataString,
                cache: false,
                success: function(html)
                {
                    document.getElementById("elementSelect").innerHTML = html;
                }

            });
        }
        
        function addObject(){
            var object = prompt("Enter the name of the new object: ");
            if(!object || object == ""){
                alert('Invalid object');
                return;
            }
            
            var dataString = {objectInput:object};
            $.ajax({        
                type: "POST",
                url: <?php echo "'".BASE_URL."edit_database.php'" ?>,
                data: dataString,
                cache: false,
                success: function(html)
                {
                    document.getElementById("objectSelect").innerHTML = html;
                }

            });
        }
                
    </script>
    
<?php closeHeader($title); ?> 
    
    <div id="content">
        <form name="contentForm" id="contentForm">
            <div id="contentLeftWindow">
                <div id="contentLeftWindowContents">
                    <table>
                        <tr>
                            <td>
                                Element:<br/>
                                <select name="elementSelect" id="elementSelect">
                                    <?php outputOptionsById("chemical","chemical_id","chemical_name"); ?>
                                </select>
                                <br/><br/>
                            </td>
                            <td>
                                <button type="button" onclick="addElement();">Add Element</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Item type:<br/>
                                <select name="objectSelect" id="objectSelect">
                                    <?php outputOptionsById("object","object_id","object_name"); ?>
                                </select><br/><br/>
                            </td>
                            <td>
                                <button type="button" onclick="addObject();">Add Object</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                New PPM:<br/>
                                <input name="ppmInput" id="ppmInput"/><br/><br/>
                            </td>
                        </tr>
    			 </table>
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
    				    <button type="button" name="confirmChangesButton" id="confirmChangesButton" 
                                            onclick="if(confirm('You are about to make changes to the database. Click OK to proceed or Cancel to return.')) 
                                            updateDatabase(); else alert('Changes cancelled')">Confirm Changes</button><br/><br/>
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
