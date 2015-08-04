<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/InputFormatter.inc");
	include("includes/SiteTools.inc");
	
	$ch;
	$user;
	$wasmodded;
	$messid;
	$bid;
	$tid;
	$tactive;
	$blevel;
	$messbyid;
	$markcount;
	$message;
	$messby;
	$markers;
	$messdate;
	$messvalid;
	$markexists;
	
	function initialize()
	{
		createConnection();
		global $ch;
		global $user;
		global $wasmodded;
		global $messid;
		global $bid;
		global $tid;
		global $tactive;
		global $blevel;
		global $messbyid;
		global $markcount;
		global $message;
		global $messby;
		global $markers;
		global $messdate;
		global $messvalid;
		global $markexists;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$messid = intval($_GET["message"]);
		
		$sql = "SELECT messages.boardnum, messages.topicnum, messages.messageby, messages.messagestuff, ".
			"messages.messdate, topics.topicactive, users.userid, boards.boardlevel FROM messages, topics, users, boards ".
			"WHERE messages.messageid = ".$messid." AND topics.topicid = messages.topicnum AND users.username = messages.messageby ".
			"AND boards.boardid = messages.boardnum";
		$result = mysql_query($sql);
		$messrdr = mysql_fetch_row($result);
		if($messrdr)
		{
			$bid = $messrdr[0];
			$tid = $messrdr[1];
			$messby = $messrdr[2];
			$message = $messrdr[3];
			$messdate = formatTimeZone($messrdr[4], $user->getTimeZone());
			$tactive = $messrdr[5];
			$messbyid = $messrdr[6];
			$blevel = $messrdr[7];
			$messvalid = TRUE;
		}
		else
		{
			$messvalid = FALSE;
		}
		
		$sql = "SELECT markcount, markers FROM modqueue WHERE mesid = ".$messid;
		$result = mysql_query($sql);
		$markrdr = mysql_fetch_array($result);
		if($markrdr)
		{
			$markcount = $markrdr["markcount"];
			$markers = explode(", ", $markrdr["markers"]);
			$markexists = TRUE;
		}
		else
		{
			$markexists = FALSE;
		}
		
		$sql = "SELECT modid FROM moderations WHERE mesnum = ".$messid." AND reason NOT LIKE 'Self-Deletion'";
		$result = mysql_query($sql);
		$wasmodded = mysql_fetch_row($result);
	}
	
	function canView()
	{
		global $user;
		global $blevel;
		global $ch;
		global $wasmodded;
		global $markers;
		global $messvalid;
		global $markexists;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: you are not <a class = \"bg0\" href = \"login.php\">logged in</a></pre>.\n";
			return FALSE;
		}
		else if(!$messvalid)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid message id.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < MIN_APPOINT_LVL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".MIN_APPOINT_LVL." or higher to mark messages.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < $blevel)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be ".$blevel." or higher to mark messages on this board.</pre>\n";
			return FALSE;
		}
		else if($wasmodded)
		{
			echo "<pre class = \"small".$ch."\">Error: This message has already been moderated.</pre>";
			return FALSE;
		}
		else if($markexists && in_array($user->getUserName(), $markers))
		{
			echo "<pre class = \"small".$ch."\">Error: You have already marked this message.";
			return FALSE;
		}
		else if(hasMarkAbuse($user->getUserName()))
		{
			echo "<pre class = \"small".$ch."\">Error: Due to prior abuse of the marking system, you can no longer mark messages for moderation.</pre>";
			return FALSE;
		}
		return TRUE;
	}
	
	function markMessage()
	{
		global $user;
		global $messid;
		global $tid;
		global $bid;
		global $markexists;
		global $markers;
		global $messby;
		global $message;
		global $ch;
		
		$markreason = $_POST["reason"];
		$otherreason = $_POST["otherreason"];
		$username = $user->getUserName();
		
		if($markreason == "Other" && $otherreason)
		{
			$markreason = $markreason.":".formatString($otherreason);
		}
		if(!$markexists)
		{
			$sql = "INSERT INTO modqueue VALUES(0, ".$messid.", '$username', '$messby', ".
                "'$markreason', '$message', 1, '$username')";
			mysql_query($sql);
		}
		else
		{
			$markers[] = $username;
			$markerlist = implode(", ", $markers);
			$sql = "UPDATE modqueue SET markcount = markcount + 1, markers = '$markerlist' WHERE mesid = ".$messid;
			mysql_query($sql);
		}
		
		echo "<pre class = \"small".$ch."\">The message has been marked for moderation.  Return <a class = \"bg".$ch."\" href = \"messages.php?board=".
			$bid."&topic=".$tid."\">here</a>";
	}
	
	function hasMarkAbuse($username)
	{
		$sql = "SELECT messageby FROM modqueue WHERE messageby = '$username' AND reason = 'Mark Abuse'";
		$result = mysql_query($sql);
		$abuserdr = mysql_fetch_array($result);
		
		if($abuserdr)
		{
			return TRUE;
		}
		return FALSE;
	}