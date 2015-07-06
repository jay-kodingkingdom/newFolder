<?php
	require_once('../debug.php');
	require_once('../internal/pureGetLogin.php');
	if ($loggedin==='true'){
		header('Location: '.'dashboard.php?pageId='.json_decode($pageId));
		exit();}
	else
		header('Content-Type: text/html; charset=utf-8;'); ?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>Log in</title>
		
		<script type="text/javascript" src="../debug.js"></script>
		<script type="text/javascript" src="encrypt.js"></script>
		<script type="text/javascript">
		<?php
			require_once('../internal/getLogin.php');?> </script>
		<script type="text/javascript">
		function getLogin(){
			window.document.login.username.value =
				encrypt(window.document.login.plainUsername.value,publicKey);
			window.document.login.password.value =
				encrypt(window.document.login.plainPassword.value,publicKey);
			window.document.login.pageId.value =
				pageId;
			window.document.login.plainUsername.value="";
			window.document.login.plainPassword.value="";} </script> </head>
	
	<body>
		<pre>
			<?php 
				loggedin_echo('You are already logged in!', 'You are not logged in!'); ?> </pre>
		
		<form name="login" method='GET' onSubmit='getLogin()' action="log_in.php">
			<table>
				<tr><td>
						<input type='text' name='plainUsername' value=''></td></tr>
				<tr><td>
						<input type='password' name='plainPassword' value=''></td></tr>
				<tr><td>
						<input name='submit' type='Submit' value='Submit'></td></tr> </table>
		    <input type='hidden' name='username' value=''>
		    <input type='hidden' name='password' value=''>
		    <input type='hidden' name='pageId' value=''> </form> </body> </html>