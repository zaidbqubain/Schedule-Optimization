<?php
require('../../includes/config.php');

// $stmt = $dbh->prepare("SELECT * FROM events");
$id = $_GET['id'];
$stmt = $dbh->prepare("SELECT events.id as id, fixed, events.type, events.assignment_id,event_start,event_end,symbol as classname,assignmentname FROM (events inner join assignments on assignments.assignment_id = events.assignment_id) inner join courses on courses.id = assignments.course_id where student_id = :id and status = 0");

$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();


class Event {}
$events = array();




//php

///

foreach($result as $row) {
  $e = new Event();
  $e->id = $row['id']; 
  $e->text = $row['classname'] . ":" . $row['assignmentname'];
  $e->start = $row['event_start'];
  $e->end = $row['event_end'];
  $e->fixed = $row['fixed'];
  // $e ->borderColor = "#E06666";
  $e->barColor = "#{$row['type']}";
  // $e->moveDisabled = $e->fixed == 1;
  // $e->clickDisabled = $e->fixed == 1;
  // $e->rightClickDisabled = $e->fixed == 1;
  $events[] = $e;
}

echo json_encode($events);
