<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");
	
	$ch;
	$user;
	$level = 0;
	$timezone = 0;
	$board = 0;
	$topic = 0;
	$page = 0;
	$tactive = 0;
	$blevel = 0;
	$tname;
	$tby;
	$valid_topic = TRUE;
	
	function initialize()
	{
		createConnection();
		global $ch;
		global $user;
		global $level;
		global $timezone;
		global $board;
		global $topic;
		global $page;
		global $tactive;
		global $blevel;
		global $tname;
		global $tby;
		global $valid_topic;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		if($user)
		{
			$timezone = $user->getTimeZone();
			$level = $user->getUserLevel();
			$user->newActivity($_REQUEST["REMOTE_ADDR"]);
		}
		$board = intval($_GET["board"]);
		$topic = intval($_GET["topic"]);
		$page = intval($_GET["page"]);
		
		$sql = "SELECT boards.boardlevel, topics.topicname, topics.topicby, topics.topicactive FROM boards, topics WHERE boards.boardid = ".
			$board." AND topics.boardnum = ".$board." AND topics.topicid = ".$topic;
		$result = mysql_query($sql);
		$trdr = mysql_fetch_row($result);
		if($trdr)
		{
			$blevel = $trdr[0];
			$tname = $trdr[1];
			$tby = $trdr[2];
			$tactive = $trdr[3];
		}
		else
		{
			$valid_topic = FALSE;
		}
	}
	
	function canView()
	{
		global $ch;
		global $level;
		global $blevel;
		global $valid_topic;
		
		if($level < $blevel)
		{
			echo "<pre class = small".$ch.">Error: You must be level ".$blevel." or higher to view this topic.\n";
			return FALSE;
		}
		else if(!$valid_topic)
		{
			echo "<pre class = \"small".$ch."\">Error: Inavlid topic.</pre>";
			return FALSE;
		}
		return TRUE;
	}
	
	function displayMessages()
	{
		global $ch;
		global $level;
		global $user;
		global $board;
		global $topic;
		global $page;
		global $timezone;
		global $tactive;
		global $tby;
		
		if($tactive == 0 || $tactive == 2)
		{
			echo "<pre class = \"small".$ch."\">This topic has been marked closed.  No more messages".
				" can be posted.</pre>";
		}
		
		$currp = $page * 20;
		$sql = "SELECT messages.messageid, messages.messageby, messages.messagestuff, messages.messdate, users.userid ".
			"FROM messages, users WHERE messages.boardnum = ".$board." AND messages.topicnum = ".$topic." AND users.username = messages.messageby".
			" ORDER BY messages.messageid LIMIT ".$currp.", 20";
		$result = mysql_query($sql);
		$messrdr = mysql_fetch_row($result);
		DisplayJumpToPage($board, $topic, $ch);
		
		$counter = $currp;
		while($messrdr)
		{
			$mid = $messrdr[0];
			$meby = $messrdr[1];
			$message = $messrdr[2];
			$medate = formatTimeZone($messrdr[3], $timezone);
			$mbyid = $messrdr[4];
			
			echo "<table class = messhead".$ch."><tr><td class = messhead".$ch.">Message By: <a class = ".
            "mencat".$ch." href = whois.php?user=".$mbyid."&board=".$board."&topic=".$topic."&message=".$mid.">".
            $meby."</a> | Date Posted: ".$medate;
            if($user)
            {
            	if($user->getUserName() == $meby && $counter > 0)
            	{
            		echo " | <a class = mencat".$ch." href = delmess.php?board=".$board."&topic=".$topic.
						"&message=".$mid.">Delete Message</a>";
            	}
            	if(($user->getUserName() == $tby || $user->getUserLevel() >= MOD_LEVEL) && $counter == 0)
            	{
            		echo " | <a class = mencat".$ch." href = clotop.php?board=".$board."&topic=".$topic.
						">Close Topic</a>";
            	}
            	if($user->getUserLevel() >= MOD_LEVEL && $counter == 0 && $tactive < 2)
            	{
            		echo " | <a class = \"mencat".$ch."\" href = \"stickytop.php?topic=".$topic."\">Sticky Topic</a>";
            	}
            	else if($user->getUserLevel() >= MOD_LEVEL && $counter == 0 && $tactive >= 2)
            	{
            		echo " | <a class = \"mencat".$ch."\" href = \"stickytop.php?topic=".$topic."\">Unsticky Topic</a>";
            	}
            	if($user->getUserName() != $meby)
            	{
            		echo " | <a class = mencat".$ch." href = marker.php?message=".$mid.">Mark Message for Moderation</a>";
            	}
            }
            echo "</td></tr><tr><td class = messlst".$ch.">".$message."</td></tr></table>";
            $counter++;
            $messrdr = mysql_fetch_row($result);
		}
		
		DisplayJumpToPage($board, $topic, $ch);
	}
	
	function DisplayJumpToPage($board, $topic, $ch)
	{
		$sql = "SELECT COUNT(*) FROM messages WHERE boardnum = ".$board." AND topicnum = ".$topic;
		$result = mysql_query($sql);
		$msgctrdr = mysql_fetch_row($result);
		$mecount = $msgctrdr[1];
		
		if($mecount > 20)
		{
			$num = 1;
			echo "<table class = menu" + th + "><tr class = mencatbd".$ch."><td class = menu".$ch.
				"><b>Jump To Page: <a class = mencat".$ch." href = messages.php?board=".$board."&topic=".
				$topic."&page=".($num - 1).">".$num."</a>";
			$mecount -= 20;
			$num++;
			while($mecount > 0)
			{
				echo " | <a class = mencat".$ch." href = messages.php?board=".$board."&topic=".$topic.
				"&page=".($num - 1).">".$num."</a>";
				$mecount -= 20;
				$num++;
			}
			echo "</b></td></tr></table>";
		}
	}