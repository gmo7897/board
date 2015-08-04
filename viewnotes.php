<?php include("codefiles/viewnotes_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Site Notifications</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Site Notifications</pre></center>
		<?php
			menuBar0($user);
			modBar($user);
			
			if(canView())
			{
				sysNoteList();
			}
		?>
	</body>
</html>