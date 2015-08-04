<?php
	function createConnection()
	{
		$host = "localhost";
		$database = "boards";
		$username = "root";
		$password = "";

		mysql_connect($host, $username, $password);
		mysql_select_db($database);
	}
