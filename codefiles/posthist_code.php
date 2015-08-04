<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");
	
	$ch;
	$page;
	$posterid;
	$poster;
	$user;
	
	function initialize()
	{
		createConnection();
		global $ch;
		global $page;
		global $posterid;
		global $poster;
		global $user;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$page = intval($_GET["page"]);
		$posterid = intval($_GET["user"]);
		$poster = getUserById($posterid);
	}
	
	function canView()
	{
		global $ch;
		global $user;
		global $poster;
		
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
		else if(!$poster)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid user id.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function displayPostingHistory()
	{
		global $ch;
		global $user;
		global $poster;
		global $page;
		global $posterid;
		$postername = $poster->getUserName();
		
		$sql = "SELECT messages.messageid, messages.boardnum, messages.topicnum, messages.messagestuff, messages.messdate, topics.topicname, boards.boardname FROM messages, ". 
			"topics, boards WHERE messages.messageby = '$postername' AND topics.topicid = messages.topicnum AND boards.boardid = messages.boardnum ORDER BY messdate DESC LIMIT ". 
			($page * 20).", 20";
		$result = mysql_query($sql);
		$postrdr = mysql_fetch_row($result);
		
		echo "<center><pre class = \"big".$ch."\">Posting History for ".$postername."</pre></center>\n";
		menuBar0($user);
		modBar($user);
		
		while($postrdr)
		{
			$messid = $postrdr[0];
			$boardnum = $postrdr[1];
			$topicnum = $postrdr[2];
			$message = $postrdr[3];
			$posttime = formatTimeZone($postrdr[4], $user->getTimeZone());
			$topicname = $postrdr[5];
			$boardname = $postrdr[6];
			
			echo "<table class = \"messhead".$ch."\"><tr><td class = \"messhead".$ch."\"><b>Board Name:</b> ".
				"<a class = \"mencat".$ch."\" href = \"topics.php?board=".$boardnum."\">".$boardname."</a>".
				" | <b>Topic Name:</b> <a class = \"mencat".$ch."\" href = \"messages.php?board=".
				$boardnum."&topic=".$topicnum."\">".$topicname."</a> | Date Posted: ".$posttime." | ".
				"<a class = \"mencat".$ch."\" href = \"marker.php?message=".$messid."\">Mark For Moderation</a></td></tr></table>\n";
			echo "<table class = \"messlst".$ch."\"><tr><td class = \"messlst".$ch."\">".$message."</td></tr></table>\n";
			
			$postrdr = mysql_fetch_row($result);
		}
		
		$sql = "SELECT COUNT(*) FROM messages WHERE messageby = '$postername'";
		$result = mysql_query($sql);
		$postctrdr = mysql_fetch_row($result);
		$numposts = $postctrdr[0];
		if($numposts > 20)
		{
			$p = 0;
			echo "<table class = \"menu".$ch."\"><tr class = \"mencatbd".$ch."\"><td class = \"menu".$ch.
				"\"><b>Jump To Page: <a class = \"mencat".$ch."\" href = \"posthist.php?user=".$uid."&page=".$p."\">".
				($p + 1)."</a>";
			$numposts -= 20;
			$p++;
			while($numposts > 0)
			{
				echo " | <a class = \"mencat".$ch."\" href = \"posthist.php?user=".$posterid."&page=".$p."\">".($p + 1)."</a>";
				$numposts -= 20;
				$p++;
			}
			echo "</b></td></tr></table>";
		}
	}