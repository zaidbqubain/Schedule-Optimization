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


function getEventPieChart($flag=1){
global $dbh,$user_id;
 $sql = "SELECT t1.course_id, count(t1.course_id) as Value, t2.classname
 from events EV
 inner join assignments t1 ON (t1.assignment_id =EV.assignment_id)
 inner join courses t2 ON(t1.course_id =t2.id)
 WHERE  EV.status= '$flag' GROUP BY t1.course_id";

$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
$json =array();
if($query->rowCount() > 0)
{
	foreach($results as $result)
	{
	$json[]=array('Value'=>$result->Value,'Course'=>$result->classname);
	}
}
	return $json;

}

function getEventChart2(){
global $dbh,$user_id;
 $sql = "SELECT EV.id, EV.student_id, EV.assignment_id,
 EV.event_start, EV.event_end, EV.status,EV.pct_complete,t1.assignmentname,t1.type,
 t1.duration,t1.course_id,t2.classname,t2.departname
			  from  events EV left join assignments t1 ON (t1.assignment_id =EV.assignment_id) left join courses t2 ON(t1.course_id =t2.id)";

/*

      "Standard": 30,
       "Actual": 30,
       "Course":"Electrical  Eng",
       "AssignementType": "Homework"
*/


/*
       "Value": 30,
       "Course":"Electrical  Eng"

*/
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
$json =array();
$total = $query->rowCount();
$completed = 0;
$uncompleted = 0;

if($query->rowCount() > 0)
{
	foreach($results as $result)
	{

	   if($result->status==1){
		  $completed++;
	   }
	   if($result->status==0){
		   $uncompleted++;
	   }

		$json []= array(
		'Standard' => $result->duration,
		'Actual'=>getTotalHoursBetween($result->event_end,$result->event_start),
		'Course' =>$result->classname,
		'AssignementType' =>$result->type,
		);

	}

}


return $json;

}


function getEvents(){
	global $dbh,$user_id;
 $sql = "SELECT EV.id, EV.student_id, EV.assignment_id,
 EV.event_start, EV.event_end, EV.status,EV.pct_complete,t1.assignmentname,t1.type,
 t1.duration,t1.course_id,t2.classname,t2.departname
			  from  events EV left join assignments t1 ON (t1.assignment_id =EV.assignment_id) left join courses t2 ON(t1.course_id =t2.id) order by EV.event_start  ";

/*

     "Date": "4/1/2021",
       "CompletionRate": 10,
       "Course":"Electrical  Eng",
       "AssignementType": "Exam"
*/
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
$json =array();
if($query->rowCount() > 0)
{
	foreach($results as $result)
	{
		$json []= array(
		'Date' => $result->event_start,
		'CompletionRate'=>$result->pct_complete,
		'Course' =>$result->classname,
		'AssignementType' =>$result->type,
		);

	}

}
return $json;
}
function getAssigments(){
global $dbh,$user_id;
 $sql = "SELECT DISTINCT(type) from assignments";

$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
$json =array();
if($query->rowCount() > 0)
{
	foreach($results as $result)
	{
      $json[]= 	array('id'=>$result->type,'text'=>$result->type);
	}
}

return $json;

}
function getCourses(){
global $dbh,$user_id;
 $sql = "SELECT DISTINCT(classname) from courses";

$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
$json =array();
if($query->rowCount() > 0)
{
	foreach($results as $result)
	{
      $json[]= 	array('id'=>$result->classname,'text'=>$result->classname);
	}
}

return $json;

}



/*Chart One */
$eventData  =  getEvents();
$eventDataObj = json_encode($eventData);
$assignmentTypeList  =  getAssigments();
$assignmentTypeObj = json_encode($assignmentTypeList);
$coursesList  =  getCourses();
$coursesListObj = json_encode($coursesList);

/*Chart 2*/
$eventDataChart2 = getEventChart2();
$eventDataChart2Obj =  json_encode($eventDataChart2);

/* Chart 3 & 4 */

