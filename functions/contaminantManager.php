<?php
/**
 * Handles all element, object, contaminant operations (CRUD)
 * @author  George
 */
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
            
        return $db->update($sql, true);
        
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
            
        return $db->update($sql, true);
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
            
        return $db->update($sql);
    }
    
    public function insertContaminant($element, $object, $ppm){
        $db = Db::getDbInstance();
        
        $sql = 
        "
        INSERT INTO contaminant
        (chemical_id, object_id, danger_level)
        VALUES
        (
        ".$element.",
        ".$object.",
        ".$ppm."
        )
        ";
            
        return $db->insert($sql);
    }
    
    public function insertChemical($id, $name){
        $db = Db::getDbInstance();
        
        $sql = 
        "
        UPDATE chemical 
        SET chemical_name = '".$name."'
        WHERE chemical_id = '".$id."'
        ";
        
        return $db->insert($sql);
    }
    
    public function updateObject($id, $name){
        $db = Db::getDbInstance();
        
        $sql = 
        "
        UPDATE object 
        SET object_name = '".$name."'
        WHERE object_id = '".$id."'
        ";
        
        return $db->update($sql);
    }
    
    public function updateChemical($id, $name){
        $db = Db::getDbInstance();
        
        $sql = 
        "
        UPDATE chemical 
        SET chemical_name = '".$name."'
        WHERE chemical_id = '".$id."'
        ";
        
        return $db->update($sql);
    }
    
    public function deleteContaminant($id){
        $db = Db::getDbInstance();
        
        $sql = 
        "
        DELETE 
        FROM contaminant
        WHERE contam_id = ".$id."
        LIMIT 1
        ";
        
        return $db->update($sql);
    }
    
}

?>
