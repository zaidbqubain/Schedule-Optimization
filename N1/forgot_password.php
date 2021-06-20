<?php
include('common_functions.php');
if(isset($_POST['login']))
{
$status='1';
$email=$_POST['username'];
$sql ="SELECT email,id FROM users WHERE email=:email ";
$query= $dbh -> prepare($sql);
$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> execute();
$results=$query->fetch(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
	$id=$results->id;
	$key = md5(2418*2+$email);
	$addKey = substr(md5(uniqid(rand(),1)),3,10);
	$key = $key . $addKey;
	$sql="UPDATE users SET `key`='".$key."' WHERE id=$id";
	$query = $dbh->prepare($sql);
	$query-> bindParam(':key', $key, PDO::PARAM_STR);
	$query->execute();

	$output ='<p>Dear user,</p>';
	$output.='<p>Please click on the following link to reset your password.</p>';
	$output.='<p>-------------------------------------------------------------</p>';
	$output.='<p><a href="'.SITE_PATH.'/reset_password.php?key='.$key.'" target="_blank">
	'.SITE_PATH.'/reset_password.php?key='.$key.'</a></p>'; 
	$output.='<p>-------------------------------------------------------------</p>';
	$output.='<p>Please be sure to copy the entire link into your browser.
	The link will expire after 1 day for security reason.</p>';
	$output.='<p>If you did not request this forgotten password email, no action 
	is needed, your password will not be reset. However, you may want to log into 
	your account and change your security password as someone may have guessed it.</p>';   
	$output.='<p>Thanks</p>';
 	$message = $output; 

  	send_mail($results,$message);
	$msg="We have sent a password link to your email! Please visit that link to change your password";
	//$msg= $key;
//echo "<script type='text/javascript'> document.location = 'route.php'; </script>";
} else{
  
  echo "<script>alert('Email not found');</script>";

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
						<h1 class="text-center text-bold mt-4x">Change Password</h1>
						<div class="well row pt-2x pb-3x bk-light">
							<div class="col-md-8 col-md-offset-2">
								<?php if($error){?><div class="errorWrap" style="color: red;"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
				                    else if($msg){?><div class="succWrap" style="color: green;"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>
								<form method="post">

									<label for="" class="text-uppercase text-sm">Your Email</label>
									<input type="text" placeholder="Email" name="username" class="form-control mb" required>
									<button class="btn btn-primary btn-block" name="login" type="submit">Reset Password</button>
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