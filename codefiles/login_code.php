<?php
	include("includes/BoardUser.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");
	include("includes/ConnectionInfo.inc");
	
	define("NO_DATA", 0);
	define("BAD_NAME", 1);
	define("BAD_PASS", 2);
	define("NO_USER", 3);
	define("OK", 4);

	$status = NO_DATA;
	$username;

	function initialize()
	{
		global $status;
		global $username;

		createConnection();
		$submit = $_POST["submit"];
		
		if($submit)
		{
			$username = $_POST["username"];
			$password = $_POST["password"];

			if(!$username || !isValidUserName($username, 25))
			{
				$status = BAD_NAME;
			}
			else if(!password || !isValidUserName($password, 50))
			{
				$status = BAD_PASS;
			}
			else
			{
				$user = getUser($username, $password);
				if(!$user)
				{
					$status = NO_USER;
				}
				else
				{
					$username = $user->getUserName();
					setSiteCookie($username, $password);
					$status = OK;
				}
			}
		}
	}

	function displayPage()
	{
		global $status;

		if($status == BAD_NAME)
		{
			echo "Error: Name is blank or is invalid.<br>\n";
			loginForm();
		}
		else if($status == BAD_PASS)
		{
			echo "Error: Password is blank or is invalid.<br>\n";
			loginForm();
		}
		else if($status == NO_USER)
		{
			echo "Error: No user exists for username/password.<br>\n";
			loginForm();
		}
		else if($status == OK)
		{
			userLogin();
			echo "You have successfully logged in.  Return to the <a href = \"index.php\">".
				"board list</a>.\n";
		}
		else
		{
			loginForm();
		}
	}

	function userLogin()
	{
		global $username;

		$ipaddr = $_SERVER["REMOTE_ADDR"];
		$iparr = array();
		$namearr = array();
		$sql = "SELECT sharedips, namelist FROM usermap WHERE sharedips LIKE '%$ipaddr%'".
			 "OR mapowner = '$username'";
		$result = mysql_query($sql);
		$infordr = mysql_fetch_array($result);

		while($infordr)
		{
			$iparr[] = $infordr["sharedips"];
			$namearr[] = $infordr["namelist"];
			$infordr = mysql_fetch_array($result);
		}

		$iparr = mergeArray($iparr, $ipaddr);
		$namearr = mergeArray($namearr, $username);

		$iplist = implode(", ", $iparr);
		$namelist = implode(", ", $namearr);

		$sql = "UPDATE usermap SET sharedips = '$iplist', namelist = '$namelist' WHERE ".
			"sharedips LIKE '%$ipaddr%' OR mapowner = '$username'";
		mysql_query($sql);
	}

	function mergeArray($arr_list, $addition)
	{
		$mainarr_size = count($arr_list);
		$merged = array();

		for($i = 0; $i < $mainarr_size; $i++)
		{
			$sub_arr = explode(", ", $arr_list[$i]);
			$sub_arr_size = count($sub_arr);
			for($j = 0; $j < $sub_arr_size; $j++)
			{
				if(!in_array($sub_arr[$j], $merged))
				{
					$merged[] = $sub_arr[$j];
				}
			}
		}

		if(!in_array($addition, $merged))
		{
			$merged[] = $addition;
		}
		return $merged;
	}

	function loginForm()
	{
		echo "<form action = \"login.php\" method = \"post\">\n";
		echo "UserName: <input type = text name = username size = 15 maxLength = 15 /><br />\n";
		echo "Password: <input type = password name = password size = 20 maxLength = 20 /><br />\n";
		echo "<input type = submit name = submit value = Login /><br /><br />\n";
		echo "If you do not have an account, register one <a href = \"register.php\">here</a>.";
		echo "</form>\n";
	}