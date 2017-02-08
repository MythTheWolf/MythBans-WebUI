<?php
error_reporting(0);
session_start();
include_once ($_SESSION['DIR'] . "lib/mySQL/MySQL.php");
include($_SESSION['DIR']."lib/user/LibUser.php");
$mySQL = new MySQL();
$con = $mySQL -> getConnection();
$PC = new PlayerCache($con);
$UU = new User();
if($UU->exist($_POST['username']) == FALSE)
{
	die("INCORRECT");
}
if(strcmp($UU->getPassword($_POST['username']) , sha1($_POST['password'])) == 0){
	$_SESSION['logged_in'] = true;
	$_SESSION['logged_uuid'] = $UU->getUUID($_POST['username']);
	die("OK");
	
}else{
	die("INCORRECT");
}
