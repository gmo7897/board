<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");
	
	$ch;
	$markid;
	$messid;
	$boardnum;
	$topicnum;
	$messagesintopic;
	$message;
	$markreason;
	$original_marker_name;
	$user;
	$poster;
	$valid_mark;
	
	function initialize()
	{
		createConnection();
		global $ch;
		global $markid;
		global $messid;
		global $boardnum;
		global $topicnum;
		global $messagesintopic;
		global $message;
		global $markreason;
		global $original_marker_name;
		global $user;
		global $poster;
		global $valid_mark;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$markid = intval($_GET["mark"]);
		$sql = "SELECT modqueue.mesid, modqueue.reason, modqueue.markby, messages.topicnum, messages.boardnum, ".
			"messages.messagestuff, topics.posts, users.userid FROM modqueue, messages, topics, users WHERE ".
			"modqueue.queueid = ".$markid." AND messages.messageid = modqueue.mesid AND topics.topicid = ".
			"messages.topicnum AND users.username = messages.messageby";
		$result = mysql_query($sql);
		$markrdr = mysql_fetch_row($result);
		if($markrdr)
		{
			$messid = $markrdr[0];
			$markreason = $markrdr[1];
			$original_marker_name = $markrdr[2];
			$topicnum = $markrdr[3];
			$boardnum = $markrdr[4];
			$message = $markrdr[5];
			$messagesintopic = $markrdr[6];
			$posterid = $markrdr[7];
			$poster = getUserById($posterid);
			$valid_mark = TRUE;
		}
		else
		{
			$valid_mark = FALSE;
		}
	}
	
	function canView()
	{
		global $user;
		global $ch;
		global $valid_mark;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a></pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < MOD_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".MOD_LEVEL." or higher to view this page.</pre>\n";
			return FALSE;
		}
		else if(!$valid_mark)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid mark id</pre>";
			return FALSE;
		}
		return TRUE;
	}
	
	function moderateMessage()
	{
		global $user;
		global $poster;
		global $boardnum;
		global $topicnum;
		global $messid;
		global $markid;
		global $message;
		global $messagesintopic;
		global $original_marker_name;
		global $ch;
		
		$modaction = intval($_POST["action"]);
		$modreason = formatString($_POST["reason"]);
		$modname = $user->getUserName();
		
		if($modaction == -1) // Mark for abuse
		{
			$sql = "INSERT INTO modqueue VALUES(0, -1, '$modname', '$original_marker_name', 'Mark Abuse', '', 0, '')";
			mysql_query($sql);
			$sql = "DELETE FROM modqueue WHERE queueid = ".$markid;
			mysql_query($sql);
			echo "<pre class = \"small".$ch."\">No action has been taken, and the original marker has been marked for abuse.  Return ".
				"<a class = \"bg".$ch."\" href = \"index.php\">here</a>.</pre>";
		}
		else if($modaction == 0) // No action
		{
			$sql = "DELETE FROM modqueue WHERE queueid = ".$markid;
			mysql_query($sql);
			echo "<pre class = \"small".$ch."\">No action has been taken.  Return <a class = \"bg".
				$ch."\" href = \"index.php\">here</a>.";
		}
		else if($modaction > 0) // Action taken
		{
			$sysmess;
			$action;
			$pointloss;
			$postername = $poster->getUserName();
			$moderatorname = $user->getUserName();
			
			if($modaction < 5) // Delete Message
			{
				$action = "Delete Message";
				switch($modaction)
				{
					case 1:
						$sysmess = "One of your messages has been moderated for no ".RANK_POINTS."  It is considered".
							" a minor violation";
						$pointloss = 0;
						break;
					case 2:
						$sysmess = "One of your messages has been moderated for 5 ".RANK_POINTS." loss.";
						$action = $action." Remove 5 points";
						$pointloss = 5;
						break;
					case 3:
						$sysmess = "Your account has been put on probation for a major tou violation.";
						$action = $action." Set Probation";
						$pointloss = 10;
						$poster->setUserLevel(PROBATION);
						$poster->setUnwarnTime(time() + 60 * 60 * 24 * 3);
						break;
					default:
						$sysmess = "Your account has been suspended for a severe tou violation";
						$action = $action." Suspend User";
						$pointloss = 0;
						$poster->setUserLevel(SUSPENDED);
						break;
				}
				$poster->setAppoints($poster->getAppoints() - $pointloss);
				$poster->setBiscuits($poster->getBiscuits() - (5 * $pointloss));
				$sql = "UPDATE messages SET messagestuff = '[This message has been deleted by a moderator]'".
					"WHERE messageid = ".$messid;
				mysql_query($sql);
			}
			else if($modaction >= 5)
			{
				$action = "Delete Topic";
				switch($modaction)
				{
					case 5:
						$sysmess = "One of your topics has been moderated for no ".RANK_POINTS." loss.".
							"  It is considered a minor violation";
						$pointloss = 0;
						break;
					case 6:
						$sysmess = "One of your topics has been moderated for 5 ".RANK_POINTS." loss.";
						$action = $action." Remove 5 points";
						$pointloss = 5;
						break;
					case 7:
						$sysmess = "Your account has been put on probation for a major tou violation.";
						$action = $action." Set Probation";
						$pointloss = 10;
						$poster->setUserLevel(PROBATION);
						$poster->setUnwarnTime(time() + 60 * 60 * 24 * 3);
						break;
					default:
						$sysmess = "Your account has been suspended for a severe tou violation.";
						$action = $action." Suspend User";
						$pointloss = 0;
						$poster->setUserLevel(SUSPENDED);
						break;
				}
				$poster->setAppoints($poster->getAppoints() - $pointloss);
				$poster->setBiscuits($poster->getBiscuits() - (5 * $pointloss));
				$sql = "UPDATE topics SET boardnum = 0 WHERE topicid = ".$topicnum;
				mysql_query($sql);
				$sql = "UPDATE messages SET boardnum = 0 WHERE topicnum = ".$topicnum;
				mysql_query($sql);
				$sql = "UPDATE boards SET topcount = topcount - 1, messcount = messcount - ".
					$messagesintopic." WHERE boardid = ".$boardnum;
				mysql_query($sql);
				$sql = "UPDATE boards SET topcount = topcount + 1, messcount = messcount + ".
					$messagesintopic." WHERE boardid = 0";
				mysql_query($sql);
			}
			$sql = "INSERT INTO systemmess VALUES(0, 'Site Staff', '$postername', '$sysmess')";
			mysql_query($sql);
			$sql = "INSERT INTO moderations VALUES(0, ".$messid.", '$moderatorname', '$modreason', '$action',".
				 " 0, ".$boardnum.", 0, '$message', '$postername', ".time().")";
			mysql_query($sql);
			$sql = "DELETE FROM modqueue WHERE queueid = ".$markid;
			mysql_query($sql);
			
			echo "<pre class = \"small".$ch."\">The message has been moderated.  Return <a class = \"bg".$ch.
				"\" href = \"index.php\">here</a>.";
		}
	}