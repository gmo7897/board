<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	
	$ch;
	$user;
	$suspendedid;
	$suspendeduser;
	$modaction;
	
	function initialize()
	{
		createConnection();
		global $ch;
		global $user;
		global $suspendedid;
		global $suspendeduser;
		global $modaction;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$suspendedid = intval($_GET["user"]);
		$suspendeduser = getUserById($suspendedid);
		$suspendedname = $suspendeduser->getUserName();
		
		$sql = "SELECT modaction FROM moderations WHERE modaction LIKE '% Suspend User' AND messby = '$suspendedname'";
		$result = mysql_query($sql);
		$actionrdr = mysql_fetch_array($result);
		
		if($actionrdr)
		{
			$modaction = $actionrdr["modaction"];
			if(strncmp($modaction, "Delete Topic", 12) == 0)
			{
				$modaction = "Delete Topic";
			}
			else if(strncmp($modaction, "Delete Message", 14) == 0)
			{
				$modaction = "Delete Message";
			}
		}
	}
	
	function canView()
	{
		global $ch;
		global $user;
		global $suspendeduser;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a>.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < ELITE_MOD_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".ELITE_MOD_LEVEL." or higher to view this page.</pre>\n";
			return FALSE;
		}
		else if(!$suspendeduser)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid user id.</pre>\n";
			return FALSE;
		}
		else if($suspendeduser->getUserLevel() != SUSPENDED)
		{
			echo "<pre class = \"small" + ch + "\">Error: This user is not suspended.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function dealWithSuspended()
	{
		global $ch;
		global $suspendeduser;
		global $modaction;
		$actchoice = intval($_POST["choice"]);
		$suspendedname = $suspendeduser->getUserName();
		
		switch($actchoice)
		{
			case 0:
				$suspendeduser->setUserLevel(BANNED);
				$modaction = $modaction." Ban User";
				break;
			case 1:
				banUserMap();
				$modaction = $modaction." Ban Usermap";
				break;
			case 2:
				$suspendeduser->setUserLevel(PROBATION);
				$suspendeduser->setUnwarnTime(time() + 60 * 60 * 25 * 3);
				$suspendeduser->setAppoints($suspendeduser->getAppoints() - 10);
				$suspendeduser->setBiscuits($suspendeduser->getBiscuits() - 25);
				$modaction = $modaction." Set Probation";
				break;
			case 3:
				$suspendeduser->setUserLevel(TIMED_SUS);
				$suspendeduser->setUnwarnTime(time() + 60 * 60 * 24 * 7);
				$suspendeduser->setAppoints($suspendeduser->getAppoints() - 15);
				$suspendeduser->setBiscuits($suspendeduser->getBiscuits() - 40);
				$modaction = $modaction." Set 7-Day Suspension";
				break;
			case 4:
				$suspendeduser->setUserLevel(TIMED_SUS);
				$suspendeduser->setUnwarnTime(time() + 60 * 60 * 24 * 30);
				$suspendeduser->setAppoints($suspendeduser->getAppoints() - 50);
				$suspendeduser->setBiscuits($suspendeduser->getBiscuits() - 100);
				$modaction = $modaction." Set 30-Day Suspension";
				break;
			case 5:
				$suspendeduser->setUserLevel(BASE_LVL);
				break;
			case 6:
				$suspendeduser->setUserLevel(LOCKED);
				$modaction = $modaction." Lock Account";
				break;
		}
		"UPDATE moderations SET modaction = '$modaction', contested = 3 WHERE modaction LIKE '%Suspend User' AND".
			" messby = '$suspendedname'";
		mysql_query($sql);
		echo "<pre class = \"small".$ch."\">The action has been taken.  Return <a class = \"bg".$ch."\" href = \"index.php\">here</a>";
	}
	
	function banUserMap()
	{
		global $suspendeduser;
		$suspendedname = $suspendeduser->getUserName();
		$namelist;
		
		$sql = "SELECT namelist FROM usermap WHERE mapowner = '$suspendedname'";
		$result = mysql_query($sql);
		$namelistrdr = mysql_fetch_array($result);
		
		if($namelistrdr)
		{
			$liststring = $namelistrdr["namelist"];
			$namelist = explode(", ", $liststring);
			$numnames = count($namelist);
			
			for($i = 0; $i < $numnames; $i++)
			{
				$currusr = $namelist[$i];
				$sql = "UPDATE users SET userlevel = ".BANNED." WHERE username = '$currusr'";
				mysql_query($sql);
			}
		}
	}