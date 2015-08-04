<?php include("codefiles/stickytop_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Sticky/Unsticky Topic</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Sticky/Unsticky Topic</pre></center>
		<?php
			menuBar4($user, $blevel, $bdid, $topid);
			modBar($user);
			if(canView())
			{
				displayStickyTop();
			}
		?>
	</body>
</html>