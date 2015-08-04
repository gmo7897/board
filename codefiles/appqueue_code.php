<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");

	$ch;
	$user;

	function initialize()
	{
		global $ch;
		global $user;

		createConnection();

		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
	}

	function canViewPage()
	{
		global $user;
		global $ch;

		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.aspx\">logged in</a>.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < MOD_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".MOD_LEVEL." or higher to view this page.</pre>\n";
			return false;
		}
		return TRUE;
	}

	function displayAppeals()
	{
		global $ch;
		global $user;

		echo "<table class = \"tophead".$ch."\"><tr class = \"mencatbd".$ch."\"><td width = \"50%\" class = \"tophead".$ch.
			"\"><b>Appeal ID</b></td><td width = \"50%\" class = \"tophead".$ch."\"><b>Appeal By</b></td></tr>\n";

		$sql = "SELECT appealid, appealby FROM appeals WHERE appealto NOT LIKE '$user->getUserName()'";
		$result = mysql_query($sql);
		$apprdr = mysql_fetch_array($result);

		while($apprdr)
		{
			$appid = $apprdr["appealid"];
			$appby = $apprdr["appealby"];

			echo "<tr class = \"mencatbd".$ch."\"><td width = \"50%\" class = \"toplst".$ch.
				"\"><a class = \"board".$ch."\" href = \"appaction.php?appealid=".$appid."\">".$appid."</a></td>".
				"<td width = \"50%\" class = \"toplst".$ch."\">".$appby."</td></tr>\n";
			$apprdr = mysql_fetch_array($result);
		}
		echo "</table>";
	}