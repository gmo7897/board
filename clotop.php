<?php include("codefiles/clotop_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Close/Delete Topic</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Close/Delete Topic Form</pre></center>
		<?
			menuBar4($user, $blevel, $boardnum, $topid);
			modBar($user);
			if(canView())
			{
				showPage();
			}
		?>
	</body>
</html>