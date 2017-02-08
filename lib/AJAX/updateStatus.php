<?php
error_reporting(0);
session_start();
include_once ($_SESSION['DIR'] . "lib/mySQL/MySQL.php");
include ($_SESSION['DIR'] . "lib/configuration/config.php");
include ($_SESSION['DIR'] . "lib/javaPlugin/player/PlayerCache.php");
include ($_SESSION['DIR'] . "lib/javaPlugin/user/User.php");

$dir = $_SESSION['HTTP_DIR'];
$mySQL = new MySQL();
$UUID = $_POST['optUUID'];
$con = $mySQL -> getConnection();
if ($con == null) {
	die("SQL ERROR!");
}
$PC = new PlayerCache($con);
$to = $PC -> getPlayerName($UUID);
switch($_POST['selectbasic'])
{
	case "Ban":
		if($perm['ban'] != 1 || $perm['ban'] !== true)
		{
			die("ERR_PERM");
		}
		break;
	case "TempBan":
		break;
	case "Probate":
		break;
	case "Mute":
		break;
	case "OK":
		break;
}
