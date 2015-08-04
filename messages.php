<?php include("codefiles/messages_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Message List</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>"><?php echo $tname; ?></pre></center>
		<?php
			menuBar3($user, $blevel, $board, $topic, $tactive);
			modBar($user);
			if(canView())
			{
				displayMessages();
			}
		?>
	</body>
</html>