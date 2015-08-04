<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	
	$ch;
	$uid;
	$viewed;
	$user;
	
	function initialize()
	{
		createConnection();
		global $ch;
		global $uid;
		global $viewed;
		global $user;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$uid = intval($_GET["user"]);
		$viewed = getUserById($uid);
	}
	
	function canView()
	{
		global $user;
		global $viewed;
		global $ch;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a>.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < MOD_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".MOD_LEVEL." or higher to view this page.</pre>\n";
			return FALSE;
		}
		else if(!$viewed)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid user id.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function userMap()
	{
		global $ch;
		global $viewed;
		global $user;
		$mapowner = $viewed->getUserName();
		
		echo "<center><pre class = \"big".$ch."\">Usermap for ".$mapowner."</pre></center><br /><br />\n";
		menuBar0($user);
		modBar($user);
		
		$sql = "SELECT namelist FROM usermap WHERE mapowner = '$mapowner'";
		$result = mysql_query($sql);
		$userdr = mysql_fetch_array($result);
		$namelist = $userdr["namelist"];
		
		$namelist = explode(", ", $namelist);
		$num_names = count($namelist);
		for($i = 0; $i < $num_names; $i++)
		{
			$currname = $namelist[$i];
			$sql = "SELECT userid FROM users WHERE username = '$currname'";
			$result = mysql_query($sql);
			$idrdr = mysql_fetch_array($result);
			$mapuserid = $idrdr["userid"];
			
			echo "<a class = \"bg".$ch."\" href = whois.php?user=".$mapuserid.">".$currname."</a><br />\n";
		}
	}