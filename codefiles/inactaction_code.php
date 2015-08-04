<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	
	$inactive;
	$user;
	$inactid;
	$ch;
	
	function initialize()
	{
		createConnection();
		global $inactive;
		global $user;
		global $inactid;
		global $ch;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$inactid = intval($_GET["user"]);
		$inactive = getUserById($inactid);
	}
	
	function canView()
	{
		global $user;
		global $inactive;
		global $ch;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a>.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < MOD_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".MOD_LEVEL." or higher to view this page.</pre>\n";
			return FALSE;
		}
		else if($inactive)
		{
			echo "<pre class = \"small".$ch."\">Error: Invalid user id.</pre>\n";
			return FALSE;
		}
		else if($inactive->getUserLevel() != INACTIVE)
		{
			echo "<pre class = \"small".$ch."\">Error: This user is not inactive.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function displayPage()
	{
		global $inactive;
		global $ch;
		
		$allow = $_POST["allow"];
		$deny = $_POST["deny"];
		$provisional = $_POST["provisional"];
		
		if($allow)
		{
			activateUser($inactive);
			echo "<pre class = \"small".$ch."\">The user has been activated.  Return <a class = \"bg".$ch."\" href = \"index.php\">here</a>.</pre>\n";
		}
		else if($provisional)
		{
			setProvisional($inactive);
			echo "<pre class = \"small".$ch."\">The user has been activated as a provisional.  Return <a class = \"bg".$ch."\" href = \"index.php\">here</a>.</pre>\n";
		}
		else if($deny)
		{
			denyActivation($inactive);
			echo "<pre class = \"small".$ch."\">The user has been denied activation.  Return <a class = \"bg".$ch."\" href = \"index.php\">here</a>.</pre>\n";
		}
		else
		{
			activationForm();
		}
	}
	
	function activateUser($inactive)
	{
		$inactive->setUserLevel(BASE_LVL);
		$inactive->setAppoints(30);
		$inactive->setBiscuits(50);
		$sysmess = "Welcome to AppletLand MessageBoards.  Your account has been activated as a full user.";
		$inactname = $inactive->getUserName();
		$sql = "INSERT INTO systemmess VALUES(0, 'Site Staff', '$inactname', '$sysmess')";
		mysql_query($sql);
	}
	
	function setProvisional($inactive)
	{
		$inactive->setUserLevel(PROVISIONAL);
		$inactive->setUnwarntime(time() + 60 * 60 * 24 * 3);
		$sysmess = "Welcome to AppletLand MessageBoards.  Your account has been set as a provisional.  You may still post but at a restricted rate.";
		$inactname = $inactive->getUserName();
		$sql = "INSERT INTO systemmess VALUES(0, 'Site Staff', '$inactname', '$sysmess')";
		mysql_query($sql);
	}
	
	function denyActivation($inactive)
	{
		$inactive->setUserLevel(DENIED);
		$sysmess = "Your account has been denied activation.  This is either due to past abuse or an offesive/disruptive username.";
		$inactname = $inactive->getUserName();
		$sql = "INSERT INTO systemmess VALUES(0, 'Site Staff', '$inactname', '$sysmess')";
		mysql_query($sql);
	}
	
	function activationForm()
	{
		global $inactid;
		global $inactive;
		global $ch;
		
		echo "<form action = \"inactaction.php?user=".$inactid."\" method = \"post\">\n";
		echo "<a class = \"bg".$ch."\" href = \"whois.php?user=".$inactid."\">".$inactive->getUserName()."</a><br><br />\n";
		echo "<input type = \"submit\" name = \"allow\" value = \"Full Activation\">\n";
		echo "<input type = \"submit\" name = \"provisional\" value = \"Activate as Provisional\">\n";
		echo "<input type = \"submit\" name = \"deny\" value = \"Deny Activation\">\n";
		echo "</form>";
	}