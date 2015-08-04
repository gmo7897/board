<?php
	class BoardUser
	{
		var $userid;
		var $username;
		var $password;
		var $privemail;
		var $pubemail;
		var $userlevel;
		var $regdate;
		var $updatetime;
		var $lastlogindate;
		var $registeredip;
		var $lastusedip;
		var $signature;
		var $themechoice;
		var $messages;
		var $appoints;
		var $regkey;
		var $unwarntime;
		var $dailytopics;
		var $dailyposts;
		var $timezone;
		var $biscuits;

		function BoardUser($uid, $uname, $pword, $privmail, $pubmail, $level, $rgdate,
			$update, $lstlogin, $regip, $lstip, $sig, $th, $msgs, $points, $key, 
			$unwarn, $dtops, $dmsgs, $zone, $bscs)
		{
			$this->userid = $uid;
			$this->username = $uname;
			$this->password = $pword;
			$this->privemail = $privmail;
			$this->pubemail = $pubmail;
			$this->userlevel = $level;
			$this->regdate = $rgdate;
			$this->updatetime = $update;
			$this->lastlogindate = $lstlogin;
			$this->registeredip = $regip;
			$this->lastusedip = $lstip;
			$this->signature = $sig;
			$this->themechoice = $th;
			$this->messages = $msgs;
			$this->appoints = $points;
			$this->regkey = $key;
			$this->unwarntime = $unwarn;
			$this->dailytopics = $dtops;
			$this->dailyposts = $dmsgs;
			$this->timezone = $zone;
			$this->biscuits = $bscs;
		}

		function getUserId()
		{
			return $this->userid;
		}

		function getUserName()
		{
			return $this->username;
		}

		function getPassword()
		{
			return $this->password;
		}

		function getPrivEmail()
		{
			return $this->privemail;
		}

		function getPubEmail()
		{
			return $this->pubemail;
		}

		function getUserLevel()
		{
			return $this->userlevel;
		}

		function getRegDate()
		{
			return $this->regdate;
		}

		function getUpdateTime()
		{
			return $this->updatetime;
		}

		function getLastLoginDate()
		{
			return $this->lastlogindate;
		}

		function getRegisteredIP()
		{
			return $this->registeredip;
		}

		function getLastUsedIP()
		{
			return $this->lastusedip;
		}

		function getSignature()
		{
			return $this->signature;
		}

		function getThemeChoice()
		{
			return $this->themechoice;
		}

		function getMessages()
		{
			return $this->messages;
		}

		function getAppoints()
		{
			return $this->appoints;
		}

		function getRegKey()
		{
			return $this->regkey;
		}

		function getUnwarnTime()
		{
			return $this->unwarntime;
		}

		function getDailyPosts()
		{
			return $this->dailyposts;
		}

		function getDailyTopics()
		{
			return $this->dailytopics;
		}

		function getTimeZone()
		{
			return $this->timezone;
		}

		function getBiscuits()
		{
			return $this->biscuits;
		}

		function setUserLevel($level)
		{
			$this->userlevel = $level;
			$sql = "UPDATE users SET userlevel = ".$this->userlevel." WHERE userid = ".$this->userid;
			mysql_query($sql);
		}

		function setAppoints($newpoints)
		{
			$this->appoints = $newpoints;
			$sql = "UPDATE users SET appoints = ".$this->appoints." WHERE userid = ".$this->userid;
			mysql_query($sql);
		}

		function setBiscuits($newbiscuits)
		{
			$this->biscuits = $newbiscuits;
			$sql = "UPDATE users SET biscuits = ".$this->biscuits." WHERE userid = ".$this->userid;
			mysql_query($sql);
		}

		function posted($topicmade)
		{
			$this->dailyposts++;
			$this->messages++;
			$sql = "UPDATE users SET dailyposts = ".$this->dailyposts.", messages = ".$this->messages." WHERE userid = ".$this->userid;
			mysql_query($sql);

			if($topicmade)
			{
				$this->dailytopics++;
				$sql = "UPDATE users SET dailytopics = ".$this->dailytopics." WHERE userid = ".$this->userid;
				mysql_query($sql);
			}
		}

		function setSignature($sig)
		{
			$this->signature = $sig;
			$sql = "UPDATE users SET signature = '$this->signature' WHERE userid = ".$this->userid;
			mysql_query($sql);
		}

		function setPubEmail($email)
		{
			$this->pubemail = $email;
			$sql = "UPDATE users SET pubemail = '$this->pubemail' WHERE userid = ".$this->userid;
			mysql_query($sql);
		}

		function setPrivEmail($email)
		{
			$this->privemail = $email;
			$sql = "UPDATE users SET privemail = '$this->privemail' WHERE userid = ".$this->userid;
			mysql_query($sql);
		}

		function setThemeChoice($th)
		{
			$this->themechoice = $th;
			$sql = "UPDATE users SET themechoice = ".$this->themechoice." WHERE userid = ".$this->userid;
			mysql_query($sql);
		}

		function setTimezone($zone)
		{
			$this->timezone = $zone;
			$sql = "UPDATE users SET timezone = ".$this->timezone." WHERE userid = ".$this->userid;
			mysql_query($sql);
		}

		function newActivity($ipaddr)
		{
			$this->lastlogindate = time();
			$sql = "UPDATE users SET lastlogindate = ".$this->lastlogindate.", lastusedip = '$ipaddr' WHERE userid = ".$this->userid;
			mysql_query($sql);
		}

		function setUnwarnTime($time)
		{
			$this->unwarntime = $time;
			$sql = "UPDATE users SET unwarntime = ".$this->unwarntime." WHERE userid = ".$this->userid;
			mysql_query($sql);
		}
	}

	function getUser($username, $password)
	{
                $phash = crypt($password, 'salt');
		$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$phash'";
		$result = mysql_query($sql);

		if(mysql_num_rows($result) == 0)
		{
			return NULL;
		}
		else
		{
			$userinfo = mysql_fetch_array($result);
			$user = new BoardUser($userinfo["userid"], $userinfo["username"], $userinfo["password"], $userinfo["privemail"],
				$userinfo["pubemail"], $userinfo["userlevel"], $userinfo["regdate"], $userinfo["updatetime"],
				$userinfo["lastlogindate"], $userinfo["registeredip"], $userinfo["lastusedip"], $userinfo["signature"],
				$userinfo["themechoice"], $userinfo["messages"], $userinfo["appoints"], $userinfo["regkey"],
				$userinfo["unwarntime"], $userinfo["dailyposts"], $userinfo["dailytopics"], $userinfo["timezone"],
				$userinfo["biscuits"]);
			return $user;
		}
	}
			
	function getUserById($userid)
	{
		$sql = "SELECT username, password FROM users WHERE userid = ".$userid;
		$result = mysql_query($sql);

		if(mysql_num_rows($result) == 0)
		{
			return NULL;
		}
		else
		{
			$userinfo = mysql_fetch_array($result);
			return getUser($userinfo["username"], $userinfo["password"]);
		}
	}