<?php

session_start();
include_once ($_SESSION['DIR'] . "lib/mySQL/MySQL.php");
include_once ($_SESSION['DIR'] . "lib/user/LibUser.php");
$UU = new User();
$mySQL = new MySQL();
if ($_SESSION['logged_in'] == true) {
	$user['isLoggedIn'] = true;
} else {
	$user['isLoggedIn'] = false;
}
if ($_SESSION['logged_in'] == true) {

	$con = $mySQL -> getConnection();
	if ($con == null) {
		die("SQL ERROR!");
	}
	$sth = $con -> prepare("SELECT * FROM MythBans_Groups WHERE group_name = :name");
	$name = $UU -> getGroup($_SESSION['logged_uuid']);
	$sth -> bindParam(':name', $name, PDO::PARAM_STR, 255);
	$sth -> execute();
	foreach ($sth->fetchAll() as $row) {
		$perm['remove_logs'] = $row['remove_logs'];
		$perm['manage_groups'] = $row['manage_groups'];
		$perm['download_logs'] = $row['download_logs'];
		$perm['kick'] = $row['kick'];
		$perm['ban'] = $row['ban'];
		$perm['probate'] = $row['probate'];
		$perm['mute'] = $row['mute'];
		$perm['pardon'] = $row['pardon'];
		$perm['delete_user'] = $row['delete_user'];
		$perm['halt_service'] = $row['halt_service'];
		$perm['advanced'] = $row['advanced'];
		echo "found UUId--";

	}
}
