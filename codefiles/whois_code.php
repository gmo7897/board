<?php
	include("includes/ConnectionInfo.inc");
	include("includes/ShopItem.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");
	
	$ch;
	$viewedid;
	$board;
	$topic;
	$message;
	$blevel;
	$tactive;
	$user;
	$vieweduser;
	$valid_board = TRUE;
	$valid_topic = TRUE;
	
	function initialize()
	{
		createConnection();
		global $ch;
		global $viewedid;
		global $board;
		global $topic;
		global $message;
		global $blevel;
		global $tactive;
		global $user;
		global $vieweduser;
		global $valid_board;
		global $valid_topic;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$viewedid = intval($_GET["user"]);
		$vieweduser = getUserById($viewedid);
		$board = intval($_GET["board"]);
		$topic = intval($_GET["topic"]);
		$message = intval($_GET["message"]);
		
		$sql = "SELECT boardlevel FROM boards WHERE boardid = ".$board;
		$result = mysql_query($sql);
		$blvlrdr = mysql_fetch_array($result);
		if($blvlrdr)
		{
			$blevel = $blvlrdr["blevel"];
		}
		else
		{
			$valid_board = FALSE;
		}
		
		$sql = "SELECT topicactive FROM topics WHERE topicid = ".$topic." AND boardnum = ".$board;
		$result = mysql_query($sql);
		$tactrdr = mysql_fetch_array($result);
		if($tactrdr)
		{
			$tactive = $tactrdr["topicactive"];
		}
		else
		{
			$valid_topic = FALSE;
		}
	}
	
	function canView()
	{
		global $ch;
		global $user;
		global $vieweduser;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a>.</pre>\n";
			return FALSE;
		}
		else if(!$vieweduser)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid user id.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function whoisPage()
	{
		global $user;
		global $vieweduser;
		global $viewedid;
		global $ch;
		global $board;
		global $blevel;
		global $topic;
		global $tactive;
		global $message;
		global $valid_board;
		global $valid_topic;
		
		$user->newActivity($_SERVER["REMOTE_ADDR"]);
		
		$uname = $vieweduser->getUserName();
		$regdate = formatTimeZone($vieweduser->getRegDate(), $user->getTimeZone());
		$lastdate = formatTimeZone($vieweduser->getLastLoginDate(), $user->getTimeZone());
		$email = $vieweduser->getPrivEmail();
		$pubemail = $vieweduser->getPubEmail();
		$signature = $vieweduser->getSignature();
		$regip = $vieweduser->getRegisteredIP();
		$lastip = $vieweduser->getLastUsedIP();
		$mess = $vieweduser->getMessages();
		$messpoints = $vieweduser->getAppoints();
		$biscuits = $vieweduser->getBiscuits();
		$ulevel = $vieweduser->getUserLevel();
		$levcap;
		$owneditems = getOwnedItems($vieweduser);
		$numowneditems = count($owneditems);
		
		$sql = "SELECT levelname FROM levels WHERE levelnum = ".$ulevel;
		$result = mysql_query($sql);
		$lvlrdr = mysql_fetch_array($result);
		if($lvlrdr)
		{
			$levcap = $lvlrdr["levelname"];
		}
		else
		{
			$levcap = "<b>".$ulevel.": ???</b><br>An unknown userlevel appointed by an admin.";
		}
		
				echo "<center><pre class = \"big".$ch."\">Whois Page for ".$uname."</pre></center>\n";
				
		if($valid_board && $valid_topic && $message)
		{
			menuBar4($user, $blevel, $board, $topic);
		}
		else if($valid_board && $valid_topic && !$message)
		{
			menuBar3($user, $blevel, $board, $topic, $topicactive);
		}
		else if($valid_board && !$valid_topic)
		{
			menuBar2($user, $blevel, $board);
		}
		else
		{
			menuBar0($user);
		}
		modBar($user);
		
		echo "<table class = \"if".$ch."\"><tr><td width = \"20%\" class = \"i1f".$ch."\">Userid</td>".
			"<td width = \"80%\" class = \"i1f".$ch."\">".$viewedid."</td></tr><tr><td width = \"20%\" class = \"i2f".$ch."\">".
			"Username</td><td width = \"80%\" class = \"i2f".$ch."\">".$uname ."</td></tr><tr><td width = \"20%\" ".
			"class = \"i1f".$ch."\">User Level</td><td width = \"80%\" class = \"i1f".$ch."\">".$levcap."</td></tr>".
			"<tr><td width = \"20%\" class = \"i2f".$ch."\">Registration Date</td><td width = \"80%\" class = \"i2f".$ch."\">".
			$regdate."</td></tr><tr><td width = \"20%\" class = \"i1f".$ch."\">Last Login Date</td><td width = \"80%\" ".
			"class = \"i1f".$ch."\">".$lastdate."</tr><tr><td width = \"20%\" class = \"i2f".$ch.
			"\">Public Email</td><td width = \"80%\" class = \"i2f".$ch."\">".$pubemail."</td></tr><tr><td width = \"20%\" ".
			"class = \"i1f".$ch."\">Signature</td><td width = \"80%\" class = \"i1f".$ch."\">".$signature."</td></tr>".
			"<tr><td width = \"20%\" class = \"i2f".$ch."\">Posted Messages</td><td width = \"80%\" class = \"i2f".$ch.
			"\">".$mess."</td></tr><tr><td width = \"20%\" class = \"i1f".$ch."\">".RANK_POINTS."</td><td width = \"80%\" ".
			"class = \"i1f".$ch."\">".$messpoints."</td></tr><tr><td width = \"20%\" class = \"i2f".$ch."\">".CURRENCY."</td>".
			"<td width = \"80%\" class = \"i2f".$ch."\">".$biscuits."</td></tr><tr><td width = \"20%\" class = \"i1f".$ch."\">Shop Items</td>".
			"<td width = \"80%\" class = \"i1f".$ch."\">";
		for($i = 0; $i < $numowneditems; $i++)
		{
			$curritem = $owneditems[$i];
			echo "<a class = \"board".$ch."\" href = \"goldshop.php?item=".$curritem->getItemId()."\">".
				$curritem->getItemName()."</a>, ";
		}
		echo "</td></tr>";
		if($user->getUserLevel() >= MOD_LEVEL)
		{
			echo "<tr><td width = \"20%\" class = \"i2f".$ch."\">Private Email</td><td width = \"80%\" class = ".
				"\"i2f".$ch."\">".$email."</td></tr><tr><td width = \"20%\" class = \"i1f".$ch."\">Registered IP</td>".
				"<td width = \"80%\" class = \"i1f".$ch."\">".$regip."</td></tr><tr><td width = \"20%\" class = \"i2f".$ch.
				"\">Last Used IP</td><td width = \"80%\" class = \"i2f".$ch."\">".$lastip."</td></tr>";
		}
		echo "</table><br />\n";
		
		if($user->getUserLevel() >= ADMIN_LEVEL)
		{
			echo "<a class = \"bg".$ch."\" href = \"useredit.php?user=".$viewedid."\">Edit User</a><br />";
		}
		if($user->getUserLevel() >= MOD_LEVEL)
		{
			echo "<a class = \"bg".$ch."\" href = \"modhist.php?user=".$viewedid."\">View Moderation History</a><br />";
			echo "<a class = \"bg".$ch."\" href = \"posthist.php?user=".$viewedid."\">View Posted Messages</a><br />";
			echo "<a class = \"bg".$ch ."\" href = \"viewmap.php?user=".$viewedid."\">View Usermap</a>";
		}
	}
	
	function getOwnedItems($u)
	{
		$owneditems = array();
		
		$sql = "SELECT itemname FROM shopitems WHERE owners LIKE '%".$u->getUserName()."%'";
		$result = mysql_query($sql);
		$itemrdr = mysql_fetch_array($result);
		while($itemrdr)
		{
			$itemname = $itemrdr["itemname"];
			$curritem = getShopItembyName($itemname);
			if($curritem->isOwner($u))
			{
				$owneditems[] = $curritem;
			}
			$itemrdr = mysql_fetch_array($result);
		}
		return $owneditems;
	}