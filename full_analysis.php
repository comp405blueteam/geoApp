<?php
require_once("inc_functions.php");

session_start();

$db = Db::getDbInstance();

$chemicals = array();
$objects = array();
$analysis = new Analysis();

$sql = "
    SELECT chemical_name
    FROM chemical
    ";

$chemicals = $db->getRset($sql);

$sql = "
    SELECT object_name
    FROM object
    ";

$objects = $db->getRset($sql);

// IF ajax query for adding item to analysis
if (isset($_POST['element']) & isset($_POST['object']) & isset($_POST['ppm']) & !isset($_POST['flag'])) {

    // Sanitize values
    $element = trim(sanitize(cleanInput($_POST['element'])));
    $object = trim(sanitize(cleanInput($_POST['object'])));
    $PPM = trim(sanitize(cleanInput($_POST['ppm'])));

    // return values
    echo json_encode(array("element" => $element, "object" => $object, "ppm" => $PPM));

    exit();
}

// If new analysis
if (isset($_POST['notes']) & isset($_POST['analysisName'])){
    
    $notes = trim(sanitize(cleanInput($_POST['notes'])));
    $name = trim(sanitize(cleanInput($_POST['analysisName'])));
    $user = $_SESSION['UID'];
    
    $sql = 
			"
			INSERT INTO analysis
			(analysis_name, notes, user_id)
			VALUES
			('$name', '$notes', '$user')
			";
    
		$db->insert($sql);
    
		// Return analysis ID
		$sql = 
			"
			Select analysis_id
			FROM analysis
			WHERE analysis_name = '$name';
			";
    
		$analysisID = $db->getVal($sql);
	
	echo json_encode($analysisID);
    
    exit();
}

// If ajax query for results
if (isset($_POST['element']) & isset($_POST['object']) & isset($_POST['ppm']) & isset($_POST['analysisID']) & isset($_POST['flag'])) {

    $element = $_POST['element'];
    $object = $_POST['object'];
    $ppm = $_POST['ppm'];
    $analysisID = $_POST['analysisID'];
    $exceedsLimit;
    $maxPPM;

    $sql = "
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

    //Sample exceeds limit
    if ($ppm > $maxPPM) {
        $exceedsLimit = 1;
        $danger_row = "id='dangerous'";
        $danger_image = "<img src='images/danger.png'>";
        echo "<tr " . $danger_row . ">";
    }

    //Sample within limit
    if ($ppm <= $maxPPM) {
        $exceedsLimit = 0;
        echo "<tr>";
    }

    echo "<td align='left'>" . $object . "</td>";
    echo "<td align='left'>" . $element . "</td>";
    echo "<td align='left'>" . $ppm . " PPM" . "</td>";
    echo "<td align='left'>" . $maxPPM . " PPM" . "</td>";

    //Sample exceeds limit
    if ($ppm > $maxPPM) {
        echo '<td>' . $danger_image . '</td>';
    } else
        echo "<td></td>";
    echo "</tr>";
    
    //Get chemical id
    $sql = "
            SELECT chemical_id
            FROM chemical
            WHERE chemical.chemical_name = '$element'
            ";

    $chem_id = $db->getVal($sql);
    
    //Get object id
    $sql = "
            SELECT object_id
            FROM object
            WHERE object.object_name = '$object'
            ";
    
    $object_id = $db->getVal($sql);
    
    //Get contaminant id
    $sql = 
            "
            SELECT contam_id
            FROM contaminant
            WHERE chemical_id = '$chem_id'
            AND object_id = '$object_id'
            ";
      
    $contam_id = $db->getVal($sql);
    
    //Insert result
    $sql = 
    "
    INSERT INTO result
    (analysis_id, contam_id, observed_level, is_dangerous)
    VALUES
    ('$analysisID', '$contam_id', '$ppm', '$exceedsLimit')
    ";
    
    $db->insert($sql);
    
    exit();
}
?>

<?php
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
    var analysisID;

