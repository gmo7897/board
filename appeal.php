<?php include("codefiles/appeal_code.php"); initialize(); ?>

<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css">
		<title><?php echo SITE_NAME; ?>: Appeal Moderation</title>
	</head>

	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Appeal Moderation</pre></center>
		<?php
			if(canAppeal())
			{
				handleContent();
			}
		?>
	</body>
</html>