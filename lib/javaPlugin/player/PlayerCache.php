<?php
class PlayerCache{
	public $conn;
	public function __construct($connect)
    {
		$this->conn = $connect;
	}
	function getPlayerName($UUID)
	{
		$std = $this->conn->prepare("SELECT * FROM MythBans_NameCache WHERE UUID = :UUID");
		$std->bindParam(':UUID', $UUID);
		$std->execute();
		foreach($std->fetchAll() as $row)
		{
			return $row['Name'];
		}
	}
	
	function getPlayerExact($name)
	{
		$name2 = $name;
		$std = $this->conn->prepare("SELECT * FROM MythBans_NameCache WHERE Name = :Name ");
		$std->bindParam(':Name', $name2);
		$std->execute();
		foreach($std->fetchAll() as $row)
		{
			
			return $row['UUID'];
		}
		return -1;
	}
	
	
}
?>