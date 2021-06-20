
<?php include("../includes/db.php"); ?>
<?php include("class_model.php");
  $colleges = getstudents();
  $courses = getclasses();
  $id_dept = $_SESSION['id'];
?>

<main class="container p-4">
  <div class="row">
    <div class="col-md-4">
      <!-- MESSAGES -->

      <?php if (isset($_SESSION['message'])) { ?>
      <div class="alert alert-<?= $_SESSION['message_type']?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php unset($_SESSION['message']); } ?>



      <!-- ADD TASK FORM -->
      <div class="card card-body">
        <form action="classes/save_task.php" method="POST">
        <div class="form-group">
            <select name="student_id" class="form-control">
              <option value="">Select Student</option>
              <?php  foreach($colleges as $college){ ?>
              <option value="<?php echo $college['id']; ?>"><?php echo $college['email']; ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <select name="classes_id" class="form-control">
              <option value="">Select Course</option>
              <?php  foreach($courses as $course){ ?>
              <option value="<?php echo $course['id']; ?>"><?php echo $course['symbol']; ?></option>
              <?php } ?>
            </select>
          </div>



          <input type="submit" name="save_task" class="btn-primary btn-lg btn-block" value="Add Student">
        </form>
      </div>
    </div>
    <div class="col-md-8">
      <table class="table table-bordered">
          <col>
          <col>
          <col>
          <col style="width:10%">

        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Enrolled In</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>

          <?php
          $query = "SELECT name,email, symbol,courses.id as id2,users.id as id1 FROM (roster inner join users on users.id = roster.student_id) inner join courses on roster.class_id = courses.id where departname  = $id_dept order by symbol";
          $result_tasks = mysqli_query($conn, $query);

          while($row = mysqli_fetch_assoc($result_tasks)) { ?>
          <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['symbol']; ?></td>
            <td>

              <a href="classes/delete_task.php?id=<?php echo $row['id1']?>&id2=<?php echo $row['id2']?>" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete?');">
                <i class="fa fa-trash fa-lg"></i>
              </a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<?php include('../includes/footer.php'); ?>
