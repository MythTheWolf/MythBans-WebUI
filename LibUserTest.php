<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once ($_SESSION['DIR'] . "lib/mySQL/MySQL.php");
include($_SESSION['DIR']."lib/user/LibUser.php");
$mySQL = new MySQL();
$con = $mySQL -> getConnection();
$PC = new PlayerCache($con);
$UU = new User();
if ($con == null) {
	die("SQL ERROR!");
}
echo $PC->getPlayerExact("SwampLion111");
if($UU->exist("SwampLion111") == false){
	die("po");
}
