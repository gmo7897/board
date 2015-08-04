<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");
	
	$ch;
	$board = 0;
	$page = 0;
	$user;
	$level = 0;
	$timezone = 0;
	$blevel = 0;
	$bname;
	$valid_board = TRUE;
	
	function initialize()
	{
		createConnection();
		
		global $ch;
		global $board;
		global $page;
		global $user;
		global $level;
		global $timezone;
		global $blevel;
		global $bname;
		global $valid_board;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$board = intval($_GET["board"]);
		$page = intval($_GET["page"]);
		
		if($user)
		{
			$level = $user->getUserLevel();
			$timezone = $user->getTimeZone();
		}
		
		$sql = "SELECT boardname, boardlevel FROM boards WHERE boardid = ".$board;
		$result = mysql_query($sql);
		$boardrdr = mysql_fetch_array($result);
		
		if($boardrdr)
		{
			$bname = $boardrdr["boardname"];
			$blevel = $boardrdr["boardlevel"];
		}
		else
		{
			$valid_board = FALSE;
		}
	}
	
	function canView()
	{
		global $level;
		global $blevel;
		global $valid_board;
		global $ch;
		
		if(!$valid_board)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid board id.</pre>";
			return FALSE;
		}
		else if($level < $blevel)
		{
			echo "<pre class = small".$ch.">You must be level ".$blevel." or higher to view this board.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function showTopicList()
	{
		global $ch;
		global $page;
		global $board;
		global $timezone;
		$numstickies = 0;
		
		echo "<table class = tophead".$ch."><tr class = mencatbd".$ch."><td width = 45% class = tophead".
			$ch."><b>Topic Name</b></td><td width = 18% class = tophead".$ch."><b>Topic By</b></td>".
			"<td width = 12% class = tophead".$ch."><b>Messages</b></td><td width = 25% class = tophead".$ch.">".
			"<b>Last Post</b></td></tr></table>\n";
		DisplayJumpToPage($board, $ch);
		
		$sql = "SELECT COUNT(*) FROM topics WHERE boardnum = ".$board." AND topicactive > 1";
		$result = mysql_query($sql);
		$stickctrdr = mysql_fetch_row($result);
		if($stickctrdr)
		{
			$numstickies = $stickctrdr[1];
		}
		
		$sql = "SELECT * FROM topics WHERE boardnum = ".$board." AND topicactive > 1 ORDER BY lastpost DESC LIMIT ".($page * 20).", 20";
		$sticktopresult = mysql_query($sql);
		displayTopics($sticktopresult, $board, $timezone);
		
		$toplimit = 20 - (max($numstickies - $page * 20, 0));
		$liststart = max($page * 20 - $numstickies, 0);
		
		$sql = "SELECT * FROM topics WHERE boardnum = ".$board." AND topicactive <= 1 ORDER BY lastpost DESC LIMIT ".$liststart.", ".$toplimit;
		$regtopresult = mysql_query($sql);
		displayTopics($regtopresult, $board, $timezone);
		
		DisplayJumpToPage($board, $ch);
	}
	
	function DisplayJumpToPage($board, $ch)
	{
		$sql = "SELECT COUNT(*) FROM topics WHERE boardnum = ".$board;
		$result = mysql_query($sql);
		$topcountrdr = mysql_fetch_row($result);
		$topcount = $topcountrdr[1];
		
		if($topcount > 20)
		{
			$num = 1;
			echo "<table class = menu".$ch."><tr class = mencatbd".$ch."><td class = menu".$ch.
				"><b>Jump To Page: <a class = mencat".$ch." href = topics.php?board=".$board."&page=".
				(num - 1).">".$num."</a>";
			$num++;
			$topcount -= 20;
			
			while($topcount > 20)
			{
				echo " | <a class = mencat".$ch." href = topics.php?board=".$board."&page=".(num - 1).
					">".$num."</a>";
				$num++;
				$topcount -= 20;
			}
		}
	}
	
	function displayTopics($topresult, $board, $timezone)
	{
		global $ch;
		$topreader = mysql_fetch_array($topresult);
		while($topreader)
		{
			$topic = $topreader["topicid"];
			$tby = $topreader["topicby"];
			$tname = $topreader["topicname"];
			$tactive = $topreader["topicactive"];
			$posts = $topreader["posts"];
			$lastpost = formatTimeZone($topreader["lastpost"], $timezone);
			
			echo "<table class = \"toplst".$ch."\"><tr class = \"mencatbd".$ch."\"><td width = \"45%\" class = \"toplst".$ch."\">";
			if($tactive == 0 || $tactive == 2)
			{
				echo "<img src = \"locked.gif\" />";
			}
			else if($tactive == 2 || $tactive == 3)
			{
				echo "<img src = \"sticky.gif\" />";
			}
			
			echo "<a class = \"board".$ch."\" href = \"messages.php?board=".$board."&topic=".$topic."\">".
				$tname."</a></td><td width = \"18%\" class = \"toplst".$ch."\">".$tby."</td><td width = \"12%\" class = ".
				"\"toplst".$ch."\">".$posts."</td><td width = \"25%\" class = \"toplst".$ch."\">".$lastpost."</td></tr></table>\n";
			$topreader = mysql_fetch_array($topresult);
		}
	}