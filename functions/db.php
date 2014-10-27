<?php

class Db {

    protected static $conn;
    
    function openDB($db = MYSQL_DATABASE, $debug = false) {
        if (!isset(self::$conn)) {
            self::$conn = new mysqli(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, $db, MYSQL_PORT);

            if (self::$conn->connect_errno) {
                $this->error("Failed to connect to MySQL.", $debug);
            }

            if ($debug) {
                $this->debug("<br/>SUCCESSFUL CONNECTION</br>");
            }

            return self::$conn;
        } else {
            return self::$conn;
        }
    }
    
    function createDB($db = MYSQL_DATABASE, $debug = true) {
        $conn = $this->openDB();

        if ($this->isConnected()) {
            $sql = "
            CREATE DATABASE " . $db . ";
            ";

            $this->runSQL($sql, $debug);
        }
    }
    
    function dropDB($db = MYSQL_DATABASE, $debug = true) {
        $conn = $this->openDB();

        if ($this->isConnected()) {
            $sql = "
            DROP DATABASE " . $db . ";
            ";

            $this->runSQL($sql, $debug);
        }
    }
    
    function sqlQuery($sql) {
        $conn = $this->openDB();
        return $conn->query($sql);
    }

    function selectDb($db) {
        $conn = $this->openDB();
        if ($this->isConnected()) {
            $conn->select_db($db);
        }
    }

    function closeDb() {
        $conn = $this->openDB();
        if ($this->isConnected()) {
            $conn->close();
            self::$conn = null;
        }
    }
    
    function insertTestData($debug = true) {
        $users = array
                    (
                    array('gmcdaid@lhup.edu', 'George', 'McDaid', md5('MickeyMinniePlutoHueyLouieDeweyDonaldGoofy'), 1, 1),
                    array('jmartin@lhup.edu', 'Justin', 'Martin', md5('rcr130td1'), 1, 1),
                    array('acohen@lhup.edu', 'Alex', 'Cohen', md5('passwd'), 1, 1),
                    array('jmarkley@lhup.edu', 'Jeff', 'Markley', md5('pass2'), 1, 1),
                    array('zheins@lhup.edu', 'Zach', 'Heins', md5('password'), 1, 0)
        );

        for ($i = 0; $i < count($users); $i++) {
            $sql = "
            INSERT INTO user
            (user_id,email, first_name, last_name, password, auth_level, active)
            VALUES
            (
            '" . ($i + 100) . "',
            '" . $users[$i][0] . "',
            '" . $users[$i][1] . "',
            '" . $users[$i][2] . "',
            '" . $users[$i][3] . "',
            " . $users[$i][4] . ",
            " . $users[$i][5] . "
            )
            ";

            $this->insert($sql, $debug);
        }

        $analyses = 
        array
        (
            array('Paint Analysis 01', 100, 'Paint Analysis 01'),
            array('Paint Analysis 02', 100, 'Second paint sample from area 2'),
            array('Water Analysis', 101, 'Sample from river after cleanup'),
            array('696641', 101, '696641'),
            array('Car Toy', 102, addslashes("From child's home")),
            array('Found Metal', 102, 'Found near other contminated source'),
            array('', 103, ''),
            array('999 Bellfonte Avenue', 103, ''),
            array('Slug', 104, 'From the garden'),
            array('Second Slug', 104, 'Why not')
        );

        for ($i = 0; $i < count($analyses); $i++) {
            $sql = "
            INSERT INTO analysis
            (analysis_id,analysis_name, user_id, notes)
            VALUES
            (
            '" . (200+$i) . "',
            '" . $analyses[$i][0] . "',
            '" . $analyses[$i][1] . "',
            '" . $analyses[$i][2] . "'
            )
            ";

            $this->insert($sql, $debug);
        }
        
        $checmicals = array("Carbon","Lead","Chromium","Tin");
        
        for ($i = 0; $i < count($checmicals); $i++) {
            $sql = "
            INSERT INTO chemical
            (chemical_id,chemical_name)
            VALUES
            (
            '" . (300+$i) . "',
            '" . $checmicals[$i] . "'
            )
            ";

            $this->insert($sql, $debug);
        }
        
        $object = array("Toy","Slug","Paint","Water");
        
        for ($i = 0; $i < count($object); $i++) {
            $sql = "
            INSERT INTO object
            (object_id,object_name)
            VALUES
            (
            '" . (400+$i) . "',
            '" . $object[$i] . "'
            )
            ";

            $this->insert($sql, $debug);
        }
        
        //5,6,7,8 for each objects checmical
        //2,20
        for($i = 0;$i<count($object);$i++){
            for($j = 0;$j<count($checmicals);$j++){
                $sql = 
                "
                INSERT INTO contaminant
                (contam_id, chemical_id, object_id, danger_level)
                VALUES
                (
                '" . (500+($i*100)+$j) . "',
                '" . (300+$j) . "',
                '" . (400+$i) . "',
                '" . (1.0/rand(2,20)) . "'
                )
                ";
                
                $this->insert($sql, $debug);
            }
        }
        
        $results = 
        array
        (
            "204" => 500,
            "208" => 600,
            "201" => 700,
            "203" => 800
        );
        
        $i = 0;
        foreach ($results as $analysis => $contam) {
            for($j = 0;$j<4;$j++){
                $sql = "
                INSERT INTO result
                (analysis_id, contam_id, observed_level, is_dangerous)
                VALUES
                (
                '" . $analysis . "',
                '" . ($contam+$j) . "',
                '".(1.0/rand(2,20))."',
                '".true."'    
                )
                ";

                $this->insert($sql, $debug);
            }
            
            $i++;
        }
               
        $sql = 
        "
        UPDATE result
        SET is_dangerous = 0
        WHERE observed_level < (SELECT danger_level FROM contaminant WHERE result.contam_id = contaminant.contam_id)
        ";
        
        $this->insert($sql, $debug);
                
    }
    
