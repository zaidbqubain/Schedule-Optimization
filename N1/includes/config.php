<?php 
// DB credentials.
// define('DB_HOST','mydb.ics.purdue.edu');
// define('DB_USER','g1117493');
// define('DB_PASS','Group12');
// define('DB_NAME','g1117493');

define('DB_HOST','mydb.ics.purdue.edu');
define('DB_USER','g1117493');
define('DB_PASS','Group12');
define('DB_NAME','g1117493');  



/*List users roles here for database */
$roles=array(
 'admin'=>'Administrator',
 'student'=>'Student',
 'staff' =>'Department'
);

//  define('SITE_PATH','https://web.ics.purdue.edu/~g1117493/N1');
 define('SITE_PATH','https://web.ics.purdue.edu/~g1117493/N1');  


// Establish database connection.
try
{
$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>
