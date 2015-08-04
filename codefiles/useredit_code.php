<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");
	
	$ch;
	$editedid;
	$user;
	$edited;
	
	function initialize()
	{
		createConnection();
		global $ch;
		global $editedid;
		global $user;
		global $edited;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$editedid = intval($_GET["user"]);
		$edited = getUserById($editedid);
	}
	
	function canView()
	{
		global $ch;
		global $user;
		global $edited;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a>.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < ADMIN_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".ADMIN_LEVEL." or higher to view this page.</pre>\n";
			return FALSE;
		}
		else if(!$edited)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid user id.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function editUser()
	{
		global $edited;
		global $ch;
		global $editedid;
		
		$newlevel = intval($_POST["userlevel"]);
		$newappoints = intval($_POST["appoints"]);
		$newbiscuits = intval($_POST["biscuits"]);
		$newsig = formatString($_POST["signature"]);
		$newsig = substr($newsig, 0, 210);
		
		$edited->setUserLevel($newlevel);
		$edited->setAppoints($newappoints);
		$edited->setBiscuits($newbiscuits);
		$edited->setSignature($newsig);
		
		echo "<pre class = \"small".$ch."\">The user has been edited.  Return <a class = \"bg".$ch.
			"\" href = \"whois.php?user=".$editedid."\">here</a>.</pre>\n";
	}