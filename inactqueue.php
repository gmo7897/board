<?php include("codefiles/inactqueue_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Inactive Users Queue</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Inactive Users Queue</pre></center>
		<?php
			menuBar0($user);
			modBar($user);
			
			if(canView())
			{
				displayPage();
			}
		?>
	</body>
</html>