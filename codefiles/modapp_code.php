<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");
	
	$ch;
	$user;
	
	function initialize()
	{
		createConnection();
		global $ch;
		global $user;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
	}
	
	function canView()
	{
		global $ch;
		global $user;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a>.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < BASE_LVL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level 20 or higher to apply to be a moderator.</pre>\n";
			return FALSE;
		}
		else if($user->getAppoints() < 50)
		{
			echo "<pre class = \"small".$ch."\">Error: You must have at least 50 ".RANK_POINTS." to apply to be a moderator.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function showApplication()
	{
		global $ch;
		global $user;
		$username = $user->getUserName();
		$applied = $_POST["apply"];
		
		if($applied)
		{
			$application = $_POST["application"];
			
			if(!isValidLength($application, 4000, 200))
			{
				echo "<pre class = \"small".$ch."\">Error: Your application is over 4000 characters or you have a word over 200 characters.</pre>\n";
				displayExistingApplication($ch, $application);
			}
			else
			{
				$application = formatString($application);
				$sql = "SELECT messageid FROM messages WHERE boardnum = -1 AND messageby = '$username'";
				$result = mysql_query($sql);
				$appchecker = mysql_fetch_array($result);
				
				if($appchecker)
				{
					$messid = $appchecker["messageid"];
					$sql = "UPDATE messages SET messagestuff = '$application', messdate = ".time()." WHERE messageid = ".$messid;
					mysql_query($sql);
					$sql = "UPDATE topics SET lastpost = ".time()." WHERE boardnum = -1 AND topicby = '$username'";
					mysql_query($sql);
				}
				else
				{
					$sql = "INSERT INTO topics VALUES(0, -1, '$username', 'Moderator Application for ".$username."', 0, 1, ".time().")";
					mysql_query($sql);
					
					$sql = "SELECT topicid FROM topics WHERE boardnum = -1 AND topicby = '$username'";
					$result = mysql_query($sql);
					$topidrdr = mysql_fetch_array($result);
					$topid = $topidrdr["topicid"];
					
					$sql = "INSERT INTO messages VALUES(0, ".$topid.", -1, '$username', '$application', ".time().")";
					mysql_query($sql);
					
					$sql = "UPDATE boards SET topcount = (topcount + 1), messcount = (messcount + 1) WHERE boardid = -1";
					mysql_query($sql);
				}
				echo "<pre class = \"small".$ch."\">You have entered your application.  Return <a class = \"bg".$ch.
					"\" href = \"index.php\">here</a>.</pre>\n";
			}
		}
		else
		{
			displayNewApplication($ch);
		}
	}
	
	function displayExistingApplication($ch, $application)
	{
		echo "<pre class = \"small".$ch."\">Please enter why you would make a good appletland moderator.\n".
			"All TOU rules apply, and abuse of this form will result in harsh penalties.\n(Max 4000 characters)</pre>\n";
		echo "<form action = \"modapp.php\" method = \"post\">";
		echo "<textarea rows = \"20\" cols = \"70\" name = \"application\">".$application."</textarea><br>\n";
		echo "<input type = \"submit\" name = \"apply\" value = \"Submit Application\" />";
		echo "</form>";
	}
	
	function displayNewApplication($ch)
	{
		echo "<pre class = \"small".$ch."\">Please enter why you would make a good appletland moderator.\n".
			"All TOU rules apply, and abuse of this form will result in harsh penalties.\n(Max 4000 characters)</pre>\n";
		echo "<form action = \"modapp.php\" method = \"post\">";
		echo "<textarea rows = \"20\" cols = \"70\" name = \"application\"></textarea><br>\n";
		echo "<input type = \"submit\" name = \"apply\" value = \"Submit Application\" />";
		echo "</form>";
	}