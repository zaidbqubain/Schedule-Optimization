<?php
define('ASSIGNMENT_TABLE','assignments');
function getAssignments(){
	global $dbh;
	
	$query = "SELECT assignment_id, assignmentname FROM ". ASSIGNMENT_TABLE;
	//echo $query;
	$stmt = $dbh->prepare($query);
	$stmt->execute();	
	$assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if($assignments){
		return $assignments;
		
	}else{
		return false;
	}
}
?>