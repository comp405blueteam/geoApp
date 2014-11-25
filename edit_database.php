<?php
/**
 * This houses also the display code to edit the database. It also houses all page specific functions. 
 * @author  George
 * @author  GUI: Paul and Tom
 */

//start session and include functions
session_start();

require_once("inc_functions.php");

$db = Db::getDbInstance();

if (isset($_POST['listContaminants'])) {
    listContaminants();
    exit;
}

//deltes a contaminant association
if (isset($_POST['deleteContam'])) {
    $id = trim(sanitize($_POST['deleteContam']));
    if (!empty($id)) {
        $sql = "
            SELECT *
            FROM contaminant
            WHERE contam_id = " . $id . "
            ";

        $contam = $db->getRow($sql);

        $cm = ContaminantManager::getCMInstance();

        if (!empty($contam)) {
            if ($cm->deleteContaminant($id)) {
                echo json_encode(array("true", "Delete successful"));
            } else {
                echo json_encode(array("false", "Delete failed. Contaminant may be found in existing reports."));
            }
        } else {
            echo json_encode(array("false", "Invalid contaminant ID."));
        }
    } else {
        echo json_encode(array("false", "Invalid contaminant ID."));
    }
    exit();
}

//updates objects and elements names
if (isset($_POST['update'])) {
    $action = trim(sanitize($_POST['update']));
    $cm = ContaminantManager::getCMInstance();

    if ($action == 'chemical') {
        $chemical = trim(sanitize($_POST['elementInput']));
        $id = trim(sanitize($_POST['elementId']));

        if (!empty($chemical) && !empty($id)) {
            $res = $cm->updateChemical($id, $chemical);
            if (!empty($res)) {
                echo json_encode(array("true", "Update Successful"));
            } else {
                echo json_encode(array("false", "Update Failed"));
            }
        } else {
            echo json_encode(array("false", "Invalid element action."));
        }
    } else if ($action == 'object') {
        $object = trim(sanitize($_POST['objectInput']));
        $id = trim(sanitize($_POST['objectId']));

        if (!empty($object) && !empty($id)) {
            $res = $cm->updateObject($id, $object);
            if (!empty($res)) {
                echo json_encode(array("true", "Update Successful"));
            } else {
                echo json_encode(array("false", "Update Failed"));
            }
        } else {
            echo json_encode(array("false", "Invalid object action."));
        }
    } else {
        echo json_encode(array("false", "Invalid update action"));
    }

    exit;
}

//adds an element 
if (isset($_POST['elementInput'])) {
    $cm = ContaminantManager::getCMInstance();

    $element = trim(sanitize($_POST['elementInput']));
    if (!empty($element)) {
        $res = $cm->addChemical($element);
        if (!empty($res)) {
            echo json_encode(array("true", 'Update successful.'));
        } else {
            echo json_encode(array("false", 'Update failed.'));
        }
    } else {
        echo json_encode(array("false", 'Invalid element.'));
    }

    exit;
}

//adds an object
if (isset($_POST['objectInput'])) {
    $cm = ContaminantManager::getCMInstance();

    $object = trim(sanitize($_POST['objectInput']));
    if (!empty($object)) {
        $res = $cm->addObject($object);
        if (!empty($res)) {
            echo json_encode(array("true", 'Update successful.'));
        } else {
            echo json_encode(array("false", 'Update failed.'));
        }
    } else {
        echo json_encode(array("false", 'Invalid object.'));
    }

    exit;
}

//updates/iserts contaminant assocaites PPMs
if (isset($_POST['element']) && isset($_POST['object']) && isset($_POST['ppm']) && isset($_POST['flag'])) {
    $cm = ContaminantManager::getCMInstance();

    $element = trim(sanitize($_POST['element']));
    $object = trim(sanitize($_POST['object']));
    $ppm = trim(sanitize($_POST['ppm']));
    $action = trim(sanitize($_POST['flag']));

    if ($action == 'update') {
        if (!empty($element) && !empty($object) && !empty($ppm)) {
            if ($cm->updateContaminant($element, $object, $ppm)) {
                echo json_encode(array("true", "Update Successful"));
            }else{
                echo json_encode(array("false", "Update Failed"));
            }
        }
    } else if ($action == 'insert') {
        if (!empty($element) && !empty($object) && !empty($ppm)) {

            if ($cm->insertContaminant($element, $object, $ppm)) {
                echo json_encode(array("true", "Insert Successful"));
            }else{
                echo json_encode(array("false", "Insert Failed. Contaminant may already exist."));
            }
            
        }
    }else{
        echo json_encode(array("false", "Invalid parameters."));
    }

    
    exit;
}


