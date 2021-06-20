<?php
include '../common_functions.php';

include("calender/assignment_model.php"); 

$assignemnts = getAssignments();

global $roles;
/* Unset the admin role so no one can register with admin role*/
unset($roles['admin']);

$departments = getDepartments();
$designations = getDesignations();
checkAuth();

if($_SERVER["REQUEST_METHOD"] == "POST") {

	if($_POST['algos'] == 1){

	$out = shell_exec("Rscript --verbose algorithim_1.R $N 2>&1");
	} else {
	$out = shell_exec("Rscript --verbose algorithim2.R $N 2>&1");

	}


}

?>

<?php include '../layout_header.php';?>
<?php 
// try {
// 	require 'calender/_db.php';
// 	include 'calender/backend_create.php';
// } catch (ErrorException $ex) {
//     echo "Unable to load configuration file.";
//     // you can exit or die here if you prefer - also you can log your error,
//     // or any other steps you wish to take
// }
?>
<?php
$email = $_SESSION['alogin'];
$id = $_SESSION['id'];
$sql = "SELECT * from users where email = (:email);";
$query = $dbh->prepare($sql);
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->execute();
$result = $query->fetch(PDO::FETCH_OBJ);
$cnt = 1;
?>
<?php include 'includes/header.php';?>
<div class="ts-main-content">
	<?php include 'includes/leftbar.php';?>
	<div class="content-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h1>Schedule:</h1>
								</div>
								<?php if ($error) {?><div class="errorWrap"><strong>ERROR</strong>:
									<?php echo htmlentities($error); ?>
								</div><?php } else if ($msg) {?><div class="succWrap">
									<strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?>
								</div><?php }?>
								<?php //print_r($result); ?>
								<div class="panel-body">
									<div>
										<form action="schedule.php" method="post">
											<button class="btn-primary btn-sm" name="refresh" onclick="loadEvents()"
												style="margin:4px;">Refresh</button>
											<button class="btn-primary btn-sm" name="optimize" type="submit"
												onclick="confirmSubmit()" style="margin:4px;">Optimize</button>
											<select id="algos" name="algos">
												<option value="1">Algo1</option>
												<option value="2">Algo2</option>
											</select>
											<select id="assignment_id">
												<option value="">Select Assignment</option>
												<?php  foreach($assignemnts as $assignment){ ?>
												<option value="<?php echo $assignment['assignment_id']; ?>">
													<?php echo $assignment['assignmentname']; ?></option>
												<?php } ?>
											</select>

										</form>
										<div class="space">
											Week:
											<a href="#" onclick="previousWeek()">Previous</a>
											|
											<a href="#F00" onclick="nextWeek()">Next</a>
										</div>
										<div id="dp"></div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="../js/daypilot-all.min.js"></script>

<script LANGUAGE="JavaScript">
	<!--
	function confirmSubmit() {
		var agree = confirm("Are you sure you want to re-organize your events");
		if (agree)
			return true;
		else
			return false;
	}
	// 
	-->
</script>

<script type="text/javascript">
	var dp = new DayPilot.Calendar("dp");
	dp.headerDateFormat = "M/dd/yyyy dddd";

	dp.contextMenu = new DayPilot.Menu({
		items: [{
			text: "Delete",
			onClick: function (args) {
				console.log(args.source.data.fixed);
				
				if(args.source.data.fixed == 1){
					var r = confirm("This is a fixed event, Confirm to delete");
						if (r == false) {
							return;
						}
				}

				let headers = {
					"Content-Type": "application/json"
				};

				fetch("calender/backend_delete.php", {
						method: 'POST',
						headers: headers,
						body: JSON.stringify({
							id: args.source.data.id
						})
					}).then(response => response.json())
					.then(res => {
						if (res && res.result === 'OK') {
							dp.events.remove(args.source);
						}
					});



			},
		}]
	});

	dp.onEventMoved = function (args) {

		var eventStart = new Date(args.newStart);
		var today = new Date();
		
		if (today.getTime() >= eventStart.getTime()) {
			alert('Warning: Event is being moved in the past');
		}

		DayPilot.Http.ajax({
			url: "calender/backend_move.php",
			data: {
				id: args.e.id(),
				newStart: args.newStart.toString(),
				newEnd: args.newEnd.toString()
			},
			success: function (data) {
				dp.message("Moved " + data.data.message);
			}
		});

	};

	dp.onEventResized = function (args) {
		DayPilot.Http.ajax({
			url: "calender/backend_move.php",
			data: {
				id: args.e.id(),
				newStart: args.newStart.toString(),
				newEnd: args.newEnd.toString()
			},
			success: function (data) {
				dp.message("Update " + data.data.message);
			}
		})
	};

	dp.update({
		viewType: "Week"
	});
	dp.init();
	dp.events.load("calender/backend_events.php?id=<?php echo $id?>");

	function loadEvents() {
		dp.events.load("calender/backend_events.php?id=<?php echo $id?>");
	}

	function previousWeek() {
		dp.startDate = dp.startDate.addDays(-7);
		dp.update();
		dp.events.load("calender/backend_events.php?id=<?php echo $id?>");

	}

	function nextWeek() {
		dp.startDate = dp.startDate.addDays(7);
		dp.update();
		dp.events.load("calender/backend_events.php?id=<?php echo $id?>");
	}

	dp.onTimeRangeSelected = function (args) {

		// callback hell, often caused by promises 
		DayPilot.Modal.prompt("New event name:", "Event").then(function (modal) {

			// model.makenewcall.then(())

			dp.clearSelection();
			if (modal.canceled) {
				return;
			}

			let assignment_id = document.getElementById('assignment_id').value;


			if (!assignment_id) {
				alert('Please select an assignment from the drop down.');
				return;
			}

			var today = new Date();
			today.setDate(today.getDate());
			// today.setHours(0,0,0,0)

			var current = new Date(args.start);
			if (today.getTime() > current.getTime()) {
				alert('Warning: Event is being created in the past');
			}


			var name = modal.result;

			var params = {
				start: args.start,
				end: args.end,
				student_id: <?php echo $_SESSION['id'] ?> ,
				assignment_id: assignment_id,
			};


			//	console.log(JSON.stringify(args));


			// store event in db
			DayPilot.Http.ajax({
				url: "calender/backend_create.php",
				data: params,
				success: function (ajax) {
					params.id = ajax.data.id;
					dp.events.add(ajax.data);

					var response = ajax.data;
					dp.message("Created: " + response.message);
					//dp.events.load("calender/backend_events.php");
				}
			});

		});

	};
</script>