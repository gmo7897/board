<?php include("codefiles/preferences_code.php"); initialize(); ?>

<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css">
		<title><?php echo SITE_NAME; ?>: Edit Preferences</title>
	</head>

	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Edit Preferences</pre></center>
		<?php
			menuBar0($user);
			modBar($user);

			if(canView())
			{
				displayPreferencesPage();
			}
		?>
	</body>
</html>