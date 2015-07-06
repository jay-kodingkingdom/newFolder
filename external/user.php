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
		<script type="text/javascript">
			<?php
				require_once('../internal/fetchLogin.php');?> </script> </head>

	<body>		
		<?php
			require_once('../internal/misc.php');
			require_once('../internal/Admin.php');
			require_once('../internal/Tutor.php');
			require_once('../internal/Student.php');
			require_once('../internal/timeSlot.php');
												
			$username = $encryption->decrypt($_GET['user']);
			$user = null;
					
			foreach (
					array_merge(Admin::getInstances()
							, Tutor::getInstances()
							, Student::getInstances())
					as
						$someUser){
				if ($username === $someUser->getUsername()) {
					$user = $someUser;
					break;}}

			
					
			if ($user->getClassName()==='Admin'){
				?>
					<pre>
					User name: <?php echo $user->getUsername(); ?>
					Display name: <?php echo $user->getDisplayname(); ?>
					</pre> <?php }

			
			elseif ($user->getClassName()==='Tutor'){
				?>
						
					<script type="text/javascript">
						function toggleTimeSlot(gridTime, weekday) {

							var request = new XMLHttpRequest();
						    request.onreadystatechange = function()
						    {
						        if (request.readyState == 4 && request.status == 200)
						        {
						            callback(request.responseText); // Another callback here
						        }
						    }; 
						    request.open('GET', url);
						    request.send();
						
							
						    var request = (window.XMLHttpRequest) ? 
												new XMLHttpRequest() : new ActiveXObject("Msxml2.XMLHTTP");
						
						    if (xmlHttpRequest == null)
							return;
						
						    xmlHttpRequest.open("GET", "api/setTutorTimslot.php", true);
												
						    xmlHttpRequest.send(null); } </script> 
				
					<pre>
					User name: <?php say($user->getUsername()); ?>
					Display name: <?php say($user->getDisplayname()); ?>
					Subjects: <?php say(json_encode($user->getSubjects())); ?>
					Is Full Time: <?php say($user->getFullTime() ? 'Yes' : 'No'); ?>
					Hourly Rate: <?php say($user->getHourlyRate()); ?>
					Profile: <?php say($user->getProfile()); ?>
					TimeSlots:
					</pre>
	
					<div class="timetable">
					
						<table style="table-layout: auto;">
							<thead> 
								<?php
									echo '<tr>';
									echo '<th>Weekday</th>';
									foreach (array('Monday','Tuesday','Wednesday','Thursday'
											,'Friday','Saturday','Sunday') as $weekday){
										echo '<th>';
										echo $weekday;
										echo '</th>';}
									echo '</tr>'; ?> </thead>
							<tbody>
								<?php
									for ($hour = 6; $hour < 24; $hour++){
										for ($minute = 0; $minute < 60; $minute += 10){
											echo '<tr>';
											if ($minute === 0){
												echo '<th rowspan="6">';
												echo sprintf('%02d', $hour).':00';
												echo '</th>';}
											foreach (array('Monday','Tuesday','Wednesday','Thursday'
													,'Friday','Saturday','Sunday') as $weekday){
												echo '<td align="center">';
												$gridTime= $hour*100 + $minute;
												$gridPeriod = timeGrid::fetchGridSlot($weekday, $gridTime);
												$overlaps = $overlaps = in_array($gridPeriod, $user->getTimeSlot()->getTimePeriods());
												echo ( $overlaps ? 'X':'&nbsp;');
												echo '</td>';}
											echo '</tr>';}} ?> </tbody> </table> </div> <?php }


			elseif ($user->getClassName()==='Student'){
				say('<a href="addstudent.php?pageId='.json_decode($pageId).'">');
				say('<div id="addstudent" class="box">');
				say('<p>Add a Student</p>');
				say('</div>');
				say('</a>');}
	
				say('</div>');
			//else {
			//throw new Exception('Undefined user class!');}
				 ?> </body> </html>