    function wipeDB($db, $debug = true) {
        $this->openDB("", $debug);
        //$this->dropDB(MYSQL_DATABASE, $debug);
        //$this->createDB(MYSQL_DATABASE, $debug);
        $this->selectDb($db);
        
        if($this->isConnected()){
            
            $tables = array('chemical','object','result','analysis','user','contaminant');
            
            $this->runSQL("SET foreign_key_checks = 0;", $debug);
            
            for($i = 0;$i<count($tables);$i++){
                
                $sql = "DROP TABLE ".$tables[$i];
                $this->runSQL($sql, $debug);
                
            }
            
            $this->runSQL("SET foreign_key_checks = 1;", $debug);
            
            $sql = 
            " 
            CREATE TABLE chemical 
            (
            chemical_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            chemical_name VARCHAR(30) NOT NULL,
            PRIMARY KEY (chemical_id)
            )
            ENGINE=InnoDB DEFAULT CHARSET=utf8
            ";

            $this->runSQL($sql, $debug);
            
            $sql = "
            CREATE TABLE object 
            (
            object_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            object_name VARCHAR(30) NOT NULL,
            PRIMARY KEY (object_id)
            )
            ENGINE=InnoDB DEFAULT CHARSET=utf8
            ";

            $this->runSQL($sql, $debug);

            $sql = "
            CREATE TABLE contaminant 
            (
            contam_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            chemical_id INT(10) UNSIGNED NOT NULL,
            object_id INT(10) UNSIGNED NOT NULL,
            danger_level decimal(10,8) NOT NULL,
            PRIMARY KEY (contam_id)
            ) 
            ENGINE=InnoDB DEFAULT CHARSET=utf8
            ";

            $this->runSQL($sql, $debug);

            $sql = "
            CREATE TABLE user 
            (
            user_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            email VARCHAR(155) NOT NULL,
            first_name VARCHAR(30) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL,
            auth_level TINYINT(3) UNSIGNED NOT NULL,
            active TINYINT(1) NOT NULL,
            PRIMARY KEY (user_id)
            ) 
            ENGINE=InnoDB DEFAULT CHARSET=utf8
            ";

            $this->runSQL($sql, $debug);

            $sql = "
            CREATE TABLE analysis 
            (
            analysis_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            analysis_name VARCHAR(35) NOT NULL,
            user_id INT(10) UNSIGNED NOT NULL,
            notes VARCHAR(255) DEFAULT NULL,
            timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (analysis_id)
            ) 
            ENGINE=InnoDB DEFAULT CHARSET=utf8
            ";

            $this->runSQL($sql, $debug);

            $sql = "
            CREATE TABLE result 
            (
            result_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            analysis_id INT(10) UNSIGNED NOT NULL,
            contam_id INT(10) UNSIGNED NOT NULL,
            observed_level DECIMAL(10,8) NOT NULL,
            is_dangerous TINYINT(1) NOT NULL,
            PRIMARY KEY (result_id)
            ) 
            ENGINE=InnoDB DEFAULT CHARSET=utf8
            ";

            $this->runSQL($sql, $debug);

            $sql = "
            ALTER TABLE contaminant
            ADD FOREIGN KEY (chemical_id)
            REFERENCES chemical(chemical_id)
            ";

            $this->runSQL($sql, $debug);

            $sql = "
            ALTER TABLE contaminant
            ADD FOREIGN KEY (object_id)
            REFERENCES object(object_id)
            ";

            $this->runSQL($sql, $debug);

            $sql = "
            ALTER TABLE result
            ADD FOREIGN KEY (analysis_id)
            REFERENCES analysis(analysis_id)
            ";

            $this->runSQL($sql, $debug);

            $sql = "
            ALTER TABLE result
            ADD FOREIGN KEY (contam_id)
            REFERENCES contaminant(contam_id)
            ";

            $this->runSQL($sql, $debug);

            $sql = "
            ALTER TABLE analysis
            ADD FOREIGN KEY (user_id)
            REFERENCES user(user_id)
            ";

            $this->runSQL($sql, $debug);
        }
        
    }

