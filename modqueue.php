<?php include("codefiles/modqueue_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Moderation Queue</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Moderation Queue</pre></center>
		<?php
			menuBar0($user);
			modBar($user);
			if(canView())
			{
				displayQueue();
			}
		?>
	</body>
</html>