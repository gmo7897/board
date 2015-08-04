<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");
	
	$ch;
	$user;
	$page;
	
	function initialize()
	{
		createConnection();
		global $ch;
		global $user;
		global $page;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$page = intval($_GET["page"]);
	}
	
	function canView()
	{
		global $user;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.aspx\">logged in</a>.";
			return FALSE;
		}
		return TRUE;
	}
	
	function modHistory()
	{
		global $user;
		global $ch;
		global $page;
		$username = $user->getUserName();
		$modrownum = $page * 20;
		
		echo "<center><pre class = \"big".$ch."\">Moderation History for ".$username."</pre></center>";
		menuBar0($user);
		modBar($user);
		
		$sql = "SELECT * FROM moderations WHERE messby = '$username' ORDER BY moddate DESC LIMIT ".$modrownum.", 20";
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
			if($appealed == 0 && $user->getUserLevel() != -2 && $user->getUserLevel() != -5 && $user->getUserLevel() != 1)
			{
				echo " | <a class = \"mencat".$ch."\" href = \"appeal.php?modid=".$modid."\">Appeal</a>";
			}
			else if($appealed == 1)
			{
				echo " | Pending";
			}
			else if($appealed == 2)
			{
				echo " | Upheld";
			}
			else if(($appealed == 0 && ($user->getUserLevel == -2 || $user->getUserLevel == -5 || $user->getUserLevel() == 1)) || $appealed == 3)
			{
				echo " | No Action Available";
			}
			echo "</td></tr></table>\n";
			echo "<table class = \"messlst".$ch."\"><tr><td class = \"messlst".$ch."\">".$message."</td></tr></table>\n";
			$modrdr = mysql_fetch_array($result);
		}
		
		$sql = "SELECT COUNT(*) FROM moderations WHERE messby = '$username'";
		$result = mysql_query($sql);
		$countrdr = mysql_fetch_row($result);
		$totalnummods = $countrdr[0];
		if($totalnummods > 20)
		{
			$pnum = 1;
			echo "<table class = \"menu".$ch."\"><tr class = \"mencatbd".$ch."\"><td class = \"menu".$ch.
				"\"><strong>Jump To Page: ";
			while($totalnummods > 0)
			{
				echo "<a href = \"viewmods.php?page=".($pnum - 1)."\">".$pnum."</a>";
				if($totalnummods > 20)
				{
					echo " | ";
				}
				$pnum++;
				$totalnummods -= 20;
			}
		}
		echo "</strong></td></tr></table>\n";
	}