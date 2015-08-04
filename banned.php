<?php include("codefiles/banned_code.php"); initialize(); ?>

<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css">
		<title><?php echo SITE_NAME; ?>: Banned Users List</title>
	</head>

	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Banned Users List</pre></center>
		<?php
			menuBar0($user);
			modBar($user);

			if(CanView())
			{
				displayBannedUsers();
			}
		?>
	</body>
</html>