//Runs when user clicks add button
    function add() {
        var element = document.getElementById("elementSelect").value;
        var object = document.getElementById("objectSelect").value;
        var ppm = document.getElementById("ppmInput").value;
        
        // Prevent an entry that has missing values
        if (element == 'temp' || object == 'temp2' || ppm == null){
            
            alert('Please select an element, an item, and the parts per million');
            return;
        }
        
        var dataString = {element: element, object: object, ppm: ppm};

        $.ajax({
            type: "POST",
            url: <?php echo "'" . BASE_URL . "full_analysis.php'" ?>,
            dataType: "json",
            data: dataString,
            cache: false,
            success: function(results)
            {
                var duplicate = false;

                //Check if element/object combo alread entered
                for (var i = 0; i < addElement.length; i++) {
                    
                    //Modify PPM for duplicated entry
                    if (addElement[i] == results.element && addObject[i] == results.object) {
                        
                        addPPM[i] = results.ppm;
                        duplicate = true;
                    }
                }
                
                //Add new entry if not duplicate
                if (!duplicate) {

                    // Add sanitized results to arrays
                    addElement[addElement.length] = results.element;
                    addObject[addObject.length] = results.object;
                    addPPM[addPPM.length] = results.ppm;
                }

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
                for (var i = 0; i < addElement.length; i++) {

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
    function analyze() {
    
        if (addElement[0] == null){
            alert("Please add items to be analyzed.");
            return;
        }
        
        var flag = 1;

        //Table head
        var html = "<table width='100%'>";
        html += "<tr>";
        html += "<th align='left'>";
        html += "<p>Item Type</p>";
        html += "</th>";
        html += "<th align='left'>";
        html += "<p>Element</p>";
        html += "</th>";
        html += "<th align='left'>";
        html += "<p>Sample Level</p>";
        html += "</th>";
        html += "<th align='left'>";
        html += "<p>Max Level</p>";
        html += "</th>";
        html += "<th align='left'>";
        html += "<p>Exceeds Limit</p>";
        html += "</th>";
        html += "</tr>";

        //Add html for each analyzed entry
        for (var i = 0; i < addElement.length; i++) {
            
            var dataString;
            
            //On first run, create new analysis entry in database
            if (i == 0){
                var analysisName = document.getElementById("analysisName").value;
                var notes = document.getElementById("analysisNotes").value;
                dataString = {analysisName:analysisName, notes:notes};
                
                if (analysisName == ""){
                    alert('Please enter a name for this analysis');
                    return;
                }
                
                $.ajax({
                type: "POST",
                url: <?php echo "'" . BASE_URL . "full_analysis.php'" ?>,
                data: dataString,
                dataType: "json",
                cache: false,
                async: false,
                success: function(analysis)
                {
                    alert("analysis created");
                    analysisID = analysis;
                    alert(analysisID);
                }
                });
            }

            dataString = {element: addElement[i], object: addObject[i], ppm: addPPM[i], analysisID: analysisID, flag: flag};

            $.ajax({
                type: "POST",
                url: <?php echo "'" . BASE_URL . "full_analysis.php'" ?>,
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
        
        //Display results
        document.getElementById('resultsTextarea').innerHTML = html;
        
        //Clear added items and display
        clearList();
    }

    //Clear arrays and display table for analysis entries
    function clearList() {
    
    //Clear arrays
        addElement = [];
        addObject = [];
        addPPM = [];
        analysisID = null;

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
        html += "</table>";

//Reset add table
        document.getElementById('addTextarea').innerHTML = html;
    }
    
    // Forces numerical input for PPM input
    $(document).ready(function () {
             $(".numberinput").forceNumeric();
         });

         // forceNumeric() plug-in implementation
         jQuery.fn.forceNumeric = function () {

             return this.each(function () {
                 $(this).keydown(function (e) {
                     var key = e.which || e.keyCode;

                     if (!e.shiftKey && !e.altKey && !e.ctrlKey &&
                     // numbers   
                         key >= 48 && key <= 57 ||
                     // Numeric keypad
                         key >= 96 && key <= 105 ||
                     // period, minus, . on keypad
                        key == 190 || key == 109 || key == 110 ||
                     // Backspace and Tab and Enter
                        key == 8 || key == 9 || key == 13 ||
                     // Home and End
                        key == 35 || key == 36 ||
                     // left and right arrows
                        key == 37 || key == 39 ||
                     // Del and Ins
                        key == 46 || key == 45)
                         return true;

                     return false;
                 });
             });
         }

</script>

<div id="content">
    <form name="contentForm" id="contentForm">
        <div id="contentLeftWindow">
            <div id="contentLeftWindowContents">
                Analysis Name:<br/>
                <input id="analysisName" name="analysisName" type="text" ><br/><br/>
                Element:<br/>
                <select name="elementSelect" id="elementSelect">
                    <option value="temp">Select an element...</option>
<?php $analysis->listChemicals($chemicals); ?>
                </select><br/><br/>
                Item type:<br/>
                <select name="objectSelect" id="objectSelect">
                    <option value="temp2">Select an item type...</option>
<?php $analysis->listObjects($objects); ?>
                </select><br/><br/>
                PPM:<br/>
                <input name="ppmInput" id="ppmInput" class="numberinput"/><br/><br/>
                <textarea name="analysisNotes" id="analysisNotes" rows="2" placeholder="Enter analysis notes here..." style="resize:none; overflow-y:auto; overflow-x:auto; width:100%;"></textarea><br/><br/>
                <table><tr>
                        <td>
                           <button name="addButton" type="button" onclick="add()">Add</button><br/><br/>
                        </td>
                        <td>
                           <button name="clearButton" type="button" onclick="clearList()">Clear List</button><br/><br/>
                        </td>
                    </tr></table>
                <div disabled name="addTextarea" id="addTextarea" style="resize:none; overflow-y:auto; overflow-x:auto; width:100%; height:20%;">RESULTS - USERS CANNOT EDIT TEXTFIELD</div>
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