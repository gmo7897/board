<?php include("codefiles/appqueue_code.php"); initialize(); ?>

<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css">
		<title><?php echo SITE_NAME; ?>: Appeal Queue</title>
	</head>

	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Appeal Queue</pre></center>
		<?php
			menuBar0($user);
			modBar($user);

			if(canViewPage())
			{
				displayAppeals();
			}
		?>
	</body>
</html>