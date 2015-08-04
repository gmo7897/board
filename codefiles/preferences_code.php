<?php
	include("includes/ConnectionInfo.inc");
	include("includes/SiteTools.inc");
	include("includes/ShopItem.inc");
	include("includes/InputFormatter.inc");

	$user;
	$level = 0;
	$ch = 0;
	$timezone = "";
	$signature = NULL;
	$pubemail = NULL;
	$privemail = NULL;

	function initialize()
	{
		createConnection();
		global $user;
		global $level;
		global $timezone;
		global $signature;
		global $ch;
		global $pubemail;
		global $privemail;
		$rawtimezone;
		$zonehours;
		$zoneminutes;

		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		
		if($user)
		{
			$level = $user->getUserLevel();
			$rawtimezone = $user->getTimeZone();
			$signature = $user->getSIgnature();
			$ch = $user->getThemeChoice();
			$pubemail = $user->getPubEmail();
			$privemail = $user->getPrivEmail();

			if($signature)
			{
				$signature = str_replace("<br />", "\n", $signature);
			}

			$zonehours = abs($rawtimezone / 3600);
			$zoneminutes = abs(($rawtimezone % 3600) / 60);

			if($rawtimezone < 0)
			{
				$timezone = "-";
			}
			if($zonehours < 10)
			{
				$timezone = $timezone."0";
			}
			$timezone = $timezone.$zonehours.":";
			if($zoneminutes < 10)
			{
				$timezone = $timezone."0";
			}
			$timezone = $timezone.$zoneminutes;
		}
	}

	function canView()
	{
		global $user;
		global $ch;

		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You must be logged in to view this page.</pre>";
			return FALSE;
		}
		else if($level == LOCKED)
		{
			echo "<pre class = \"small".$ch."\"Error: Locked accounts cannot change their preferences.</pre>";
			return FALSE;
		}
		return TRUE;
	}

	function displayPreferencesPage()
	{
		global $user;
		global $ch;

		$sigupdate = $_POST["sigupdate"];
		$themeupdate = $_POST["themeupdate"];
		$pubmailupdate = $_POST["pubmailupdate"];
		$privmailupdate = $_POST["privmailupdate"];
		$timezoneupdate = $_POST["timezoneupdate"];

		if($sigupdate)
		{
			$newsig = formatString($_POST["signature"]);

			if (!isValidLength($newsig, 210, 200))
			{
				echo "<pre class = \"small".$ch."\">Error: Your signature is over 210 characters or has a word over 80 characters.</pre>\n";
				displayPreferenceForm();
			}
			else if (countLines(newsig) > 3)
			{
				echo "<pre class = \"small".$ch."\">Error: Your signature has more than 3 lines.</pre>\n";
				displayPreferenceForm();
			}
			else
			{
				$user->setSignature($newsig);
				echo "<pre class = \"small".$ch."\">Your signature has been updated.  Return <a class = \"bg".$ch."\" href = \"user.php\">here</a>.</pre>\n";
			}
		}
		else if($themeupdate)
		{
			$themename = $_POST["colchoice"];
			$newtheme = 0;

			if($themename == "Default") $newtheme = 1;
			else if($themename == "Yellow Theme") $newtheme = 2;
			else if($themename == "Blue Theme") $newtheme = 3;
			else if($themename == "Black and White") $newtheme = 4;
			else if($themename == "GameFAQs Classic (Stevewins123)") $newtheme = 5;
			else if($themename == "Red-Turquoise (Ocelot529, Stevewins123)") $newtheme = 6;
			else if($themename == "Forest Green (Stevewins123)") $newtheme = 7;
			else if($themename == "GFH") $newtheme = 8;

			$user->setThemeChoice($newtheme);
			echo "<pre class = \"small".$ch."\">Your theme has been updated.  Return <a class = \"bg".
				$ch."\" href = \"user.php\">here</a>.</pre>\n";
		}
		else if($pubmailupdate)
		{
			$newmail = $_POST["pubemail"];

			if(!$newmail || !isValidEmail($newmail))
			{
				echo "<pre class = \"small".$ch."\">Error: Your public email of ".$newmail." is invalid.</pre>\n";
				displayPreferenceForm();
			}
			else
			{
				$user->setPubEmail($newmail);
				echo "<pre class = \"small".$ch."\">Your public email has been updated.  Return <a class = \"bg".
					$ch."\" href = \"user.php\">here</a>.</pre>\n";
			}
		}
		else if($privmailupdate)
		{
			$newmail = $_POST["privemail"];

			if(!$newmail || !isValidEmail($newmail))
			{
				echo "<pre class = \"small".$ch."\">Error: Your private email of ".$newmail." is invalid.</pre>\n";
				displayPreferenceForm();
			}
			else
			{
				$user->setPrivEmail($newmail);
				echo "<pre class = \"small".$ch."\">Your private email has been updated.  Return <a class = \"bg".
					$ch."\" href = \"user.php\">here</a>.</pre>\n";
			}
		}
		else if($timezoneupdate)
		{
			$timezonestring = $_POST["timezone"];
			$timezonearray = explode(":", $timezonestring);
			$hours = intval($timezonearray[0]);
			$minutes = intval($timezonearray[1]);
			$newtimezone = $hours * 3600 + $minutes * 60;

			$user->setTimeZone($newtimezone);
			echo "<pre class = \"small".$ch."\">Your timezone has been updated.  Return <a class = \"bg".
				$ch."\" href = \"user.php\">here</a>.</pre>\n";
		}
		else
		{
			displayPreferenceForm();
		}
	}

	function displayPreferenceForm()
	{
		global $ch;
		global $signature;
		global $pubemail;
		global $privemail;
		global $timezone;

		echo "<form action = \"preferences.php\" method = \"post\">";
		echo "<pre class = \"small".$ch."\">Signature: <textarea cols = \"70\" rows = \"3\" wrap = \"soft\" name = \"signature\">".
			$signature."</textarea></pre>\n";
		echo "<input type = \"submit\" name = \"sigupdate\" value = \"Update Signature\" /><br />\n";
		echo "<pre class = \"small".$ch."\">Theme Choice: <select name = \"colchoice\" size = \"1\">\n";
		echo "<option>Default</option>\n<option>Yellow Theme</option>\n<option>Blue Theme</option>\n".
			"<option>Black and White</option>\n<option>GameFAQs Classic (Stevewins123)</option>\n".
			"<option>Red-Turquoise (Ocelot529, Stevewins123)</option>\n<option>Forest Green (Stevewins123)</option>\n".
			"<option>GFH</option>\n</select></pre>\n";
		echo "<input type = \"submit\" name = \"themeupdate\" value = \"Update Theme\" /><br />\n";
		echo "<pre class = \"small".$ch."\">Public Email: <input type = \"text\" name = \"pubemail\" value = \"".$pubemail."\" size = \"25\" /></pre>\n";
		echo "<input type = \"submit\" name = \"pubmailupdate\" value = \"Update Public Email\" />";
		echo "<pre class = \"small".$ch."\">Private Email: <input type = \"text\" name = \"privemail\" value = \"".$privemail."\" size = \"25\" /></pre>\n";
		echo "<input type = \"submit\" name = \"privmailupdate\" value = \"Update Private Email\" />";
		echo "<pre class = \"small".$ch."\">GMT Offest (hh:mm): <input type = \"text\" name = \"timezone\" value = \"".$timezone."\" size = \"25\" /></pre>\n";
		echo "<input type = \"submit\" name = \"timezoneupdate\" value = \"Update Timezone\" />";
		echo "</form>";
	}