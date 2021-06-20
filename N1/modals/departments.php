<?php
define('DEPARTMENT_TABLE','departments');
function getDepartments(){
	global $dbh;
	
	$query = "SELECT id, departname FROM ". DEPARTMENT_TABLE;
	//echo $query;
	$stmt = $dbh->prepare($query);
	$stmt->execute();	
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if($result){
		return $result;
		
	}else{
		return false;
	}
}

define('DESIGNATION_TABLE','designations');
function getDesignations(){
	global $dbh;
	
	$query = "SELECT id,name FROM ". DESIGNATION_TABLE;
	//echo $query;
	$stmt = $dbh->prepare($query);
	$stmt->execute();	
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if($result){
		return $result;
		
	}else{
		return false;
	}
}



?>