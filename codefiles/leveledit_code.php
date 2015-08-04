<?php
	include("includes/ConnectionInfo.inc");
	include("includes/ShopItem.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");
	
	$levelid;
	$ch;
	$user;
	
	function initialize()
	{
		createConnection();
		global $levelid;
		global $ch;
		global $user;
		
		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$levelid = intval($_GET["level"]);
	}
	
	function canView()
	{
		global $ch;
		global $user;
		
		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You must be <a class = \"bg0\" href = \"login.php\">logged in</a>".
				" to view this page.</pre>\n";
			return FALSE;
		}
		else if($user->getUserLevel() < ADMIN_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must bel level ".ADMIN_LEVEL.
				" or higher to view this page.</pre>\n";
			return FALSE;
		}
		return TRUE;
	}
	
	function displayPage()
	{
		global $ch;
		global $levelid;
		
		if(!$levelid)
		{
			$addlevel = $_POST["addlevel"];
			if($addlevel)
			{
				addLevel();
				echo "<pre class = \"small".$ch."\">A new level has been added.  Return <a class = \"small".$ch."\"".
					" href = \"index.php\">here</a>.\n";
			}
			else
			{
				echo "<form action = \"leveledit.php\" method = \"post\">\n";
				echo "<pre class = \"small".$ch."\">Level Number: <input type = \"text\" name = \"newlevnum\" size = \"10\" maxLength = \"10\" /></pre>\n";
				echo "<pre class = \"small".$ch."\">Level Caption: <textarea name = \"newlevname\" rows = \"3\" cols = \"70\" wrap = \"soft\"></textarea></pre>\n";
				echo "<input type = \"submit\" name = \"addlevel\" value = \"Add Level\">\n";
				echo "</form>\n";
				
				$sql = "SELECT levelid, levelnum FROM levels ORDER BY levelnum";
				$result = mysql_query($sql);
				$levrdr = mysql_fetch_array($result);
				
				echo "<table width = \"100%\" ><tr class = \"mencatbd".$ch."\"><td  width = \"50%\" class = \"tophead".$ch."\">".
                    "<b>Level Number</b></td><td width = \"50%\" class = \"tophead".$ch."\"><b>Edit Level</b></td></tr>";
				while($levrdr)
				{
					$lnum = $levrdr["levelnum"];
					$lid = $levrdr["levelid"];
					echo "<tr class = \"mencatbd".$ch."\"><td width = \"50%\" class = \"toplst".$ch."\">".$lnum."</td>".
                        "<td width = \"50%\" class = \"toplst".$ch."\"><a class = \"board".$ch."\" href = \"leveledit.php?level=".$lid.
                        "\">EDIT</a></td></tr>";
					$levrdr = mysql_fetch_array($result);
				}
				echo "</table>";
			}
		}
		else
		{
			$edit = $_POST["editlevel"];
			$delete = $_POST["deletelevel"];
			
			if($edit)
			{
				editLevel();
				echo "<pre class = \"small".$ch."\">The level has been updated.  Return <a class = \"bg".$ch.
					"\" href = \"index.php\">here</a>.\n";
			}
			else if($delete)
			{
				deleteLevel();
				echo "<pre class = \"small".$ch."\">The level has been deleted.  Return <a class = \"bg".$ch.
					"\" href = \"index.php\">here</a>.\n";
			}
			else
			{
				$sql = "SELECT levelnum, levelname FROM levels WHERE levelid = ".$levelid;
				$result = mysql_query($sql);
				$levelrdr = mysql_fetch_array($result);
				if($levelrdr)
				{
					$currlevnum = $levelrdr["levelnum"];
					$currlevname = $levelrdr["levelname"];
					$currlevname = str_replace("<br />", "\n", $currlevname);
					
					echo "<form action = \"leveledit.php?level=".$levelid."\" method = \"post\">\n";
					echo "<pre class = \"small".$ch."\">Level Number: <input type = \"text\" name = \"editlevnum\" size = \"10\" maxLength = \"10\"".
						"value = \"".$currlevnum."\" /></pre>\n";
					echo "<pre class = \"small".$ch."\">Level Caption: <textarea name = \"editlevname\" rows = \"3\" cols = \"70\" wrap = \"soft\">".
						$currlevname."</textarea></pre>\n";
					echo "<input type = \"submit\" name = \"editlevel\" value = \"Edit Level\" />\n";
					echo "<input type = \"submit\" name = \"deletelevel\" value = \"Delete Level\" />\n";
					echo "</form>";
				}
			}
		}
	}
	
	function addLevel()
	{
		$newlevnum = intval($_POST["newlevnum"]);
		$newlevname = fixTags(formatString($_POST["newlevname"]), 0);
		$sql = "INSERT INTO levels VALUES(0, ".$newlevnum.", '$newlevname')";
		mysql_query($sql);
	}
	
	function editLevel()
	{
		global $levelid;
		
		$editlevnum = intval($_POST["editlevnum"]);
		$editlevname = fixTags(formatString($_POST["editlevname"]), 0);
		$sql = "UPDATE levels SET levelnum = ".$editlevnum.", levelname = '$editlevname' WHERE levelid = ".$levelid;
		mysql_query($sql);
	}
	
	function deleteLevel()
	{
		global $levelid;
		
		$sql = "DELETE FROM levels WHERE levelid = ".$levelid;
		mysql_query($sql);
	}