    public function getRset($sql) {
        //get 2d result set
        $conn = $this->openDB();
        $results = array();
        
        if($this->isConnected()){
            if ($result = $conn->query($sql)) {
                while ($row = $result->fetch_assoc()) {
                    $results[count($results)] = $row;
                }

                $result->free();
            }                
        }
        
        return $results;
    }

    public function getRow() {
        //gets a single row result set
        $conn = $this->openDB();
        $row = array();
        
        if($this->isConnected()){
            if ($result = $conn->query($sql)) {
                $row = $result->fetch_assoc();
            }
        }
        
        return $row;
        
    }

    public function getVal($sql) {
        //return single value from query
        $conn = $this->openDB();
        $value = null;
        
        if($this->isConnected()){
            if ($result = $conn->query($sql)) {
                $row = $result->fetch_array(MYSQLI_NUM);
                $value = $row[0];
            }
        }
        
        return $value;
    }

    public function update($sql, $debug = false) {
        // updates based on sql statement
        if ($this->isConnected()) {
            runSQL($sql, $debug);
        }
    }

    public function insert($sql, $debug = false) {
        //insert based on sql statement
        if ($this->isConnected()) {
            $this->runSQL($sql, $debug);
        }
    }

    function error($mesg, $debug = false) {
        //figure out more extensive error handling later
        echo $mesg;
    }

    function debug($mesg) {
        echo $mesg;
    }
    
    function isConnected(){
        $conn = $this->openDB();
        if ($conn->connect_errno) {
            error("Connect failed: %s\n", $mysqli->connect_error);
            return false;
        }else{
            return true;
        }
    }
    
    function runSQL($sql, $debug){
        if ($this->sqlQuery($sql) === true) {
            if($debug){
                $this->debug("<br/>SUCCESS: " . $sql . "<br/>");
            }
        } else {
            $this->error("<br/>FAILURE: " . $sql . "<br/>", $debug);
        }
    }
    

}

$db = new Db();

?>
