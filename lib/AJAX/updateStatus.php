<?php
include_once ($_SESSION['DIR'] . "lib/mySQL/MySQL.php");
include ($_SESSION['DIR'] . "lib/configuration/config.php");
include ($_SESSION['DIR'] . "lib/javaPlugin/player/PlayerCache.php");

$dir = $_SESSION['HTTP_DIR'];
$mySQL = new MySQL();
$UUID = $_POST['optUUID'];
$con = $mySQL -> getConnection();
if ($con == null) {
	die("SQL ERROR!");
}
$PC = new PlayerCache($con);
$to = $PC -> getPlayerName($UUID);