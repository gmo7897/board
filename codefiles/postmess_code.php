<?php
	include("includes/ConnectionInfo.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");
	include("includes/ShopItem.inc");
	include("includes/PostHelp.inc");
	
	$ch;
	$board;
	$topic;
	$user;
	$blevel = 0;
	$tactive = 0;
	$valid_topic;
	
	function initialize()
	{
		createConnection();
		
		global $ch;
		global $board;
		global $topic;
		global $user;
		global $blevel;
		global $tactive;
		global $valid_topic;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$board = intval($_GET["board"]);
		$topic = intval($_GET["topic"]);
		
		$sql = "SELECT boards.boardlevel, topics.topicactive FROM boards, topics WHERE boards.boardid = ".
			$board." AND topics.topicid = ".$topic;
		$result = mysql_query($sql);
		$infordr = mysql_fetch_row($result);
		if($infordr)
		{
			$blevel = $infordr[0];
			$tactive = $infordr[1];
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
		global $blevel;
		global $tactive;
		global $valid_topic;
		global $ch;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = login.php>".
				"logged in</a></pre>";
			return FALSE;
		}
		else if(!$valid_topic)
		{
			echo "<pre class = \"small".$ch.">Error: Invalid topic.</pre>";
			return FALSE;
		}
		else if($user->getUserLevel() < POSTING_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".POSTING_LEVEL." or higher to post messages.</pre>";
			return FALSE;
		}
		else if($user->getUserLevel() < $blevel)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".$blevel." or higher to ".
				"create topics on this board.</pre>";
			return FALSE;
		}
		else if($user->getUserLevel() == 3 && $user->getDailyPosts() >= 5)
		{
			echo "<pre class = small".$ch.">Error: Users on probation can only post 5 messages per day.</pre>";
			return FALSE;
		}
		else if($user->getUserLevel() == 4 && $user->getDailyPosts() >= 10)
		{
			echo "<pre class = small".$ch.">Error: Level 4 users can only post 10 messages per day.</pre>";
			return FALSE;
		}
		else if($user->getUserLevel() == 5 && $user->getDailyPosts() >= 20)
		{
			echo "<pre class = small".$ch.">Error: Level 5 users can only post 20 messages per day.</pre>";
			return FALSE;
		}
		else if(($tactive == 0 || $tactive == 2) && $user->getUserLevel() < MOD_LEVEL)
		{
			echo "<pre class = small".$ch.">Error: You cannot post in closed topics.</pre>";
			return FALSE;
		}
		return TRUE;
	}
	
	function displayPostMessage()
	{
		global $ch;
		global $board;
		global $topic;
		global $user;
		
		$sent = $_POST["post"];
		$view = $_POST["preview"];
		
		if($sent || $view)
		{
			$message = stripslashes($_POST["message"]);
			$previewedmess = $message;
			$trimmedmess = trimString($message);
			
			if(strlen($trimmedmess) < 5)
			{
				echo "<pre class = small" + ch + ">Error: Messages must have at least 5 non-whitespace characters.<br><br></pre>\n";
				postMessageForm1($ch, $previewedmess);
			}
			else
			{
				if(!isValidLength($message, 4000, 200))
				{
					echo "<pre class = \"small".$ch."\">Error: Your message is either over 4000 characters or you have a word over 200 characters.</pre>\n";
					postMessageForm1($ch, $previewedmess);
				}
				else if(getLastMinutePosts($user->getUserName()) >= 3)
				{
					echo "<pre class = \"small".$ch."\">Error: You may only post up to 3 messages per minute</pre>\n";
					postMessageForm1($ch, $previewedmess);
				}
				else
				{
					$message = fixTags(formatString($message), $user);
					
					if($sent)
					{
						$username = $user->getUserName();
						$user->posted(FALSE);
						$sql = "INSERT INTO messages VALUES(0, ".$topic.", ".$board.", '$username', ".
                        "'$message', ".time().")";
						mysql_query($sql);
						addMessageToTopic($topic, $board);
						
						echo "<pre class = \"small".$ch."\">Your message has been posted.  Return to the ".
							"<a class = \"bg".$ch."\" href = \"messages.php?board=".$board."&topic=".$topic."\">".
							"Message list</a>.";
					}
					else if($view)
					{
						echo "<table class = \"messlst".$ch."\"><tr><td class = \"messlst".$ch."\">".$message.
							"</td></tr></table>";
						postMessageForm1($ch, $previewedmess);
					}
				}
			}
		}
		else
		{
			postMessageForm($ch, $user->getSignature());
		}
	}
	
	function postMessageForm($ch, $signature)
	{
		global $board;
		global $topic;
		
		echo "<form action = \"postmess.php?board=".$board."&topic=".$topic."\" method = \"post\">";
		echo "<pre class = small".$ch.">Message (Max 4000 characters):\n".
			"<textarea name = message cols = 70 rows = 20>";
		if($signature)
		{
			$signature = str_replace("<br />", "\n", $signature);
			echo "&shy;\n---\n".$signature;
		}
		echo "</textarea></pre><br>\n";
		echo "<input type = \"submit\" name = \"post\" value = \"Post Message\" />\n";
		echo "<input type = \"submit\" name = \"preview\" value = \"Preview Message\" />\n";
		echo "</form>";
	}
	
	function postMessageForm1($ch, $message)
	{
		global $board;
		global $topic;
		
		echo "<form action = \"postmess.php?board=".$board."&topic=".$topic."\" method = \"post\">";
		echo "<pre class = \"small".$ch."\">Message (Max 4000 characters):\n".
			"<textarea name = \"message\" cols = \"70\" rows = \"20\">".$message."</textarea></pre><br />\n";
		echo "<input type = \"submit\" name = \"post\" value = \"Post Message\" />\n";
		echo "<input type = \"submit\" name = \"preview\" value = \"Preview Message\" />\n";
		echo "</form";
	}