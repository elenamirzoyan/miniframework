<?php 
$page = $_GET['p'];
if (empty($page) || !file_exists('skin/'.$page.'.php'))
	$page=$mainpage; 

	
include('system/classes/mysql.class.php');
$db = new Mysql($mysql_hostname, $mysql_username, $mysql_password, $mysql_database, 0);
$db->connect();
include('system/functions.php');
include('skin/header.php');
include('skin/page.php');
include('skin/footer.php');
$db->close();
?>