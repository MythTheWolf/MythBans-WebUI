<?php
class Player {
	public $conn;
	public function __construct($connect) {
		$this -> conn = $connect;
	}

	function getAmountBanned($UUID) {

		$count = 0;
		$std = $this -> conn -> prepare("SELECT * FROM MythBans_History WHERE UUID = :UUID AND action= :ACT ");
		$std -> bindParam(':UUID', $UUID);
		$act = "userBan";
		$std -> bindParam(':ACT', $act);
		$std -> execute();
		foreach ($std->fetchAll() as $row) {

			$count++;
		}
		return $count;
	}

	function getAmountKicked($UUID) {
		$count = 0;
		$std = $this -> conn -> prepare("SELECT * FROM MythBans_History WHERE UUID = :UUID AND action= :ACT ");
		$std -> bindParam(':UUID', $UUID);
		$act = "userKick";
		$std -> bindParam(':ACT', $act);
		$std -> execute();
		foreach ($std->fetchAll() as $row) {

			$count++;
		}
		return $count;
	}

	function getAmountMuted($UUID) {
		$count = 0;
		$std = $this -> conn -> prepare("SELECT * FROM MythBans_History WHERE UUID = :UUID AND action= :ACT ");
		$std -> bindParam(':UUID', $UUID);
		$act = "userMute";
		$std -> bindParam(':ACT', $act);
		$std -> execute();
		foreach ($std->fetchAll() as $row) {

			$count++;
		}
		return $count;
	}

	function getAmountProbate($UUID) {
		$count = 0;
		$std = $this -> conn -> prepare("SELECT * FROM MythBans_History WHERE UUID = :UUID AND action= :ACT ");
		$std -> bindParam(':UUID', $UUID);
		$act = "userProbate";
		$std -> bindParam(':ACT', $act);
		$std -> execute();
		foreach ($std->fetchAll() as $row) {

			$count++;
		}
		return $count;
	}

	function getStatusHTML($UUID) {
		$std = $this -> conn -> prepare("SELECT * FROM MythBans_PlayerStats WHERE UUID = :UUID");
		$std -> bindParam(':UUID', $UUID);
		$std -> execute();
		foreach ($std->fetchAll() as $row) {
			switch($row['status']) {
				case "banned" :
					$status = "<span class=\"label label-danger\">Banned</span>";
					break;
				case "tempBanned" :
					$status = "<span class=\"label label-danger\">Temp Banned</span>";
					break;
				case "muted" :
					$status = "<span class=\"label label-primary\">Muted</span>";
					break;
				case "trial" :
					$status = "<span class=\"label label-warning\">On Probation</span>";
					break;
				case "OK" :
					$status = "<span class=\"label label-success\">Active</span>";
					break;
			}
		}
		return $status;
	}

}
