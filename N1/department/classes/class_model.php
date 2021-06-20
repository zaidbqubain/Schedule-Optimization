<?php
// define('COLLEGES_TABLE','classes');
function getstudents(){
	global $dbh;
	
	// $query = "SELECT email,users.id FROM users inner join students on users.id = students.id";
	$query = "SELECT email,users.id FROM users inner join students on users.id = students.id";
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

// define('DEPARTMENT_TABLE','departments');
function getclasses(){
	global $dbh;

	$id_dept = $_SESSION['id'];
	
	$query = "SELECT symbol, id FROM courses " . " where departname = " . $id_dept ;
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