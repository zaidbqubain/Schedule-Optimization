<?php

include("../../includes/db.php");

if (isset($_POST['save_task'])) {


  if(strtotime( $_POST['start']) <=  strtotime( $_POST['end'])){

  if(($_POST['duration']*60) <= (strtotime( $_POST['end']) - strtotime( $_POST['start']))){


 

  $title = $_POST['title'];
  $description = $_POST['description'];
  $course_symbol = $_POST['course_symbol'];
  $assigntypeid = $_POST['assign_type'];
  $start = date("Y-m-d H:i:s", strtotime( $_POST['start']));
  $end = date("Y-m-d H:i:s", strtotime( $_POST['end']));
  $duration = $_POST['duration'];
  $exam = $_POST['examdur'];


  if($assigntypeid == "Exam"){

  $today = date('Y-m-d H:m:s');


  $query = "INSERT INTO assignments (course_id, assignmentname, description, startdate, enddate, duration, type) VALUES  ('$course_symbol','$title','$description', '$start', '$end',  '$exam',  '$assigntypeid')";
  $result = mysqli_query($conn, $query);
  if(!$result) {
    die("Query Failed.". $query.error_log());
  }

  $title = $title . "-study";

  $query = "INSERT INTO assignments (course_id, assignmentname, description, startdate, enddate, duration, type) VALUES  ('$course_symbol','$title','$description', '$today', '$start',  '$exam',  '$assigntypeid')";
  $result = mysqli_query($conn, $query);
  if(!$result) {
    die("Query Failed.". $query.error_log());
  }




} else {

  $query = "INSERT INTO assignments ( course_id, assignmentname, description, startdate, enddate, duration, type) VALUES  ('$course_symbol','$title','$description', '$start', '$end',  '$duration',  '$assigntypeid')";
  $result = mysqli_query($conn, $query);
  if(!$result) {
    die("Query Failed.". $query.error_log());
  }

}

}
}

  $_SESSION['message'] = 'Task Saved Successfully';
  $_SESSION['message_type'] = 'success';
  header('Location: ../syllabus.php');

}

?>
