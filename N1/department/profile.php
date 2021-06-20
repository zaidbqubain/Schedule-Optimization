<?php
include('../common_functions.php');

global $roles;
/* Unset the admin role so no one can register with admin role*/
unset($roles['admin']);

$departments = getDepartments();
$designations = getDesignations();
checkAuth();
if(isset($_POST['submit']))
  {
	$file = $_FILES['image']['name'];
	$file_loc = $_FILES['image']['tmp_name'];
	$folder="../images/";
	$new_file_name = strtolower($file);
	$final_file=str_replace(' ','-',$new_file_name);

	$name=$_POST['name'];
	// $mobileno=$_POST['mobile'];
	// $designation=$_POST['designation'];
	$departname=$_POST['departname'];
	$idedit=$_POST['editid'];
	$image=$_POST['image'];

	if(move_uploaded_file($file_loc,$folder.$final_file))
		{
			$image=$final_file;
		}

	// $sql="UPDATE users SET name=(:name), designation=(:designation), department_id=(:department_id), Image=(:image) WHERE id=(:idedit)";
	// $query = $dbh->prepare($sql);
	// $query-> bindParam(':name', $name, PDO::PARAM_STR);
	// $query-> bindParam(':designation', $designation, PDO::PARAM_STR);
	// $query-> bindParam(':department_id', $department_id, PDO::PARAM_STR);
	// $query-> bindParam(':image', $image, PDO::PARAM_STR);
	// $query-> bindParam(':idedit', $idedit, PDO::PARAM_STR);
	// $query->execute();
	// $msg="Information Updated Successfully";

	$sql="UPDATE users SET name=(:name), Image=(:image) WHERE id=(:idedit)";
	$query = $dbh->prepare($sql);
	$query-> bindParam(':name', $name, PDO::PARAM_STR);
	// $query-> bindParam(':designation', $designation, PDO::PARAM_STR);
	// $query-> bindParam(':department_id', $department_id, PDO::PARAM_STR);
	// $query-> bindParam(':student_id', $student_id, PDO::PARAM_STR);
	$query-> bindParam(':image', $image, PDO::PARAM_STR);
	$query-> bindParam(':idedit', $idedit, PDO::PARAM_STR);
	$query->execute();


	$sql="UPDATE departments SET departname =(:departname) WHERE id=(:idedit)";
	$query = $dbh->prepare($sql);
	$query-> bindParam(':departname', $departname, PDO::PARAM_STR);
	$query-> bindParam(':idedit', $idedit, PDO::PARAM_STR);
	$query->execute();
	$msg="Information Updated Successfully";
}
?>

<?php include('../layout_header.php');?>
<?php
		$email = $_SESSION['alogin'];
		$sql = "SELECT * FROM users inner join departments on users.id = departments.id where email = (:email);";
		$query = $dbh -> prepare($sql);
		$query-> bindParam(':email', $email, PDO::PARAM_STR);
		$query->execute();
		$result=$query->fetch(PDO::FETCH_OBJ);
		$cnt=1;
?>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
	<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading"><?php echo htmlentities($_SESSION['alogin']); ?></div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php }
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
<?php //print_r($result); ?>
									<div class="panel-body">
<form method="post" class="form-horizontal" enctype="multipart/form-data">

<div class="form-group">
	<div class="col-sm-4">
	</div>
	<div class="col-sm-4 text-center">
		<img src="<?php echo SITE_PATH; ?>/images/<?php echo htmlentities($result->image);?>" style="width:150px; height: 150px; border-radius:50%; margin:10px;">
		<input type="file" name="image" class="form-control">
		<input type="hidden" name="image" class="form-control" value="<?php echo htmlentities($result->image);?>">
	</div>
	<div class="col-sm-4">
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label">Name<span style="color:red">*</span></label>
	<div class="col-sm-4">
	<input type="text" name="name" class="form-control" required value="<?php echo htmlentities($result->name);?>">
	</div>

	<label class="col-sm-2 control-label">Email<span style="color:red">*</span></label>
	<div class="col-sm-4">
	<input type="email"  class="form-control" value="<?php echo htmlentities($result->email);?>" disabled>
	</div>
</div>


<div class="form-group">
	<label class="col-sm-2 control-label">Department Name<span style="color:red">*</span></label>
	<div class="col-sm-4">
	<input type="text" name="departname" class="form-control" required value="<?php echo htmlentities($result->departname);?>">
	</div>

</div>




<input type="hidden" name="editid" class="form-control" required value="<?php echo htmlentities($result->id);?>">

<div class="form-group">
	<div class="col-sm-8 col-sm-offset-2">
		<button class="btn btn-primary" name="submit" type="submit">Save Changes</button>
	</div>
</div>

</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
