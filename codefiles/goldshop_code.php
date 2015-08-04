<?php
	include("includes/ConnectionInfo.inc");
	include("includes/ShopItem.inc");
	include("includes/SiteTools.inc");
	include("includes/InputFormatter.inc");

	define("BUY", 0);
	define("SELL", 1);
	define("EDIT", 2);
	define("NO_ACTION", 3);

	$ch;
	$item;
	$action = NO_ACTION;
	$user;
	
	function initialize()
	{
		createConnection();
		
		global $ch;
		global $item;
		global $action;
		global $user;

		$ch = loadTheme();
		$user = getUser($_COOKIE["info1"], $_COOKIE["info2"]);
		$item = getShopItembyId(intval($_GET["item"]));
		$raw_action = $_GET["action"];

		if(!$raw_action)
		{
			$action = NO_ACTION;
		}
		else if($raw_action == "buy")
		{
			$action = BUY;
		}
		else if($raw_action == "sell")
		{
			$action = SELL;
		}
		else if($raw_action == "edit")
		{
			$action = EDIT;
		}
	}

	function canView()
	{
		global $ch;
		global $user;

		if(!$user)
		{
			echo "<pre class = \"small0\">Error: You are not <a class = \"bg0\" href = \"login.php\">logged in</a>.<br />";
			return FALSE;
		}
		else if($user->getUserLevel() < BASE_LVL)
		{
			echo "<pre class = \"small".$ch."\">Error: You must be level 20 or higher to use the ".CURRENCY." shop.</pre>";
			return FALSE;
		}
		return TRUE;
	}

	function itemList()
	{
		global $ch;
		global $user;

		$sql = "SELECT itemid FROM shopitems";
		$result = mysql_query($sql);
		$allitemrdr = mysql_fetch_array($result);

		echo "<table class = \"messhead".$ch."\">\n<tr class = \"mencatbd".$ch."\"><td class = \"messhead".$ch."\"".
			" width = \"40%\"><b>Item Name</b></td><td class = \"messhead".$ch."\" width = \"30%\">".
			"<b>Price</b></td><td class = \"messhead".$ch."\" width = \"30%\"><b>Actions</b></td></tr>\n";

		while($allitemrdr)
		{
			$curritem = getShopItembyId($allitemrdr["itemid"]);
			echo "<tr class = \"mencatbd".$ch."\"><td class = \"messlst".$ch."\" width = \"40%\"><a class = ".
				"\"board".$ch."\" href = \"goldshop.php?item=".$curritem->getItemId()."\">".$curritem->getItemName().
				"</a></td><td class = \"messlst".$ch."\" width = \"30%\">".$curritem->getPrice()."</td><td class = \"messlst".
				$ch."\" width = \"30%\">";
			if($curritem->isOwner($user))
			{
				echo "<a class = \"board".$ch."\" href = \"goldshop.php?item=".$curritem->getItemId()."&action=sell\">Sell</a> ";
			}
			else if($user->getBiscuits() >= $curritem->getPrice())
			{
				echo "<a class = \"board".$ch."\" href = \"goldshop.php?item=".$curritem->getItemId()."&action=buy\">Buy</a> ";
			}
			if($user->getUserLevel() >= ADMIN_LEVEL)
			{
				echo "<a class = \"board".$ch."\" href = \"goldshop.php?item=".$curritem->getItemId()."&action=edit\">Edit</a> ";
			}
			echo "</td></tr>\n";
			$allitemrdr = mysql_fetch_array($result);
		}
		echo "</table>";
	}

	function buyScreen()
	{
		global $item;
		global $ch;
		global $user;

		if($item->isOwner($user))
		{
			echo "<pre class = \"small".$ch."\">Uhh... yeah... You own this item already.</pre>";
		}
		else if($user->getBiscuits() < $item->getPrice())
		{
			echo "<pre class = \"small".$ch."\">Error: You don't have enough ".CURRENCY." to buy this item.</pre>";
		}
		else
		{
			$buyitem = $_POST["confirmbuy"];

			if($buyitem)
			{
				$item->buyItem($user);
				echo "<pre class = \"small".$ch."\">Congratulations!  You now own ".$item->getItemName().
					"!  Return <a class = \"bg".$ch."\" href = \"goldshop.php\">here</a>.</pre>";
			}
			else
			{
				echo "<form action = \"goldshop.php?item=".$item->getItemId()."&action=buy\" method = \"post\">\n".
					"<pre class = \"small".$ch."\">Are you sure you want to buy ".$item->getItemName()."?</pre>".
					"<input type = \"submit\" name = \"confirmbuy\" value = \"Buy Item\" />\n</form>";
			}
		}
	}

	function sellScreen()
	{
		global $item;
		global $ch;
		global $user;

		if(!$item->isOwner($user))
		{
			echo "<pre class = \"small".$ch."\">Hmm... So you want to sell an item you haven't even bought.  Interesting idea but no.\n";
		}
		else
		{
			$sellitem = $_POST["confirmsell"];

			if($sellitem)
			{
				$user->setBiscuits($user->getBiscuits() + $item->getPrice() / 2);
				$item->removeOwner($user);
				$currencyearned = $item->getPrice() / 2;
				echo "<pre class = \"small".$ch."\">Congratulations!  You earned ".$currencyearned." ".CURRENCY." by selling ".
					$item->getItemName()."!  Return <a class = \"bg".$ch."\" href = \"goldshop.php\">here</a>.</pre>";
			}
			else
			{
				echo "<form action = \"goldshop.php?item=".$item->getItemId()."&action=sell\" method = \"post\">\n".
					"<pre class = \"small".$ch."\">Are you sure you want to sell ".$item->getItemName()."?</pre>".
					"<input type = \"submit\" name = \"confirmsell\" value = \"Sell Item\" />\n</form>";
			}
		}
	}

	function editScreen()
	{
		global $item;
		global $ch;
		global $user;

		if($user->getUserLevel() < ADMIN_LEVEL)
		{
			echo "<pre class = \"small".$ch."\">What are you doing here?  Get out!</pre>";
		}
		else
		{
			$edit = $_POST["confirmedit"];

			if($edit)
			{
				$price = intval($_POST["newprice"]);
				$description = formatString($_POST["newdescription"]);
				$item->setPrice($price);
				$item->setDescription($description);
				echo "<pre class = \"small".$ch."\">Item has been edited.  Return <a class = \"bg".$ch.
					"\" href = \"goldshop.php\">here</a>.</pre>";
			}
			else
			{
				echo "<form action = \"goldshop.php?item=".$item->getItemId()."&action=edit\" method = \"post\">\n".
					"<pre class = \"small".$ch."\">Item Price: <input type = \"text\" name = \"newprice\" size = \"7\" value = \"".$item->getPrice().
					"\" /></pre>\n<pre class = \"small".$ch."\">Item Description: <textarea rows = \"3\" cols = \"20\" name = \"newdescription\" wrap = \"soft\"".
					"\">".$item->getItemDescription()."</textarea></pre><input type = \"submit\" name = \"confirmedit\" value = \"Edit ".$item->getItemName()."\" />\n".
					"</form>";
			}
		}
	}

	function viewItem()
	{
		global $ch;
		global $user;
		global $item;

		echo "<table class = \"if".$ch."\">\n<tr><td class = \"i1f".$ch."\"><b>Name:</b> ".$item->getItemName()."</td></tr>\n<tr><td class = \"i2f".$ch.
			"\">".$item->getItemDescription()."</td></tr>\n<tr><td class = \"i1f".$ch."\"><b>Owners:</b> ".$item->getOwnerString()."</td></tr>";

		if($item->isOwner($user) || $user->getUserLevel() >= 100 || $user->getBiscuits() >= $item->getPrice())
		{
			echo "<tr><td class = \"i2f".$ch."\">";
			if($item->isOwner($user))
			{
				echo "<a class = \"board".$ch."\" href = \"goldshop.php?item=".$item->getItemId()."&action=sell\">Sell</a> ";
			}
			else if($user->getBiscuits() >= $item->getPrice())
			{
				echo "<a class = \"board".$ch."\" href = \"goldshop.php?item=".$item->getItemId()."&action=buy\">Buy</a> ";
			}
			if($user->getUserLevel() >= ADMIN_LEVEL)
			{
				echo "<a class = \"board".$ch."\" href = \"goldshop.php?item=".$item->getItemId()."&action=edit\">Edit</a> ";
			}
			echo "</td></tr>\n";
		}
		echo "</table>";
	}