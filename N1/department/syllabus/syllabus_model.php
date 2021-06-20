<?php
define('COURSES_TABLE','courses');
define('ASSIGN_TABLE','assigntype');
function getCourses(){
	global $dbh;

	$id_dept = $_SESSION['id'];
	
	$query = "SELECT * FROM ". COURSES_TABLE . " where departname = " . $id_dept ;
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

function getAssigntype(){
	global $dbh;
	
	$query = "SELECT * FROM ". ASSIGN_TABLE;
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