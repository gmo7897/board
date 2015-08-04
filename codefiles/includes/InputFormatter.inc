<?php
	// shortcuts for "<", ">", and "/"
	define("LT", "&#60;");
	define("GT", "&#62;");
	define("BS", "&#47;");

	define("LB_OPEN", LT."b".GT);
	define("CB_OPEN", LT."B".GT);
	define("LB_CLOSE", LT.BS."b".GT);
	define("CB_CLOSE", LT.BS."B".GT);
	define("LI_OPEN", LT."i".GT);
	define("CI_OPEN", LT."I".GT);
	define("LI_CLOSE", LT.BS."i".GT);
	define("CI_CLOSE", LT.BS."I".GT);

	// html equivalents for items unlocked by biscuit shop
	define("LU_OPEN", LT."u".GT);
	define("CU_OPEN", LT."U".GT);
	define("LU_CLOSE", LT.BS."u".GT);
	define("CU_CLOSE", LT.BS."U".GT);

	define("MARQUEE_OPEN", LT."marquee".GT);
	define("MARQUEE_CLOSE", LT.BS."marquee".GT);
	
	define("BIG_OPEN", LT."big".GT);
	define("BIG_CLOSE", LT.BS."big".GT);

	define("SMALL_OPEN", LT."small".GT);
	define("SMALL_CLOSE", LT.BS."small".GT);

	define("CODE_OPEN", LT."code".GT);
	define("CODE_CLOSE", LT.BS."code".GT);

	define("LINK_OPEN", LT."link".GT);
	define("LINK_CLOSE", LT.BS."link".GT);

	define("RED_OPEN", LT."red".GT);
	define("RED_CLOSE", LT.BS."red".GT);
	define("BLUE_OPEN", LT."blue".GT);
	define("BLUE_CLOSE", LT.BS."blue".GT);
	define("GREEN_OPEN", LT."green".GT);
	define("GREEN_CLOSE", LT.BS."green".GT);
	define("YELLOW_OPEN", LT."yellow".GT);
	define("YELLOW_CLOSE", LT.BS."yellow".GT);
	define("PURPLE_OPEN", LT."purple".GT);
	define("PURPLE_CLOSE", LT.BS."purple".GT);

	function isValidLength($input, $max_size, $max_line_length)
	{
		if(strlen($input) > $max_size)
		{
			return FALSE;
		}
		else if(maxLine($input) > $max_line_length)
		{
			return FALSE;
		}
		return TRUE;
	}

	function isValidUserName($name, $maxsize)
	{
		$namarr = str_split($name);

		if(strlen($name) > $maxsize || strlen($name) < 1)
		{
			return FALSE;
		}
		else if(containsIllegalCharacters($name))
		{
			return FALSE;
		}
		else if($namarr[0] == " ")
		{
			return FALSE;
		}
		return TRUE;
	}

	function isValidEmail($email)
	{
		$mailarr = str_split($email);

		if(strlen($email) < 1)
		{
			return FALSE;
		}
		else if(containsIllegalCharacters($email))
		{
			return FALSE;
		}
		else if($mailarr[0] == " ")
		{
			return FALSE;
		}
		return TRUE;
	}

	function formatString($input)
	{
		if($input)
		{
			$input = stripslashes($input);
			$output = "";
			$inputarr = str_split($input);
			$inputsize = count($inputarr);
	
			for($i = 0; $i < $inputsize; $i++)
			{
				if($inputarr[$i] == "\n")
				{
					$output = $output."<br />";
				}
				else if(containsIllegalCharacters($inputarr[$i]))
				{
					$output = $output."&#".ord($inputarr[$i]).";";
				}
				else
				{
					$output = $output.$inputarr[$i];
				}
			}
			return $output;
		}
		else
		{
			return $input;
		}
	}

	function fixTags($input, $user)
	{
		$underline = getShopItembyName("HTML: Underline");
		$marquee = getShopItembyName("HTML: Marquee");
		$big = getShopItembyName("HTML: Big");
		$small = getShopItembyName("HTML: Small");
		$code = getShopItembyName("HTML: Code");
		$links = getShopItembyName("HTML: Links");
		$color = getShopItembyName("HTML: Color");

		$input = str_replace(LB_OPEN, "<b>", $input);
		$input = str_replace(LB_CLOSE, "</b>", $input);
		$input = str_replace(CB_OPEN, "<B>", $input);
		$input = str_replace(CB_CLOSE, "</B>", $input);
		$input = str_replace(LI_OPEN, "<i>", $input);
		$input = str_replace(LI_CLOSE, "</i>", $input);
		$input = str_replace(CI_OPEN, "<I>", $input);
		$input = str_replace(CI_CLOSE, "</I>", $input);

		if($underline != NULL && $underline->isOwner($user))
		{
			$input = str_replace(LU_OPEN, "<u>", $input);
			$input = str_replace(LU_CLOSE, "</u>", $input);
			$input = str_replace(CU_OPEN, "<U>", $input);
			$input = str_replace(CU_CLOSE, "</u>", $input);
		}
		if($marquee != NULL && $marquee->isOwner($user))
		{
			$input = str_replace(MARQUEE_OPEN, "<marquee>", $input);
			$input = str_replace(MARQUEE_CLOSE, "</marquee>", $input);
		}
		if($big != NULL && $big->isOwner($user))
		{
			$input = str_replace(BIG_OPEN, "<big>", $input);
			$input = str_replace(BIG_CLOSE, "</big>", $input);
		}
		if($small != NULL && $small->isOwner($user))
		{
			$input = str_replace(SMALL_OPEN, "<small>", $input);
			$input = str_replace(SMALL_CLOSE, "</small>", $input);
		}
		if($code != NULL && $code->isOwner($user))
		{
			$input = str_replace(CODE_OPEN, "<code>", $input);
			$input = str_replace(CODE_CLOSE, "</code>", $input);
		}
		if($links != NULL && $links->isOwner($user))
		{
			$inputarr = explode(LINK_OPEN, $input);
			$arrsize = count($inputarr);
			$formatedinput = "";

			for($i = 0; $i < $arrsize; $i++)
			{
				$ending = substr($inputarr[$i], -19);
				$linkarr = explode(LINK_CLOSE, $inputarr[$i]);
				$linkarrsize = count($linkarr);
				if(!is_bool($ending) && $ending == LINK_CLOSE)
				{
					$link = rtrim($inputarr[$i], LINK_CLOSE);
					$fullink = "<a href = \"".$link."\">".$link."</a>";
					$fullink = str_replace("&#58;", ":", $fullink);
					$fullink = str_replace("&#47;", "/", $fullink);
					$fullink = str_replace("&#38;", "&", $fullink);
					$fullink = str_replace("&#37;", "%", $fullink);
					$formatedinput = $formatedinput.$fullink;
				}
				else if($linkarrsize > 1)
				{
					$link = $linkarr[0];
					$fullink = "<a href = \"".$link."\">".$link."</a>";
					$fullink = str_replace("&#58;", ":", $fullink);
					$fullink = str_replace("&#47;", "/", $fullink);
					$fullink = str_replace("&#38;", "&", $fullink);
					$fullink = str_replace("&#37;", "%", $fullink);
					$formatedinput = $formatedinput.$fullink;
					for($j = 1; $j < $linkarrsize; $j++)
					{
						$formatedinput = $formatedinput.$linkarr[$j];
					}
				}
				else
				{
					$formatedinput = $formatedinput.$inputarr[$i];
				}
			}
			$input = $formatedinput;
		}
		if($color != NULL && $color->isOwner($user))
		{
			$input = str_replace(RED_OPEN, "<font color = \"#FF0000\">", $input);
			$input = str_replace(RED_CLOSE, "</font>", $input);
			$input = str_replace(GREEN_OPEN, "<font color = \"#00FF00\">", $input);
			$input = str_replace(GREEN_CLOSE, "</font>", $input);
			$input = str_replace(BLUE_OPEN, "<font color = \"#0000FF\">", $input);
			$input = str_replace(BLUE_CLOSE, "</font>", $input);
			$input = str_replace(YELLOW_OPEN, "<font color = \"#FFFF00\">", $input);
			$input = str_replace(YELLOW_CLOSE, "</font>", $input);
			$input = str_replace(PURPLE_OPEN, "<font color = \"#7700FF\">", $input);
			$input = str_replace(PURPLE_CLOSE, "</font>", $input);
		}

		return $input;
	}

	function countLines($input)
	{
		$arr = explode("\n", $input);
		return count($arr);
	}

	function trimString($input)
	{
		$input = trim($input);
		$input = strip_tags($input);

		return $input;
	}

	function maxLine($input)
	{
		$input = str_replace(" ", "\n", $input);
		$inputarr = explode("\n", $input);
		$maxline = 0;
		$numlines = count($inputarr);

		for($i = 0; $i < $numlines; $i++)
		{
			$linesize = strlen($inputarr[$i]);
			if($linesize > $maxline)
			{
				$maxline = $linesize;
			}
		}
		return $maxline;
	}

	function formatTimeZone($date, $timezone)
	{
		// Since the server uses CST and offset is from GMT, add five hours
		return date("n/j/Y h:i:s A", $date + $timezone + (5 * 3600));
	}

	function containsIllegalCharacters($input)
	{
		$inputarr = str_split($input);
		$arrsize = count($inputarr);

		for($i = 0; $i < $arrsize; $i++)
		{
			$c = $inputarr[$i];
			if((ord($c) < ord("A") || ord($c) > ord("Z")) &&
				(ord($c) < ord("a") || ord($c) > ord("z")) &&
				(ord($c) < ord("0") || ord($c) > ord("9")) &&
				$c != " " && $c != "@" && $c != "@" && $c != "." && $c != "+")
			{
				return TRUE;
			}
		}
		return FALSE;
	}