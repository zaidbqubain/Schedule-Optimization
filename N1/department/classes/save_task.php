<?php

include("../../includes/db.php");

if (isset($_POST['save_task'])) {
  $student_id = $_POST['student_id'];
  $classes_id = $_POST['classes_id'];   
  $query = "INSERT INTO roster (student_id, class_id) VALUES ('$student_id', '$classes_id')";
  $result = mysqli_query($conn, $query);
  if(!$result) {
    header('Location: ../classes.php');
  }

  $_SESSION['message'] = 'Task Saved Successfully';
  $_SESSION['message_type'] = 'success';
  header('Location: ../classes.php');

}


?>
