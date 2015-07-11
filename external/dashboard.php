<?php
	require_once('../debug.php');
	require_once('../internal/pureFetchLogin.php');
	if ($loggedin==='true')
		header('Content-Type: text/html; charset=utf-8;');
	else {
		header('Location: '.'log_in.php');
		exit();} ?>

<!DOCTYPE html>
<html>

	<head>
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		
		<script src="../debug.js"></script>
		<script type="text/javascript">
			<?php
				require_once('../internal/fetchLogin.php');?> </script> </head>
			
			
	<body>
	<?php 
		require_once('../internal/misc.php');
		
		if ($login->getUser()->getClassName()==='Admin'){ ?>
<p>You are an Admin!</p>
			<br>
			<div class="container">
				<a href="users.php?pageId=<?php echo json_decode($pageId); ?>">
					<div id="users" class="box">
<p>Users</p>
						</div> </a>
			
				<a href="lessons.php?pageId=<?php echo json_decode($pageId); ?>">
					<div id="lessons" class="box">
<p>Lessons</p>
						</div> </a>
				<a href="phpConsole.php?pageId=<?php echo json_decode($pageId); ?>">
					<div id="phpconsole" class="box">
<p>PHP &shy;Console</p>
						</div> </a>  </div> <?php }			
		elseif ($login->getUser()->getClassName()==='Tutor'){
			say('<p>You are a Tutor!</p>');
			say('<br>');
			say('<div class="container">');
			say('<a href="timeslots.php">');
			say('<div id="timeslots" class="box">');
			say('<p>Timeslots</p>');
			say('</div>');
			say('</a>');
			say('<a href="lessons.php">');
			say('<div id="lessons" class="box">');
			say('<p>Lessons</p>');
			say('</div>');
			say('</a>');
			say('</div>');}
		elseif ($login->getUser()->getClassName()==='Student'){
			say('<p>You are a Student!</p>');
			say('<br>');
			say('<div class="container">');
			say('<a href="timeslots.php">');
			say('<div id="timeslots" class="box">');
			say('<p>Timeslots</p>');
			say('</div>');
			say('</a>');
			say('<a href="lessons.php">');
			say('<div id="lessons" class="box">');
			say('<p>Lessons</p>');
			say('</div>');
			say('</a>');
			say('</div>');}
		else {
			throw new Exception('Undefined user class!');} ?> </body> </html>