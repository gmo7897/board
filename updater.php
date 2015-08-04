<?php include("codefiles/updater_code.php"); initialize(); ?>
<html>
	<head>
		<title><?php echo SITE_NAME; ?>: Updater</title>
	</head>
	
	<body>
		<?php
			if(canView())
			{
				update();
				echo "Boards have been updated.  Return <a href = \"index.php\">here</a>.";
			}
		?>
	</body>
</html>