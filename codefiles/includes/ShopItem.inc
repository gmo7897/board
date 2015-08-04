<?php
	include("BoardUser.inc");

	class ShopItem
	{
		var $itemid;
		var $itemname;
		var $itemdescription;
		var $price;
		var $owners;
		var $ownerstring;

		function ShopItem($id, $name, $desc, $pr, $own, $string)
		{
			$this->itemid = $id;
			$this->itemname = $name;
			$this->itemdescription = $desc;
			$this->price = $pr;
			$this->owners = $own;
			$this->ownerstring = $string;
		}

		function getItemId()
		{
			return $this->itemid;
		}

		function getItemName()
		{
			return $this->itemname;
		}

		function getItemDescription()
		{
			return $this->itemdescription;
		}

		function getPrice()
		{
			return $this->price;
		}

		function getOwners()
		{
			return $this->owners;
		}

		function getOwnerString()
		{
			return $this->ownerstring;
		}

		function isOwner($user)
		{
			if($user != NULL)
			{
				return in_array($user->getUserName(), $this->owners);
			}
			return FALSE;
		}

		function setPrice($pr)
		{
			$this->price = $pr;

			mysql_query("UPDATE shopitems SET price = ".$this->price." WHERE itemid = ".$this->itemid);
		}

		function setDescription($desc)
		{
			$this->itemdescription = $desc;

			mysql_query("UPDATE shopitems SET itemdescription = '$desc' WHERE itemid = ".$this->itemid);
		}

		function buyItem($user)
		{
			$this->owners[] = $user->getUserName();
			$this->ownerstring = $this->ownerstring.", ".$user->getUserName();
			

			mysql_query("UPDATE shopitems SET owners = '$this->ownerstring' WHERE itemid = ".$this->itemid);

			if($user->getUserLevel() < 60 && strcmp($this->itemname, "VIP Card") == 0)
			{
				$user->setUserLevel(60);
			}
			$user->setBiscuits($user->getBiscuits() - $this->price);
		}

		function removeOwner($user)
		{
			if(in_array($user->getUserName(), $this->owners))
			{
				$count = count($this->owners);
				$newownerstring = "";

				for($i = 0; $i < $count; $i++)
				{
					if(strcmp($user->getUserName(), $this->owners[$i]) != 0)
					{
						$newownerstring = $newownerstring.$this->owners[$i].", ";
					}
				}
				$this->ownerstring = rtrim($newownerstring, ", ");
				$owners = explode(", ", $this->ownerstring);

				$sql = "UPDATE shopitems SET owners = '$this->ownerstring' WHERE itemid = ".$this->itemid;
				mysql_query($sql);

				if(strcmp($this->itemname, "VIP Card") == 0 && $user->getUserLevel() == 60)
				{
					$user->setUserLevel(20);
				} 
			}
		}
	}

	function getShopItembyId($id)
	{
		$sql = "SELECT * FROM shopitems WHERE itemid = ".$id;
		$result = mysql_query($sql);
		
		if(mysql_num_rows($result) > 0)
		{
			$iteminfo = mysql_fetch_array($result);
			return new ShopItem($iteminfo["itemid"], $iteminfo["itemname"], $iteminfo["itemdescription"],
				$iteminfo["price"], explode(", ", rtrim($iteminfo["owners"], ", ")), rtrim($iteminfo["owners"], ", "));
		}
		else
		{
			return NULL;
		}
	}

	function getShopItembyName($name)
	{
		$sql = "SELECT itemid FROM shopitems WHERE itemname = '$name'";
		$result = mysql_query($sql);

		if(mysql_num_rows($result) > 0)
		{
			$iteminfo = mysql_fetch_array($result);
			$id = $iteminfo["itemid"];
			return getShopItembyId($id);
		}
		else
		{
			return NULL;
		}
	}