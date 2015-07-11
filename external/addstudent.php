<?php

	require_once('../debug.php');
	require_once('../internal/pureFetchLogin.php');
	if (isset($_GET['user'])
			&& $loggedin==='true'
			&& $login->getUser()->getClassName()==='Admin')
		header('Content-Type: text/html; charset=utf-8;');
	else {
		header('Location: '.'log_in.php');
		exit();} ?>


<!DOCTYPE html>
<html>

	<head>
		<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
		
		<script src="../debug.js"></script>
		<script type="text/javascript" src="encrypt.js"></script>
		<script type="text/javascript">
			<?php
				require_once('../internal/fetchLogin.php');?> </script>
		<script type="text/javascript">
			function getStudent(){
				if (window.document.newAdmin.plainPassword.value !==
							window.document.newAdmin.passwordConfirm.value){
					 alert('Please make sure your passwords are the same');
					 return false;}
				else{
					window.document.newAdmin.username.value =
						encrypt(window.document.newAdmin.plainUsername.value,publicKey);
					window.document.newAdmin.realname.value =
						encrypt(window.document.newAdmin.plainRealname.value,publicKey);
					window.document.newAdmin.password.value =
						encrypt(window.document.newAdmin.plainPassword.value,publicKey);
					window.document.newAdmin.pageId.value =
						pageId;
					window.document.newAdmin.plainRealname.value="";
					window.document.newAdmin.plainUsername.value="";
					window.document.newAdmin.plainPassword.value="";
					window.document.newAdmin.passwordConfirm.value="";

					return true;}} </script> </head>

		
	<body>
		<?php
			require_once('../internal/Admin.php');
			if (isset($_GET['realname'])
					&& isset($_GET['username'])
					&& isset($_GET['password'])){
				$realname = $encryption->decrypt($_GET['realname']);
				$username = $encryption->decrypt($_GET['username']);
				$password = $encryption->decrypt($_GET['password']);
				
								
				
				try{
					$existingUser=null;
					foreach (array_merge(Admin::getInstances(), Tutor::getInstances(), Student::getInstances())
							as $user){
						if ($user->getUsername()===$username) {
							$existingUser=$user;
							break;}}
					
					if ($existingUser!==null)
						alert('Another user with the same username already exists!');
						
					Student::getStudent($username, $password, $displayname, '');
					?>
						<script type="text/javascript">
							alert('Student successfully created!'); </script> <?php }

				catch (Exception $e){
					?>
						<script type="text/javascript">
							alert('<?php echo $e->getMessage(); ?>'); </script> <?php }} ?> 
				
				

						
		Add a Student!
		
		<br>
		
		<form name="newStudent" method='GET'
				onSubmit='return getStudent();'
				action="addstudent.php">
			<table>
				<tr><td>Real name:<input type='text' name='plainRealname' value=''></td></tr>
				<tr><td>Username:<input type='text' name='plainUsername' value=''></td></tr>
				<tr><td>Password:<input type='password' name='plainPassword' value=''></td></tr>
				<tr><td>Please type your password again:<input type='password' name='passwordConfirm' value=''></td></tr>
				<tr><td><input name='submit' type='Submit' value='Submit'></td></tr></table>
		    <input type='hidden' name='realname' value=''>
		    <input type='hidden' name='username' value=''>
		    <input type='hidden' name='password' value=''>
		    <input type='hidden' name='pageId' value=''> </form> <br> </body> </html>