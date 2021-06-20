<?php
define('COLLEGES_TABLE','classes');
function getColleges(){
	global $dbh;
	
	$query = "SELECT id,name,created FROM ". COLLEGES_TABLE;
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

define('DEPARTMENT_TABLE','departments');
function getDepartmentss(){
	global $dbh;
	$id_dept = $_SESSION['id'];
	$query = "SELECT id, departname FROM ". DEPARTMENT_TABLE . " where id = " . $id_dept;
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