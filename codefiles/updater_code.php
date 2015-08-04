<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteConstants.inc");
	
	$user;
	
	function initialize()
	{
		createConnection();
		global $user;
		
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
	}
	
	function canView()
	{
		global $user;
		
		if(!$user)
		{
			echo "You are not <a href = \"login.php\">logged in</a>";
			return FALSE;
		}
		else if($user->getUserLevel() < ADMIN_LEVEL)
		{
			echo "You are not authorized to view this page";
			return FALSE;
		}
		return TRUE;
	}
	
	function update()
	{
		$sql = "SELECT userid, appoints, biscuits, userlevel, dailyposts, unwarntime FROM users";
		$userlistresult = mysql_query($sql);
		$userrdr = mysql_fetch_array($userlistresult);
		
		while($userrdr)
		{
			$userid = $userrdr["userid"];
			$appoints = $userrdr["appoints"];
			$biscuits = $userrdr["biscuits"];
			$userlevel = $userrdr["userlevel"];
			$dailyposts = $userrdr["dailyposts"];
			$unwarntime = $userrdr["unwarntime"];
			
			if($dailyposts > 0 && $appoints < 1000 && $userlevel > 3)
			{
				$appoints++;
				$biscuits += 7;
			}
			
			if(($userlevel == PROVISIONAL || $userlevel == TIMED_SUS || $userlevel == PROBATION) && $unwarntime <= time())
			{
				$userlevel = 20;
			}
			else if($userlevel == PENDING_CLOSE && $unwarntime <= time())
			{
				$userlevel = CLOSED;
			}
			
			if($userlevel >= MIN_APPOINT_LVL && $userlevel <= MAX_APPOINT_LVL && $userlevel != PROVISIONAL)
			{
				if($appoints < 0) $userlevel = 4;
				else if($appoints >= 0 && $appoints < 50) $userlevel = 20;
				else if($appoints >= 50 && $appoints < 111) $userlevel = 25;
				else if($appoints >= 111 && $appoints < 220) $userlevel = 30;
				else if($appoints >= 220 && $appoints < 400) $userlevel = 33;
				else if($appoints >= 400 && $appoints < 650) $userlevel = 36;
				else if($appoints >= 650 && $appoints < 1000) $userlevel = 39;
				else $userlevel = 42;
			}
			
			$sql = "UPDATE users SET userlevel = ".$userlevel.", appoints = ".$appoints.", dailyposts = 0, ".
				"dailytopics = 0, updatetime = ".time().", biscuits = ".$biscuits. 
				" WHERE userid = ".$userid;
			mysql_query($sql);
			
			$userrdr = mysql_fetch_array($userlistresult);
		}
	}