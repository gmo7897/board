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
		global $ch;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a>.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < MOD_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".MOD_LEVEL." or higher to view this page.</pre>";
			return FALSE;
		}
		return TRUE;
	}
	
	function displayPage()
	{
		global $ch;
		
		echo "<table class = \"tophead".$ch."\"><tr class = \"mencatbd".$ch."\"><td width = \"50%\" class = \"tophead".$ch.
			"\"><b>Username</b></td><td width = \"50%\" class = \"tophead".$ch."\"><b>IP Address</b></td></tr></table>\n";
		$sql = "SELECT userid, username, registeredip FROM users WHERE userlevel = ".INACTIVE;
		$result = mysql_query($sql);
		$inactrdr = mysql_fetch_array($result);
		
		while($inactrdr)
		{
			$inactid = $inactrdr["userid"];
			$inactname = $inactrdr["username"];
			$inactip = $inactrdr["registeredip"];
			echo "<table class = \"tophead".$ch."\"><tr class = \"mencatbd".$ch."\"><td width = \"50%\" class = \"toplst".$ch.
				"\"><a class = \"board".$ch."\" href = \"inactaction.php?user=".$inactid."\">".$inactname."</a></td>".
				"<td width = \"50%\" class = \"toplst".$ch."\">".$inactip."</td></tr></table>\n";
			$inactrdr = mysql_fetch_array($result);
		}
	}