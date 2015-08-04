<?php include("codefiles/modapp_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Moderator Application</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Moderator Application</pre></center>
		<?php
			menuBar0($user);
			modBar($user);
			
			if(canView())
			{
				showApplication();
			}
		?>
	</body>
</html>