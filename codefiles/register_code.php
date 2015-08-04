<?php
	include("includes/ConnectionInfo.inc");
	include("includes/InputFormatter.inc");
	include("includes/SiteConstants.inc");

	$badaccounts = 0;
	$ipaddr;

	function initialize()
	{
		createConnection();

		global $ipaddr;
		global $badaccounts;

		$ipaddr = $_SERVER["REMOTE_ADDR"];
		$sql = "SELECT COUNT(*) FROM users WHERE (registeredip LIKE '$ipaddr' OR lastusedip LIKE '$ipaddr') AND (userlevel = ".INACTIVE." OR userlevel = ".SUSPENDED.
			" OR userlevel = ".BANNED." OR userlevel = ".DENIED.")";
		$result = mysql_query($sql);
		$badactrdr = mysql_fetch_row($result);

		$badaccounts = $badactrdr[1];
	}

	function canView()
	{
		global $badaccounts;

		if($badaccounts >= 10)
		{
			echo "Error: You have too many inactive accounts or accounts in bad standing.";
			return FALSE;
		}
		return TRUE;
	}

	function registerAccount()
	{
		global $ipaddr;
		$sent = $_POST["submit"];

		if($sent)
		{
			$username = $_POST["username"];
			$password = $_POST["password"];
			$confirm = $_POST["confirm"];
			$email = $_POST["email"];

			if(!$username || !$password || !$confirm || !$email)
			{
				echo "Error: One or more fields are blank<br />\n";
				displayRegForm();
			}
			else if($password != $confirm)
			{
				echo "Error: Your passwords don't match<br />\n";
				displayRegForm();
			}
			else if(!isValidUserName($username, 15))
			{
				echo "Error: Your Username is invalid<br />\n";
				displayRegForm();
			}
			else if(!isValidUserName($password, 20))
			{
				echo "Error: Your password is invalid<br />\n";
				displayRegForm();
			}
			else if(!isValidEmail($email))
			{
				echo "Error: Your Email is invalid<br />\n";
				displayRegForm();
			}
			else
			{
				$sql = "SELECT username FROM users WHERE username = '$username'";
				$result = mysql_query($sql);
				$nameinuse = mysql_fetch_array($result);

				if($nameinuse)
				{
					echo "Error: This username is already in use<br />\n";
					displayRegForm();
				}
				else
				{
					$currtime = time();
                                        $phash = crypt($password, 'salt');
					$sql = "INSERT INTO users VALUES(0, '$username', '$phash', '$email', '', ".INACTIVE.", ".$currtime.", ".
							$currtime.", ".$currtime.", '$ipaddr', '$ipaddr', '', 0, 0, 0, '--', ".$currtime.", ".
							"0, 0, 0, 0)";
					mysql_query($sql);

					$sharedips = $ipaddr;
					$namelist = $username;
					$sql = "SELECT sharedips, namelist FROM usermap WHERE sharedips LIKE '%".$ipaddr."%'";
					$result = mysql_query($sql);
					$maprdr = mysql_fetch_array($result);

					if($maprdr)
					{
						$sharedips = $maprdr["sharedips"].", ".$ipaddr;
						$namelist = $maprdr["namelist"].", ".$username;
						$sql = "UPDATE usermap SET namelist = '$namelist' WHERE sharedips LINKE '%".$ipaddr."%'";
						mysql_query($sql);
					}
					$sql = "INSERT INTO usermap VALUES(0, '$username', '$ipaddr', '$sharedips', '$namelist')";
					mysql_query($sql);

					echo "Your account has been registered and is waiting activation.  You can <a href = ".
						"\"login.php\">login</a> or go to the <a href = \"index.php\">board list</a>.\n";
				}
			}
		}
		else
		{
			displayRegForm();
		}
	}

	function displayRegForm()
	{
		echo "<form action = \"register.php\" method = \"post\">\n";
		echo "Username: <input type = \"text\" name = \"username\" size = \"15\" maxLength = \"15\" /><br />\n";
		echo "Password: <input type = \"password\" name = \"password\" size = \"20\" maxLength = \"20\" /><br />\n";
		echo "Confirm Password: <input type = \"password\" name = \"confirm\" size = \"20\" maxLength = \"20\" /><br>\n";
		echo "E-Mail: <input type = \"text\" name = \"email\" size = \"20\" /><br>\n";
		echo "<input type = \"submit\" name = \"submit\" value = \"Register Account\" />\n";
		echo "</form>";
	}