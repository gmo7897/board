<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");

	$messby = NULL;
	$appstatus = 0;
	$modid = 0;
	$modby;
	$ch;
	$user;
	$mod_exists = TRUE;

	function initialize()
	{
		global $modid;
		global $modby;
		global $appstatus;
		global $ch;
		global $user;
		global $messby;
		global $mod_exists;

		createConnection();

		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		
		$modid = intval($_GET["modid"]);

		$sql = "SELECT modby, contested, messby FROM moderations WHERE modid = ".$modid;
		$result = mysql_query($sql);
		$modrdr = mysql_fetch_array($result);

		if($modrdr)
		{
			$modby = $modrdr["modby"];
			$appstatus = $modrdr["contested"];
			$messby = $modrdr["messby"];
		}
		else
		{
			$mod_exists = FALSE;
		}
	}

	function canAppeal()
	{
		global $user;
		global $ch;
		global $messby;
		global $appstatus;
		global $mod_exists;

		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You must be <a class = \"bg0\" href = \"login.php\">logged in</a> to view this page</pre>\n";
			return FALSE;
		}
		else if(!$mod_exists)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid moderation id.</pre>\n";
			return FALSE;
		}
		else if($user->getUserName() != $messby)
		{
			echo "<pre class = \"small".$ch."\">Error: This is not your moderation.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() == -5 || $user->getUserLevel() == -2)
		{
			echo "<pre class = \"small".$ch."\">Error: Banned and Denied accounts cannot appeal moderations.</pre>";
			return FALSE;
		}
		else if($user->getUserLevel() == 1)
		{
			echo "<pre class = \"small".$ch."\">Error: Locked accounts cannot appeal moderations.</pre>";
			return FALSE;
		}
		else if($appstatus > 0)
		{
			echo "<pre class = \"small".$ch."\">Error: This moderation has already been appealed.</pre>";
			return FALSE;
		}
		return TRUE;
	}

	function handleContent()
	{
		global $user;
		global $ch;
		global $modby;
		global $modid;

		$appsent = $_POST["appsent"];

		if($appsent)
		{
			$apptext = $_POST["appeal"];

			if(!isValidLength($apptext, 2000, 200))
			{
				echo "<pre class = \"small".$ch."\">Error: Your appeal exceeded 2000 characters or had a word over 200 characters.</pre>";
				appealForm();
			}
			else
			{
				$apptext = formatString($apptext);
				$username = $user->getUserName();
				$sql = "INSERT INTO appeals VALUES(0, '$username', '$modby', ".$modid.", '$apptext')";
				mysql_query($sql);
				$sql = "UPDATE moderations SET contested = 1 WHERE modid = ".$modid;
				mysql_query($sql);

				echo "<pre class = \"small".$ch."\">Your appeal has been sent and is waiting review by a moderator.  Return <a class = \"bg".
					$ch."\" href = \"user.php\">here</a></pre>";
			}
		}
		else
		{
			appealForm();
		}
	}

	function appealForm()
	{
		global $ch;
		global $modid;

		echo "<form action = \"appeal.php?modid=".$modid."\" method = \"post\">\n";
		echo "<pre class = \"small".$ch."\">Enter a reason for why your moderation should be overturned. ".
			"No HTML and a max of 2000 characters.\nAlso, don't do anything stupid or your appeal will just be ignored.</pre>\n";
		echo "<textarea rows = \"8\" cols = \"70\" name = \"appeal\" wrap = \"soft\"></textarea><br />\n";
		echo "<input type = \"submit\" name = \"appsent\" value = \"Appeal Moderation\">";
		echo "</form>\n";
	}