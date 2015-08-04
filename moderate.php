<?php include("codefiles/moderate_code.php"); initialize(); ?>
<html>
	<head>
		<link rel = "StyleSheet" type = "text/css" href = "Colors.css" />
		<title><?php echo SITE_NAME; ?>: Moderate Message</title>
	</head>
	
	<body class = "bg<?php echo $ch; ?>">
		<center><pre class = "big<?php echo $ch; ?>">Moderate Message</pre></center>
		<?php
			menuBar0($user);
			modBar($user);
			
			if(canView())
			{
				$moderate = $_POST["moderate"];
				if($moderate)
				{
					moderateMessage();
				}
				else
				{
					?>
						<form action = "moderate.php?mark=<?php echo $markid; ?>" method = "post">
							<table class = "tophead<?php echo $ch; ?>">
								<tr><td class = "messhead<?php echo $ch; ?>">Message By: <?php echo $poster->getUserName(); ?> | 
									Mark Reason: <?php echo $markreason; ?> | <a class = "mencat<?php echo $ch; ?>" href =
										"messages.php?board=<?php echo $boardnum; ?>&topic=<?php echo $topicnum; ?>" target = "new page">View Topic</a></td></tr>
								<tr><td class = "messlst<?php echo $ch; ?>"><?php echo $message; ?></td></tr>
							</table><br /><br />
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "action" value = "-1" /> - No Action, Mark for Abuse</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "action" value = "0" /> - No Action</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "action" value = "1" /> - Delete Post</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "action" value = "2" /> -  Delete Post Remove 5 points</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "action" value = "3" /> - Delete Post set Probation</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "action" value = "4" /> - Delete Post Suspend User</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "action" value = "5" /> - Delete Topic</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "action" value = "6" /> - Delete Topic Remove 5 points</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "action" value = "7" /> - Delete Topic set Probation</pre>
							<pre class = "small<?php echo $ch; ?>"><input type = "radio" name = "action" value = "8" /> - Delete Topic Suspend User</pre><br />
							<pre class = "small<?php echo $ch; ?>"> Mod Reason: <select name = "reason" size = "1">
								<option><?php echo $markreason; ?></option>
								<option>Trolling</option>
								<option>Flaming</option>
								<option>Disruptive</option>
								<option>Illegal Activities</option>
								<option>Offensive</option>
								<option>Moderator Discretion</option>
							</select></pre><br />
							<input type = "submit" name = "moderate" value = "Moderate Message" />
						</form>
					<?php
				}
			}
		?>
	</body>
</html>