<?php include("codefiles/boardedit_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Board Editor</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Board Editor</pre></center>
		<?php
			menuBar0($user);
			modBar($user);
			
			if(canView())
			{
				displayEditor();
			}
		?>
	</body>
</html>