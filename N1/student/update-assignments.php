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


function delete_assigments(){
	global $dbh;
 if(!empty($_GET['del'])){

        $id = $_GET['del'];
        $stmt = $dbh->prepare( "DELETE FROM events WHERE id =:id" );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if( $stmt->rowCount() )
		{
		  	header("Location:".SITE_PATH."/student/update-assignments.php/?del_status=200");
			die;
		}else{
			header("Location:".SITE_PATH."/student/update-assignments.php/?del_status=100");
			die;
		}
 }

}

function change_status_assigments(){
	global $dbh;
 if(!empty($_GET['confirm'])){

        $id = $_GET['confirm'];
        $status = 0;

		$data = [
			'status' =>$status,
			'id' => $id,
		];
		$sql = "UPDATE events SET status=:status WHERE id=:id";
		$stmt= $dbh->prepare($sql);
		$stmt->execute($data);

	    header("Location:".SITE_PATH."/student/update-assignments.php/?change_status=200");
		die;

 }


 if(!empty($_GET['unconfirm'])){

        $id = $_GET['unconfirm'];
        $status = 1;

		$data = [
			'status' =>$status,
			'id' => $id,
		];
		$sql = "UPDATE events SET status=:status WHERE id=:id";
		$stmt= $dbh->prepare($sql);
		$stmt->execute($data);

	    header("Location:".SITE_PATH."/student/update-assignments.php/?change_status=200");
		die;

 }
}




/*To delete event record by id */
delete_assigments();

/* To change the status of record */
change_status_assigments();

$error = '';
$msg ='';
if(!empty($_GET['del_status']) && $_GET['del_status']==100){
	$error = 'Record could not be deleted. Please try again.';
}
if(!empty($_GET['del_status']) && $_GET['del_status']==200){
	$msg = 'Record deleted successfully.';
}
if(!empty($_GET['change_status']) && $_GET['change_status']==200){
	$msg = 'Record status changed successfully.';
}




?>

<?php include('../layout_header.php');?>
<?php
		$email = $_SESSION['alogin'];
		$sql = "SELECT * from users where email = (:email);";
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
									<div class="panel-heading"> <h1>Update Assignments:</h1></div>
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php }
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>




<?php //print_r($result); ?>
									<div class="panel-body">


									  		<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
										        <th>#</th>
                                                <th>Class</th>
                                                <th>Assignment</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
												<th>Duration</th>
                                                <th>Actual</th>
                                                <th>Status</th>
											   <th>% of Time Completed</th>
											    <th>Action</th>
										</tr>
									</thead>

									<tbody>

<?php
$sql = "SELECT EV.id, EV.student_id, EV.assignment_id, EV.event_start, EV.event_end, EV.status,EV.pct_complete,t1.assignmentname,t1.duration,t2.classname
			  from  events EV left join assignments t1 ON (t1.assignment_id =EV.assignment_id) left join courses t2 ON(t1.course_id =t2.id)
			  WHERE student_id = '$user_id' ";

$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{				?>
										<tr>
											<td><?php echo htmlentities($cnt);?></td>
                                            <td><?php echo htmlentities($result->classname);?></td>
                                            <td><?php echo htmlentities($result->assignmentname);?></td>
                                            <td><?php echo date('d-M-Y H:i:s',  strtotime($result->event_start));?></td>
                                            <td><?php echo date('d-M-Y H:i:s', strtotime($result->event_end));?></td>
											<td><?php echo htmlentities($result->duration).' Min';?></td>
                                            <td><?php echo getTotalHoursBetween($result->event_end,$result->event_start).' Min'; ?></td>
											<td>
                                            <?php if($result->status == 1)
                                                    {?>
                                                    <a href="update-assignments.php?confirm=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you want to incomplete the record?')">Completed <i class="fa fa-check-circle"></i></a>
                                                    <?php } else {?>
                                                    <a href="update-assignments.php?unconfirm=<?php echo htmlentities($result->id);?>" onclick="return confirm('Do you want to complete the record?')">Incomplete <i class="fa fa-times-circle"></i></a>
                                                    <?php } ?>


										</td>

										 <td style="text-align:center;"><?php echo htmlentities($result->pct_complete);?></td>
										<td>
										<a href="<?php echo SITE_PATH; ?>/student/edit-assignments.php?edit=<?php echo $result->id;?>" onclick="return confirm('Do you want to edit the record?');">&nbsp; <i class="fa fa-pencil"></i></a>&nbsp;&nbsp;
										<a href="update-assignments.php?del=<?php echo $result->id;?>" onclick="return confirm('Do you want to delete the record?');"><i class="fa fa-trash" style="color:red"></i></a>&nbsp;&nbsp;
										</td>
																				</tr>
										<?php $cnt=$cnt+1; }} ?>

									</tbody>
								</table>



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
