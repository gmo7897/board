<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	
	$topid;
	$bdid;
	$topicactive;
	$blevel;
	$ch;
	$user;
	$valid_topic;
	
	function initialize()
	{
		createConnection();
		
		global $topid;
		global $bdid;
		global $topicactive;
		global $blevel;
		global $ch;
		global $user;
		global $valid_topic;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$topid = intval($_GET["topic"]);
		
		$sql = "SELECT topics.boardnum, topics.topicactive, boards.boardlevel FROM topics, boards WHERE topics.topicid = ".
			$topid." AND boards.boardid = topics.boardnum";
		$result = mysql_query($sql);
		$topinfordr = mysql_fetch_row($result);
		if($topinfordr)
		{
			$bdid = $topinfordr[0];
			$topicactive = $topinfordr[1];
			$blevel = $topinfordr[2];
			$valid_topic = TRUE;
		}
		else
		{
			$valid_topic = FALSE;
		}
	}
	
	function canView()
	{
		global $user;
		global $ch;
		global $valid_topic;
		global $blevel;
		
		if(!$user)
		{
			echo "<pre class = \"small".$ch."\">Error: You are not <a href = \"login.aspx\">logged in</a>.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < MOD_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".MOD_LEVEL." or higher to view this page.</pre>\n";
			return FALSE;
		}
		else if(!$valid_topic)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid topic id.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < $blevel)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".$blevel." or higher to sticky topics on this board</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function displayStickyTop()
	{
		global $topicactive;
		global $ch;
		global $bdid;
		global $topid;
		$stickytop = $_POST["sticky"];
		
		if($stickytop && $topicactive < 2)
		{
			stickyTopic();
			echo "<pre class = \"small".$ch."\">The topic has been stickied.  Return <a class = \"bg".$ch.
				"\" href = \"topics.php?board=".$bdid."&topic=".$topid."\">here</a>.</pre>\n";
		}
		else if($stickytop && $topicactive >= 2)
		{
			unstickyTopic();
			echo "<pre class = \"small".$ch."\">The topic has been unstickied.  Return <a class = \"bg".$ch.
				"\" href = \"topics.php?board=".$bdid."&topic=".$topid."\">here</a>.</pre>\n";
		}
		else
		{
			displayForm($topicactive);
		}
	}
	
	function stickyTopic()
	{
		global $topid;
		$sql = "UPDATE topics SET topicactive = topicactive + 2 WHERE topicid = ".$topid;
		mysql_query($sql);
	}
	
	function unstickyTopic()
	{
		global $topid;
		$sql = "UPDATE topics SET topicactive = topicactive - 2 WHERE topicid = ".$topid;
		mysql_query($sql);
	}
	
	function displayForm($tactive)
	{
		global $ch;
		global $topid;
		
		echo "<form action = \"stickytop.php?topic=".$topid."\" method = \"post\">\n";
		if($tactive < 2)
		{
			echo "<pre class = \"small".$ch."\">Are your sure you want to sticky this topic?</pre>\n".
				"<input type = \"submit\" name = \"sticky\" value = \"Sticky Topic\" />\n";
		}
		else
		{
			echo "<pre class = \"small".$ch."\">Are your sure you want to unsticky this topic?</pre>\n".
				"<input type = \"submit\" name = \"sticky\" value = \"Unsticky Topic\" />\n";
		}
		echo "</form>\n";
	}