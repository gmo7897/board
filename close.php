<?php include("codefiles/close_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Close Account Form</title>
	</head>
	
	<body class = "bg<?php echo ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Close Account Form</pre></center>
		<?php
			menuBar1($user);
			modBar($user);
			
			if(canView())
			{
				showPage();
			}
		?>
	</body>
</html>