<?php include("codefiles/viewposts_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Your Posting History</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<?php
			if(canView())
			{
				userPosts();
			}
		?>
	</body>
</html>