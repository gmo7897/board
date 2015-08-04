<?php
	include("includes/ConnectionInfo.inc");
	include("includes/SiteConstants.inc");
	
	function initialize()
	{
		createConnection();
	}
	
	function displayLevels()
	{
		$sql = "SELECT levelname FROM levels ORDER BY levelnum";
		$result = mysql_query($sql);
		$lvlrdr = mysql_fetch_array($result);
		
		while($lvlrdr)
		{
			$lvlname = $lvlrdr["levelname"];
			echo $lvlname."<br />";
			$lvlrdr = mysql_fetch_array($result);
		}
	}
	
	function displayModerators()
	{
		$sql = "SELECT username FROM users WHERE userlevel >= ".MOD_LEVEL;
		$result = mysql_query($sql);
		$modrdr = mysql_fetch_array($result);
		
		if($modrdr)
		{
			while($modrdr)
			{
				$modname = $modrdr["username"];
				echo $modname.", ";
				$modrdr = mysql_fetch_array($result);
			}
		}
		else
		{
			echo "<i>There are currently no moderators.</i>";
		}
	}