<?
	include("includes/SiteTools.inc");
	include("includes/ConnectionInfo.inc");
	include("includes/ShopItem.inc");
	include("includes/InputFormatter.inc");

	$appid;
	$modnum = 0;
	$modaction = NULL;
	$moddedmess = NULL;
	$modreason = NULL;
	$appreason = NULL;
	$appealer = NULL;
	$user = NULL;
	$ch;
	$appealvalid = TRUE;

	function initialize()
	{
		createConnection();

		global $appid;
		global $modnum;
		global $modaction;
		global $moddedmess;
		global $modreason;
		global $appreason;
		global $appealer;
		global $user;
		global $ch;
		global $appealvalid;

		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);

		$appid = intval($_GET["appealid"]);
		$sql = "SELECT appeals.appealby, appeals.modnum, appeals.reason, users.password FROM appeals, ".
			"users WHERE appeals.appealid = ".$appid." AND users.username = (SELECT appealby FROM appeals WHERE appealid = ".$appid.")";
		$result = mysql_query($sql);
		$apprdr = mysql_fetch_row($result);
		if($apprdr)
		{
			$username = $apprdr[0];
			$modnum = $apprdr[1];
			$appreason = $apprdr[2];
			$password = $apprdr[3];
			
			$appealer = getUser($username, $password);
		}
		else
		{
			$appealvalid = FALSE;
		}

		$sql = "SELECT modaction, message, reason FROM moderations WHERE modid = ".$modnum;
		$result = mysql_query($sql);
		$actionrdr = mysql_fetch_array($result);
		if($actionrdr)
		{
			$modaction = $actionrdr["modaction"];
			$moddedmess = $actionrdr["message"];
			$modreason = $actionrdr["reason"];
		}
	}

	function canViewPage()
	{
		global $user;
		global $ch;
		global $appealvalid;

		if($user == NULL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be <a class = \"bg".$ch.
				"\" href = \"login.aspx\">logged in</a> to view this page</pre>.";
			return FALSE;
		}
		else if($user->getUserLevel() < MOD_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".MOD_LEVEL.
				" or higher to view this page.";
			return FALSE;
		}
		else if(!$appealvalid)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid appeal id.</pre>";
			return FALSE;
		}
		return TRUE;
	}

	function displayPage()
	{
		$response;
		$overturn = $_POST["overturn"];
		$uphold = $_POST["uphold"];

		if($uphold)
		{
			$response = "A moderator has upheld your moderation and responded with the following:<br />".formatString($_POST["comments"]);
			upholdModeration($response);
		}
		else if($overturn)
		{
			$response = "A moderator has overturned your moderation and responded with the following:<br />".formatString($_POST["comments"]);
			overturnModeration($response);
		}
		else
		{
			displayAppealForm();
		}
	}

	function upholdModeration($response)
	{
		global $modnum;
		global $ch;
		global $appealer;
		global $appid;
		$appealername = $appealer->getUserName();
		
		$sql = "UPDATE moderations SET contested = 2 WHERE modid = ".$modnum;
		mysql_query($sql);
		$sql = "INSERT INTO systemmess VALUES(0, 'Site Staff', '$appealername', '$response')";
		mysql_query($sql);
		$sql = "DELETE FROM appeals WHERE appealid = ".$appid;
		mysql_query($sql);

		echo "<pre class = \"small".$ch."\">The moderation has been upheld.  Return <a class = \"bg".$ch."\" href = \"index.php\">here</a>.</pre>\n";
	}

	function overturnModeration($response)
	{
		global $modaction;
		global $ch;
		global $appealer;
		global $moddedmess;
		global $modnum;
		global $appid;
		$appealername = $appealer->getUserName();

		if(substr_compare($modaction, "Delete Message", 0, 14) == 0)
		{
			$sql = "SELECT mesnum FROM moderations WHERE modid = ".$modnum;
			$result = mysql_query($sql);
			$messrdr = mysql_fetch_array($result);
			$mesnum = $messrdr["mesnum"];

			$sql = "UPDATE messages SET messagestuff = '$moddedmess' WHERE messageid = ".
				$mesnum;
			mysql_query($sql);
		}
		else if(substr_compare($modaction, "Delete Topic", 0, 12) == 0)
		{
			$messcount = 0;
			$topicnum = 0;
			$boardnum = 0;

			$sql = "SELECT moderations.boardnum, messages.topicnum, topics.posts FROM moderations, messages, topics".
				" WHERE messages.messageid = (SELECT mesnum FROM moderations WHERE modid = ".
				$modnum.") AND moderations.modid = ".$modnum." AND topics.topicid = messages.topicnum";
			$result = mysql_query($sql);
			$topinfordr = mysql_fetch_row($result);

			$boardnum = $topinfordr[1];
			$topicnum = $topinfordr[2];
			$messcount = $topinfordr[3];

			$sql = "UPDATE topics SET boardnum = ".$boardnum." WHERE topicid = ".$topicnum;
			mysql_query($sql);
			$sql = "UPDATE messages SET boardnum = ".$boardnum." WHERE topicnum = ".$topicnum;
			mysql_query($sql);
			$sql = "UPDATE boards SET topcount = topcount + 1, messcount = messcount + ".$messcount.
				" WHERE boardid = ".$boardnum;
			mysql_query($sql);
			$sql = "UPDATE boards SET topcount = topcount - 1, messcount = messcount - ".$messcount.
				" WHERE boardid = 0";
			mysql_query($sql);
		}

		if(substr_compare($modaction, "Remove 5 points", -15) == 0)
		{
			$appealer->setAppoints($appealer->getAppoints() + 5);
		}
		else if(substr_compare($modaction, "Set Probation", -13) == 0)
		{
			$appealer->setAppoints($appealer->getAppoints() + 10);
			$vip_card = getShopItembyName("VIP Card");
			if($vip_card != NULL && $vip_card->isOwner($appealer))
			{
				$appealer->setUserLevel(60);
			}
			else if($appealer->getUserLevel() == 3)
			{
				$appealer->setUserLevel(20);
			}
		}
		else if(substr_compare($modaction, "Suspend User", -12) == 0)
		{
			$vip_card = getShopItembyName("VIP Card");
			if($vip_card != NULL && $vip_card->isOwner($appealer))
			{
				$appealer->setUserLevel(60);
			}
			else if($appealer->getUserLevel() == 3)
			{
				$appealer->setUserLevel(20);
			}
		}

		$sql = "INSERT INTO systemmess VALUES(0, 'Site Staff', '$appealername', '$response')";
		mysql_query($sql);
		$sql = "DELETE FROM appeals WHERE appealid = ".$appid;
		mysql_query($sql);
		$sql = "DELETE FROM moderations WHERE modid = ".$modnum;
		mysql_query($sql);

		echo "<pre class = \"small".$ch."\">The moderation has been overturned.  Return <a class = \"bg".$ch."\" href = \"index.php\">here</a>.</pre>\n";
	}

	function displayAppealForm()
	{
		global $appid;
		global $ch;
		global $modreason;
		global $modaction;
		global $moddedmess;
		global $appreason;

		echo "<form action = \"appaction.php?appealid=".$appid."\" method = \"post\">\n";
		echo "<table class = messhead".$ch."><tr><td class = messhead".$ch."><b>Mod Reason:</b> ".$modreason.
			" | <b>Mod Action:</b> ".$modaction."</td></tr><tr><td class = messlst".$ch.">".$moddedmess.
			"</td></tr></table>\n";
		echo "<pre class = small".$ch.">Reason for Appeal:<br>".$appreason."</pre>\n";
		echo "<input type = \"submit\" name = \"overturn\" value = \"Overturn Moderation\">\n";
		echo "<input type = \"submit\" name = \"uphold\" value = \"Uphold Moderation\">\n";
		echo "<pre class = \"small".$ch."\">Comments: <textarea cols = \"70\" rows = \"3\" wrap = \"soft\" name = \"comments\"></textarea></pre>\n";
		echo "</form>\n";
	}
?>