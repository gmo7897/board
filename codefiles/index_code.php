<?php
	include("includes/ConnectionInfo.inc");
	include("includes/SiteTools.inc");
	include("includes/BoardUser.inc");
	include("includes/InputFormatter.inc");

	$ch;
	$user;
	$level = 0;
	$timezone = 0;
	$hassysnote = FALSE;
	$bupdate = FALSE;

	function initialize()
	{
		createConnection();

		global $ch;
		global $user;
		global $level;
		global $timezone;
		global $hassysnote;
		global $bupdate;

		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$username = "";
		if($user)
		{
			$username = $user->getUserName();
			$user->newActivity($_SERVER["REMOTE_ADDR"]);
		}
		
		if($user != NULL)
		{
			$level = $user->getUserLevel();
			$timezone = $user->getTimeZone();
			$sql = "SELECT messid FROM systemmess WHERE messto = '$username'";
			$result = mysql_query($sql);
			$notereader = mysql_fetch_array($result);
			if($notereader)
			{
				$hassysnote = TRUE;
			}
			if($user->getUserLevel() >= ADMIN_LEVEL)
			{
				$yesterday = time() - (60 * 60 * 24);
				$sql = "SELECT COUNT(*) FROM users WHERE username = '$username' AND updatetime < ".$yesterday;
				$result = mysql_query($sql);
				$updaterdr = mysql_fetch_row($result);
				if($updaterdr[0])
				{
					$bupdate = TRUE;
				}
			}
		}
	}

	function displayPage()
	{
		global $user;
		global $level;
		global $hassysnote;
		global $ch;
		global $bupdate;
		
		menuBar0($user);
		modBar($user);

		if($hassysnote)
		{
			echo "<table class = \"note".$ch."\"><tr class = \"mencatbd".$ch."\"><td class = \"note".$ch."\">You".
				" have one or more unread <a class = \"note".$ch."\" href = \"viewnotes.php\">notifications</a></td></tr></table>";
		}
		if($bupdate)
		{
			echo "<table class = \"note".$ch."\"><tr class = \"mencatbd".$ch."\"><td class = \"note".$ch."\">The boards have not been updated".
				" in over 24 hours.  Update them <a class = \"note".$ch."\" href = \"updater.php\">here</a>.</td></tr></table>\n";
		}

		echo "<table class = \"cat".$ch."\">\n";
		echo "<tr class = \"mencatbd".$ch."\"><td width = \"50%\" class = \"cat".$ch."\">".
			"Board Name</td><td width = \"12%\" class = \"cat".$ch."\">Topics</td><td width = \"13%\" ".
			"class = \"cat".$ch."\">Messages</td><td width = \"25%\" class = \"cat".$ch."\">Last Post</td></tr>\n";

		displayCategories();
	}

	function displayCategories()
	{
		global $ch;

		$sql = "SELECT * FROM catagories ORDER BY catplacement";
		$result = mysql_query($sql);
		$catarr = mysql_fetch_array($result);

		while($catarr)
		{
			$catid = $catarr["catid"];
			$catname = $catarr["catname"];

			echo "<tr class = \"mencatbd".$ch."\"><td class = \"cat".
				$ch."\" colspan = \"4\">".$catname."</td></tr>";
			displayBoards($catid);
			$catarr = mysql_fetch_array($result);
		}
	}

	function displayBoards($catid)
	{
		global $ch;
		global $level;
		global $timezone;

		$bsql = "SELECT * FROM boards WHERE catnum = ".$catid." AND boardlevel <= ".$level;
		$bresult = mysql_query($bsql);
		$brdr = mysql_fetch_array($bresult);

		while($brdr)
		{
			$bdid = $brdr["boardid"];
			$bname = $brdr["boardname"];
			$bcap = $brdr["boardextrainfo"];
			$topcount = $brdr["topcount"];
			$messcount = $brdr["messcount"];
			$lastpost = "No Posts";
			
			$tsql = "SELECT lastpost FROM topics WHERE boardnum = ".$bdid." ORDER BY lastpost DESC";
			$tresult = mysql_query($tsql);
			$trdr = mysql_fetch_array($tresult);
			if($trdr)
			{
				$lastpost = formatTimeZone($trdr["lastpost"], $timezone);
			}

			echo "<tr class = \"mencatbd".$ch."\"><td width = \"50%\" class = \"board".$ch.
				"\"><a class = \"board".$ch."\" href = \"topics.php?board=".$bdid."\">".$bname."</a><br />".
				"<font size = \"-2\">".$bcap."</font></td><td width = \"12%\" class = \"board".$ch."\">".$topcount.
				"</td><td width = \"13%\" class = \"board".$ch."\">".$messcount."</td><td width = \"25%\" class = ".
				"\"board".$ch."\">".$lastpost."</td></tr>";
			$brdr = mysql_fetch_array($bresult);
		}
	}