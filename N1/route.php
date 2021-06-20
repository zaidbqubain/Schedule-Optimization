<?php
include('common_functions.php');
// $pagename= 'schedule';
pr($_GET);

if(!empty($_GET['page'])){
    $pagename= $_GET['page']; 
}
 echo $path = $_SESSION['dir_path'].'.php';

header('location:'.$path);
die;
?>