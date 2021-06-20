<?php include("../includes/db.php"); ?>

<?php include("syllabus_model.php");
  $courses = getCourses();
  $assigntypes = getAssigntype();

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
      <div class="card card-body h-100">
        <form action="syllabus/save_task.php" method="POST">
          <div class="form-group">
            <select name="course_symbol" class="form-control">
              <option value="">Select Course Symbol</option>
              <?php  foreach($courses as $course){ ?>
              <option value="<?php echo $course['id']; ?>"><?php echo $course['symbol']; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <select name="assign_type" class="form-control" onchange="yesnoCheck(this)" >
              <option value="">Select Assignment Type</option>

              <option value="Homework">Homework</option>
              <option value="Exam">Exam</option>
              <option value="Project">Project</option>
              <option value="Lab">Lab</option>

            </select>
          </div>

          <div class='input-group date form-group' id='start_datetime'>
            <input type='text' class="form-control"  name="start" placeholder="Start Date"/>
            <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>

          <div class='input-group date form-group' id='end_datetime' name="end">
            <input type='text' class="form-control" name="end" placeholder="End Date"/>
            <span class="input-group-addon">
              <span class="glyphicon glyphicon-calendar"></span>
            </span>
          </div>

          <div class="form-group">
            <input type="text" name="duration" class="form-control" placeholder="Duration (minutes)" autofocus>
          </div>

           <div class="form-group" style="display: none;" id='ifYes'>
            <input type="text" name="examdur" class="form-control" id='examdur' placeholder="Exam Duration (minutes)" autofocus>
          </div>

          <div class="form-group">
            <input type="text" name="title" class="form-control" placeholder="Assignment Title" autofocus>
          </div>
          <div class="form-group">
            <textarea name="description" rows="2" class="form-control" placeholder="Assignment Description"></textarea>
          </div>
          <input type="submit" name="save_task" class="btn-primary btn-lg btn-block" value="Save Assignment">
        </form>
      </div>
    </div>
    <div class="col-md-8">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Assignment Type</th>
            <th>Title</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Duration</th>
            <th>Class Name</th>
            <th>Action</th>

          </tr>
        </thead>
        <tbody>

          <?php
          $query = "SELECT * FROM assignments inner join courses on course_id = id where departname = $id_dept order by enddate ";
          $result_tasks = mysqli_query($conn, $query);

          while($row = mysqli_fetch_assoc($result_tasks)) { ?>
          <tr>
            <td><?php echo $row['type']; ?></td>
            <td><?php echo $row['assignmentname']; ?></td>
            <td><?php echo date( 'Y-m-d H:s:i', strtotime($row['startdate'])); ?></td>
            <td><?php echo date( 'Y-m-d H:s:i', strtotime($row['enddate'])); ?></td>
            <td><?php echo $row['duration']; ?></td>
            <td><?php echo $row['symbol']; ?></td>

            <td>
              <a href="syllabus/delete_task.php?id=<?php echo $row['assignment_id']?>" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete?');">
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
<script src="<?php echo SITE_PATH; ?>/js/jquery.min.js"></script>
<script src="<?php echo SITE_PATH; ?>/js/moment.min.js"></script>
<script src="<?php echo SITE_PATH; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo SITE_PATH; ?>/js/bootstrap-select.min.js"></script>
<script src="<?php echo SITE_PATH; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo SITE_PATH; ?>/js/bootstrap-datetimepicker.min.js"></script>
<?php echo ""?>
<script type="text/javascript">
  $(function () {
    $('#start_datetime').datetimepicker();
    $('#end_datetime').datetimepicker();
  });
</script>

<script type="text/javascript">

function yesnoCheck(that) {
    if (that.value == "Exam") {
        document.getElementById("ifYes").style.display = "block";
    } else {
        document.getElementById("ifYes").style.display = "none";
    }
}
</script>
