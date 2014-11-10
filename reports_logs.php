<?php

    session_start();

    require_once("../constants.php");
    require_once("functions/utils.php");
    require_once("functions/db.php");
    require_once("functions/gui.php");   
    
    
    $db = Db::getDbInstance();
    
    if(isset($_POST['element']) && isset($_POST['reportid']) && isset($_POST['date'])){
        $element = trim(sanitize($_POST['element']));
        $reportid = trim(sanitize($_POST['reportid']));
        $date = trim(sanitize($_POST['date']));
        
        getReports($reportid, $element, $date);
        exit;
    }
    
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
                    echo '<th>Danger Level</th>';
                    echo '<th>Is Dangerous</th>';
                    
                    for($i = 0;$i<count($results);$i++){
                       $danger_row = "";
                       $danger_image = "";
                       
                       if($results[$i]['is_dangerous'] == 1){
                           $danger_row = "id='dangerous'";
                           $danger_image = "<img src='/images/danger.png'>";
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
    
    function getReports($reportId = "", $element = "", $date = ""){
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
            $sql .= "AND chemical_name LIKE '".$element."'";
        }
        
        if(!empty($reportId)){
            $sql .= "AND analysis_id = ".$reportId;
        }
        
        if(!empty($date)){
            $sql .= "AND timestamp = ".$date;
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
    function openReport(reportId){
        window.open('reports_logs.php?singleReportId='+reportId, null, 'height=700,width=600');
    }
    
    function getReports(){
        var element = document.getElementById('elementInput').value;
        var reportid = document.getElementById('reportIdInput').value;
        var date = document.getElementById('dateInput').value;
        
        var dataString = {element:element, reportid:reportid, date:date};
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
    	   <div id="reportsLogsReportID">
    		  Report ID: <input name="reportIdInput" id="reportIdInput"/>
    	   </div>
    	   <div id="reportsLogsElement">
    		  Element: <input name="elementInput" id="elementInput"/>
    	   </div>
    	   <div id="reportsLogsDate">
    		  Date: <input name="dateInput" id="dateInput"/>
    	   </div>
    	   <div id="reportsLogsSearchButton">
               <button type="button" name="searchButton" id="searchButton" onclick="getReports();">Search</button>
    	   </div>
    	   <div id="reportsLogsContent">
    		  <div id="reportsLogsContentResults">
    			 <div id="resultsTextarea" style="resize:none; overflow-y:auto; overflow-x:auto; width:100%; height:99%;";><?php getReports(); ?></div>
    		  </div>
    		  <div id="lowerContentButtons">
    			 <button name="myReportsButton" id="myReportsButton">My Reports</button>&nbsp&nbsp<button>Clear Results</button>
    		  </div>
    	   </div>
        </form>
    </div>

<?php

outputFooter();

?>