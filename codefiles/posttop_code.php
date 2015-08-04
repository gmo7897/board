<?php
	include("includes/ConnectionInfo.inc");
	include("includes/SiteTools.inc");
	include("includes/ShopItem.inc");
	include("includes/InputFormatter.inc");
	include("includes/PostHelp.inc");
	
	$ch;
	$user;
	$board;
	$blevel;
	$boardexists;
	
	function initialize()
	{
		createConnection();
		
		global $ch;
		global $user;
		global $board;
		global $blevel;
		global $boardexists;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$board = intval($_GET["board"]);
		
		$sql = "SELECT boardlevel FROM boards WHERE boardid = ".$board;
		$result = mysql_query($sql);
		$blvlrdr = mysql_fetch_array($result);
		if($blvlrdr)
		{
			$blevel = $blvlrdr["boardlevel"];
			$boardexists = TRUE;
		}
		else
		{
			$boardexists = FALSE;
		}
	}
	
	function canView()
	{
		global $user;
		global $blevel;
		global $boardexists;
		global $board;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a>.\n";
			return FALSE;
		}
		else if($user->getUserLevel() < $blevel)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".$blevel." or higher to create topics on this board.";
			return FALSE;
		}
		else if($user->getUserLevel() < TOPIC_MAKING_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: Users under level ".TOPIC_MAKING_LEVEL." cannot create topics.</pre>";
			return FALSE;
		}
		else if($board == 1 && $user->getUserLevel() < ADMIN_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: Only administrators can create topics on Message Board Announcements.</pre>\n";
			return FALSE;
		}
		else if($board < 1)
		{
			echo "<pre class = \"small".$ch."\">Error: Topics canot be created on maintainance boards.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() == 5 && $user->getDailyPosts() > 20)
		{
			echo "<pre class = \"small".$ch."\">Error: You have exceeded your daily post limit of 20.</pre>";
			return FALSE;
		}
		else if($user->getUserLevel() == 5 && $user->getDailyTopics() > 5)
		{
			echo "<pre class = \"small".$ch."\">Error: You have exceeded your daily post limit of 20.</pre>";
			return FALSE;
		}
		return TRUE;
	}
	
	function displayPostTopic()
	{
		global $user;
		global $ch;
		global $board;
		
		$sent = $_POST["post"];
		$view = $_POST["view"];
		
		if($sent || $view)
		{
			$topic = stripslashes($_POST["topic"]);
			$message = stripslashes($_POST["message"]);
			$previewedmess = $message;
			
			$trimmedtop = trimString($topic);
			$trimmedmess = trimString($message);
			
			if(strlen($trimmedtop) < 5)
			{
				echo "<pre class = \"small".$ch."\">Error: Topic titles must have ".
					"at least 5 non-whitespace characters.</pre>\n";
				postTopicForm1($ch, $topic, $previewedmess);
			}
			else if(strlen($trimmedmess) < 5)
			{
				echo "<pre class = \"small".$ch."\">Error: messages must have at least ".
					"5 non-whitespace characters.</pre>\n";
				postTopicForm1($ch, $topic, $previewedmess);
			}
			else if(!isValidLength($topic, 70, 20))
			{
				echo "<pre class = \"small".$ch."\">Error: Your topic title is over 70 characters or ".
					"has a word over 20 characters.</pre>\n";
				postTopicForm1($ch, $topic, $previewedmess);
			}
			else if(!isValidLength($message, 4000, 200))
			{
				echo "<pre class = \"small".$ch."\">Error: Your message is over 4000 characters or has ".
					"a word over 200 characters.</pre>\n";
				postTopicForm1($ch, $topic, $previewedmess);
			}
			else if(getLastMinutePosts($user->getUserName() >= 3))
			{
				echo "<pre class = \"small".$ch."\">Error: You may only post up to 3 messages per minute</pre>\n";
				postTopicForm1($ch, $topic, $previewedmess);
			}
			else
			{
				$topic = formatString($topic);
				$message = fixTags(formatString($message), $user);
				
				if($sent)
				{
					$sql = "SELECT topicid FROM topics WHERE topicname = '$topic' AND boardnum = ".$board;
					$result = mysql_query($sql);
					$texist = mysql_fetch_array($result);
					
					if($texist)
					{
						echo "<pre class = \"small".$ch."\">Error: A topic with this title already exists.</pre>";
						postTopicForm1($ch, $topic, $previewedmess);
					}
					else
					{
						$username = $user->getUserName();
						$sql = "INSERT INTO topics VALUES(0, ".$board.", '$username', '$topic', 1, 1, ".time().")";
						mysql_query($sql);
						$sql = "SELECT topicid FROM topics WHERE topicname = '$topic' AND boardnum = ".$board;
						$result = mysql_query($sql);
						$tidrdr = mysql_fetch_array($result);
						$topid = $tidrdr["topicid"];
						$sql = "INSERT INTO messages VALUES(0, ".$topid.", ".$board.", '$username', '$message'".
							", ".time().")";
						mysql_query($sql);
						addTopicToBoard($board);
						
						$user->posted(TRUE);
						
						echo "<pre class = \"small".$ch."\">Your topic has been posted.  Return to the <a class = \"bg".$ch.
							"\" href = \"topics.php?board=".$board."\">Topic List</a> or <a class = \"bg".$ch."\" href = \"messages.php?".
							"board=".$board."&topic=".$topid."\">Message List</a>.\n";
					}
				}
				else if($view)
				{
					echo "<table class = \"tophead".$ch."\"><tr><td class = \"tophead".$ch."\">".$topic."</td></tr>".
						"<tr><td class = \"messlst".$ch."\">".$message."</td></tr></table>";
					postTopicForm1($ch, $topic, $previewedmess);
				}
			}
		}
		else
		{
			postTopicForm($ch, $user->getSignature());
		}
	}
	
	function postTopicForm($ch, $signature)
	{
		global $board;
		
		echo "<form action = \"posttop.php?board=".$board."\" method = \"post\">";
		echo "<pre class = \"small".$ch."\">Topic Title: <input type = \"text\" name = \"topic\" size = \"70\" maxLength = \"70\" /></pre><br>\n";
		echo "<pre class = \"small".$ch."\">Message (Max 4000 characters):\n".
			"<textarea name = \"message\" cols = \"70\" rows = \"20\" wrap = \"soft\">";
		if($signature)
		{
			$signature = str_replace("<br />", "\n", $signature);
			echo "&shy;\n---\n".$signature;
		}
		echo "</textarea></pre><br>\n";
		echo "<input type = \"submit\" name = \"post\" value = \"Post Topic\" />\n";
		echo "<input type = \"submit\" name = \"view\" value = \"Preview Topic\" />\n";
		echo "</form>";
	}
	
	function postTopicForm1($ch, $topic, $message)
	{
		global $board;
		
		echo "<form action = \"posttop.php?board=".$board."\" method = \"post\">";
		echo "<pre class = \"small".$ch."\">Topic Title: <input type = \"text\" name = \"topic\" size = \"70\" maxLength = \"70\"".
			" value = \"".$topic."\" /></pre><br>\n";
		echo "<pre class = \"small".$ch."\">Message (Max 4000 characters):\n".
			"<textarea name = \"message\" cols = \"70\" rows = \"20\">".$message."</textarea></pre><br>\n";
		echo "<input type = \"submit\" name = \"post\" value = \"Post Topic\" />\n";
		echo "<input type = \"submit\" name = \"view\" value = \"Preview Topic\" />\n";
		echo "</form>";
	}