<?php include("codefiles/goldshop_code.php"); initialize(); ?>

<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css">
		<title><?php echo SITE_NAME; ?>: <?php echo CURRENCY; ?> Shop</title>
	</head>

	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>"><?php echo CURRENCY; ?> Shop</pre></center>
		<?php
			menuBar0($user);
			modBar($user);

			if(canView())
			{
				if($item)
				{
					switch($action)
					{
						case BUY:
							buyScreen();
							break;
						case SELL:
							sellScreen();
							break;
						case EDIT:
							editScreen();
							break;
						case NO_ACTION:
							viewItem();
							break;
						default:
							break;
					}
				}
				else
				{
					itemList();
				}
			}
		?>
	</body>
</html>