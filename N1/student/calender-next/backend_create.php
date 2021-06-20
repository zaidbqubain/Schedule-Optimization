<?php
require('../../includes/config.php');

$json = file_get_contents('php://input');
$params = json_decode($json);

// avoid sql injeciton
$stmt = $dbh->prepare("INSERT INTO personal_events (title, event_start, event_end, student_id) 
VALUES ('Hi', :start, :end, :student_id)");
$stmt->bindParam(':student_id', $params->student_id);
// $stmt->bindParam(':assignment_id', $params->assignment_id);
$stmt->bindParam(':end', $params->end);
$stmt->bindParam(':start', $params->start);
$stmt->execute();

class Result {}

$response = new Result();
$response->result = 'OK';
$response->id = $dbh->lastInsertId();
$response->message = 'Created with id: '.$dbh->lastInsertId();

// $stmt = $dbh->prepare("SELECT id, fixed, events.type, events.assignment_id ,event_start,event_end,classname,assignmentname FROM events inner join assignments on assignments.assignment_id = events.assignment_id where student_id =:id and id=:event_id");
// // $stmt = $dbh->prepare("SELECT assignments.assignment_id as id ,event_start,event_end,classname,assignmentname FROM events inner join assignments on assignments.assignment_id = events.assignment_id where student_id =:id and id=:event_id");
// $stmt->bindParam(':id', $params->student_id);
// $stmt->bindParam(':event_id', $response->id);
// $stmt->execute();
// $result = $stmt->fetch();

// class Event {}
// $e = new Event();
// $e->id = $response->id;
// $e->text = $result['classname'] . ":" . $result['assignmentname'];
// $e->start = $result['event_start'];
// $e->end = $result['event_end'];
// $e->message = "{$e->text} created";
// $e->text = "{$e->text}";
// $e->barColor = "#{$result['type']}";
// // $e->backColor = "#{$result['type']}";
// $e->fixed = $result['fixed'];

// echo json_encode($e);


/////////////////////

$stmt = $dbh->prepare("SELECT id, title,event_start,event_end 
from personal_events where student_id = :id and id=:event_id");

$stmt->bindParam(':event_id', explode(":",$params->student_id)[0]);
$stmt->bindParam(':id', $params->student_id);
$stmt->execute();
$result = $stmt->fetchAll();

foreach($result as $row) {
  $e = new Event();
  $e->id = $row['id'] . ":" . "personal_events"; 
  $e->text = $row['title'];
  $e->start = $row['event_start'];
  $e->end = $row['event_end'];
  // $e->fixed = $row['fixed'];
  // $e ->borderColor = "#E06666";
  // $e->barColor = "#{$row['type']}";
  // $e->moveDisabled = $e->fixed == 1;
  // $e->clickDisabled = $e->fixed == 1;
  // $e->rightClickDisabled = $e->fixed == 1;
  $events[] = $e;
}

echo json_encode($events);
