<?php
	include("SiteConstants.inc");

	function setSiteCookie($username, $password)
	{
		setcookie("info1", $username, time() + 60 * 60 * 24 * 30);
		setcookie("info2", $password, time() + 60 * 60 * 24 * 30);
	}

	function deleteCookie()
	{
		setcookie("info1", "", 0);
		setcookie("info2", "", 0);
	}

	function loadTheme()
	{
		$username = $_COOKIE["info1"];
		$password = $_COOKIE["info2"];
		$user = getUser($username, $password);

		if($user == NULL)
		{
			return 0;
		}
		return $user->getThemeChoice();
	}

	function menuBar0($user)
	{
		if($user == NULL)
		{
			echo "<table class = \"menu0\"><tr class = \"mencatbd0\"><td class = \"menu0\"".
				"><b><a class = \"mencat0\" href = \"login.php\">Login</a> | <a class = \"mencat0\" href = \"faq.php\"".
				">FAQ</a> | <a class = \"mencat0\" href = \"tou.php\">TOU</a></b></td></tr></table>\n";
		}
		else
		{
			echo "<table class = \"menu".$user->getThemeChoice()."\"><tr class = \"mencatbd".$user->getThemeChoice().
				"\"><td class = \"menu".$user->getThemeChoice()."\"><b><a class = \"mencat".$user->getThemeChoice().
				"\" href = \"user.php\">".$user->getUserName()."(".$user->getUserLevel().")</a> | <a class = \"mencat".
				$user->getThemeChoice()."\" href = \"logout.php\">Logout</a> | <a class = \"mencat".$user->getThemeChoice().
				"\" href = faq.php>FAQ</a> | <a class = \"mencat".$user->getThemeChoice()."\" href = tou.php>TOU</a></b></td></tr></table>\n";
		}
	}

	function menuBar1($user)
	{
		if($user == NULL)
		{
			echo "<table class = \"menu0\"><tr class = \"mencatbd0\"><td class = \"menu0\"".
				"><b><a class = \"mencat0\" href = \"login.aspx\">Login</a> | <a class = \"mencat0\" href = \"faq.aspx\"".
				">FAQ</a> | <a class = \"mencat0\" href = \"tou.aspx\">TOU</a></b></td></tr></table>\n";
		}
		else
		{
			echo "<table class = \"menu".$user->getThemeChoice()."\"><tr class = \"mencatbd".$user->getThemeChoice().
				"\"><td class = \"menu".$user->getThemeChoice()."\"><b><a class = \"mencat".$user->getThemeChoice().
				"\" href = \"index.php\">Board List</a> | <a class = \"mencat".$user->getThemeChoice()."\" href = \"logout.php\">".
				"Logout</a> | <a class = \"mencat".$user->getThemeChoice()."\" href = \"faq.php\">FAQ</a> | <a class = \"mencat".
				$user->getThemeChoice()."\" href = \"tou.php\">TOU</a></b></td></tr></table>\n";
		}
	}

	function menuBar2($user, $blevel, $board)
	{
		if($user == NULL)
		{
			echo "<table class = \"menu0\"><tr class = \"mencatbd0\"><td class = \"menu0\"".
				"><b><a class = \"mencat0\" href = \"index.php\">Board List</a> | <a class = \"mencat0\"".
				"href = \"login.php\">Login</a> | <a class = \"mencat0\" href = \"faq.php\"".
				">FAQ</a> | <a class = \"mencat0\" href = \"tou.php\">TOU</a></b></td></tr></table>\n";
		}
		else
		{
			echo "<table class = \"menu".$user->getThemeChoice()."\"><tr class = \"mencatbd".$user->getThemeChoice()."\">".
				"<td class = \"menu".$user->getThemeChoice()."\"><b><a class = \"mencat".$user->getThemeChoice()."\" ".
				"href = \"user.php\">".$user->getUserName()."(".$user->getUserLevel().")</a> | <a class = \"mencat".
				$user->getThemeChoice()."\" href = \"index.php\">Board List</a> | ";
			if($board > 0)
			{
				if($board != 1 && $user->getUserLevel() >= TOPIC_MAKING_LEVEL && $user->getUserLevel() > $blevel)
				{
					echo "<a class = \"mencat".$user->getThemeChoice()."\" href = \"posttop.php?board=".$board."\">".
						"Create New Topic</a> | ";
				}
				else if($board == 1 && $user->getUserLevel() >= ADMIN_LEVEL)
				{
					echo "<a class = \"mencat".$user->getThemeChoice()."\" href = \"posttop.php?board=".$board."\">".
						"Create New Topic</a> | ";
				}
			}
			echo "<a class = \"mencat".$user->getThemeChoice()."\" href = \"logout.php\">"."Logout</a> | <a class = \"mencat".$user->getThemeChoice().
				"\" href = \"faq.php\">FAQ</a> | <a class = \"mencat".$user->getThemeChoice()."\" href = \"tou.php\">TOU</a></b></td></tr></table>\n";
		}
	}

	function menuBar3($user, $blevel, $board, $topic, $topicactive)
	{
		if($user == NULL)
		{
			echo "<table class = \"menu0\"><tr class = \"mencatbd0\"><td class = \"menu0\"><b><a class = \"mencat0\"".
				" href = \"index.php\">Board List</a> | <a class = \"mencat0\" href = \"topics.php?board=".$board.
				"\">Topic List</a> | <a class = \"mencat0\" href = \"login.php\">Login</a> | <a class = \"mencat0\"".
				" href = \"faq.php\">FAQ</a> | <a class = \"mencat0\" href = \"tou.php\">TOU</a></b></td></tr></table>\n";
		}
		else
		{
			echo "<table class = \"menu".$user->getThemeChoice()."\"><tr class = \"mencatbd".$user->getThemeChoice()."\">".
				"<td class = \"menu".$user->getThemeChoice()."\"><b><a class = \"mencat".$user->getThemeChoice()."\" ".
				"href = \"user.php\">".$user->getUserName()."(".$user->getUserLevel().")</a> | <a class = \"mencat".
				$user->getThemeChoice()."\" href = \"index.php\">Board List</a> | <a class = \"mencat".$user->getThemeChoice().
				"\" href = \"topics.php?board=".$board."\">Topic List</a> | ";
			if($board > 0 && $user->getUserLevel() >= POSTING_LEVEL && $user->getUserLevel() >= $blevel && ($topicactive % 2 != 0 || $user->getUserLevel() >= MOD_LEVEL))
			{
				echo "<a class = \"mencat".$user->getThemeChoice()."\" href = \"postmess.php?board=".$board."&topic=".$topic."\">Create New Post</a> | ";
			}
			echo "<a class = \"mencat".$user->getThemeChoice()."\" href = \"logout.php\">"."Logout</a> | <a class = \"mencat".$user->getThemeChoice().
				"\" href = \"faq.php\">FAQ</a> | <a class = \"mencat".$user->getThemeChoice()."\" href = \"tou.php\">TOU</a></b></td></tr></table>\n";
		}
	}

	function menuBar4($user, $blevel, $board, $topic)
	{
		if($user == NULL)
		{
			echo "<table class = \"menu0\"><tr class = \"mencatbd0\"><td class = \"menu0\"><b><a class = \"mencat0\"".
				" href = \"index.php\">Board List</a> | <a class = \"mencat0\" href = \"topics.aspx?board=".$board.
				"\">Topic List</a> | <a class = \"mencat0\" href = \"messages.php?board=".$board."&topic=".$topic."\"".
				">Message List</a> | <a class = \"mencat0\" href = \"login.php\">Login</a> | <a class = \"mencat0\"".
				" href = \"faq.php\">FAQ</a> | <a class = \"mencat0\" href = \"tou.php\">TOU</a></b></td></tr></table>\n";
		}
		else
		{
			echo "<table class = \"menu".$user->getThemeChoice()."\"><tr class = \"mencatbd".$user->getThemeChoice()."\">".
				"<td class = \"menu".$user->getThemeChoice()."\"><b><a class = \"mencat".$user->getThemeChoice()."\" ".
				"href = \"user.php\">".$user->getUserName()."(".$user->getUserLevel().")</a> | <a class = \"mencat".
				$user->getThemeChoice()."\" href = \"index.php\">Board List</a> | <a class = \"mencat".$user->getThemeChoice().
				"\" href = \"topics.php?board=".$board."\">Topic List</a> | <a class = \"mencat".$user->getThemeChoice()."\" ".
				"href = \"messages.php?board=".$board."&topic=".$topic."\">Message List</a> | <a class = \"mencat".$user->getThemeChoice().
				"\" href = \"logout.php\">"."Logout</a> | <a class = \"mencat".$user->getThemeChoice()."\" href = \"faq.php\">FAQ</a> | ".
				"<a class = \"mencat".$user->getThemeChoice()."\" href = \"tou.php\">TOU</a></b></td></tr></table>\n";
		}
	}

	function modBar($user)
	{
		if($user != NULL && $user->getUserLevel() >= MOD_LEVEL)
		{
			$queuevals = queueVals($user->getUserName());
			$modval = intval($queuevals[0]);
			$appval = intval($queuevals[1]);
			$inactval = intval($queuevals[2]);
			$susval = intval($queuevals[3]);

			echo "<table class = \"menu".$user->getThemeChoice()."\"><tr class = \"mencatbd".$user->getThemeChoice()."\">".
				"<td class = \"menu".$user->getThemeChoice()."\"><b><a class = \"mencat".$user->getThemeChoice()."\" ".
				"<b><a class = \"mencat".$user->getThemeChoice()."\" href = \"modqueue.php\">Mod Queue(".$modval.")</a> | ".
				"<a class = \"mencat".$user->getThemeChoice()."\" href = \"appqueue.php\">Appeal Queue(".$appval.")</a> | ".
				"<a class = \"mencat".$user->getThemeChoice()."\" href = \"inactqueue.php\">Inactive Users(".$inactval.")</a> | ".
				"<a class = \"mencat".$user->getThemeChoice()."\" href = \"userlist.php\">User List</a>";
			if($user->getUserLevel() >= ELITE_MOD_LEVEL)
			{
				echo " | <a class = \"mencat".$user->getThemeChoice()."\" href = \"susqueue.php\">Suspended Users(".$susval.")</a>";
			}
			if($user->getUserLevel() >= ADMIN_LEVEL)
			{
				echo " | <a class = \"mencat".$user->getThemeChoice()."\" href = \"leveledit.php\">Edit Levels</a> | <a class".
				" = \"mencat".$user->getThemeChoice()."\" href = \"boardedit.php\">Edit Boards</a> | <a class = \"mencat".
				$user->getThemeChoice()."\" href = \"purgemonster.php\">Run Purge</a>";
			}
			echo "</b></td></tr></table>\n";
		}
	}

	function queueVals($username)
	{
		$vals = array();

		$modsql = "SELECT COUNT(*) FROM modqueue WHERE reason NOT LIKE 'Mark Abuse'";
		$appsql = "SELECT COUNT(*) FROM appeals WHERE appealto <> '$username'";
		$inactsql = "SELECT COUNT(*) FROM users WHERE userlevel = 0";
		$sussql = "SELECT COUNT(*) FROM users WHERE userlevel = -1";

		$result = mysql_query($modsql);
		$modrow = mysql_fetch_row($result);
		if($modrow)
		{
			$vals[0] = $modrow[0];
		}
		else
		{
			$vals[0] = -1;
		}

		$result = mysql_query($appsql);
		$approw = mysql_fetch_row($result);
		if($approw)
		{
			$vals[1] = $approw[0];
		}
		else
		{
			$vals[1] = -1;
		}

		$result = mysql_query($inactsql);
		$inactrow = mysql_fetch_row($result);
		if($inactrow)
		{
			$vals[2] = $inactrow[0];
		}
		else
		{
			$vals[2] = -1;
		}

		$result = mysql_query($sussql);
		$susrow = mysql_fetch_row($result);
		if($susrow)
		{
			$vals[3] = $susrow[0];
		}
		else
		{
			$vals[3] = -1;
		}

		return $vals;
	}