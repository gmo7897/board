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
			echo "<pre class = \"small0\">Error: You must be <a class = \"bg0\" href = \"login.php\">logged in</a> to view this page.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < MOD_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".MOD_LEVEL." or higher to view this page.</pre>";
			return FALSE;
		}
		return TRUE;
	}
	
	function displayQueue()
	{
		global $ch;
		
		echo "<table class = \"tophead".$ch."\"><tr class = \"mencatbd".$ch."\"><td class = \"cat".$ch."\" colspan = \"5\">".
			"Multiply Marked Messages</td></tr><tr class = \"mencatbd".$ch."\"><td width = \"20%\" ".
			"class = \"tophead".$ch."\"><b>Markid</b></td><td width = \"20%\" class = \"tophead".$ch."\"><b>Original Marker".
			"</b></td><td width = \"20%\" class = \"tophead".$ch."\"><b>Message By</b></td><td width = \"20%\" class = \"tophead".$ch.
			"\"><b>Marks</b></td><td width = \"20%\" class = \"tophead".$ch."\"><b>Reason</b></td></tr>";
		
		$sql = "SELECT * FROM modqueue WHERE markcount > 1 AND mesid != -1 ORDER BY markcount DESC";
		$result = mysql_query($sql);
		$multimarkrdr = mysql_fetch_array($result);
		while($multimarkrdr)
		{
			$mmid = $multimarkrdr["queueid"];
			$messby = $multimarkrdr["messageby"];
			$markby = $multimarkrdr["markby"];
			$markcount = $multimarkrdr["markcount"];
			$reason = $multimarkrdr["reason"];
			echo "<tr class = \"mencatbd".$ch."\"><td width = \"20%\" class = \"toplst".$ch."\">".
				"<a class = \"board".$ch."\" href = \"moderate.php?mark=".$mmid."\">".$mmid.
				"</a></td><td width = \"20%\" class = \"toplst".$ch."\">".
				$markby."</td><td width = \"20%\" class = \"toplst".$ch."\">".
				$messby."</td><td width = \"20%\" class = \"toplst".$ch."\">".$markcount."</td><td ".
				"width = \"20%\" class = \"toplst".$ch."\">".$reason."</td></tr>";
			$multimarkrdr = mysql_fetch_array($result);
		}
		echo "</table>";
		
		echo "<table class = \"tophead".$ch."\"><tr class = \"mencatbd".$ch."\"><td class = \"cat".$ch."\" colspan = \"4\">".
			"Singly Marked Messages</td></tr><tr class = \"mencatbd".$ch."\"><td width = \"25%\" ".
			"class = \"tophead".$ch."\"><b>Markid</b></td><td width = \"25%\" class = \"tophead".$ch."\"><b>Marked By".
			"</b></td><td width = \"25%\" class = \"tophead".$ch."\"><b>Message By</b></td></td><td width = \"25%\" ".
			"class = \"tophead".$ch."\"><b>Reason</b></td></tr>";
		
		$sql = "SELECT * FROM modqueue WHERE markcount = 1 AND mesid != -1 ORDER BY queueid";
		$result = mysql_query($sql);
		$singlemarkrdr = mysql_fetch_array($result);
		while($singlemarkrdr)
		{
			$smid = $singlemarkrdr["queueid"];
			$messby = $singlemarkrdr["messageby"];
			$markby = $singlemarkrdr["markby"];
			$reason = $singlemarkrdr["reason"];
			echo "<tr class = \"mencatbd".$ch."\"><td width = \"25%\" class = \"toplst".$ch."\"><a class = ".
				"\"board".$ch."\" href = \"moderate.php?mark=".$smid."\">".$smid."</a></td><td width = \"25%\" ".
				"class = \"toplst".$ch."\" colspan = \"1.25\">".$markby."</td><td width = \"25%\" class = \"toplst".$ch."\">".
				$messby."</td><td width = \"25%\" class = \"toplst".$ch."\">".$reason."</td></tr>";
			$singlemarkrdr = mysql_fetch_array($result);
		}
		echo "</table>";
	}