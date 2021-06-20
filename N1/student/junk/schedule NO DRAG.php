<?php
include '../common_functions.php';

global $roles;
/* Unset the admin role so no one can register with admin role*/
unset($roles['admin']);

$departments = getDepartments();
$designations = getDesignations();
checkAuth();

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
										<h1>Schedule:</h1></div>
										<?php if ($error) {?><div class="errorWrap"><strong>ERROR</strong>:
										<?php echo htmlentities($error); ?>
										 </div><?php } else if ($msg) {?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?>
										 </div><?php }?>
<?php //print_r($result); ?>
									<div class="panel-body">
										<div>
										<button class="btn-primary btn-sm" name="refresh" onclick="loadEvents()" style="margin:4px;">Refresh</button>
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

<script type="text/javascript">

var dp = new DayPilot.Calendar("dp");
dp.update({viewType: "Week"});
dp.init();
dp.events.load("calender/backend_events.php");

function loadEvents() {
	dp.events.load("calender/backend_events.php");	
}

dp.onTimeRangeSelected = function (args) {

	// callback hell, often caused by promises 
//   DayPilot.Modal.prompt("New event name:", "Event").then(function(modal) {

// 	// model.makenewcall.then(())

//     dp.clearSelection();
//     if (modal.canceled) {
//       return;
//     }

//     var name = modal.result;

//     var params = {
//       start: args.start,
//       end: args.end,
//       text: name
// 	};	

	
// //	console.log(JSON.stringify(args));


// // store event in db
//     DayPilot.Http.ajax({
//       url: "calender/backend_create.php",
//       data: params,
//       success: function(ajax) {

//         params.id = ajax.data.id;
//         dp.events.add(params);

//         var response = ajax.data;
//         dp.message("Created: " + response.message);
// 		//dp.events.load("calender/backend_events.php");
//       }
// 	});
	
//   });

};
</script>

