<?php

class Db {

    protected static $conn;
    protected static $db;
    
    public static function getDbInstance(){
        if(!isset(self::$db)){
            self::$db = new Db();
            return self::$db;
        }else{
            return self::$db;
        }
    }
    
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
        
        $checmicals = array("Antimony","Antimony(Canada)","Arsenic","Arsenic(Canada)","Barium","Beryllium","Cadmium","Cadmium(Canada)","Chromium","Copper","Lead","Lead(Canada)","Mercury","Mercury(Canada)","Molybdenum","Nickel","Selenium","Thallium","Uranium","Zinc");
        
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
        
        $object = array("Bare Soil","Bare Soil(Children's Play Area)","Cosmetics","Drinking Water","Food Item(Apple Juice)","Modeling Clay","Residential/Commercial Paint","Soil(Containing Heavy Metals)","Toys");
        
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
        // Manual entry of default contaminents
        
        // Toys
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 500 . "','" . 300 . "','" . 408 . "','" . 60 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 501 . "','" . 302 . "','" . 408 . "','" . 25 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 502 . "','" . 304 . "','" . 408 . "','" . 1000 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 503 . "','" . 306 . "','" . 408 . "','" . 75 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 504 . "','" . 308 . "','" . 408 . "','" . 60 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 505 . "','" . 310 . "','" . 408 . "','" . 90 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 506 . "','" . 312 . "','" . 408 . "','" . 60 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 507 . "','" . 316 . "','" . 408 . "','" . 500 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 508 . "','" . 313 . "','" . 408 . "','" . 10 . "')";  $this->insert($sql, $debug);
        
        // Modeling Clays
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 509 . "','" . 300 . "','" . 405 . "','" . 60 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 510 . "','" . 302 . "','" . 405 . "','" . 25 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 511 . "','" . 304 . "','" . 405 . "','" . 250 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 512 . "','" . 306 . "','" . 405 . "','" . 50 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 513 . "','" . 308 . "','" . 405 . "','" . 25 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 514 . "','" . 310 . "','" . 405 . "','" . 90 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 515 . "','" . 312 . "','" . 405 . "','" . 25 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 516 . "','" . 316 . "','" . 405 . "','" . 500 . "')";  $this->insert($sql, $debug);
        
        // Cosmetics
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 517 . "','" . 312 . "','" . 402 . "','" . 1 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 518 . "','" . 313 . "','" . 402 . "','" . 3 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 519 . "','" . 311 . "','" . 402 . "','" . 10 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 520 . "','" . 303 . "','" . 402 . "','" . 3 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 521 . "','" . 307 . "','" . 402 . "','" . 3 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 522 . "','" . 301 . "','" . 402 . "','" . 5 . "')";  $this->insert($sql, $debug);
        
        // Residential/Commercial Paint
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 523 . "','" . 310 . "','" . 406 . "','" . 100 . "')";  $this->insert($sql, $debug);
        
        // Bare Soil (Children's Play Area)
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 524 . "','" . 310 . "','" . 401 . "','" . 400 . "')";  $this->insert($sql, $debug);
        
        // Bare Soil
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 525 . "','" . 310 . "','" . 400 . "','" . 1200 . "')";  $this->insert($sql, $debug);
        
        // Drinking Water
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 526 . "','" . 300 . "','" . 403 . "','" . 0.006 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 527 . "','" . 302 . "','" . 403 . "','" . 0.01 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 528 . "','" . 304 . "','" . 403 . "','" . 2 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 529 . "','" . 305 . "','" . 403 . "','" . 0.004 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 530 . "','" . 306 . "','" . 403 . "','" . 0.005 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 531 . "','" . 308 . "','" . 403 . "','" . 0.1 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 532 . "','" . 309 . "','" . 403 . "','" . 1.3 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 533 . "','" . 310 . "','" . 403 . "','" . 0.015 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 534 . "','" . 312 . "','" . 403 . "','" . 0.002 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 535 . "','" . 316 . "','" . 403 . "','" . 0.05 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 536 . "','" . 317 . "','" . 403 . "','" . 0.002 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 537 . "','" . 318 . "','" . 403 . "','" . 0.03 . "')";  $this->insert($sql, $debug);
        
        // Soil (Containing Heavy Metals)
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 538 . "','" . 302 . "','" . 407 . "','" . 75 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 539 . "','" . 306 . "','" . 407 . "','" . 85 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 540 . "','" . 308 . "','" . 407 . "','" . 3000 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 541 . "','" . 309 . "','" . 407 . "','" . 4300 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 542 . "','" . 310 . "','" . 407 . "','" . 420 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 543 . "','" . 312 . "','" . 407 . "','" . 840 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 544 . "','" . 314 . "','" . 407 . "','" . 57 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 545 . "','" . 315 . "','" . 407 . "','" . 75 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 546 . "','" . 316 . "','" . 407 . "','" . 100 . "')";  $this->insert($sql, $debug);
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 547 . "','" . 319 . "','" . 407 . "','" . 7500 . "')";  $this->insert($sql, $debug);
         
        // Food Item (Apple Juice)
        $sql = "INSERT INTO contaminant(contam_id, chemical_id, object_id, danger_level) VALUES('" . 548 . "','" . 302 . "','" . 404 . "','" . 0.01 . "')";  $this->insert($sql, $debug);
        
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
            
            $tables = array('chemical','object','result','analysis','user','contaminant', 'debug', 'error');
            
            $this->runSQL("SET foreign_key_checks = 0;", $debug);
            
