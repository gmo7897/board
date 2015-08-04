<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");
	
	$page;
	$user;
	$ch;
	
	function initialize()
	{
		createConnection();
		global $page;
		global $user;
		global $ch;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$page = intval($_GET["page"]);
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
	
	function userPosts()
	{
		global $user;
		global $ch;
		global $page;
		$username = $user->getUserName();
		$startpost = $page * 20;
		
		$sql = "SELECT messages.messagestuff, messages.boardnum, messages.topicnum, messages.messdate, boards.boardname, topics.topicname FROM messages, ".
			" boards, topics WHERE messages.messageby = '$username' AND boards.boardid = messages.boardnum AND topics.topicid = messages.topicnum ".
			"ORDER BY messdate DESC LIMIT ".$startpost.", 20";
		$result = mysql_query($sql);
		$messrdr = mysql_fetch_row($result);
		
		echo "<center><pre class = \"big".$ch."\">Posting History for ".$username."</pre></center>\n";
		menuBar0($user);
		modBar($user);
		while($messrdr)
		{
			$message = $messrdr[0];
			$boardnum = $messrdr[1];
			$topicnum = $messrdr[2];
			$posttime = formatTimeZone($messrdr[3], $user->getTimeZone());
			$boardname = $messrdr[4];
			$topicname = $messrdr[5];
			
			echo "<table class = \"messhead".$ch."\"><tr><td class = \"messhead".$ch."\"><b>Board Name:</b> ".
				"<a class = \"mencat".$ch."\" href = \"topics.php?board=".$boardnum."\">".$boardname."</a>".
				" | <b>Topic Name:</b> <a class = \"mencat".$ch."\" href = \"messages.php?board=".
				$boardnum."&topic=".$topicnum."\">".$topicname."</a> | Date Posted: ".$posttime."</td></tr></table>\n";
			echo "<table class = \"messlst".$ch."\"><tr><td class = \"messlst".$ch."\">".$message."</td></tr></table>\n";
			$messrdr = mysql_fetch_row($result);
		}
		
		$sql = "SELECT COUNT(*) FROM messages WHERE messageby = '$username'";
		$result = mysql_query($sql);
		$postctrdr = mysql_fetch_row($result);
		$totnumposts = $postctrdr[0];
		
		if($totnumposts > 20)
		{
			$pnum = 0;
			echo "<table class = \"menu".$ch."\"><tr class = \"mencatbd".$ch."\"><td class = \"menu".$ch.
				"\"><b>Jump To Page: <a class = \"mencat".$ch."\" href = \"viewposts.php?page=".$pnum."\">".
				($pnum + 1)."</a>";
			$pnum++;
			$totnumposts -= 20;
			while($totnumposts > 0)
			{
				echo " | <a class = \"mencat".$ch."\" href = \"viewposts.php?page=".$pnum."\">".($pnum + 1)."</a>";
				$pnum++;
				$totnumposts -= 20;
			}
			echo "</b></td></tr></table>";
		}
	}