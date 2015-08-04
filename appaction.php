<?php include("codefiles/appaction_code.php"); initialize(); ?>

<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css">
		<title><?php echo SITE_NAME; ?>: Appeal Action</title>
	</head>

	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Appeal Action</pre></center>
		<?php
			if(canViewPage())
			{
				displayPage();
			}
		?>
	</body>
</html>