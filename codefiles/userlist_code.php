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
		global $ch;
		global $user;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.aspx\">logged in</a>.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < MOD_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".MOD_LEVEL." or higher to view this page</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function listUsers()
	{
		global $ch;
		
		$sql = "SELECT userid, username FROM users ORDER BY userid";
		$result = mysql_query($sql);
		$userrdr = mysql_fetch_array($result);
		
		echo "<center><pre class = \"small".$ch."\"><b>Username</b></pre>\n";
		while($userrdr)
		{
			$userid = $userrdr["userid"];
			$username = $userrdr["username"];
			echo "<a class = \"bg".$ch."\" href = \"whois.php?user=".$userid."\">".$username."</a><br />\n";
			$userrdr = mysql_fetch_array($result);
		}
		echo "</center>";
	}