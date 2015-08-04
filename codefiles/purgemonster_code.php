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
	
	function runPurge()
	{
		$sql = "SELECT topicid, boardnum, lastpost, posts FROM topics WHERE boardnum > 1 AND topicactive < 2";
		$result = mysql_query($sql);
		$toprdr = mysql_fetch_array($result);
		while($toprdr)
		{
			$postime = $toprdr["lastpost"];
			$topnum = $toprdr["topicid"];
			$boardnum = $toprdr["boardnum"];
			$postcount = $toprdr["posts"];
			
			if($postime < time() - 60 * 60 * 24 * 30)
			{
				$sql = "UPDATE boards SET topcount = (topcount - 1), messcount = (messcount - ".postcount.") WHERE boardid = ".$boardnum;
				mysql_query($sql);
				$sql = "DELETE FROM topics WHERE topicid = ".$topnum;
				mysql_query($sql);
				$sql = "DELETE FROM messages WHERE topicnum = ".$topnum;
				mysql_query($sql);
			}
			$toprdr = mysql_fetch_array($result);
		}
		
		$sql = "SELECT username FROM users";
		$userresult = mysql_query($sql);
		$userdr = mysql_fetch_array($userresult);
		while($userdr)
		{
			$username = $userdr["username"];
			$sql = "SELECT COUNT(*) FROM messages WHERE boardnum <> -1 AND messageby = '$username'";
			$countresult = mysql_query($sql);
			$countrdr = mysql_fetch_row($countresult);
			$messcount = $countrdr[0];
			$sql = "UPDATE users SET messages = ".$messcount." WHERE username = '$username'";
			mysql_query($sql);
			$userdr = mysql_fetch_array($userresult);
		}
		
		$sql = "DELETE FROM moderations WHERE moddate <= ".(time() - 60 * 60 * 24 * 15)." AND (contested = 0 OR contested = 3)".
			" AND (modaction LIKE 'Delete Message' OR modaction LIKE 'Delete Topic')"; // Unappealed no point loss
		mysql_query($sql);
		
		$sql = "DELETE FROM moderations WHERE moddate <= ".(time() - 60 * 60 * 24 * 30)." AND contested = 2".
			" AND (modaction LIKE 'Delete Message' OR modaction LIKE 'Delete Topic')"; // Upheld no point loss
		mysql_query($sql);
		
		$sql = "DELETE FROM moderations WHERE moddate <= ".(time() - 60 * 60 * 24 * 30)." AND (contested = 0 OR contested = 3)".
			" AND modaction LIKE '%Remove 5 Points'"; // Unappealed 5 point loss
		mysql_query($sql);
		
		$sql = "DELETE FROM moderations WHERE moddate <= ".(time() - 60 * 60 * 24 * 45)." AND contested = 2".
			" AND modaction LIKE '%Remove 5 Points'"; // Upheld 5 point loss
		mysql_query($sql);
		
		$sql = "DELETE FROM moderations WHERE moddate <= ".(time() - 60 * 60 * 24 * 45)." AND (contested = 0 OR contested = 3)".
			" AND modaction LIKE '%Set Probation'"; // Unappealed probation
		mysql_query($sql);
		
		$sql = "DELETE FROM moderations WHERE moddate <= ".(time() - 60 * 60 * 24 * 60)." AND contested = 2".
			" AND modaction LIKE '%Set Probation'"; // Upheld probation
		mysql_query($sql);
		
		$sql = "DELETE FROM moderations WHERE moddate <= ".(time() - 60 * 60 * 24 * 60)." AND contested = 3".
			" AND modaction LIKE '%Set 7-Day Suspension'"; // 7-day suspension
		mysql_query($sql);
		
		$sql = "DELETE FROM moderations WHERE moddate <= ".(time() - 60 * 60 * 24 * 90)." AND contested = 3".
			" AND modaction LIKE '%Set 30-Day Suspension'"; // 30-day suspension
		mysql_query($sql);
	}