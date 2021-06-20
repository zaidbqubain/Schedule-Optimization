
<?php include("../includes/db.php"); ?>
<?php include("college_model.php");
  $colleges = getDepartmentss();
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
        <form action="courses/save_task.php" method="POST">
        <div class="form-group">
            <select name="college_id" class="form-control">
              <option value="">Select Department</option>
              <?php  foreach($colleges as $college){ ?>
              <option value="<?php echo $college['id']; ?>"><?php echo $college['departname']; ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <textarea name="symbol" rows="1" class="form-control" placeholder="Course Symbol"></textarea>
          </div>

          <div class="form-group">
            <input type="text" name="title" class="form-control" placeholder="Course Title" autofocus>
          </div>
          <div class="form-group">
            <textarea name="description" rows="2" class="form-control" placeholder="Course Description"></textarea>
          </div>

          <input type="submit" name="save_task" class="btn-primary btn-lg btn-block" value="Save Course">
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
            <th>Symbol</th>
            <th>Title</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>

          <?php
          $query = "SELECT * FROM courses WHERE departname = $id_dept";
          $result_tasks = mysqli_query($conn, $query);


          while($row = mysqli_fetch_assoc($result_tasks)) { ?>
          <tr>
            <td><?php echo $row['symbol']; ?></td>
            <td><?php echo $row['classname']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td>

              <a href="courses/delete_task.php?id=<?php echo $row['id']?>" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete?');">
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
