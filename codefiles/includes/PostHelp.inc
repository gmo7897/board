<?php
	function addTopicToBoard($board)
	{
		$sql = "UPDATE boards SET topcount = topcount + 1, messcount = messcount + 1 WHERE boardid = ".
			$board;
		mysql_query($sql);
	}

	function addMessageToTopic($topic, $board)
	{
		$currdate = time();
		$sql = "UPDATE topics SET posts = posts + 1, lastpost = ".$currdate." WHERE topicid = ".$topic;
		mysql_query($sql);
		$sql = "UPDATE boards SET messcount = messcount + 1 WHERE boardid = ".$board;
		mysql_query($sql);
	}
	
	function getLastMinutePosts($username)
	{
		$sql = "SELECT COUNT(*) FROM messages WHERE messageby = '$username'";
		$result = mysql_query($sql);
		$postrdr = mysql_fetch_row($result);
		return $postrdr[1];
	}