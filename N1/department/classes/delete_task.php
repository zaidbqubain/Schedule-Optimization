<?php

include("../../includes/db.php");

if(isset($_GET['id'])) {
  $id = $_GET['id'];
  $id2 = $_GET['id2'];
  $query = "DELETE FROM roster WHERE student_id = $id and class_id = $id2 ";
  $result = mysqli_query($conn, $query);
  if(!$result) {
    die("Query Failed.");
  }

  $_SESSION['message'] = 'Task Removed Successfully';
  $_SESSION['message_type'] = 'danger';
  header('Location: ../classes.php');
}

?>
