<?php include("codefiles/userlist_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: User List</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">User List</pre></center>
		<?php
			menuBar0($user);
			modBar($user);
			
			if(canView())
			{
				listUsers();
			}
		?>
	</body>
</html>