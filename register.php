<?php include("codefiles/register_code.php"); initialize(); ?>

<html>
	<head>
		<title><?php echo SITE_NAME?>: Account Registration</title>
	</head>

	<body>
		<center><h1>Account Registration</h1></center>
		<?php
			if(canView())
			{
				registerAccount();
			}
		?>
	</body>
</html>