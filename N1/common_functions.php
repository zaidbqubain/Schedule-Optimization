<?php
session_start();
error_reporting(0);
/*
This files has common functions for across the website 

 Print Array or Object in nice format */

function pr($obj){
	echo '<pre>';
	print_r($obj);
	echo '</pre>';
}
include(__DIR__.'/includes/config.php');
include('modals/departments.php');

function manage_route($obj, $type){
	$_SESSION['alogin']=$obj->email;
	$_SESSION['email']=$obj->email;
	$_SESSION['role']=$type;
   
	if($type=='student'){
		$_SESSION['dir_path']='student/schedule';
		$_SESSION['id'] = $obj->id;
	}
	if($type=='staff'){
		$_SESSION['dir_path']='department/profile';	
		$_SESSION['id'] = $obj->id;
	}
}
 function send_mail($obj,$message){
$to = $obj->email;
$subject = "HTML email";


// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <zaidqubain2@gmail.com>' . "\r\n";

mail($to,$subject,$message,$headers);
	}

function checkAuth(){

	if(empty($_SESSION['email'])){
		header('location:index.php');	
		die;
	}else{
		return true;
	}
}


?>