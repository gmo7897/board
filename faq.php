<?php include("codefiles/faq_code.php"); initialize(); ?>
<html>
	<head>
		<title><?php echo SITE_NAME; ?>: FAQ</title>
	</head>
	
	<body>
		<center><h1><?php echo SITE_NAME ; ?> FAQ</h1></center><br /><br />
		<b>How do I create topics?</b><br /><br />
		You create topics by clicking on the create new topic link on a topic list.  You must have an account of at least level 5, and have viewing access to the board to create new topics.  The only exception is message board announcements.<br /><br /><br />
		<b>How do I create messages?</b><br /><br />
		You create messages by clicking on the create new post link on any message list.  You must have an account of at least level 3, and have viewing access to the board to create new posts.<br /><br /><br />
		<b>What are <?php echo RANK_POINTS; ?>?</b><br /><br />
		<?php echo RANK_POINTS; ?> are a ranking system that measure seniority and activity on the message boards.  You gain one <?php echo RANK_POINTS; ?> each day for logging in and posting at least one message.  You can lose <?php echo RANK_POINTS; ?> by breaking the tou.  The amount varies by the severity of the violation.<br /><br /><br />
		<b>How were these boards made?</b><br /><br />
		<?php echo SITE_NAME; ?> Messageboards were made with a custom-made source code.<br /><br /><br />
		<b>What are user levels?</b><br /><br />
		User levels are a way to show how active a particular user is.  As you gain message points, your rank will increase.  This will give you access to more message boards.  The user levels are as follows:<br /><br />
		<?php displayLevels(); ?>
		<br /><br />
		<b>I created an account. How do I activate it?</b><br /><br />
		A staff member will review the account and decide whether to activate with full priveledges, activate with partial priveledges, or deny activation.<br /><br /><br />
		<b>I found a message that violates the <a href = "tou.php">TOU</a>.  What should I do?</b><br /><br />
		Click on the mark for moderation link beside the message to report it to the AppletLand / Lostfacts enforcers.  They will then take appropriate action on it.<br /><br /><br />
		<b>I was unfairly moderated.  How do I let the AppletLand / Lostfacts enforcer know.</b><br /><br />
		If you think a moderation was unfair, you can challenge it by clicking the appeal link next to the moderation in your moderation history.  Also, you can only appeal a moderation once.  Once you send the appeal, a AppletLand / Lostfacts enforcer will review the moderation and your response.  They will then choose to uphold or overturn the decision.<br /><br /><br />
		<b>Topics are disappearing for no reason.  Have we been hacked?</b><br /><br />
		No, that is the topic purge.  Once a topic is over 30 days old it will be erased from existence by the ever hungry purge monster.  Moderations will also be purged at the following rate:<br />
		<ul>
			<li>Unappealed No <?php echo RANK_POINTS; ?> Loss - 15 days</li>
			<li>Upheld No <?php echo RANK_POINTS; ?> Loss - 30 days</li>
			<li>Unappealed 5 <?php echo RANK_POINTS; ?> Loss - 30 days</li>
			<li>Upheld 5 <?php echo RANK_POINTS; ?> Loss - 45 days</li>
			<li>Unappealed Probation - 45 days</li>
			<li>Upheld Probation - 60 days</li>
			<li>7-day Suspension - 60 days</li>
			<li>30-day Suspenson - 90 days</li>
			<li>Moderations with a Pending appeal - No Purge</li>
			<li>Suspensions (Not Timed Suspensions) - No Purge</li>
			<li>Bannings - No Purge</li>
		</ul>
		<b>Can I be a AppletLand / Lostfacts enforcer?</b><br /><br />
		No.  Don't ask.  AppletLand / Lostfacts enforcer applications will be available when AppletLand / Lostfacts enforcers are needed.  If you really want to be a moderator, you can apply <a href = "modapp.php">here</a>.<br /><br /><br />
		<b>Who are the current AppletLand / Lostfacts enforcers?</b><br /><br />
		<?php displayModerators(); ?>
	</body>
</html>