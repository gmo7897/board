<?php include("codefiles/posthist_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: User Posting History</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<?php
			if(canView())
			{
				displayPostingHistory();
			}
		?>
	</body>
</html>