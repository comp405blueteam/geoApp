<?php

Class ContaminantManager{
    protected static $cm;
    
    public static function getCMInstance(){
        if(!isset(self::$cm)){
            self::$cm = new ContaminantManager();
            return self::$cm;
        }else{
            return self::$cm;
        }
    }
    
    public function addChemical($chemical){
        $db = Db::getDbInstance();
        
        $sql = 
        "
        INSERT INTO chemical
        (chemical_name)
        VALUES
        (
        '".$chemical."'
        )
        ";
            
        $db->update($sql, true);
    }
    
    public function addObject($object){
        $db = Db::getDbInstance();
        
        $sql = 
        "
        INSERT INTO object
        (object_name)
        VALUES
        (
        '".$object."'
        )
        ";
            
        $db->update($sql, true);
    }
    
    public function updateContaminant($element, $object, $ppm){
        $db = Db::getDbInstance();
        
        $sql = 
        "
        UPDATE contaminant
        SET danger_level = ".$ppm."
        WHERE chemical_id = ".$element."
        AND object_id = ".$object."
        ";
            
        $db->update($sql);
    }
    
}

?>

