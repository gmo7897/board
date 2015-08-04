<?php include("codefiles/leveledit_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Level Editor</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Level Editor</pre></center>
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