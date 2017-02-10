<?php 


foreach ($_POST as $key => $value) {
    $_POST[$key] = htmlspecialchars($value, ENT_QUOTES,"UTF-8");
}
			session_start();
			include($_SESSION['DIR']."lib/configuration/config.php");
			include($_SESSION['DIR']."lib/user/User.php");
			$dir = $_SESSION['HTTP_DIR'];
			$mySQL = new MySQL();
			$con = $mySQL->getConnection();
			$PC = new PlayerCache($con);
			if($con == null)
			{
				die("SQL ERROR!");
			}
		$base = "SELECT * FROM MythBans_PlayerStats";
		$std = $con->prepare($base." WHERE status = 'banned'");
		$std->execute();
		$banned = 0;
		foreach($std->fetchAll() as $r)
		{
			$banned++;
		}
		$std = $con->prepare($base);
		$std->execute();
		$total = 0;
		foreach($std->fetchAll() as $r)
		{
			$total++;
		}
		$count = 0;
		if(!empty($_POST['filterName']))
		{
			$UUID = $PC->getPlayerExact($_POST['filterName']);
			if($count > 0)
			{
				$suffix = $suffix." AND last_name LIKE ".$_POST['filterName']."%";
				$count++;
			}else{
				$suffix = " WHERE last_name LIKE '".$_POST['filterName']."%'";
				$count++;
			}
		}
		if($_POST['actionType'] !== "All"){
			switch($_POST['actionType'])
			{
				case "Ban":
					$act = "banned";
					break;
				case "TempBan":
					$act = "tempBanned";
					break;
				case "Mute":
					$act = "mute";
					break;
				case "Probation":
					$act = "trial";
					break;
				case "OK":
					break;
					$act = "OK";
			}
			if($count > 0)
			{
				$suffix = $suffix." AND status = \"".$act."\"";
				$count++;
			}else{
				$suffix = " WHERE status = \"".$act."\"";
				$count++;
			}
		}
		if(!empty($_POST['filterBy']))
		{
			if($_POST['filterBy'] == "CONSOLE" || $_POST['filterBy'] == "console")
			{
				$UUID = "CONSOLE";
			}else{
				$UUID = $PC->getPlayerExact($_POST['filterBy']);
			}
			if($count > 0)
			{
				$suffix = $suffix." AND byUUID = \"".$UUID."\"";
				$count++;
			}else{
				$suffix = " WHERE byUUID = \"".$UUID."\"";
				$count++;
			}
		}
	try{
		$pdo = $con;
		$std = $pdo->prepare($base.$suffix." ORDER BY ID DESC LIMIT 0, 10 ");
		$std->execute();
			
			echo "
			
			<table class='table table-striped' id='ajaxStatsTable'>
			<thead>
			<tr>
			<th>UUID/Player</th>
			<th>Status</th>
			<th>Banned By</th>
			<th>Reason</th>
			<th>Expires</th>
			<th>Actions</th>
			</tr>
			</thead>
			<tbody>";
			$name = "";
			$count_row = 0;
			$results = $std->fetchAll();
			echo "<center><h4>".$total." total users. $banned banned.</h4></center>";
			$count_row = 0;
		foreach($results as $row)
		{
			$name = $PC->getPlayerName($row['UUID']);
			$real_UUID = $row['UUID'];
			$tmp = $dir."?UUID=".$real_UUID;
		$UUID = "<img src=\"".$dir."lib/javaPlugin/player/skin.php?u=$name&s=35&v=f\"> <a href=\"$tmp\">$name</a>";
		switch($row['status']){
			case "banned":
				$status = "<span class=\"label label-danger\">Banned</span>";
				break;
			case "tempBanned":
				$status = "<span class=\"label label-danger\">Temp Banned</span>";
				break;
			case "muted":
				$status = "<span class=\"label label-primary\">Muted</span>";
				break;
			case "trial":
				$status = "<span class=\"label label-warning\">On Probation</span>";
				break;
			case "OK": 
				$status = "<span class=\"label label-success\">Active</span>";
				break;
		}
		if($row['byUUID'] == null)
		{
			$by = "N/A";
		}else{
			if($row['byUUID'] == "CONSOLE")
			{
				$loc = $dir."?STAFF_UUID=CONSOLE";
				$by = "<img src=\"".$dir."lib/javaPlugin/player/skin.php?u=Hack&s=35&v=f\"> <a href=\"$loc\">CONSOLE</a>";
			}else{
				$loc = $dir."?STAFF_UUID=".$row['byUUID'];
				$tmp = $PC->getPlayerName($row['byUUID']);
				$by = "<img src=\"".$dir."lib/javaPlugin/player/skin.php?u=$tmp&s=35&v=f\"> <a href=\"$loc\">$tmp</a>";
			}
		}
		if($row['reason'] == null)
		{
			$reason = "N/A";
		}else{
			$reason = $row['reason'];
		}
		if($row['expires'] == null)
		{
			$exp = "N/A";
		}else{
			$exp = $row['expires'];
		}
		if($user['isLoggedIn'] == true){
			echo "
		<tr>
		<td>$UUID</td>
		<td>$status</td>
		<td>$by</td>
		<td>$reason</td>
		<td>$exp</td>
		<td>
		<button type = 'button' class = 'btn btn-primary testClass' id='$real_UUID'>Set Status</button>
		<button type = 'button' class = 'btn btn-warning moreOptionsButton' id='$real_UUID'>More options</button>
		</td>
		</tr>
		";
		}else{
		echo "
		<tr>
		<td>$UUID</td>
		<td>$status</td>
		<td>$by</td>
		<td>$reason</td>
		<td>$exp</td>
		<td>
		None
		</td>
		</tr>
		";
		}
			$count_row++;
		}
			
			echo "
			
			</tbody>
			
			<input id=\"count\" type=\"hidden\" value =\"$count_row\">
			";
		}catch(PDOException $e)
		{
			die($e->getMessage());
		}