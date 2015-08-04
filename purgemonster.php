<?php include("codefiles/purgemonster_code.php"); initialize(); ?>
<html>
	<head>
		<title><?php echo SITE_NAME; ?>: Purge Monster</title>
	</head>
	
	<body>
		<center><h1>The Hungry Purge Monster</h1></center>
		<?php
			if(!$user || $user->getUserLevel() < ADMIN_LEVEL)
			{
				echo "You cannot run the purge.";
			}
			else
			{
				runPurge();
				echo "The purge has run.  Return <a href = \"index.php\">here</a>";
			}
		?>
	</body>
</html>