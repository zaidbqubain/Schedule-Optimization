<?php
include('common_functions.php');
if(empty($_GET['key']))
{
header('location:index.php');	
}
else
{
if(isset($_POST['reset']))
{
$password=$_POST['password'];

$retype_password=$_POST['retype_password'];
if($password==$retype_password)
{
	$key=$_GET['key'];
	$password=md5($password);

	$sql="UPDATE users SET `password`='".$password."' WHERE `key`='$key'";
	$query = $dbh->prepare($sql);
	$query-> bindParam(':password', $password, PDO::PARAM_STR);
	$query->execute();
	$msg="Password Updated Successfully";

     $msg;

//echo "<script type='text/javascript'> document.location = 'route.php'; </script>";
}
else
{
	 $error="Password does not matched";
}

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
						<h1 class="text-center text-bold mt-4x">Reset Password</h1>
						<div class="well row pt-2x pb-3x bk-light">
							<div class="col-md-8 col-md-offset-2">
								<?php if($error){?><div class="errorWrap" style="color: red;"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
								else if($msg){?><div class="succWrap" style="color: green;"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
								<form method="post">

									<label for="" class="text-uppercase text-sm">Password</label>
									<input type="password" placeholder="Enter Password" name="password" class="form-control mb" required>
									<label for="" class="text-uppercase text-sm">Confirm Password</label>
									<input type="password" placeholder="Re-type Password" name="retype_password" class="form-control mb" required>
									<button class="btn btn-primary btn-block" name="reset" type="submit">Reset Password</button>
								</form>
								<br>
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