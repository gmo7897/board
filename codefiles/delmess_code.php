<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	
	$wasmoderated;
	$messid;
	$boardid;
	$topid;
	$boardlevel;
	$topactive;
	$messby;
	$message;
	$ch;
	$user;
	$valid_message;
	
	function initialize()
	{
		createConnection();
		global $wasmoderated;
		global $messid;
		global $boardid;
		global $topid;
		global $boardlevel;
		global $topactive;
		global $messby;
		global $message;
		global $ch;
		global $user;
		global $valid_message;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$messid = intval($_GET["message"]);
		
		$sql = "SELECT messages.boardnum, messages.topicnum, messages.messageby, messages.messagestuff, ".
			" boards.boardlevel, topics.topicactive FROM messages, boards, topics WHERE messages.messageid = ".
			$messid." AND boards.boardid = messages.boardnum AND topics.topicid = messages.topicnum";
		$result = mysql_query($sql);
		$messrdr = mysql_fetch_row($result);
		if($messrdr)
		{
			$boardid = $messrdr[0];
			$topid = $messrdr[1];
			$messby = $messrdr[2];
			$message = $messrdr[3];
			$boardlevel = $messrdr[4];
			$topactive = $messrdr[5];
			$valid_message = TRUE;
		}
		else
		{
			$valid_message = FALSE;
		}
		
		$sql = "SELECT modid FROM moderations WHERE mesnum = ".$messid;
		$result = mysql_query($sql);
		$modrdr = mysql_fetch_array($result);
		if($modrdr)
		{
			$wasmoderated = TRUE;
		}
		else
		{
			$wasmoderated = FALSE;
		}
	}
	
	function canView()
	{
		global $user;
		global $messby;
		global $valid_message;
		global $wasmoderated;
		global $ch;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a>.</pre>\n";
			return FALSE;
		}
		else if(!$valid_message)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid message id.</pre>";
			return FALSE;
		}
		else if($user->getUserName() != $messby)
		{
			echo "<pre class = \"small".$ch."\">Error: You are not the creator of this message.</pre>\n";
			return FALSE;
		}
		else if($wasmoderated)
		{
			echo "<pre class = \"small".$ch."\">Error: This message has already been deleted.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function showPage()
	{
		global $messid;
		global $messby;
		global $boardid;
		global $topid;
		global $message;
		global $ch;
		$delete = $_POST["delete"];
		
		if($delete)
		{
			$delstr = "[This message was deleted by the original poster.]";
			$sql = "INSERT INTO moderations VALUES(0, ".$messid.", '$messby', 'Self-Deletion', 'Delete Message', 0, ".
				$boardid.", 3, '$message', '$messby', ".time().")";
			mysql_query($sql);
			$sql = "UPDATE messages SET messagestuff = '$delstr' WHERE messageid = ".$messid;
			mysql_query($sql);
			
			echo "<pre class = \"small".$ch."\">The message has been deleted.  Return <a class = \"bg".$ch."\" href = \"messages.php?".
				"board=".$boardid."&topic=".$topid."\">here</a>.</pre>\n";
		}
		else
		{
			echo "<form action = \"delmess.php?message=".$messid."\" method = \"post\">\n";
			echo "<pre class = \"small".$ch."\">Are you sure you want to delete this message?</pre>";
			echo "<input type = \"submit\" name = \"delete\" value = \"Delete Message\">";
			echo "</form>\n";
		}
	}