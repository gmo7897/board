<?php
	include("includes/ConnectionInfo.inc");
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");

	$edited;
	$boardid;
	$catid;
	$ch;
	$user;

	function initialize()
	{
		createConnection();

		global $boardid;
		global $catid;
		global $ch;
		global $user;

		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$ch = loadTheme($ch);
		$boardid = intval($_GET["boardid"]);
		$catid = intval($_GET["catid"]);
	}

	function canView()
	{
		global $user;
		global $ch;

		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You must be <a class = \"bg0\" href = \"login.php\">logged in</a>.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < ADMIN_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level ".ADMIN_LEVEL." or higher to view this page.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}

	function displayEditor()
	{
		global $boardid;
		global $catid;
		global $ch;

		if($boardid > 1)
		{
			$edit = $_POST["chngbd"];
			$delete = $_POST["delbd"];

			if($edit)
			{
				editBoard($boardid);
			}
			else if($delete)
			{
				deleteBoard($boardid);
			}
			else
			{
				$sql = "SELECT boardname, boardextrainfo, boardlevel, catnum FROM boards WHERE boardid = ".$boardid;
				$result = mysql_query($sql);
				$bdrdr = mysql_fetch_array($result);

				if($bdrdr)
				{
					$bname = $bdrdr["boardname"];
					$bcap = $bdrdr["boardextrainfo"];
					$blevel = $bdrdr["boardlevel"];
					$catnum = $bdrdr["catnum"];

					echo "<form action = \"boardedit.php?boardid=".$boardid."\" method = \"post\">\n";
					boardEditForm($bname, $bcap, $blevel, $catnum, $ch);
					echo "</form>\n";
				}
				else
				{
					echo "<pre class = \"small".$ch."\">Error: Invalid board id</pre>\n";
				}
			}
		}
		else if($catid != 0)
		{
			$edited = $_POST["chngcat"];
			$delete = $_POST["delcat"];

			if($edited)
			{
				editCategory($catid);
			}
			else if($delete)
			{
				deleteCategory($catid);
			}
			else
			{
				$sql = "SELECT catname, catplacement FROM catagories WHERE catid = ".$catid;
				$result = mysql_query($sql);
				$catrdr = mysql_fetch_array($result);

				if($catrdr)
				{
					$catname = $catrdr["catname"];
					$catplacement = $catrdr["catplacement"];

					echo "<form action = \"boardedit.php?catid=".$catid."\" method = \"post\">\n";
					catEditForm($catname, $catplacement, $ch);
					echo "</form>\n";
				}
				else
				{
					echo "<pre class = \"small".$ch."\">Error: Invalid catagory id.</pre>\n";
				}
			}
		}
		else
		{
			$addboard = $_POST["chngbd"];
			$addcat = $_POST["chngbd"];

			if($addboard)
			{
				addBoard();
			}
			else if($addcat)
			{
				addCategory();
			}
			else
			{
				echo "<form action = \"boardedit.php\" method = \"post\">\n";
				boardAddForm($ch);
				
				$sql = "SELECT boardid, boardname FROM boards WHERE boardid > 1";
				$result = mysql_query($sql);
				$boardlist = mysql_fetch_array($result);

				echo "<table width = \"100%\" class = \"tophead".$ch."\"><tr class = \"mencatbd".$ch."\"><td class = \"tophead".
					$ch."\" width = \"50%\"><b>Board Name</b></td><td class = \"tophead".$ch."\" width = \"50%\"><b>Edit Board</b>".
					"</td></tr>\n";

				while($boardlist)
				{
					$bdname = $boardlist["boardname"];
					$bdid = $boardlist["boardid"];

					echo "<tr class = \"mencatbd".$ch."\"><td class = \"toplst".$ch."\" width = \"50%\">".$bdname.
						"</td><td class = \"toplst".$ch."\" width = \"50%\"><a class = \"board".$ch."\" href = \"boardedit.php?boardid=".
						$bdid."\">EDIT</a></td></tr>\n";
					$boardlist = mysql_fetch_array($result);
				}
				echo "</table>";

				catAddForm($ch);

				$sql = "SELECT catid, catname FROM catagories";
				$result = mysql_query($sql);
				$catlist = mysql_fetch_array($result);

				echo "<table width = \"100%\" class = \"tophead".$ch."\"><tr class = \"mencatbd".$ch."\"><td class = \"tophead".
					$ch."\" width = \"50%\"><b>Catagory Name</b></td><td class = \"tophead".$ch."\" width = \"50%\">".
					"<b>Edit Catagory</b></td></tr>";

				while($catlist)
				{
					$ctname = $catlist["catname"];
					$ctid = $catlist["catid"];

					echo "<tr class = \"mencatbd".$ch."\"><td class = \"toplst".$ch."\" width = \"50%\">".$ctname.
						"</td><td class = \"toplst".$ch."\"><a class = \"board".$ch."\" href = \"boardedit.php?catid=".$ctid.
						"\">EDIT</a></td></tr>";
					$catlist = mysql_fetch_array($result);
				}
				echo "</table></form>";
			}
		}
	}

	function boardEditForm($boardname, $boardcaption, $viewlevel, $catnum, $ch)
	{
		$boardcaption = str_replace("<br />", "\n", $boardcaption);

		echo "<pre class = \"small".$ch."\">Board Name: <input type = \"text\" name = \"bdname\" size = \"30\" maxLength = \"30\" value = \"".$boardname."\" /></pre>\n";
		echo "<pre class = \"small".$ch."\">Catagory Number: <input type = \"text\" name = \"bcatnum\" size = \"10\" maxLength = \"10\" value = \"".$catnum."\" /></pre>\n";
		echo "<pre class = \"small".$ch."\">Board Viewing Level: <input type = \"text\" name = \"blevel\" size = \"10\" maxLength = \"10\" value = \"".$viewlevel."\" /></pre>\n";
		echo "<pre class = \"small".$ch."\">Board Caption: <textarea name = \"newcap\" rows = \"3\" cols = \"70\">".$boardcaption."</textarea></pre>\n";
		echo "<input type = \"submit\" name = \"chngbd\" value = \"Edit Board\" />";
		echo "<input type = \"submit\" name = \"delbd\" value = \"Delete Board\" />";
	}

	function boardAddForm($ch)
	{
		echo "<pre class = \"small".$ch."\">Board Name: <input type = \"text\" name = \"bdname\" size = \"30\" maxLength = \"30\" /></pre>\n";
		echo "<pre class = \"small".$ch."\">Catagory Number: <input type = \"text\" name = \"bcatnum\" size = \"10\" maxLength = \"10\" /></pre>\n";
		echo "<pre class = \"small".$ch."\">Board Viewing Level: <input type = \"text\" name = \"blevel\" size = \"10\" maxLength = \"10\" /></pre>\n";
		echo "<pre class = \"small".$ch."\">Board Caption: <textarea name = \"newcap\" rows = \"3\" cols = \"70\"></textarea></pre>\n";
		echo "<input type = \"submit\" name = \"chngbd\" value = \"Add Board\" />";
	}

	function catEditForm($catname, $catplacement, $ch)
	{
		echo "<pre class = \"small".$ch."\">Catagory Name: <input type = \"text\" name = \"cname\" size = \"20\" maxLength = \"20\" value = \"".$catname."\" /></pre>\n";
		echo "<pre class = \"small".$ch."\">Catagory Placement (Number): <input text name = \"cplace\" size = \"10\" maxLength = \"10\" value = \"".$catplacement."\" /></pre>\n";
		echo "<input type = \"submit\" name = \"chngcat\" value = \"Edit Catagory\">";
		echo "<input type = \"submit\" name = \"delcat\" value = \"Delete Catagory\">";
	}

	function catAddForm($ch)
	{
		echo "<pre class = \"small".$ch."\">Catagory Name: <input type = \"text\" name = \"cname\" size = \"20\" maxLength = \"20\" /></pre>\n";
		echo "<pre class = \"small".$ch."\">Catagory Placement (Number): <input text name = \"cplace\" size = \"10\" maxLength = \"10\" /></pre>\n";
		echo "<input type = \"submit\" name = \"chngcat\" value = \"Add Catagory\">";
	}
	
	function addBoard()
	{
		global $ch;
		
		$newbname = formatString($_POST["bdname"]);
		$newbinfo = formatString($_POST["newcap"]);
		$blevel = intval($_POST["blevel"]);
		$bcatnum = intval($_POST["bcatnum"]);
		
		$sql = "INSERT INTO boards VALUES(0, ".$bcatnum.", '$newbname', ".$blevel.", 0, 0, '$newbinfo')";
		mysql_query($sql);
		
		echo "<pre class = \"small".$ch."\">The board has been added.  Return <a class = \"bg".$ch."\" href = \"index.php\">here</a>.</pre>";
	}
	
	function editBoard($boardid)
	{
		global $ch;
		
		$bname = formatString($_POST["bdname"]);
		$binfo = formatString($_POST["newcap"]);
		$blevel = intval($_POST["blevel"]);
		$bcatnum = intval($_POST["bcatnum"]);
		
		$sql = "UPDATE boards SET boardname = '$bname', catnum = ".$bcatnum.", boardlevel = ".$blevel.
            ", boardextrainfo = '$binfo' WHERE boardid = ".$boardid;
		mysql_query($sql);
		
		echo "<pre class = \"small".$ch."\">The board has been edited.  Return <a class = \"bg".$ch."\" href = \"index.php\">here</a>.</pre>";
	}
	
	function deleteBoard($boardid)
	{
		global $ch;
		
		$usersql = "SELECT messageby FROM messages WHERE boardnum = ".$boardid;
		$userresult = mysql_query($usersql);
		$userrdr = mysql_fetch_array($userresult);
		
		while($userrdr)
		{
			$uname = $userrdr["messageby"];
			$newpostct = 0;
			$sql = "SELECT COUNT(*) FROM messages WHERE boardnum <> ".$boardid." AND messageby = '$uname'";
			$result = mysql_query($result);
			$messctrdr = mysql_fetch_row($result);
			$newpostct = $messctrdr[1];
			
			$sql = "UPDATE users set messages = ".$newpostct." WHERE username = '$uname'";
			mysql_query($sql);
			$userrdr = mysql_fetch_array($userresult);
		}
		
		$sql = "DELETE FROM topics WHERE boardnum = ".$boardid;
		mysql_query($sql);
		$sql = "DELETE FROM messages WHERE boardnum = ".$boardid;
		mysql_query($sql);
		$sql = "DELETE FROM boards WHERE boardid = ".$boardid;
		mysql_query($sql);
		
		echo "<pre class = \"small".$ch."\">The board has been deleted.  Return <a class = \"bg".$ch."\" href = \"index.php\">here</a>.</pre>";
	}
	
	function addCategory()
	{
		global $ch;
		
		$cname = formatString($_POST["cname"]);
		$cplace = intval($_POST["cplace"]);
		$sql = "INSERT INTO catagories VALUES(0, '$cname', ".$cplace.")";
		mysql_query($sql);
		
		echo "<pre class = \"small".$ch."\">The catagory has been added.  Return <a class = \"bg".$ch."\" href = \"index.php\">here</a>.</pre>\n";
	}
	
	function editCategory($catid)
	{
		global $ch;
		
		$cname = formatString($_POST["cname"]);
		$cplace = intval($_POST["cplace"]);
		$sql = "UPDATE catagories SET catname = '$cname', catplacement = ".$cplace." WHERE catid = ".$catid;
		mysql_query($sql);
		
		echo "<pre class = \"small".$ch."\">The catagory has been edited.  Return <a class = \"bg".$ch."\" href = \"index.php\">here</a>.</pre>\n";
	}
	
	function deleteCategory($catid)
	{
		global $ch;
		
		$sql = "DELETE FROM catagories WHERE catid = ".$catid;
		mysql_query($sql);
		
		echo "<pre class = \"small".$ch."\">The catagory has been deleted.  Return <a class = \"bg".$ch."\" href = \"index.php\">here</a>.</pre>\n";
	}