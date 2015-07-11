<?php
	require_once('../debug.php');
	require_once('../internal/pureFetchLogin.php');
	if ($loggedin==='true'
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
			function _search(){
		    	var element = document.getElementById("search");
				var search = window.document.search.searchText.value;
				var filter = "";

				for (var index = 0
						, getArray = location.search.substr(1).split("&")
							; index < getArray.length
							; index++) {
			        if (getArray[index].split("=")[0] === "filter") {
						search = getArray[index].split("=")[1];
				        break;}}
		        							    
			    window.location.href = "lessons.php?pageId="+pageId
											+"&filter="+filter
			    							+"&search="+search; } </script>
		<script type="text/javascript">
			function filter(){
		    	var element = document.getElementById("filter");
				var filter = encodeURIComponent(
								element.options[element.selectedIndex]
									.text);
				var search = "";

				for (var index = 0
						, getArray = location.search.substr(1).split("&")
							; index < getArray.length
							; index++) {
			        if (getArray[index].split("=")[0] === "search") {
						search = getArray[index].split("=")[1];
				        break;}}
			    
			    window.location.href = "users.php?pageId="+pageId
			    							+"&filter="+filter
			    							+"&search="+search; } </script></head>

	<body>

		<select id="filter" onchange="filter()">
		    <option value="" disabled="disabled" selected="selected">
		    	What kind of users do you want to browse?</option>
		    	
		    <option value="All">All</option>
		    <option value="Admin">Admin</option>
		    <option value="Tutor">Tutor</option>
		    <option value="Student">Student</option> </select>
		    
		<br>

		<?php 
			require_once('../internal/misc.php');
			require_once('../internal/Admin.php');
			require_once('../internal/Tutor.php');
			require_once('../internal/Student.php');
	
			if ( ! isset($_GET['filter'])) 
				$_GET['filter'] = null;
			if ( ! isset($_GET['search']))
				$_GET['search'] = null;
			
			
			if ($_GET['filter']==='Admin'){
				$users=Admin::getInstances();}
			
			elseif ($_GET['filter']==='Tutor'){
				$users=Tutor::getInstances();}
			
			elseif ($_GET['filter']==='Student'){
				$users=Student::getInstances();}
			
			else {
				$users = array_merge(
							Admin::getInstances()
							, Tutor::getInstances()
							, Student::getInstances());}
			
				
			if (urldecode($_GET['search']) !== '') {

				$searchedUsers = array();
				foreach ($users as $user){
					if (strpos($user->getUsername()
							, urldecode($_GET['search'])) !== false){
						$searchedUsers[$user->getUsername()]=$user;}}
			
				$users = $searchedUsers;} ?>
					
		<div class="container"> <?php 
					
			foreach ($users as $user){
				?>
					<a href="user.php?pageId=<?php
									echo json_decode($pageId);
								?>&user=<?php
									echo $page->getEncryption()->encrypt($user->getUsername()); ?>">
						<div class="box">
							<p><?php echo $user->getUsername(); ?></p></div></a> <?php }
		
								
								
			if ($_GET['filter']==='Admin'){
				?>
					<a href="addadmin.php?pageId=<?php
										echo json_decode($pageId); ?>">
						<div id="addadmin" class="box">
							<p>Add an Admin</p> </div> </a> <?php }
						
			elseif ($_GET['filter']==='Tutor'){
				?>
					<a href="addtutor.php?pageId=<?php
										echo json_decode($pageId); ?>">
						<div id="addtutor" class="box">
							<p>Add a Tutor</p> </div> </a> <?php }
							
			elseif ($_GET['filter']==='Student'){
				?>
					<a href="addstudent.php?pageId=<?php
										echo json_decode($pageId); ?>">
						<div id="addstudent" class="box">
							<p>Add a Student</p> </div> </a> <?php } ?> </div> </body> </html>