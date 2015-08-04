<?php include("codefiles/inactaction_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "colors.css" />
		<title><?php echo SITE_NAME; ?>: User Activation</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">User Activation</pre></center>
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