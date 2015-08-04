<?php include("codefiles/postmess_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?></title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Create New Post</pre></center>
		<?php
			if(canView())
			{
				displayPostMessage();
			}
		?>
	</body>
</html>