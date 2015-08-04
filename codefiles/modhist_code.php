<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");
	
	$ch;
	$user;
	$viewedid;
	$viewed;
	
	function initialize()
	{
		createConnection();
		global $ch;
		global $user;
		global $viewedid;
		global $viewed;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$viewedid = intval($_GET["user"]);
		$viewed = getUserById($viewedid);
	}
	
	function canView()
	{
		global $ch;
		global $user;
		global $viewed;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a>.";
			return FALSE;
		}
		else if($user->getUserLevel() < MOD_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".MOD_LEVEL." or higher to view this page.</pre>\n";
			return FALSE;
		}
		else if(!$viewed)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid user id.</pre>";
			return FALSE;
		}
		return TRUE;
	}
	
	function viewModerations()
	{
		global $ch;
		global $user;
		global $viewed;
		global $viewedid;
		$pagenum = intval($_GET["page"]);
		$viewedname = $viewed->getUserName();
		
		echo "<center><pre class = \"big".$ch."\">Moderation History for ".$viewedname."</pre></center>";
		menuBar0($user);
		modBar($user);
		
		$sql = "SELECT * FROM moderations WHERE messby = '$viewedname' ORDER BY moddate DESC LIMIT ".($pagenum * 20).", 20";
		$result = mysql_query($sql);
		$modrdr = mysql_fetch_array($result);
		while($modrdr)
		{
			$modreason = $modrdr["reason"];
			$modaction = $modrdr["modaction"];
			$message = $modrdr["message"];
			$appealed = $modrdr["contested"];
			$modid = $modrdr["modid"];
			$moddate = formatTimeZone($modrdr["moddate"], $user->getTimeZone());
			
			echo "<table class = \"messhead".$ch."\"><tr><td class = \"messhead".$ch."\"><b>Mod Reason:</b> ".$modreason.
				" | <b>Mod Action:</b> ".$modaction." | <b>Date Moderated:</b> ".$moddate;
			if($appealed == 0 && $viewed->getUserLevel() != -2 && $viewed->getUserLevel() != -5 && $viewed->getUserLevel() != 1)
			{
				echo " | Not Appealed";
			}
			else if($appealed == 1)
			{
				echo " | Pending";
			}
			else if($appealed == 2)
			{
				echo " | Upheld";
			}
			else
			{
				echo " | No Appealable";
			}
			echo "</td></tr></table>\n";
			echo "<table class = \"messlst".$ch."\"><tr><td class = \"messlst".$ch."\">".$message."</td></tr></table>\n";
			$modrdr = mysql_fetch_array($result);
		}
		
		$sql = "SELECT COUNT(*) FROM moderations WHERE messby = '$viewedname'";
		$result = mysql_query($sql);
		$countrdr = mysql_fetch_row($result);
		$num_moderations = $countrdr[0];
		if($num_moderations > 20)
		{
			$p = 1;
			echo "<table class = \"menu".$ch."\"><tr class = \"mencatbd".$ch."\"><td class = \"menu".$ch.
				"\"><strong>Jump To Page: <a href = \"modhist.php?user=".$viewedid."&page=0\">1</a>";
			$num_moderations -= 20;
			while($num_moderations > 0)
			{
				echo " | <a href = \"modhist.php?user=".$viewedid."&page=".$p.">".($p + 1)."</a>";
				$p++;
				$num_moderations -= 20;
			}
		}
	}