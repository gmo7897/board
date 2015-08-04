<?php
	include("includes/ConnectionInfo.inc");
	include("includes/ShopItem.inc");
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
		global $user;

		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a></pre>\n";
			return FALSE;
		}
		return TRUE;
	}

	function userPage()
	{
		global $ch;
		global $user;

		$user->newActivity($_SERVER["REMOTE_ADDR"]);
		$username = $user->getUserName();
		$userid = $user->getUserId();
		$level = $user->getUserLevel();
		$privemail = $user->getPrivEmail();
		$pubemail = $user->getPubEmail();
		$signature = $user->getSignature();
		$appoints = $user->getAppoints();
		$messages = $user->getMessages();
		$biscuits = $user->getBiscuits();
		$regdate = formatTimeZone($user->getRegDate(), $user->getTimeZone());
		$lastlogin = formatTimeZone($user->getLastLoginDate(), $user->getTimeZone());
		$levelcaption;
		$owneditems = getOwnedItems();
		$numowneditems = count($owneditems);

		$sql = "SELECT levelname FROM levels WHERE levelnum = ".$level;
		$result = mysql_query($sql);
		$lvlrdr = mysql_fetch_array($result);
		if($lvlrdr)
		{
			$levelcaption = $lvlrdr["levelname"];
		}
		else
		{
			$levelcaption = "<b>".$level.": ???</b><br />An unknown userlevel appointed by an admin.";
		}

		echo "<center><pre class = \"big".$ch."\">User Information Page for ".$username."</pre></center>\n";
			menuBar1($user);
			modBar($user);

		echo "<table class = \"if".$ch."\"><tr><td width = \"20%\" class = \"i1f".$ch."\">Userid</td>".
			"<td width = \"80%\" class = \"i1f".$ch."\">".$userid."</td></tr><tr><td width = \"20%\" class = \"i2f".$ch."\">".
			"Username</td><td width = \"80%\" class = \"i2f".$ch."\">".$username."</td></tr><tr><td width = \"20%\" ".
			"class = \"i1f".$ch."\">User Level</td><td width = \"80%\" class = \"i1f".$ch."\">".$levelcaption."</td></tr>".
			"<tr><td width = \"20%\" class = \"i2f".$ch."\">Registration Date</td><td width = \"80%\" class = \"i2f".$ch."\">".
			$regdate."</td></tr><tr><td width = \"20%\" class = \"i1f".$ch."\">Last Login Date</td><td width = \"80%\" ".
			"class = \"i1f".$ch."\">".$lastlogin."</tr><tr><td width = \"20%\" class = \"i2f".$ch."\">Private Email</td>".
			"<td width = \"80%\" class = \"i2f".$ch."\">".$privemail."</td></tr><tr><td width = \"20%\" class = \"i1f".$ch.
			"\">Public Email</td><td width = \"80%\" class = \"i1f".$ch."\">".$pubemail."</td></tr><tr><td width = \"20%\" ".
			"class = \"i2f".$ch."\">Signature</td><td width = \"80%\" class = \"i2f".$ch."\">".$signature."</td></tr>".
			"<tr><td width = \"20%\" class = \"i1f".$ch."\">Posted Messages</td><td width = \"80%\" class = \"i1f".$ch.
			"\">".$messages."</td></tr><tr><td width = \"20%\" class = \"i2f".$ch."\">".RANK_POINTS."</td><td width = \"80%\" ".
			"class = \"i2f".$ch."\">".$appoints."</td></tr><tr><td width = \"20%\" class = \"i1f".$ch."\">".CURRENCY."</td>".
			"<td width = \"80%\" class = \"i1f".$ch."\">".$biscuits."</td></tr><tr><td width = \"20%\" class = \"i2f".$ch.
			"\">Owned Items</td><td width = \"80%\" class = \"i2f".$ch."\">";
		for($i = 0; $i<$numowneditems; $i++)
		{
			$curritem = $owneditems[$i];
			echo "<a class = \"board".$ch."\" href = \"goldshop.php?item=".$curritem->getItemId()."\">".
				$curritem->getItemName()."</a>, ";
		}
		echo "</td></tr></table>";

		if ($level != LOCKED)
		{
			echo "<center><a class = \"bg".$ch."\" href = \"preferences.php\">Edit Preferences</a></center><br />";
		}
		echo "<center><a class = \"bg".$ch."\" href = \"viewposts.php\">View Posted Messages</a></center><br />";
		echo "<center><a class = \"bg".$ch."\" href = \"viewmods.php\">View Moderations</a></center><br />";
		echo "<center><a class = \"bg".$ch."\" href = \"banned.php\">Banned User List</a></center><br />";
		echo "<center><a class = \"bg".$ch."\" href = \"highpoints.php\">".RANK_POINTS." Leaders</a></center><br />";
		if ($level >= BASE_LVL)
		{
			echo "<center><a class = \"bg".$ch."\" href = \"goldshop.php\">".CURRENCY." Shop</a></center><br />";
		}
		if ($level >= MIN_APPOINT_LVL)
		{
			echo "<center><a class = \"bg".$ch."\" href = \"close.php\">Close Account</a></center><br />";
		}
		else if ($level == PENDING_CLOSE)
		{
			echo "<center><a class = \"bg".$ch."\" href = \"close.php\">Restore Account</a></center><br />";
		}
	}

	function getOwnedItems()
	{
		global $user;

		$items = array();

		$sql = "SELECT itemname FROM shopitems WHERE owners LIKE '%".$user->getUserName()."%'";
		$result = mysql_query($sql);
		$namerdr = mysql_fetch_array($result);

		while($namerdr)
		{
			$items[] = getShopItembyName($namerdr["itemname"]);
			$namerdr = mysql_fetch_array($result);
		}
		return $items;
	}