$pieChart1= getEventPieChart($flag=1);
$pieChart2= getEventPieChart($flag=0);
$pieChart1Obj = json_encode($pieChart1);
$pieChart2Obj = json_encode($pieChart2);

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
<!-- Plese include the bellow links into your header or leave it as is-->
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/bootstrap.min.css"/>
  <link rel="stylesheet" href="Plugins/daterangepicker/daterangepicker.css"/>
  <link rel="stylesheet" href="Plugins/select2/css/select2.min.css"/>
  <link rel="stylesheet" href="Plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">


		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
								<div class="panel panel-default">
                <div class="panel-heading"><h5 style="text-align: center;">Peformance</h5></div>
                <?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php }
				else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
<?php //print_r($result); ?>
<div class="panel-body">
                      <div class="row">
                      <div class="col-sm-6">
                      <div class="form-group">
                        <label>Date Range</label>

                        <div class="input-group">
                          <input type="text" class="form-control float-right" id="Date">
                        </div>
                        <!-- /.input group -->
                      </div>
                      <!-- /.form group -->
                      </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="Courses">Courses:</label>
                            <select id="Courses" class="select2" multiple="multiple" data-placeholder="Select a State" style="width: 100%;">

                            </select>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="AssignementTypes">Assignement Type:</label>
                            <select id="AssignementTypes" class="select2" multiple="multiple" data-placeholder="Select a State" style="width: 100%;">

                            </select>
                          </div>
                        </div>
                      </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="card">
                          <div class="card-body">
                            <canvas id="CompletionPerformance" style="height:6  0vh; width:80vw"></canvas>
                          </div>
                          <div class="card-body">
                            <canvas id="CourseTimeDistribution" style="height:6  0vh; width:80vw"></canvas>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="card">
                          <div class="card-body">
                            <canvas id="TimeUsage" style="height:6  0vh; width:80vw"></canvas>
                          </div>
                          <div class="card-body">
                            <canvas id="CourseTimeDistribution-b" style="height:6  0vh; width:80vw"></canvas>
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
  <!-- /.content-wrapper -->

</div>

<script>

var coursesListObj = '<?php echo $coursesListObj; ?>';
var assignmentTypeObj = '<?php echo $assignmentTypeObj; ?>';
var eventDataObj = '<?php echo $eventDataObj; ?>';
var eventDataChart2Obj = '<?php echo $eventDataChart2Obj; ?>';
var pieChart1Obj = '<?php echo $pieChart1Obj; ?>';
var pieChart2Obj = '<?php echo $pieChart2Obj; ?>';


coursesListObj = JSON.parse(coursesListObj);
assignmentTypeObj = JSON.parse(assignmentTypeObj);
eventDataObj = JSON.parse(eventDataObj);
eventDataChart2Obj = JSON.parse(eventDataChart2Obj);

pieChart1Obj = JSON.parse(pieChart1Obj);
pieChart2Obj = JSON.parse(pieChart2Obj);

console.log(coursesListObj);
console.log(assignmentTypeObj);
console.log(eventDataObj);
console.log(eventDataChart2Obj);

console.log(pieChart1Obj);

console.log(pieChart2Obj);

</script>
<!-- ./wrapper -->

  <script src="<?php echo SITE_PATH;?>/student/Plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo SITE_PATH;?>/student/Plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo SITE_PATH;?>/student/Plugins/select2/js/select2.min.js"></script>
<script src="<?php echo SITE_PATH;?>/student/Plugins/chart.js/Chart.min.js"></script>
<script src="<?php echo SITE_PATH;?>/student/Plugins/moment/moment.min.js"></script>

<script src="<?php echo SITE_PATH;?>/student/Plugins/daterangepicker/daterangepicker.js"></script>
<script src="Scripts/chart1.js"></script>
<script src="Scripts/chart2.js"></script>
<script src="Scripts/chart4-a.js"></script>
<script src="Scripts/chart4-b.js"></script>
<script src="Scripts/Filters.js"></script>
