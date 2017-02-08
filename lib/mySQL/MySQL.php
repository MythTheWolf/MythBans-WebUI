<?php
class MySQL {

	function getConnection() {
		session_start();
		try {
			include $_SESSION['DIR']."lib/configuration/config.php";
			$servername = $config['SQL-HOST'];
			$username = $config['SQL-USERNAME'];
			$password = $config['SQL-PASSWORD'];
			$dbname = $config['SQL-DATABASE'];
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			// set the PDO error mode to exception
			$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;
		} catch(Exception $e) {
			return null;
		}
	}

}
