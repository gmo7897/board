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
		else if($user->getUserLevel() < ELITE_MOD_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".ELITE_MOD_LEVEL." or higher to view this page.</pre>";
			return FALSE;
		}
		return TRUE;
	}
	
	function showQueue()
	{
		global $ch;
		
		$sql = "SELECT userid, username FROM users WHERE userlevel = ".SUSPENDED;
		$result = mysql_query($sql);
		$sususrrdr = mysql_fetch_array($result);
		echo "<table class = \"tophead".$ch."\"><tr class = \"mencatbd".$ch."\"><td class = \"tophead".$ch."\"><b>Username</b></td>".
			"<td class = \"tophead".$ch."\"><b>Suspension Reason</b></td></tr>\n";
		
		while($sususrrdr)
		{
			$suspendedid = $sususrrdr["userid"];
			$suspendedname = $sususrrdr["username"];
			$suspensionreason = getSuspensionReason($suspendedname);
			
			echo "<tr class = \"mencatbd".$ch."\"><td class = \"toplst".$ch."\"><a class = \"board".$ch.
				"\" href = \"susaction.php?user=".$suspendedid."\">".$suspendedname."</a></td><td class = \"toplst".$ch."\">".
				$suspensionreason."</td></tr>\n";
			
			$sususrrdr = mysql_fetch_array($result);
		}
	}
	
	function getSuspensionReason($username)
	{
		$reason = "Admin Discretion";
		$sql = "SELECT reason FROM moderations WHERE messby = '$username' AND modaction LIKE '%Suspend User'";
		$result = mysql_query($sql);
		$susrdr = mysql_fetch_array($result);
		if($susrdr)
		{
			$reason = $susrdr["reason"];
		}
		
		return $reason;
	}