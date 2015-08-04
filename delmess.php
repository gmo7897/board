<?php include("codefiles/delmess_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Delete Message</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Delete Message</pre></center>
		<?php
			menuBar4($user, $boardlevel, $boardid, $topid);
			modBar($user);
			
			if(canView())
			{
				showPage();
			}
		?>
	</body>
</html>