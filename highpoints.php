<?php include("codefiles/highpoints_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: <?php echo RANK_POINTS; ?> Leaders</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>"><?php echo RANK_POINTS; ?> Leaders</pre></center>
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