            for($i = 0;$i<count($tables);$i++){
                
                $sql = "DROP TABLE ".$tables[$i];
                $this->runSQL($sql, $debug);
                
            }
            
            $this->runSQL("DROP TRIGGER debug_delete;");
            $this->runSQL("DROP TRIGGER error_delete;");
            
            $sql = 
            "
            CREATE TABLE debug 
            (
            timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            tag VARCHAR(30) DEFAULT NULL,
            message varchar(1000) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            ";

            $this->runSQL($sql);
            
            $sql = 
            "
            CREATE TABLE error 
            (
            timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            tag VARCHAR(30) DEFAULT NULL,
            message VARCHAR(1000) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
            ";

            $this->runSQL($sql, $debug);
            
            $sql = 
            "
            CREATE TRIGGER debug_delete 
            BEFORE INSERT ON debug
            FOR EACH ROW 
            BEGIN
                SELECT COUNT(timestamp) INTO @cnt FROM debug;
                IF @cnt >= 1000 THEN
                    DELETE FROM debug ORDER BY timestamp ASC LIMIT 1;
                END IF;
            END;
            ";

            $this->runSQL($sql, $debug);
            
            $sql = 
            "
            CREATE TRIGGER error_delete 
            BEFORE INSERT ON error
            FOR EACH ROW 
            BEGIN
                SELECT COUNT(timestamp) INTO @cnt FROM error;
                IF @cnt >= 1000 THEN
                    DELETE FROM error ORDER BY timestamp ASC LIMIT 1;
                END IF;
            END;
            ";

            $this->runSQL($sql, $debug);
            
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
            
            $sql = "ALTER TABLE chemical ADD UNIQUE (chemical_name)";
            $this->runSQL($sql, $debug);
            
            $sql = 
                    "
            CREATE TABLE object 
            (
            object_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            object_name VARCHAR(30) NOT NULL,
            PRIMARY KEY (object_id)
            )
            ENGINE=InnoDB DEFAULT CHARSET=utf8
            ";

            $this->runSQL($sql, $debug);
            
            $sql = "ALTER TABLE object ADD UNIQUE (object_name)";
            $this->runSQL($sql, $debug);

            $sql = 
            "
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

            $sql = 
            "
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

            $sql = 
            "
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

            $sql = 
            "
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
            
            $sql = 
            "
            ALTER TABLE contaminant
            ADD FOREIGN KEY (object_id)
            REFERENCES object(object_id)
            ";

            $this->runSQL($sql, $debug);

            $sql = 
            "
            ALTER TABLE result
            ADD FOREIGN KEY (analysis_id)
            REFERENCES analysis(analysis_id)
            ";

            $this->runSQL($sql, $debug);

            $sql = 
            "
            ALTER TABLE result
            ADD FOREIGN KEY (contam_id)
            REFERENCES contaminant(contam_id)
            ";

            $this->runSQL($sql, $debug);

            $sql = 
            "
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
            $result = $conn->query($sql);
            
            if (!$result === false) {
                while ($row = $result->fetch_assoc()) {
                    $results[count($results)] = $row;
                }

                $result->free();
            }else{
                $this->error($sql, "SQL QUERY FAILURE");
            }                
        }
        
        return $results;
    }

    public function getRow($sql) {
        //gets a single row result set
        $conn = $this->openDB();
        $row = array();
        
        if($this->isConnected()){
             $result = $conn->query($sql);
            
            if (!$result === false) {
                $row = $result->fetch_assoc();
            }else{
                $this->error($sql, "SQL QUERY FAILURE");
            }    
        }
        
        return $row;
        
    }

    public function getVal($sql) {
        //return single value from query
        $conn = $this->openDB();
        $value = null;
        
        if($this->isConnected()){
             $result = $conn->query($sql);
            
            if (!$result === false) {
                $row = $result->fetch_array(MYSQLI_NUM);
                $value = $row[0];
            }else{
                $this->error($sql, "SQL QUERY FAILURE");
            }    
        }
        
        return $value;
    }

    public function update($sql, $debug = false) {
        // updates based on sql statement
        if ($this->isConnected()) {
            $this->runSQL($sql, $debug);
        }
    }

    public function insert($sql, $debug = false) {
        //insert based on sql statement
        if ($this->isConnected()) {
            $this->runSQL($sql, $debug);
        }
    }

    function error($mesg, $tag = '') {
        //figure out more extensive error handling later
        $sql = 
        "
        INSERT INTO error
        (message, tag)
        VALUES
        (
        \"".$mesg."\",
        '".$tag."'
        )
        ";
        
        if ($this->isConnected()) {
            $this->sqlQuery($sql);
        }
    }

    function debug($mesg, $tag = '') {
        $sql = 
        "
        INSERT INTO debug
        (message, tag)
        VALUES
        (
        \"".$mesg."\",
        '".$tag."'
        )
        ";
        
        if ($this->isConnected()) {
            $this->sqlQuery($sql);
        }
    }
    
    function isConnected(){
        $conn = $this->openDB();
        if ($conn->connect_errno) {
            //error("Connect failed: ". $conn->connect_error, "Connection Error");
            return false;
        }else{
            return true;
        }
    }
    
    function runSQL($sql, $debug = false){
        if ($this->sqlQuery($sql) === true) {
            if($debug){
                $this->debug("SUCCESS: " . $sql, "SQL");
            }
        } else {
            $this->error("FAILURE: " . $sql, "SQL");
        }
    }
    
    function realEscapeString($string){
        if($this->isConnected()){
            $conn = $this->openDB();
            return $conn->real_escape_string($string);
        }
        
        return $string;
    }
    

}

?>
