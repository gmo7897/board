<?php include("codefiles/user_code.php"); initialize(); ?>

<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css">
		<title><?php echo SITE_NAME; ?>: User Page</title>
	</head>

	<body class = "bg<?php echo $ch; ?>">
		<?php
			if(canView())
			{
				userPage();
			}
		?>
	</body>
</html>