$title = 'Edit Database';
openHeader($title);
?>
<script>
    //prompts to add element/object when approipiate options is selected
    function selectChange(){
        if (document.getElementById('elementSelect').value == "other"){
            addElement();
        }

        if (document.getElementById('objectSelect').value == "other"){
            addObject();
        }
    }
    
    //displays edit dialog for contaminant association
    function dbEdit(id){
        var edit = window.open(<?php BASE_URL ?>"edit_database.php?ecid=" + id, null, "height=300,width=340");
        edit.onload = function() {
            edit.onunload = function () {
                edit.opener.location.reload();
            };
        }
    }
    
    //displays add dialog for contams
    function dbAdd(){
        var edit = window.open(<?php BASE_URL ?>"edit_database.php?addContaminant=create", null, "height=300,width=340");
        edit.onload = function() {
            edit.onunload = function () {
                edit.opener.location.reload();
            };
        }
    }

    //displays dialog to edit chemicals(elements)
    function editChem(){
        var edit = window.open(<?php BASE_URL ?>"edit_database.php?editContaminant=chemical", null, "height=300,width=340");
        edit.onload = function() {
            edit.onunload = function () {
                edit.opener.location.reload();
            };
        }
    }

    //displays dialog to edit objects
    function editObj(){
        var edit = window.open(<?php BASE_URL ?>"edit_database.php?editContaminant=object", null, "height=300,width=340");
        edit.onload = function() {
            edit.onunload = function () {
                edit.opener.location.reload();
            };
        }
    }

    //displays delete dialog for contaminant association
    function deleteContam(){
        var id = document.getElementById("dcid").value;
        if (id && id != ""){
            var dataString = {deleteContam:id};
            $.ajax({
            type: "POST",
                    url: <?php echo "'" . BASE_URL . "edit_database.php'" ?>,
                    data: dataString,
                    async: false,
                    cache: false,
                    success: function(html)
                    {

                    if (html[0] == "true"){
                    alert(html[1]);
                            self.close();
                    } else{
                    alert(html[1]);
                    }

                    return;
                    },
                    dataType:"json"

            });
        } else{
            alert("Invalid contaminant ID.");
        }
    }

    //inserts new ppm association, calls ajax
    function addContam(){
        var element = document.getElementById('elementSelect').value;
        var object = document.getElementById('objectSelect').value;
        updatePPM(element, object, 'insert');
        return;
    }

    //edits contam ppm, calls ajax
    function editContamLevel(){
        var element = document.getElementById('chemicalId').value;
        var object = document.getElementById('objectId').value;
        updatePPM(element, object, 'update');
        return;
    }

    //inserts/updates ppm depending on flag
    function updatePPM(element, object, flag){
        var regexp = /^\d*\.?\d+$/;
        var ppm = document.getElementById('ppmInput').value
        var ppmMatch = regexp.exec(ppm);
        if (element == ""){
            alert('A valid element has not been selected.');
            return;
        }

        if (object == ""){
            alert('A valid object has not been selected.');
            return;
        }

        if (!ppmMatch){
            alert('A valid PPM has not been entered.');
            document.getElementById('ppmInput').value = "";
            return;
        }

        var dataString = {element:element, object:object, ppm:ppm, flag:flag};
        $.ajax({
        type: "POST",
                url: <?php echo "'" . BASE_URL . "edit_database.php'" ?>,
                data: dataString,
                async: false,
                cache: false,
                success: function(html)
                {

                if (html[0] == "true"){
                alert(html[1]);
                        self.close();
                } else{
                alert(html[1]);
                }

                return;
                },
                dataType:"json"

        });
        return;
    }

    //updates element name
    function updateChemical(chemical){
        chemical = document.getElementById('chemicalNameInput').value;
        var id = document.getElementById('chemicalSelect').value;
        if (chemical && chemical != ""){
            var action = "chemical";
            var dataString = {update:action, elementInput:chemical, elementId:id};
            $.ajax({
            type: "POST",
                    url: <?php echo "'" . BASE_URL . "edit_database.php'" ?>,
                    data: dataString,
                    cache: false,
                    success: function(html)
                    {
                    if (html[0] == "true"){
                    alert(html[1]);
                            self.close();
                    } else{
                    alert(html[1]);
                    }
                    },
                    dataType:"json"

            });
        } else{
            alert("Invalid element name.");
        }
    }
    
    //updates object name
    function updateObject(object){
        object = document.getElementById('objectNameInput').value;
        var id = document.getElementById('objectSelect').value;
        if (object && object != ""){
            var action = "object";
            var dataString = {update:action, objectInput:object, objectId:id};
            $.ajax({
                    type: "POST",
                    url: <?php echo "'" . BASE_URL . "edit_database.php'" ?>,
                    data: dataString,
                    cache: false,
                    success: function(html)
                    {
                    if (html[0] == "true"){
                    alert(html[1]);
                            self.close();
                    } else{
                    alert(html[1]);
                    }
                    },
                    dataType:"json"

            });
        } else{
            alert("Invalid object name.");
        }
    }
    
    //Adds new element by name
    function addElement(){
        var element = prompt("Enter the name of the new element: ");
        if (!element || element == ""){
            alert('Invalid element');
            return;
        }

        var dataString = {elementInput:element};
        $.ajax({
        type: "POST",
                url: <?php echo "'" . BASE_URL . "edit_database.php'" ?>,
                data: dataString,
                cache: false,
                dataType:"json",
                success: function(html)
                {
                if (html[0] == "true"){
                alert(html[1]);
                        location.reload();
                } else{
                alert(html[1]);
                }
                }
        });
    }

    //adds ne wobject by name
    function addObject(object){
        var object = prompt("Enter the name of the new object: ");
        if (!object || object == ""){
            alert('Invalid object');
            return;
        }

        var dataString = {objectInput:object};
        $.ajax({
                type: "POST",
                url: <?php echo "'" . BASE_URL . "edit_database.php'" ?>,
                data: dataString,
                cache: false,
                dataType:"json",
                success: function(html)
                {
                if (html[0] == "true"){
                alert(html[1]);
                        location.reload();
                } else{
                alert(html[1]);
                }
                }
        });
    }

