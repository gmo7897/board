<?php include("codefiles/marker_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Mark Message for Moderation</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Mark Message for Moderation</pre></center>
		<?php
			menuBar4($user, $blevel, $bid, $tid);
			modBar($user);
			
			if(canView())
			{
				$markplaced = $_POST["marksubmit"];
				if($markplaced)
				{
					markMessage();
				}
				else
				{
					?>
						<table class = "messhead<?php echo $ch; ?>">
							<tr>
								<td class = "messhead<?php echo $ch; ?>">
									<b>Message By:</b> <a class = "mencat<?php echo $ch; ?>" href = "whois.php?user=<?php echo $messbyid; ?>&board=<?php echo $bid; ?>&topic=<?php echo $tid; ?>&message=<?php echo $messid; ?>"><?php echo $messby; ?></a> | <b>Date Posted:</b> <?php echo $messdate; ?>
								</td>
							</tr>
							<tr>
								<td class = "messlst<?php echo $ch; ?>">
									<?php echo $message; ?>
								</td>
							</tr>
						</table>
						<form action = "marker.php?message=<?php echo $messid; ?>" method = "post">
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "reason" value = "Disruptive" /> - Disruptive</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "reason" value = "Flaming" /> - Flaming</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "reason" value = "Trolling" /> - Trolling</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "reason" value = "Illegal Activities" /> - Illegal Activities</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "reason" value = "Offensive" /> - Offensive</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "reason" value = "Other" /> - Other: Please Specify <input type = "text" name = "otherreason" size = "25" maxlength = "50" /></pre>
							<input type = "submit" name = "marksubmit" value = "Mark Message" />
						</form>
					<?php
				}
			}
		?>
	</body>
</html>