<?php include("codefiles/topics_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>:Topic List</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>"><?php echo $bname; ?></pre></center>
		<?php
			menuBar2($user, $blevel, $board);
			modBar($user);
			if(canView())
			{
				showTopicList();
			}
		?>
	</body>
</html>