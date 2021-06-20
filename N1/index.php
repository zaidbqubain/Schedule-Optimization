<?php
include('common_functions.php');
if(isset($_POST['login']))
{
// $status='1';
$email=$_POST['username'];
$password=md5($_POST['password']);
$sql ="SELECT email,password,users.id as id FROM users inner join students on users.id = students.id WHERE email=:email and password=:password";
$query= $dbh -> prepare($sql);
$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
// $query-> bindParam(':status', $status, PDO::PARAM_STR);
$query-> execute();
$results=$query->fetch(PDO::FETCH_OBJ);


$sql ="SELECT email,password,users.id as id FROM users inner join departments on users.id = departments.id WHERE email=:email and password=:password";
$query2= $dbh -> prepare($sql);
$query2-> bindParam(':email', $email, PDO::PARAM_STR);
$query2-> bindParam(':password', $password, PDO::PARAM_STR);
// $query-> bindParam(':status', $status, PDO::PARAM_STR);
$query2-> execute();
$results2=$query2->fetch(PDO::FETCH_OBJ);

if($query->rowCount() > 0)
{

  manage_route($results,"student");
  echo "<script type='text/javascript'> document.location = 'route.php'; </script>";
} else if($query2->rowCount() > 0){

	manage_route($results2,"staff");
	echo "<script type='text/javascript'> document.location = 'route.php'; </script>";

} else{

  echo "<script>alert('Invalid Details Or Account Not Confirmed');</script>";

}

}

?>
<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">


	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">

</head>

<body>
	<div class="login-page bk-img">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<h1 class="text-center text-bold mt-4x">Login</h1>
						<div class="well row pt-2x pb-3x bk-light">
							<div class="col-md-8 col-md-offset-2">
								<form method="post">

									<label for="" class="text-uppercase text-sm">Your Email</label>
									<input type="text" placeholder="Username" name="username" class="form-control mb" required>

									<label for="" class="text-uppercase text-sm">Password</label>
									<input type="password" placeholder="Password" name="password" class="form-control mb" required>
									<button class="btn btn-primary btn-block" name="login" type="submit">LOGIN</button>
								</form>
								<br>
								<p>Don't Have an Account? <a href="register.php" >Sign up</a> or if department faculty <a href="dep_signup.php" >Department Sign up</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>

</body>

</html>
