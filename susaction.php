<?php include("codefiles/susaction_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Suspended User Action</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Suspended User Action</pre></center>
		<?php
			menuBar0($user);
			modBar($user);
			
			if(canView())
			{
				$decision = $_POST["action"];
				
				if($decision)
				{
					dealWithSuspended();
				}
				else
				{
					?>
						<form action = "susaction.php?user=<?php echo $suspendedid; ?>" method = "post">
							<a class = "bg<?php echo $ch; ?>" href = "modhist.php?user=<?php echo $suspendedid; ?>">View Moderation History</a><br>
							<a class = "bg<?php echo $ch; ?>" href = "viewmap.php?user=<?php echo $suspendedid; ?>">View Usermap</a><br><br>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "choice" value = "0" /> - Ban User</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "choice" value = "1" /> - Ban Usermap</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "choice" value = "2" /> - Set Probation</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "choice" value = "3" /> - Set 7-Day Suspension</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "choice" value = "4" /> - Set 30-Day Suspension</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "choice" value = "5" /> - Restore User</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "choice" value = "6" /> - Lock Account</pre>
							<input type = "submit" name = "action" value = "Perform Action" />
						</form>
					<?php
				}
			}
		?>
	</body>
</html>