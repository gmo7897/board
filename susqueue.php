<?php include("codefiles/susqueue_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "colors.css" />
		<title><?php echo SITE_NAME; ?>: Suspended Users Queue</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Suspended Users Queue</pre></center>
		<?php
			menuBar0($user);
			modBar($user);
			
			if(canView())
			{
				showQueue();
			}
		?>
	</body>
</html>