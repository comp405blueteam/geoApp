<?php

//old header
function header_out($title = "PHP Test"){
    echo "<html>";

    echo "<head>";
    
    echo "<title>".$title."</title>";
    
    echo "</head>";
    
    echo "<body bgcolor = 'gray'>";
}


//starts header, sets title in meta info
function openHeader($title){
    ?>
        <html>

        <head>
            
        <title><?php echo $title ?> - CIT</title>

        <link href="StyleSheet.css" rel="stylesheet" type="text/css">
        
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo BASE_URL; ?>images/icons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo BASE_URL; ?>images/icons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo BASE_URL; ?>images/icons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo BASE_URL; ?>images/icons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo BASE_URL; ?>images/icons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo BASE_URL; ?>images/icons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo BASE_URL; ?>images/icons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo BASE_URL; ?>images/icons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo BASE_URL; ?>images/icons/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>images/icons/favicon-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>images/icons/favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>images/icons/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>images/icons/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>images/icons/favicon-32x32.png" sizes="32x32">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-TileImage" content="<?php echo BASE_URL; ?>images/icons/mstile-144x144.png">
 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        
    <?php
}

//ends header, displays title in content of page
function closeHeader($title){
    ?>
        </head>
        
        <div class="container">

        <div id="sidebar">
            <div id="sidebarQuickSearch">
                <?php
                    if(isset($_SESSION['AUTH_LEVEL'])) {
                        displayLink('<a href="quick_search.php"> <img style="max-width:100%; max-height:100%;" src="images/quick_search_label.png" /></a>'); 
                    }
                ?>
            </div>
            <div id="sidebarFullAnalysis">
                <?php
                    if(isset($_SESSION['AUTH_LEVEL'])) {
                        displayLink('<a href="full_analysis.php"> <img style="max-width:100%; max-height:100%;" src="images/full_analysis_label.png" /></a>'); 
                    }
                ?>
            </div>
            <div id="sidebarReportsLogs">
                <?php
                    if(isset($_SESSION['AUTH_LEVEL'])) {
                        displayLink('<a href="reports_logs.php"> <img style="max-width:100%; max-height:100%;" src="images/reports_logs_label.png" /></a>'); 
                    }
                ?>
            </div>
            <div id="sidebarUpperBlankSpace"></div>
            <div id="sidebarManageAccounts">
                <?php
                    if(isset($_SESSION['AUTH_LEVEL'])) {
                        if($_SESSION['AUTH_LEVEL'] == 1) {
                            displayLink('<a href="manage_accounts.php"> <img style="max-width:100%; max-height:100%;" src="images/manage_accounts_label.png" /></a>'); 
                        }
                    }
                ?>
            </div>
            <div id="sidebarEditDatabase">
                <?php
                    if(isset($_SESSION['AUTH_LEVEL'])) {
                        if($_SESSION['AUTH_LEVEL'] == 1) {
                            displayLink('<a href="edit_database.php"> <img style="max-width:100%; max-height:100%;" src="images/edit_database_label.png" /></a>'); 
                        }
                    }
                ?>
            </div>
            <div id="sidebarLowerBlankSpace"></div>
            <div id="sidebarUserInfo">
            
                <?php
                    
                    if(!empty($_SESSION['NAME'])){
                        echo 'Welcome '.$_SESSION['NAME'];
                    }   
                    
                ?>
                
            </div>
        </div>

        <div id="applicationLogo"></div>

        <div id="header">
            <div id="headerTitle"><?php echo $title; ?></div>
            <div id="headerLogin">
                                  
                    <?php
                    
                    if(!empty($_SESSION['UID'])){
                        displayLink('<a href="logout.php"> <img src="images/buttons/logout_button.png" /></a>');  
                    }    
                    
                    ?>
                
                </a>
            </div>
        </div>
    <?php
}

//outputs footer
function outputFooter(){
    echo "</div>";
    
    echo "</html>";
}


function displayLink($link) {
    echo $link;
}

//outputs select options by name and id using the given columns
function outputOptionsById($table, $idColum, $nameColumn, $select = ""){
    $db = Db::getDbInstance();
    
    $sql = 
    "
    SELECT ".$idColum." AS id, ".$nameColumn." AS name
    FROM ".$table."
    ";

    $items = $db->getRset($sql);

    echo '<option value="">Select an option...</option>';
    for($i = 0;$i<count($items);$i++){
        $selected = "";
        if($items[$i]['id'] == $select){
            $selected = 'selected';
        }
        echo '<option '.$selected.'  value="'.$items[$i]['id'].'">'.$items[$i]['name'].'</option>';                    
    }
}

//displays users using criteria
function displayUsers($name = "", $email = ""){
    $am = AccountManager::getAmInstance();
    $users = $am->getUsers($name, $email);
    
    echo "<table width='100%' id='selectTable'>";
    echo "<tr>";
    echo "<th>User ID</th>";
    echo "<th>Email</th>";
    echo "<th>First Name</th>";
    echo "<th>Last Name</th>";
    echo "<th>Admin?</th>";
    echo "<th>Active?</th>";
    echo "</tr>";
    
    for($i = 0;$i<count($users);$i++){
        $admin = "No";
        $active = "No";
        
        if($users[$i]['auth_level']){
            $admin = "Yes";
        }
        
        if($users[$i]['active']){
            $active = "Yes";
        }
        
        echo "<tr onclick='displayEdit(".$users[$i]['user_id'].")'>";
            echo "<td>".$users[$i]['user_id']."</td>";
            echo "<td>".$users[$i]['email']."</td>";
            echo "<td>".$users[$i]['first_name']."</td>";
            echo "<td>".$users[$i]['last_name']."</td>";
            echo "<td>".$admin."</td>";
            echo "<td>".$active."</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

//lists contams
function listContaminants(){
        $db = Db::getDbInstance();
        $sql = 
        "
        SELECT contam_id, chemical_name, object_name, danger_level
        FROM contaminant
        JOIN chemical USING (chemical_id)
        JOIN object USING (object_id)
        ";
                                
        $contams = $db->getRset($sql);

        echo "<table width = '100%' id='selectTable'>";

        echo "<tr>";
        echo "<th>Element Name</th>";
        echo "<th>Object Name</th>";
        echo "<th>Max Level PPM</th>";
        echo "</tr>";

        for ($i = 0; $i < count($contams); $i++) {
            echo "<tr onclick='dbEdit(".$contams[$i]['contam_id'].")'>";
                echo "<td>" . $contams[$i]['chemical_name'] . "</td>";
                echo "<td>" . $contams[$i]['object_name'] . "</td>";
                echo "<td>" . $contams[$i]['danger_level'] . "</td>";
            echo "</tr>";
        }

    echo "</table>";
    }
    
    //outputs a yes no select given the column
    function getYesNoSelect($field, $name){
        $select = "";
        $select .= '<select id="'.$name.'">';
            if($field){
                $select .= '<option selected value="1">Yes</option>';
                $select .= '<option value="0">No</option>';
            }else{
                $select .= '<option value="1">Yes</option>';
                $select .= '<option selected value="0">No</option>';
            }
        $select .= '</select>';
        return $select;
    }

?>
