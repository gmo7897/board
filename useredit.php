<?php include("codefiles/useredit_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: User Editor</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">User Editor Page</pre></center>
		<?php
			menuBar0($user);
			modBar($user);
			
			if(canView())
			{
				$editsmade = $_POST["edit"];
				
				if($editsmade)
				{
					editUser();
				}
				else
				{
					?>
						<form action = "useredit.php?user=<?php echo $editedid; ?>" method = "post">
							<table class = "if<?php echo $ch; ?>">
								<tr>
									<td class = "i1f<?php echo $ch; ?>" width = "20%">
										<b>Username</b>
									</td>
									<td class = "i1f<?php echo $ch; ?>" width = "80%">
										<?php echo $edited->getUserName(); ?>
									</td>
								</tr>
								<tr>
									<td class = "i2f<?php echo $ch; ?>" width = "20%">
										<b>Userlevel</b>
									</td>
									<td class = "i2f<?php echo $ch; ?>" width = "80%">
										<input type = "text" name = "userlevel" size = "10" maxlength = "10" value = "<?php echo $edited->getUserLevel(); ?>" />
									</td>
								</tr>
								<tr>
									<td class = "i1f<?php echo $ch; ?>" width = "20%">
										<b><?php echo RANK_POINTS; ?></b>
									</td>
									<td class = "i1f<?php echo $ch; ?>" width = "80%">
										<input type = "text" name = "appoints" size = "10" maxlength = "10" value = "<?php echo $edited->getAppoints(); ?>" />
									</td>
								</tr>
								<tr>
									<td class = "i2f<?php echo $ch; ?>" width = "20%">
										<b><?php echo CURRENCY; ?></b>
									</td>
									<td class = "i2f<?php echo $ch; ?>" width = "80%">
										<input type = "text" name = "biscuits" size = "10" maxlength = "10" value = "<?php echo $edited->getBiscuits(); ?>" />
									</td>
								</tr>
								<tr>
									<td class = "i1f<?php echo $ch; ?>" width = "20%">
										<b>Signature</b>
									</td>
									<td class = "i1f<?php echo $ch; ?>" width = "80%">
										<textarea name = "signature" rows = "3" cols = "70" wrap = "soft"><?php echo str_replace("<br />", "\n", $edited->getSignature()); ?></textarea>
									</td>
								</tr>
							</table>
							<input type = "submit" name = "edit" value = "Update User" />
						</form>
					<?php
				}
			}
		?>
	</body>
</html>