<?php include("codefiles/posttop_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Create New Topic</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Create New Topic</pre></center>
		<?php
			if(canView())
			{
				displayPostTopic();
			}
		?>
	</body>
</html>