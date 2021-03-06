<?php
/**
 * Displays all reports and handles display of single reports
 * @author  George
 * @author  GUI: Paul and Tom
 */ 

    //start session, include functions
    session_start();

    require_once("inc_functions.php");
   
    $db = Db::getDbInstance();
    
    error_reporting(0);
    ini_set('error_reporting', E_NONE);
    
    //gets reports based on current cruteria, calls PHP function
    if(isset($_POST['element']) && isset($_POST['reportid']) && isset($_POST['name']) && isset($_POST['notes'])){
        $element = trim(sanitize($_POST['element']));
        $reportid = trim(sanitize($_POST['reportid']));
        $rep_name = trim(sanitize($_POST['name']));
        $notes = trim(sanitize($_POST['notes']));
        
        getReports($reportid, $element, $rep_name, $notes);
        exit;
    }
    
    //sends csv report to user
    if(isset($_GET['dcReportId'])){
        $reportid = trim(sanitize($_GET['dcReportId']));
        
        if(!empty($reportid)){
            $sql = 
            "
            SELECT timestamp, analysis_name, notes, object_name, chemical_name, observed_level, danger_level, is_dangerous
            FROM result 
            JOIN contaminant USING (contam_id)
            JOIN chemical USING (chemical_id)
            JOIN object USING (object_id)
            JOIN analysis USING (analysis_id)
            WHERE analysis_id = ".$reportid."
            ";
            
            $results = $db->getRset($sql);
            
            if(!empty($results)){
                $date = strtotime( $results[0]['timestamp'] );
                
                $report = 
                array(
                    array("Report Name",$results[0]['analysis_name']),
                    array("Notes",$results[0]['notes']),
                    array("Report ID",date("m/d/y g:i:s A", $date)),
                    array("",""),
                    array('Element Name','Observed Level','Max Level PPM','Exceeds Max')
                );
                
                for($i = 0;$i<count($results);$i++){
                    $danger = "";
                    if($results[$i]['is_dangerous'] == 1){
                        $danger = "Yes";
                    }else{
                        $danger = "No";
                    }
                    
                    $data = array($results[$i]['chemical_name'],$results[$i]['observed_level'],$results[$i]['danger_level'], $danger);
                    
                    $report[count($report)] = $data;
                }
                
                start_send_file("report_export_" . date("Y-m-d") . ".csv");
                echo create_csv_file($report);
                exit;
            }else{
                echo "<h2>Analysis ".$reportid." cannot be found.</h2>";
                exit;
            }
            
        }else{
            echo "<h2>Invalid analysis id.</h2>";
            exit;
        }
    }
    
    //displays a single report
    if(isset($_GET['singleReportId'])){
        $title = 'Report Log';
        openHeader($title);
        
        $reportid = trim(sanitize($_GET['singleReportId']));
        
        if(!empty($reportid)){
            $sql = 
            "
            SELECT timestamp, analysis_name, notes, object_name, chemical_name, observed_level, danger_level, is_dangerous
            FROM result 
            JOIN contaminant USING (contam_id)
            JOIN chemical USING (chemical_id)
            JOIN object USING (object_id)
            JOIN analysis USING (analysis_id)
            WHERE analysis_id = ".$reportid."
            ";
            
            $results = $db->getRset($sql);
            
            if(!empty($results)){
                $date = strtotime( $results[0]['timestamp'] );
                
                echo '<div style="float:right; margin-bottom: 5px;"><a href='.BASE_URL.'reports_logs.php?dcReportId='.$reportid.'><button type=button>CSV</button></a></div>';
                
                echo '<div id="reportHeader">';
                    echo '<p>'.$results[0]['analysis_name'].'</p>';
                    echo '<p>'.$results[0]['notes'].'</p>';
                    echo '<p>Report '.$reportid.'</p>';
                    echo '<p>Report Time: '.date("m/d/y g:i:s A", $date).'</p>';
                echo '</div>';                
                
                echo '<div id="reportBox">';
                    
                    echo '<table>';

                    echo '<th>Element Name</th>';
                    echo '<th>Observed Level</th>';
                    echo '<th>Max Level PPM</th>';
                    echo '<th>Exceeds Max</th>';
                    
                    for($i = 0;$i<count($results);$i++){
                       $danger_row = "";
                       $danger_image = "";
                       
                       if($results[$i]['is_dangerous'] == 1){
                           $danger_row = "id='dangerous'";
                           $danger_image = "<img src='images/danger.png'>";
                       }
                        
                       echo '<tr '.$danger_row.'>';

                       echo '<td>'.$results[$i]['chemical_name'].'</td>';
                       echo '<td>'.$results[$i]['observed_level'].'</td>';
                       echo '<td>'.$results[$i]['danger_level'].'</td>';
                       echo '<td>'.$danger_image.'</td>';
                       
                       echo '</tr>';
                    }

                    echo '<table>';
                echo '</div>';
                
                exit;
            }else{
                echo "<h2>Analysis ".$reportid." cannot be found.</h2>";
                exit;
            }
            
            
        }
        
        echo "<h2>Invalid analysis id.</h2>";
        exit;
    }
    
    $title = 'Report Logs';
    openHeader($title);    
    
    //gets reports based on criteria
    function getReports($reportId = "", $element = "", $rep_name = "", $notes=""){
        $db = Db::getDbInstance();
        
        $sql = 
        "
        SELECT * 
        FROM analysis
        WHERE analysis_id IN (
            SELECT analysis_id
            FROM analysis
            JOIN result USING (analysis_id)
            JOIN contaminant USING (contam_id)
            JOIN chemical USING (chemical_id)
            JOIN object USING (object_id)
            WHERE analysis_id = analysis_id
        ";
        
        if(!empty($element)){
            $sql .= "AND chemical_name LIKE '%".$element."%'";
        }
        
        if(!empty($reportId)){
            $sql .= "AND analysis_id = ".$reportId;
        }
        
        if(!empty($rep_name)){
            $sql .= "AND analysis_name LIKE '%".$rep_name."%'";
        }
        
        if(!empty($notes)){
            $sql .= "AND notes LIKE '%".$notes."%'";
        }
        
        $sql .= ");";
        
        $reports = $db->getRset($sql);
        
        echo '<table width="100%">';
        
        echo "<th align='left'>Report ID</th><th align='left'>Report Time</th><th align='left'>Report Name</th><th align='left'>Report Notes</th>";
        
        for($i = 0;$i < count($reports);$i++){
            echo '<tr>';
            
                $date = strtotime( $reports[$i]['timestamp'] );
                
                echo 
                "
                <td>".$reports[$i]['analysis_id']."</td>
                <td>".date("m/d/y g:i:s A", $date)."</td>
                <td>".$reports[$i]['analysis_name']."</td>
                <td>".$reports[$i]['notes']."</td>
                <td><button type='button' onclick='openReport(".$reports[$i]['analysis_id'].");'>View Report</button></td>
                ";
                
            echo '</tr>';
        }
        
        echo '</table>';
    }

?>

<script>
    //opens a new dialog and displays the given report
    function openReport(reportId){
        window.open('reports_logs.php?singleReportId='+reportId, null, 'height=700,width=600');
    }
    
    //gets the reports and sets HTML to result, calls ajax
    function getReports(){
        var element = document.getElementById('elementInput').value;
        var reportid = document.getElementById('reportIdInput').value;
        var name = document.getElementById('nameInput').value;
        var notes = document.getElementById('notesInput').value;
        
        var dataString = {element:element, reportid:reportid, name:name, notes:notes};
        $.ajax({        
            type: "POST",
            url: <?php echo "'".BASE_URL."reports_logs.php'" ?>,
            data: dataString,
            async: false,
            cache: false,
            success: function(html)
            {
                document.getElementById('resultsTextarea').innerHTML =  html;
                return;
                alert(html);
            }
        });
    }
</script>

<?php closeHeader($title); ?>
    
    <div id="content">
        <form name="contentForm" id="contentForm">
    	   <div id="searchBarReportID">
    		  Report ID: <input name="reportIdInput" id="reportIdInput" size="5"/>
    	   </div>
    	   <div id="searchBarElement">
    		  Element: <input name="elementInput" id="elementInput" size="15"/>
    	   </div>
    	   <div id="searchBarName">
    		  Name: <input name="nameInput" id="nameInput"/>
    	   </div>
            <div id="searchBarNotes">
    		  Notes: <input name="notesInput" id="notesInput"/>
    	   </div>
           <div id="searchBarSearchButton">
               <button type="button" name="searchButton" id="searchButton" onclick="getReports();">Search</button>
    	   </div>
    	   <div id="mainContent">
    		  <div id="mainContentResults">
    			 <div id="resultsTextarea" style="resize:none; overflow-y:auto; overflow-x:auto; width:100%; height:99%;";><?php getReports(); ?></div>
    		  </div>
    		  <div id="lowerContentButtons">
                      <button name="myReportsButton" id="myReportsButton" type="button">My Reports</button>&nbsp&nbsp
                      <button name="clearReportsButton" id="clearReportsButton" type="button">Clear Results</button>
    		  </div>
    	   </div>
        </form>
    </div>

<?php

outputFooter();

?>