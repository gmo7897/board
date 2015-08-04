<?php
	function createConnection()
	{
		$host = "localhost";
		$database = "gmocom_apple_boards";
		$username = "gmocom";
		$password = "354oih2XWk";

		mysql_connect($host, $username, $password);
		mysql_select_db($database);
	}