<?php
include('../common_functions.php');

global $roles;
/* Unset the admin role so no one can register with admin role*/
unset($roles['admin']);

$departments = getDepartments();
$designations = getDesignations();
checkAuth();
$user_id = $_SESSION['id'];

function getTotalHoursBetween($enddate,$startdate){

$t1 = strtotime($enddate);
$t2 = strtotime($startdate);
$diff = $t1 - $t2;
$hours = $diff / ( 60 * 60 );
return $hours * 60;
}


function update_events(){
	global $dbh,$user_id;
if(isset($_GET['edit']))
	{
		$editid=$_GET['edit'];
	}

if(isset($_POST['submit'])){

        $pct_complete = $_POST['pct_complete'];

		$data = [
			'pct_complete' =>$pct_complete,
			'id' => $editid,
		];
		$sql = "UPDATE events SET pct_complete=:pct_complete WHERE id=:id";
		$stmt= $dbh->prepare($sql);
		$stmt->execute($data);

	    header("Location:".SITE_PATH."/student/update-assignments.php/?change_status=200");
		die;

 }
}




/*To delete event record by id */
update_events();


$error = '';
$msg ='';
if(!empty($_GET['del_status']) && $_GET['del_status']==100){
	$error = 'Record could not be deleted Please try again..';
}
if(!empty($_GET['del_status']) && $_GET['del_status']==200){
	$msg = 'Record deleted successfully..';
}
if(!empty($_GET['change_status']) && $_GET['change_status']==200){
	$msg = 'Record status changed successfully..';
}




?>

<?php include('../layout_header.php');?>
<?php
		$id = $_GET['edit'];
		$sql = "SELECT * from events where id = (:id);";
		$query = $dbh -> prepare($sql);
		$query-> bindParam(':id', $id, PDO::PARAM_STR);
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
									<div class="panel-heading"> <h1>Update Assignments:</h1></div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php }
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>




<?php //print_r($result); ?>
									<div class="panel-body">

											<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">
											<div class="form-group">
											<label class="col-sm-2 control-label">% Complete<span style="color:red">*</span></label>
											<div class="col-sm-6">
											<input type="number" name="pct_complete" class="form-control" required value="<?php echo htmlentities($result->pct_complete);?>">
											</div>

											</div>


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
