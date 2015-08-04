<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	
	$topid;
	$topby;
	$topactive;
	$blevel;
	$postcount;
	$boardnum;
	$ch;
	$user;
	$valid_topic;
	
	function initialize()
	{
		createConnection();
		
		global $topid;
		global $topby;
		global $topactive;
		global $blevel;
		global $postcount;
		global $boardnum;
		global $ch;
		global $user;
		global $valid_topic;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$topid = intval($_GET["topic"]);
		$sql = "SELECT topics.topicby, topics.topicactive, topics.posts, topics.boardnum, boards.boardlevel FROM topics, boards ".
			"WHERE topics.topicid = ".$topid." AND boards.boardid = topics.boardnum";
		$result = mysql_query($sql);
		$toprdr = mysql_fetch_row($result);
		
		if($toprdr)
		{
			$topby = $toprdr[0];
			$topactive = $toprdr[1];
			$postcount = $toprdr[2];
			$boardnum = $toprdr[3];
			$blevel = $toprdr[4];
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
		global $topby;
		global $topactive;
		global $blevel;
		global $valid_topic;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"small0\" href = \"login.php\">logged in</a>.</pre>\n";
			return FALSE;
		}
		else if(!$valid_topic)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid topic id.</pre>";
			return FALSE;
		}
		else if($topactive == 0 || $topactive == 2)
		{
			echo "<pre class = \"small".$ch."\">Error: This topic is already closed.</pre>";
			return FALSE;
		}
		else if($user->getUserName() != $topby && $user->getUserLevel() < MOD_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: Only ".SITE_NAME." staff can close topics they did not create.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function showPage()
	{
		global $user;
		global $topby;
		global $postcount;
		
		$action = $_POST["action"];
		
		if($action)
		{
			if($postcount == 1 && $user->getUserName() == $topby)
			{
				deleteTopic();
			}
			else
			{
				closeTopic();
			}
		}
		else
		{
			closeTopicForm();
		}
	}
	
	function deleteTopic()
	{
		global $user;
		global $ch;
		global $topid;
		global $boardnum;
		global $postcount;
		$username = $user->getUserName();
		$messid;
		$message;
		
		$sql = "SELECT messageid, messagestuff FROM messages WHERE topicnum = ".$topid." LIMIT 0, 1";
		$result = mysql_query($sql);
		$firstmsgrdr = mysql_fetch_array($result);
		
		if($firstmsgrdr)
		{
			$messid = $firstmsgrdr["messageid"];
			$message = $firstmsgrdr["messagestuff"];
		}
		
		$sql = "UPDATE topics SET boardnum = 0 WHERE topicid = ".$topid;
		mysql_query($sql);
		$sql = "UPDATE messages SET boardnum = 0 WHERE topicnum = ".$topid;
		mysql_query($sql);
		$sql = "UPDATE boards SET messcount = messcount - ".$postcount.", topcount = topcount - 1 WHERE boardid = ".$boardnum;
		mysql_query($sql);
		$sql = "UPDATE boards SET messcount = messcount + ".$postcount.", topcount = topcount + 1 WHERE boardid = 0";
		mysql_query($sql);
		$sql = "INSERT INTO moderations VALUES(0, ".$messid.", '$username', 'Self-Deletion', 'Delete Topic', 1, ".$boardnum.
			", 3, '$message', '$username', ".time().")";
		mysql_query($sql);
		
		echo "<pre class = \"small".$ch."\">The topic has been deleted.  Return <a class = \"bg".$ch."\" href = \"topics.php?board=".$boardnum."\">here</a>.</pre>\n";
	}
	
	function closeTopic()
	{
		global $topid;
		global $ch;
		global $boardnum;
		
		$sql = "UPDATE topics SET topicactive = topicactive - 1 WHERE topicid = ".$topid;
		mysql_query($sql);
		
		echo "<pre class = \"small".$ch."\">The topic has been closed.  Return <a class = \"bg".$ch."\" href = \"messages.php?board=".$boardnum."&topic=".$topid."\">here".
			"</a>.</pre>\n";
	}
	
	function closeTopicForm()
	{
		global $topid;
		global $user;
		global $topby;
		global $postcount;
		global $ch;
		$actionname;
		
		if($postcount == 1 && $user->getUserName() == $topby)
		{
			$actionname = "Delete Topic";
		}
		else
		{
			$actionname = "Close Topic";
		}
		
		echo "<form action = \"clotop.php?topic=".$topid."\" method = \"post\">\n";
		echo "<pre class = \"small".$ch."\">Are you sure you want to close/delete this topic?</pre>\n";
		echo "<input type = \"submit\" name = \"action\" value = \"".$actionname."\" />";
		echo "</form>\n";
	}