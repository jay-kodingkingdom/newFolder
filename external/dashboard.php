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
		
		if ($login->getUser()->getClassName()==='Admin'){
			say('<p>You are an Admin!</p>');
			say('<br>');
			say('<div class="container">');
			say('<a href="users.php?pageId='.json_decode($pageId).'">');
			say('<div id="users" class="box">');
			say('<p>Users</p>');
			say('</div>');
			say('</a>');
			say('<a href="tutors.php">');
			say('<div id="tutors" class="box">');
			say('<p>Tutors</p>');
			say('</div>');
			say('</a>');
			say('</div>');}
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