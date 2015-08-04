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
			echo "<pre class = \"small0\">Error: You must be <a class = \"bg0\" href = \"login.aspx\">logged in</a>".
				" to view this page</pre>";
			return FALSE;
		}
		return TRUE;
	}
	
	function sysNoteList()
	{
		global $user;
		global $ch;
		$username = $user->getUserName();
		
		$sql = "SELECT messby, mess FROM systemmess WHERE messto = '$username'";
		$result = mysql_query($sql);
		$noterdr = mysql_fetch_array($result);
		
		while($noterdr)
		{
			$noteby = $noterdr["messby"];
			$note = $noterdr["mess"];
			
			echo "<table class = \"tophead".$ch."\"><tr><td class = \"tophead".$ch."\">Message By: ".$noteby."</td></tr>".
				"<tr><td class = \"toplst".$ch."\">".$note."</td></tr></table>\n";
			$noterdr = mysql_fetch_array($result);
		}
		
		$sql = "DELETE FROM systemmess WHERE messto = '$username'";
		mysql_query($sql);
	}