<?php include("codefiles/index_code.php"); initialize(); ?>

<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css">
		<title><?php echo SITE_NAME; ?> Message Board Index</title>
	</head>

	<body class = "bg<?=$ch?>">
		<center><pre class = "big<?php echo $ch; ?>"><?php echo SITE_NAME; ?> Message Boards</pre></center>
		<?php
			displayPage();
		?>
	</body>
</html>