</script>

<?php
//handles calls for add new contam association dialog
if (isset($_GET['addContaminant'])) {
    $action = sanitize($_GET['addContaminant']);

    if ($action == "create") {
        echo '<h2>Add Contaminant</h2>';

        echo '<div>';
        echo '<form>';
        echo '<table>';
        echo '<tr><td>Element Name:</td><td> <select id="elementSelect" onchange="selectChange();">';
        outputOptionsById("chemical", "chemical_id", "chemical_name");
        echo '<option value="other">Other</option>';
        echo '</select></td></tr>';

        //echo '<tr style="display: none;" id ="newElementRow"><td>New Element Name:</td><td> <input name="newElementInput" id="newElementInput"/></td></tr>';

        echo '<tr><td>Object Name:</td><td> <select id="objectSelect" onchange="selectChange();">';
        outputOptionsById("object", "object_id", "object_name");
        echo '<option value="other">Other</option>';
        echo '</select></td></tr>';

        //echo '<tr style="display: none;" id ="newObjectRow" style="display:none;"><td>New Oject Name:</td><td> <input name="newObjectInput" id="newObjectInput"/></td></tr>';

        echo '<tr><td>PPM:</td><td> <input name="ppmInput" id="ppmInput"/></td></tr>';
        echo '</table>';

        echo '<br/>';

        echo '<button type="button" onclick="addContam();">Add</button>&nbsp;&nbsp;&nbsp;';

        echo '</form>';
        echo '</div>';
    } else {
        echo "Invalid Operation.";
    }

    exit();
}

