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
			function getLesson(){
				var locations = document.getElementById("locations");
				var location = locations.options[locations.selectedIndex].value;
				var subjects = document.getElementById("subjects");
				var subject = subjects.options[subjects.selectedIndex].value;
				var tutors = document.getElementById("tutors");
				var tutor = tutors.options[tutors.selectedIndex].value;
				var students = document.getElementById("students");
				var student = students.options[students.selectedIndex].value;
				window.document.newLesson.location.value = location;
				window.document.newLesson.subject.value = subject;
				window.document.newLesson.tutor.value = tutor;
				window.document.newLesson.student.value = student;
				window.document.newLesson.pageId.value = pageId;} </script> </head>

		
	<body>
		<?php
			require_once('../internal/Admin.php');
			require_once('../internal/Student.php');
			require_once('../internal/Tutor.php');
			require_once('../internal/location.php');
			require_once('../internal/Subject.php');
			require_once('../internal/lesson.php');
			if (isset($_GET['tutor'])
					&& isset($_GET['student'])
					&& isset($_GET['subject'])
					&& isset($_GET['location'])
					&& isset($_GET['recurring'])
					&& isset($_GET['startTime'])
					&& isset($_GET['endTime'])
					&& isset($_GET['startDate'])){
				$tutor = Tutor::getInstance($_GET['tutor']);
				$student = Student::getInstance($_GET['student']);
				$subject = Subject::getInstance($_GET['subject']);
				
				$recurring = ($_GET['recurring'] === 'repeats');
				$startTime = substr(str_replace(':', '', $_GET['startTime']),0,4);
				$endTime = substr(str_replace(':', '', $_GET['endTime']),0,4);
				$startDate = $_GET['startDate'];
				
				$locationName = explode(',', $_GET['location'])[0];
				$locationSlotName = explode(',', $_GET['location'])[1];
				$location = location::fetchInstance($locationName);
				foreach ($location->getSlots() as $someSlot){
					if ($someSlot->getName() === $locationSlotName){
						$locationSlot = $someSlot;
						break;}}
					
				try{
					$timeIntervals = timeGrid::fetchGridIntervals(
											timeGrid::getWeekday($startDate)
											, $startTime, $endTime);
						
					$lesson = lesson::getLesson(array($tutor), array($student)
												, $locationSlot, $subject);
										
					foreach ($timeIntervals as $timeInterval){
						$isFree = $lesson->isFree($timeInterval);
						if ($isFree !== true){
							throw new Exception($isFree.' at '.$timeInterval->startTime->format(timeGrid::timeFormat).'-'.$timeInterval->endTime->format(timeGrid::timeFormat));}}
					
					$lesson->setRepeatOffset(
							timeGrid::getRepeatOffset($startDate));
					$lesson->setTimeIntervals($timeIntervals);
					
							
					?>
						<script type="text/javascript">
							alert('Lesson successfully created!'); </script> <?php }

				catch (Exception $e){
					$lesson->destroy();
					?>
						<script type="text/javascript">
							alert('<?php echo $e->getMessage(); ?>'); </script> <?php }} ?> 
				
						
		Add a Lesson!
		
		<br>
		
		<form name="newLesson" method='GET'
				onSubmit='getLesson()'
				action="addlesson.php">
			<table>
				<tr><td><select id="locations">
				    <option value="" disabled="disabled" selected="selected">
				    	Where is the lesson taking place?</option>
				    	<?php foreach (location::getInstances() as $location){
				    				foreach ($location->getSlots() as $locationSlot){
				    					$locationName = $location->getName().','.$locationSlot->getName();
				    					?>
				    					<option value="<?php echo $locationName;
				    						?>"><?php echo $locationName;?></option>
				    					<?php 
				    				}} ?></select></td></tr>
				<tr><td><select id="subjects">
				    <option value="" disabled="disabled" selected="selected">
				    	What subject is the lesson??</option>
				    	<?php foreach (Subject::getInstances() as $subject){
			    					$subjectName = $subject->getName();
			    					?>
			    					<option value="<?php echo $subjectName;
			    						?>"><?php echo $subjectName;?></option>
			    					<?php 
				    				} ?></select></td></tr>
				<tr><td><select id="tutors">
				    <option value="" disabled="disabled" selected="selected">
				    	Who will tutor this lesson?</option>
				    	<?php foreach (Tutor::getInstances() as $tutor){
				    				?>
				    					<option value="<?php echo $tutor->getName();
				    						?>"><?php echo $tutor->getRealname();?></option>
				    					<?php } ?></select></td></tr>
				<tr><td><select id="students">
				    <option value="" disabled="disabled" selected="selected">
				    	Who is the student?</option>
				    	<?php foreach (Student::getInstances() as $student){
				    				?>
				    					<option value="<?php echo $student->getName();
				    						?>"><?php echo $student->getRealname();?></option>
				    					<?php } ?></select></td></tr>
				<tr><td>Start Time:<input type="time" name='startTime' value='12:00:00' step='600' min='06:00:00' max='23:59:00'></td></tr>
				<tr><td>End Time:<input type="time" name='endTime' value='12:00:00' step='600' min='06:00:00' max='23:59:00'></td></tr>
				<tr><td>Start Date:<input type="date" name='startDate'></td></tr>
				<tr><td>Recurring:   
					Yes<input type='radio' name="recurring" id="repeats">
					No<input type='radio' name="recurring" id="norepeats"></td></tr>
				<tr><td><input name='submit' type='Submit' value='Submit'></td></tr></table>
		    <input type='hidden' name='location' value=''>
		    <input type='hidden' name='subject' value=''>
		    <input type='hidden' name='tutor' value=''>
		    <input type='hidden' name='student' value=''>
		    <input type='hidden' name='pageId' value=''> </form> <br> </body> </html>