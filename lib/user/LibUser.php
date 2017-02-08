<?php
session_start();
include_once ($_SESSION['DIR'] . "lib/mySQL/MySQL.php");
include ($_SESSION['DIR'] . "lib/javaPlugin/player/PlayerCache.php");

class User {

	function exist($username) {
		$mySQL = new MySQL();
		$con = $mySQL -> getConnection();
		if ($con == null) {
			die("SQL ERROR!");
		}
		$PC = new PlayerCache($con);
		if ($PC -> getPlayerExact($username) == -1) {
			return false;
		}
		$sth = $con -> prepare("SELECT * FROM MythBans_SiteUsers WHERE UUID = :uuid");
		$name = $PC -> getPlayerExact($username);
		$sth -> bindParam(':uuid', $name, PDO::PARAM_STR, 255);
		$sth -> execute();
		foreach ($sth->fetchAll() as $r) {
			return true;
		}
		return false;
	}
	
	function getPassword($username)
	{
		$mySQL = new MySQL();
		$con = $mySQL -> getConnection();
		if ($con == null) {
			die("SQL ERROR!");
		}
		$PC = new PlayerCache($con);
		if ($PC -> getPlayerExact($username) == -1) {
			return -1;
		}
		$sth = $con -> prepare("SELECT * FROM MythBans_SiteUsers WHERE UUID = :uuid");
		$name = $PC -> getPlayerExact($username);
		$sth -> bindParam(':uuid', $name, PDO::PARAM_STR, 255);
		$sth -> execute();
		foreach ($sth->fetchAll() as $r) {
			return $r['password'];
		}
		return -1;
	}
	function getUUID($username)
	{
		$mySQL = new MySQL();
		$con = $mySQL -> getConnection();
		if ($con == null) {
			die("SQL ERROR!");
		}
		$PC = new PlayerCache($con);
		if ($PC -> getPlayerExact($username) == -1) {
			return -1;
		}
		$UUID = $PC -> getPlayerExact($username);
		return $UUID;
	}
	
	
	function getGroup($uuid)
	{
	    $mySQL = new MySQL();
		$con = $mySQL -> getConnection();
		if ($con == null) {
			die("SQL ERROR!");
		}
		$PC = new PlayerCache($con);
		if ($PC -> getPlayerExact($username) == -1) {
			return -1;
		}
		$sth = $con -> prepare("SELECT * FROM MythBans_SiteUsers WHERE UUID = :uuid");
		$name = $PC -> getPlayerExact($username);
		$sth -> bindParam(':uuid', $name, PDO::PARAM_STR, 255);
		$sth -> execute();
		foreach ($sth->fetchAll() as $r) {
			return $r['group'];
		}
		return -1;
	}
}
