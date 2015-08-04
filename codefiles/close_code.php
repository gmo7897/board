<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	
	$user;
	$ch;
	
	function initialize()
	{
		createConnection();
		global $user;
		global $ch;
		
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$ch = loadTheme();
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
		else if($user->getUserLevel() == CLOSED)
		{
			echo "<pre class = \"small".$ch."\">Error: Closed accounts cannot be restored.</pre>";
			return FALSE;
		}
		else if($user->getUserLevel() < MIN_APPOINT_LVL && $user->getUserLevel() != PENDING_CLOSE)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".MIN_APPOINT_LVL." or higher to close your account.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function showPage()
	{
		global $ch;
		global $user;
		
		$action = $_POST["action"];
		if($action)
		{
			$username = $_POST["username"];
			$password = $_POST["password"];
			
			if($username != $user->getUserName() || $password != $user->getPassword())
			{
				echo "<pre class = \"small".$ch."\">Error: Username or password does not match.</pre>";
				closeAccountForm();
			}
			else
			{
				if($user->getUserLevel() == PENDING_CLOSE)
				{
					$user->setUserLevel(20);
					echo "<pre class = \"small".$ch."\">Your account has been restored.  Return <a class = \"bg".$ch."\" href = \"user.php\">here</a>.</pre>\n";
				}
				else
				{
					$user->setUserLevel(PENDING_CLOSE);
					$user->setUnwarnTime(time() + 60 * 60 * 24 * 2);
					echo "<pre class = \"small".$ch."\">Your account is now pending closure.  It will be closed in 48 - 72 hours.  Return ".
						"<a class = \"bg".$ch."\" href = \"user.php\">here</a>.</pre>\n";
				}
			}
		}
		else
		{
			closeAccountForm();
		}
	}
	
	function closeAccountForm()
	{
		global $user;
		global $ch;
		
		$actionval;
		if($user->getUserLevel() == PENDING_CLOSE) $actionval = "Restore Account";
		else $actionval = "Close Account";
		
		echo "<form action = \"close.php\" method = \"post\">";
		echo "<pre class = \"small".$ch."\">Please enter your username and password for confirmation.</pre>";
		echo "<pre class = \"small".$ch."\">Username: <input type = \"text\" name = \"username\" size = \"15\"></pre>\n";
		echo "<pre class = \"small".$ch."\">Password: <input type = \"password\" name = \"password\" size = \"20\"></pre>\n";
		echo "<input type = \"submit\" name = \"action\" value = \"".$actionval."\">\n";
		echo "</form>";
	}