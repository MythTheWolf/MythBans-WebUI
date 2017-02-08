<?php
session_start();
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
$by = "POO";
$REASON = $_POST['optReason'];
try {
	switch($_POST['optAction']) {
		case "kick" :
			$sth = $con -> prepare("INSERT INTO MythBans_CronJobs (`UUID`,`action`,`value1`) VALUES (:UUID,:ACT,:REASON)");
			$sth -> bindParam(':UUID', $UUID, PDO::PARAM_STR, 12);
			$act = "KICK_USER";
			$sth -> bindParam(':ACT', $act, PDO::PARAM_STR, 255);
			$sth -> bindParam(':REASON', $REASON, PDO::PARAM_STR, 255);
			$sth -> execute();
			$message = $by . " created a kick cron job for " . $to . ". (Reason: " . $REASON . " )";
			$sth = $con -> prepare("INSERT INTO MythBans_Log (`action`,`message`) VALUES (:ACT,:MESS)");
			$act = "CREATE_CRON";
			$sth -> bindParam(':ACT', $act, PDO::PARAM_STR, 255);
			$sth -> bindParam(':MESS', $message, PDO::PARAM_STR, 255);
			$sth -> execute();
			break;
		case "clrlogs" :
			$sth = $con -> prepare("DELETE FROM MythBans_History WHERE UUID = :UUID");
			$sth -> bindParam(':UUID', $UUID, PDO::PARAM_STR, 12);
			$sth -> execute();
			$message = $by . " cleared  all the logs of " . $to;
			$act = "LOG_ALTER";
			$sth = $con -> prepare("INSERT INTO MythBans_Log (`action`,`message`) VALUES (:TESST,:MESS)");
			$sth -> bindParam(':TESST', $act, PDO::PARAM_STR, 255);
			$sth -> bindParam(':MESS', $message, PDO::PARAM_STR, 255);
			$sth -> execute();
			break;
		case "dwnld" :
			$sth = $con -> prepare("SELECT * FROM MythBans_History WHERE UUID = :UUID");
			$sth -> bindParam(':UUID', $UUID, PDO::PARAM_STR, 12);
			$sth -> execute();
			$file = fopen("log_tmp/" . $UUID . ".txt", "w") or die("Unable to open file!");
			$txt = "TIMESTAMP | ACTION | STAFF MEMBER | REASON | STAFF MEMBER (UUID)  | EXPIRES \n";
			fwrite($file, $txt);
			foreach ($sth->fetchAll() as $r) {
			if($r['byUUID'] == CONSOLE){
				$txt = $r['timestamp']."//".$r['action']."//"."CONSOLE"."//".$r['reason']."//". "CONSOLE"."//".$r['expires'];
			}
			fwrite($file, $txt);
			}
			
			fclose($file);
			die("DWN");
			break;
	}

} catch(Exception $e) {
	die(print_r($e));
}
