<?php
session_start();
include ($_SESSION['DIR'] . "lib/configuration/config.php");
include ($_SESSION['DIR'] . "lib/mySQL/MySQL.php");
include ($_SESSION['DIR'] . "lib/javaPlugin/player/PlayerCache.php");
error_reporting(0);
ini_set('display_errors', 0);
$dir = $_SESSION['HTTP_DIR'];
$mySQL = new MySQL();
$con = $mySQL -> getConnection();
$PC = new PlayerCache($con);
if ($con == null) {
	die("SQL ERROR!");
}
$UUID = $_GET['UUID'];
$base = "SELECT * FROM MythBans_History WHERE UUID = :uuid";
$sth = $con -> prepare($base);
$sth->bindParam(":uuid",$UUID);
$sth -> execute();


echo("
<table class=\"table table-striped\">
<thead>
			<tr>
			<th>TimeStamp</th>
			<th>Status</th>
			<th>Staff Member</th>
			<th>Reason</th>
			<th>Expires</th>
			</tr>
			</thead>
			<tbody>

");
foreach ($sth->fetchAll() as $row) {
	$time = $row['timestamp'];
	switch($row['action']) {
		case "userBan" :
			$status = "<span class=\"label label-danger\">Banned</span>";
			break;
		case "userTempBan" :
			$status = "<span class=\"label label-danger\">Temp Banned</span>";
			break;
		case "userMute" :
			$status = "<span class=\"label label-primary\">Muted</span>";
			break;
		case "userProbate" :
			$status = "<span class=\"label label-warning\">On Probation</span>";
			break;
		case "userKick" :
			$status = "<span class=\"label label-warning\">Kicked</span>";
			break;
	}
	if ($row['byUUID'] == null) {
		$by = "N/A";
	} else {
		if ($row['byUUID'] == "CONSOLE") {
			$loc = $dir . "?STAFF_UUID=CONSOLE";
			$name = "CONSOLE";
			$by = "<img src=\"" . $dir . "lib/javaPlugin/player/skin.php?u=Hack&s=35&v=f\"> <a href=\"$loc\">CONSOLE</a>";
		} else {
			$tmp = $PC -> getPlayerName($row['byUUID']);
			$loc = $dir . "?STAFF_UUID=" . $row['byUUID'];
			$by = "<img src=\"" . $dir . "lib/javaPlugin/player/skin.php?u=$tmp&s=35&v=f\"> <a href=\"$loc\">$tmp</a>";
		}
	}

	if ($row['reason'] == null) {
		$reason = "N/A";
	} else {
		$reason = $row['reason'];
	}
	if ($row['expires'] == null) {
		$expire = "N/A";
	} else {
		
		$expire = $row['expires'];
	}
	
	/*
	 * 
	 * <tr>
			<th>TimeStamp</th>
			<th>Status</th>
			<th>Staff Member</th>
			<th>Reason</th>
			<th>Expires</th>
			</tr>
	 */
	
	 echo("
	 		<tr>
			<td>$time</td>
			<td>$status</td>
			<td>$by</td>
			<td>$reason</td>
			<td>$expire</td>
			</tr>
	 ");
	 
	 
}

echo "</tbody></table>";
