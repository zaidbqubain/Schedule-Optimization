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



  	$student_id=$_POST['student_id'];
  	 $email=$_POST['email'];
  	if(empty($email))
  	{
  		$email=NULL;
  	}
  	    		$idedit=$_POST['editid'];

/* check whether using a unique student id or not */
        $sql = "SELECT * from users where id =(:idedit);";
        $qry = $dbh -> prepare($sql);
        $qry-> bindParam(':student_id', $student_id, PDO::PARAM_STR);
		$qry-> bindParam(':idedit', $idedit, PDO::PARAM_STR);
        $qry->execute();
        $results=$qry->fetch(PDO::FETCH_OBJ);
        if($results>0)
        {
               $error='Not a unique id for student ID!';

        }

        /* check whether using a student id or not */
	$file = $_FILES['image']['name'];
	$file_loc = $_FILES['image']['tmp_name'];
	$folder="../images/";
	$new_file_name = strtolower($file);
	$final_file=str_replace(' ','-',$new_file_name);

	$name=$_POST['name'];
	$gpa=$_POST['gpa'];
	$sleeptime=$_POST['sleeptime'];

	$sleepdur=$_POST['sleepdur'];

	if($sleeptime < 15 || $sleeptime > 24){

		$error='sleep time needs be between 15 and 24';
		$sleeptime = 20;

	}

	if($sleepdur > 13 || $sleepdur < 1){

		$error='sleep duration needs be between 1 and 12 hrs';
		$sleepdur = 9;

	}



	$mobileno=$_POST['mobile'];
	$designation=$_POST['designation'];
	$department_id=$_POST['department_id'];

	$image=$_POST['image'];

	if(move_uploaded_file($file_loc,$folder.$final_file))
		{
			$image=$final_file;
		}

	// $sql="UPDATE users SET name=(:name), designation=(:designation), department_id=(:department_id), student_id=(:student_id), Image=(:image) WHERE id=(:idedit)";
	// $query = $dbh->prepare($sql);
	// $query-> bindParam(':name', $name, PDO::PARAM_STR);
	// $query-> bindParam(':designation', $designation, PDO::PARAM_STR);
	// $query-> bindParam(':department_id', $department_id, PDO::PARAM_STR);
	// $query-> bindParam(':student_id', $student_id, PDO::PARAM_STR);
	// $query-> bindParam(':image', $image, PDO::PARAM_STR);
	// $query-> bindParam(':idedit', $idedit, PDO::PARAM_STR);
	// $query->execute();
	// $msg="Information Updated Successfully";



	$sql="UPDATE users SET name=(:name), image=(:image),  WHERE id=(:idedit)";
	$query = $dbh->prepare($sql);
	$query-> bindParam(':name', $name, PDO::PARAM_STR);
	// $query-> bindParam(':designation', $designation, PDO::PARAM_STR);
	// $query-> bindParam(':department_id', $department_id, PDO::PARAM_STR);
	// $query-> bindParam(':student_id', $student_id, PDO::PARAM_STR);
	$query-> bindParam(':image', $image, PDO::PARAM_STR);
	$query-> bindParam(':idedit', $idedit, PDO::PARAM_STR);
	$query->execute();
	// $msg="Information Updated Successfully";

	$sql="UPDATE students SET designation=(:designation), department_id=(:department_id),Gpa=(:gpa), sleeptime=(:sleeptime), sleepduration=(:sleepduration)  WHERE id=(:idedit)";
	$query = $dbh->prepare($sql);
	$query-> bindParam(':sleeptime', $sleeptime, PDO::PARAM_STR);
	$query-> bindParam(':sleepduration', $sleepdur, PDO::PARAM_STR);
	$query-> bindParam(':designation', $designation, PDO::PARAM_STR);
	$query-> bindParam(':gpa', $gpa, PDO::PARAM_STR);
	$query-> bindParam(':department_id', $department_id, PDO::PARAM_STR);
	$query-> bindParam(':idedit', $idedit, PDO::PARAM_STR);
	$query->execute();
	$msg="Information Updated Successfully";
}
?>

<?php include('../layout_header.php');?>
<?php
		$email = $_SESSION['alogin'];
		$sql = "SELECT * FROM users inner join students on users.id = students.id where email = (:email);";
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
		<img src="<?php echo SITE_PATH; ?>/images/<?php echo htmlentities($result->image);?>" style="width:150px; height:150px; border-radius:50%; margin:10px;">
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
	<input type="email" name="email"  class="form-control" value="<?php echo htmlentities($result->email);?>" disabled>
	</div>
</div>


<div class="form-group">
	<label class="col-sm-2 control-label">Department<span style="color:red">*</span></label>
	<div class="col-sm-4">
	   <!-- <input type="text" name="mobile" class="form-control" required value="<?php echo htmlentities($result->mobile);?>"> -->

		<select name="department_id" class="form-control" required>
		<option value="">Select</option>
	    	<?php  foreach($departments as $department){ ?>
		     <option value="<?php echo $department['id']; ?>" <?php if(htmlentities($result->department_id)==$department['id']) { echo "selected"; } ?>><?php echo $department['departname']; ?></option>
		   <?php } ?>
		</select>
	</div>

	<label class="col-sm-2 control-label">Year<span style="color:red">*</span></label>
	<div class="col-sm-4">
	<select name="designation" class="form-control" required="required">
    <option value="">Select</option>
    <option value="Freshman" <?php if(htmlentities($result->designation)== "Freshman") { echo "selected"; } ?>>Freshman</option>
    <option value="Sophomore" <?php if(htmlentities($result->designation)== "Sophomore") { echo "selected"; } ?>>Sophomore</option>
    <option value="Junior"<?php if(htmlentities($result->designation)== "Junior") { echo "selected"; } ?>>Junior</option>
    <option value="Senior"<?php if(htmlentities($result->designation)== "Senior") { echo "selected"; } ?>>Senior</option>
<!--     <?php  foreach($designations as $designation){ ?>
    <option value="<?php echo $designation['id']; ?>" <?php if( htmlentities($result->designation==$designation['id'])) { echo "selected";} ?>><?php echo $designation['name']; ?></option>
    <?php } ?>   -->
    </select>
	</div>
</div>

<div class="form-group">

		<label class="col-sm-2 control-label">GPA<span style="color:red">*</span></label>
	<div class="col-sm-4">
	<input type="text" name="gpa" class="form-control" required value="<?php echo htmlentities($result->Gpa);?>">
	</div>

	<label class="col-sm-2 control-label">Sleep Time(24hr)<span style="color:red">*</span></label>
	<div class="col-sm-4">
	<input type="text" name="sleeptime" class="form-control" required value="<?php echo htmlentities($result->sleeptime);?>">
	</div>
</div>

<div class="form-group">

		<label class="col-sm-2 control-label">Sleep Duration(hr)<span style="color:red">*</span></label>
	<div class="col-sm-4">
	<input type="text" name="sleepdur" class="form-control" required value="<?php echo htmlentities($result->sleepduration);?>">
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
<?php include('../layout_footer.php');?>

	<script>
        $('input[name="student_id"]').mask('00000-00000');

    </script>
