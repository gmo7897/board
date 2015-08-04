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
	
	function displayPage()
	{
		global $ch;
		
		$sql = "SELECT username, appoints FROM users ORDER BY appoints DESC LIMIT 0, 20";
		$result = mysql_query($sql);
		$userrdr = mysql_fetch_array($result);
		
		echo "<table class = \"if".$ch."\"><tr><td class = \"i1f".$ch."\">Username</td>".
			"<td class = \"i1f".$ch."\">Applet Points</td></tr>";
		while($userrdr)
		{
			$name = $userrdr["username"];
			$points = $userrdr["appoints"];
			echo "<td class = \"i2f".$ch."\">".$name."</td><td class = \"i2f".$ch."\">".
				$points."</td></tr>";
			$userrdr = mysql_fetch_array($result);
		}
	}