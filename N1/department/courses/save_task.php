<?php

include("../../includes/db.php");

if (isset($_POST['save_task'])) {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $college_id = $_POST['college_id'];  
  $symbol = $_POST['symbol'];  
  $query = "INSERT INTO courses(classname, description, departname, symbol) VALUES ('$title', '$description', '$college_id', '$symbol')";
  $result = mysqli_query($conn, $query);
  if(!$result) {
    die("Query Failed.");
  }

  $_SESSION['message'] = 'Task Saved Successfully';
  $_SESSION['message_type'] = 'success';
  header('Location: ../courses.php');

}

?>
