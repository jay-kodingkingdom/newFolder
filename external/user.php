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
			$someuser = null;
					
			foreach (
					array_merge(Admin::getInstances()
							, Tutor::getInstances()
							, Student::getInstances())
					as
						$someoneUser){
				if ($username === $someoneUser->getUsername()) {
					$someuser = $someoneUser;
					break;}}

			
					
			if ($someuser->getClassName()==='Admin'){
				?>
					<pre>
User name: <?php say($someuser->getUsername()); ?>
Real name: <?php say($someuser->getRealname()); ?>
Email: <?php say($someuser->getEmail()); ?>
Mobile Number: <?php say($someuser->getMobileNumber()); ?>
					</pre> <?php }

			
			elseif ($someuser->getClassName()==='Tutor'){
				$getNameFunc = function(&$someuser){
					return $someuser->getName();};
				?>
					<pre>
User name: <?php say($someuser->getUsername()); ?>
Real name: <?php say($someuser->getRealname()); ?>
Email: <?php say($someuser->getEmail()); ?>
Mobile Number: <?php say($someuser->getMobileNumber()); ?>
Subjects: <?php say(implode(',  ', array_map($getNameFunc, $someuser->getSubjects()))); ?>
Is Full Time: <?php say($someuser->getFullTime() ? 'Yes' : 'No'); ?>
Hourly Rate: <?php say($someuser->getHourlyRate()); ?>
Profile: <?php say($someuser->getProfile()); ?>
TimeSlots:
					</pre>
	
					<div class="timetable">
					
						<table style="table-layout: auto;">
							<thead> 
								<?php
									echo '<tr>';
									echo '<th>Weekday</th>';
									foreach (array('Mon','Tue','Wed','Thu'
											,'Fri','Sat','Sun') as $weekday){
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
											foreach (array('Mon','Tue','Wed','Thu'
													,'Fri','Sat','Sun') as $weekday){

												$gridTime= $hour*100 + $minute;
												$gridInterval = timeGrid::fetchGridInterval($weekday, $gridTime);
												$timeSlotHas = in_array($gridInterval, $someuser->getTimeSlot()->getTimeIntervals());
												$available = in_array($gridInterval, $someuser->getFreeTimeIntervals());
												echo '<td align="center" id="'.$weekday.sprintf('%04d', $gridTime).'">';
												echo ( $timeSlotHas ? ( $available ? 'O':'X'):'&nbsp;');
												echo '</td>';}
											echo '</tr>';}} ?> </tbody> </table> </div> 
											
											
					<script type="text/javascript">
						function toggleTimeSlot(gridTime, weekday, value) {

							eksplode();
							var request = new XMLHttpRequest();
						    request.onreadystatechange = function() {
						        if (request.readyState == 4
							        	&& request.status == 200) {
						        	document.getElementById(weekday+String(gridTime)).innerHTML = (request.responseText === 'true' ? 'O' : '&nbsp;');}}; 
						    request.open("GET", "api/setTutorTimeInterval.php?pageId=<?php echo json_decode($pageId);
						    											?>&username=<?php echo $someuser->getUsername(); ?>"+
							    										 "&gridTime="+gridTime+
							    										 "&weekday="+weekday+
							    										 "&value="+value);
						    request.send();} 


						var weekdays = ["Mon", "Tue", "Wed"
										, "Thu", "Fri", "Sat"
										, "Sun"];

						var getAction = function (gridTime, weekday, value) {
							if (value === null) return function(){};
						    return function() { toggleTimeSlot(gridTime, weekday, value); };}
						
						for (var weeknum = 0; weeknum < weekdays.length; weeknum++){
							var weekday = weekdays[weeknum];
						    for (var hour = 6; hour < 24; hour++){
						    	for (var minute = 0; minute < 60; minute += 10){
									var gridTime = ("0"+hour).slice(-2) + ("0"+minute).slice(-2);
							    	var timeGridElement = document.getElementById(weekday + gridTime);
									
									var action = getAction( gridTime, weekday, (timeGridElement.innerHTML === "O" ? "false" : (timeGridElement.innerHTML === "&nbsp;" ? "true" : null)));
									
						    		timeGridElement.onclick = action; }}} </script>
					
					<script src="clickExplode.js"></script><?php }


			elseif ($someuser->getClassName()==='Student'){
				?>
					<pre>
User name: <?php say($someuser->getUsername()); ?>
Real name: <?php say($someuser->getRealname()); ?>
Email: <?php say($someuser->getEmail()); ?>
Mobile Number: <?php say($someuser->getMobileNumber()); ?>
Profile: <?php say($someuser->getProfile()); ?>
					</pre> <?php } ?></div> </body> </html>