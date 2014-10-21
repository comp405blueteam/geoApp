<?php
class DatabaseFunctions{
	
	public function openDB(){
		//open connection to db
		echo "openDB Called";
	}
    
	public function getRset(){
		//get 2d result set
		echo "getRset Called";
	}
    
	public function getRow(){
		//gets a single row result set
		echo "getRow Called";
	}
    
	public function getVal(){
		//return single value from query
		echo "getVal Called";
	}

	public function update(){
		// updates based on sql statement
		echo "update called";
	}

	public function insert(){
		//insert based on sql statement
		echo "insert called";
	}

}
?>
