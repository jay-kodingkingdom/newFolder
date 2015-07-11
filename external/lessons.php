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
		<script type="text/javascript">
			<?php
				require_once('../internal/fetchLogin.php');?> </script>
		<script type="text/javascript">
			function _search(){
				window.document.search.pageId.value=pageId; } </script></head>

	<body>
	<br>
		<form name="search" method='GET' onSubmit='_search()' action="lessons.php">
			<input type='text' name='search' value=''>
			<input type='hidden' name='pageId' value=''>
			<input name='submit' type='Submit' value='Search'></form>
	
		<?php 
			require_once('../internal/lesson.php');
			require_once('../internal/misc.php');
	
			$lessons = lesson::getInstances();
			
			if (isset($_GET['search'])){
				$search = urldecode($_GET['search']);
				
				$searchedLessons = array();
				
				foreach ($lessons as $lesson){
					if (strpos($lesson->getSubject()->getName()
							, $search) !== false){
						$searchedLessons[$lesson->getName()]=$lesson;
						continue;}
					foreach ($lesson->getStudents() as $student){
						if (strpos($student->getRealname()
								, $search) !== false){
							$searchedLessons[$lesson->getName()]=$lesson;
							continue 2;}}
					foreach ($lesson->getTutors() as $tutor){
						if (strpos($tutor->getRealname()
								, $search) !== false){
							$searchedLessons[$lesson->getName()]=$lesson;
							continue 2;}}}
				
				$lessons = $searchedLessons;} ?>
<br>					
		<div class="container"> <?php 
		
			$getNameFunc = function($someuser){
				return $someuser->getRealname();};
					
			foreach ($lessons as $lesson){
				?>
					<a href="lesson.php?pageId=<?php
									echo json_decode($pageId);
								?>&lesson=<?php
									echo $lesson->getName(); ?>">
						<div class="line">
<p>Subject:<?php say($lesson->getSubject()->getName());
?>; Students:<?php say(implode(',', array_map($getNameFunc, $lesson->getStudents())));
?>; Tutors:<?php say(implode(',', array_map($getNameFunc, $lesson->getTutors())));?></p></div></a> <?php } ?> 

			<a href="addlesson.php?pageId=<?php
								echo json_decode($pageId); ?>">
				<div id="addlesson" class="line">
					<p>Add an Lesson</p> </div> </a> </div> </body> </html>