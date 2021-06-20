<?php
include("../../includes/db.php");
include '../../common_functions.php';
$title = '';
$description = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM courses WHERE id=$id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $title = $row['title'];
        $description = $row['description'];
    }
}

if (isset($_POST['update'])) {
    $id = $_GET['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $query = "UPDATE courses set classname = '$title', description = '$description' WHERE id=$id";
    mysqli_query($conn, $query);
    $_SESSION['message'] = 'courses Updated Successfully';
    $_SESSION['message_type'] = 'warning';    
    header('Location: ../syllabus.php');
}

?>
<?php include '../../layout_header.php';?>
<?php include '../includes/header.php';?>
<div class="ts-main-content p-4">
  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <div class="row">
                    <div class="col-md-4 mx-auto">
                      <div class="card card-body">
                        <form action="edit.php?id=<?php echo $_GET['id']; ?>" method="POST">
                          <div class="form-group">
                            <input name="title" type="text" class="form-control" value="<?php echo $title; ?>"
                              placeholder="Update Title" />
                          </div>
                          <div class="form-group">
                            <textarea name="description" class="form-control" cols="30"
                              rows="10"><?php echo $description; ?></textarea>
                          </div>
                          <button class="btn-success" name="update">
                            Update
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>Î
        </div>
      </div>
    </div>
  </div>
</div>