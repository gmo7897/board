<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	
	$ch;
	$user;

	function initialize()
	{
		createConnection();
		global $ch;
		global $user;

		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
	}

	function canView()
	{
		global $user;

		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a>.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}

	function displayBannedUsers()
	{
		global $ch;

		$sql = "SELECT username FROM users WHERE userlevel = -2";
		$result = mysql_query($sql);
		$bannedrdr = mysql_fetch_array($result);

		echo "<center><b><pre class = \"small".$ch."\">Username</pre></b></center>\n";

		while($bannedrdr)
		{
			$bannedname = $bannedrdr["username"];
			echo "<center><pre class = \"small".$ch."\">".$bannedname."</pre></center>\n";
			$bannedrdr = mysql_fetch_array($result);
		}
	}