//handles call to display dialog to edit existing association
if (isset($_GET['editContaminant'])) {
    $action = sanitize($_GET['editContaminant']);

    if ($action == "chemical") {
        echo '<h2>Edit Elements</h2>';

        echo '<div>';
        echo '<form>';
        echo '<table>';
        echo '<tr><td>Element Name:</td><td> <select id="chemicalSelect">';

        outputOptionsById("chemical", "chemical_id", "chemical_name", $contam['chemical_id']);
        echo '</select></td></tr>';

        echo '<tr><td>New Name:</td><td> <input name="chemicalNameInput" id="chemicalNameInput" /></td></tr>';
        echo '</table>';

        echo '<br/>';

        echo '<button type="button" onclick="updateChemical();">Confirm Changes</button>&nbsp;&nbsp;&nbsp;';
        echo '<div>';
    } else if ($action == "object") {
        echo '<h2>Edit Objects</h2>';

        echo '<div>';
        echo '<form>';
        echo '<table>';
        echo '<tr><td>Object Name:</td><td> <select id="objectSelect">';
        outputOptionsById("object", "object_id", "object_name", $contam['object_id']);
        echo '</select></td></tr>';

        echo '<tr><td>New Name:</td><td> <input name="objectNameInput" id="objectNameInput" /></td></tr>';
        echo '</table>';

        echo '<br/>';

        echo '<button type="button" onclick="updateObject();">Confirm Changes</button>&nbsp;&nbsp;&nbsp;';
    } else {
        echo "<h2>Invalid Action</h2>";
    }

    exit();
}

//handles calls to display edit contam 
if (isset($_GET['ecid'])) {
    $cid = sanitize($_GET['ecid']);

    $sql = "
            SELECT contam_id, object_id, chemical_id, chemical_name, object_name, danger_level
            FROM contaminant
            JOIN chemical USING (chemical_id)
            JOIN object USING (object_id)
            WHERE contam_id = " . $cid . "
            ";

    $contam = $db->getRow($sql);

    if (empty($contam)) {
        echo "<h2>Contaminant cannot be found.</h2>";
    } else {
        echo '<h2>Contaminant Editing</h2>';

        echo '<div>';
        echo '<form>';
        echo '<input id="dcid" type="hidden" value="' . $contam['contam_id'] . '"/>';
        echo '<table>';
        echo '<tr><td>Element Name:</td><td> ';
        echo '<input type="hidden" value="' . $contam['chemical_id'] . '" id="chemicalId"/>';
        echo $contam['chemical_name'];
        echo '</td></tr>';

        echo '<tr><td>Object Name:</td><td>';
        echo '<input type="hidden" value="' . $contam['object_id'] . '" id="objectId"/>';
        echo $contam['object_name'];
        echo '</td></tr>';

        echo '<tr><td>Max PPM:</td><td> <input name="ppmInput" id="ppmInput" value="' . $contam['danger_level'] . '"/></td></tr>';
        echo '</table>';

        echo '<br/>';

        echo '<button type="button" onclick="editContamLevel();">Confirm Changes</button>&nbsp;&nbsp;&nbsp;';
        echo '<button type="button" onclick="deleteContam();">Delete Contaminant</button>&nbsp;&nbsp;&nbsp;';


        echo '</form>';
        echo '</div>';
    }

    exit();
}

closeHeader($title);
?> 

<div id="content">

    <div id="createUser">
        <input name="addContamButton" id="addContamButton" type="image" src="images/buttons/add_cont_button.png" onclick="dbAdd();" />
    </div>



    <div id="mainContent">

        <div id="mainContentResults">
            <?php listContaminants(); ?>
        </div>
        <div id="editDatabaseButtons">
            <input name="editElemButton" id="editElemButton" type="image" src="images/buttons/edit_elem_button.png" onclick="editChem();" /> &nbsp&nbsp
            <input name="editObjButton" id="editObjButton" type="image" src="images/buttons/edit_obj_button.png" onclick="editObj();" /></div>
    </div>

</div>

<?php outputFooter(); ?>
