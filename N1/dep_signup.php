<?php
include('common_functions.php');
global $roles;
/* Unset the admin role so no one can register with admin role*/
unset($roles['admin']);

$departments = getDepartments();
$designations = getDesignations();

if(isset($_POST['submit']))
{
    $email=$_POST['email'];
/* check whether using a unique email or not */
        $sql = "SELECT * from users where email = (:email);";
        $qry = $dbh -> prepare($sql);
        $qry-> bindParam(':email', $email, PDO::PARAM_STR);
        $qry->execute();
        $results=$qry->fetch(PDO::FETCH_OBJ);
        if($results>0)
        {
              echo "<script>alert('Email already exits!');</script>";

        }

        /* check whether using a unique email or not */
else{

$file = $_FILES['image']['name'];
$file_loc = $_FILES['image']['tmp_name'];
$folder="images/";
$new_file_name = strtolower($file);
$final_file=str_replace(' ','-',$new_file_name);

$name=$_POST['name'];

$password=md5($_POST['password']);
$department=$_POST['Department'];
$college=$_POST['College'];



if(move_uploaded_file($file_loc,$folder.$final_file))
	{
		$image=$final_file;
    }
// $notitype='Create Account';
// $reciver='Admin';
// $sender=$email;

// $sqlnoti="insert into notification (notiuser,notireciver,notitype) values (:notiuser,:notireciver,:notitype)";
// $querynoti = $dbh->prepare($sqlnoti);
// $querynoti-> bindParam(':notiuser', $sender, PDO::PARAM_STR);
// $querynoti-> bindParam(':notireciver',$reciver, PDO::PARAM_STR);
// $querynoti-> bindParam(':notitype', $notitype, PDO::PARAM_STR);
// $querynoti->execute();

$sql ="INSERT INTO users(name,email, password, image)
VALUES(:name, :email, :password, :image)";
$query= $dbh -> prepare($sql);
$query-> bindParam(':name', $name, PDO::PARAM_STR);
$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
$query-> bindParam(':image', $image, PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();



if($lastInsertId)
{
    // if($role == "student"){
$sql ="INSERT INTO departments(id, departname, collegename)VALUES(:id, :departname, :collegename)";
$query= $dbh -> prepare($sql);
$query-> bindParam(':id', $lastInsertId, PDO::PARAM_INT);
$query-> bindParam(':departname', $department, PDO::PARAM_STR);
$query-> bindParam(':collegename', $college, PDO::PARAM_STR);
$query->execute();
// }
echo "<script type='text/javascript'>alert('Registration Sucessfull!');</script>";
echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
}
else
{
$error="Something went wrong. Please try again";
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
    <script type="text/javascript">

	function validate()
        {
            var extensions = new Array("jpg","jpeg");
            var image_file = document.regform.image.value;
            var image_length = document.regform.image.value.length;
            var pos = image_file.lastIndexOf('.') + 1;
            var ext = image_file.substring(pos, image_length);
            var final_ext = ext.toLowerCase();
            for (i = 0; i < extensions.length; i++)
            {
                if(extensions[i] == final_ext)
                {
                return true;

                }
            }
            alert("Image Extension Not Valid (Use Jpg,jpeg)");
            return false;
        }

</script>
<style>
.col-centered{
    float: none;
    margin: 0 auto;
}

</style>
</head>

<body>
	<div class="login-page bk-img">
		<div class="form-content">
			<div class="container">
				<div class="row ">
					<div class="col-md-12 col-centered">
						<h1 class="text-center text-bold mt-2x">Register</h1>
                        <div class="hr-dashed"></div>
						<div class="well row pt-2x pb-3x bk-light text-center">
                         <form method="post" class="form-horizontal" enctype="multipart/form-data" name="regform" onSubmit="return validate();">

<!--                          <div class="form-group">
                            <label class="col-sm-2 control-label">Role<span style="color:red">*</span></label>
                            <div class="col-sm-6">
							<?php foreach($roles as $rolekey => $role) {?>
							<input class="" type="radio" name="role" required="required" value="<?php echo $rolekey ?>" > <?php echo $role; ?>
							<?php }?>

                           </div>
                           </div> -->

                            <div class="form-group">
                            <label class="col-sm-2 control-label">Department HR User<span style="color:red">*</span></label>
                            <div class="col-sm-6">
                            <input type="text" name="name" class="form-control" required>
                            </div>
                            </div>


                            <div class="form-group">
                            <label class="col-sm-2 control-label">Department Name<span style="color:red">*</span></label>
                            <div class="col-sm-6">
                            <input type="text" name="Department" class="form-control" required>
                            </div>
                            </div>

                            <div class="form-group">
                            <label class="col-sm-2 control-label">College Name<span style="color:red">*</span></label>
                            <div class="col-sm-6">
                            <input type="text" name="College" class="form-control" required>
                            </div>
                            </div>


                            <div class="form-group">
                            <label class="col-sm-2 control-label">Department Email<span style="color:red">*</span></label>
                                <div class="col-sm-6">
                                    <input type="text" name="email" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                            <label class="col-sm-2 control-label">Password<span style="color:red">*</span></label>
                            <div class="col-sm-6">
                            <input type="password" name="password" class="form-control" id="password" required >
                            </div>
                            </div>



                            <div class="form-group">
                            <label class="col-sm-2 control-label">Picture<span style="color:red">*</span></label>
                            <div class="col-sm-6">
                            <div style="margin-left: -5px;margin-right: 30px;"><input type="file" name="image" class="form-control"></div>
                            </div>
                            </div>

								<br>
                                <button class="btn btn-primary" name="submit" type="submit">Register</button>
                                </form>
                                <br>
                                <br>
								<p>Already have an account? <a href="index.php" >Sign in</a></p>
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
