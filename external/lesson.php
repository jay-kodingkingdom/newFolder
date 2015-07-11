<?php

	require_once('../internal/pureFetchLogin.php');
	require_once('../debug.php');
	if (isset($_GET['lesson'])
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
				require_once('../internal/fetchLogin.php');?> </script> 
</head>

	<body>		
		<?php
			require_once('../internal/lesson.php');
			require_once('../internal/timeSlot.php');
												
			$lessonname = $_GET['lesson'];
			$lesson = lesson::fetchInstance($lessonname);
					
			$getNameFunc = function(&$someuser){
				return $someuser->getRealname();};
			?>
				<pre>
Subject: <?php say($lesson->getSubject()->getName()); ?>
Students: <?php say(implode(',  ', array_map($getNameFunc, $students = $lesson->getStudents())));?>
Tutors: <?php say(implode(',  ', array_map($getNameFunc, $tutors = $lesson->getTutors())));?>
Location: <?php say($lesson->getLocationSlot()->getName().', '.$lesson->getLocationSlot()->getLocation()->getName().'; '.$lesson->getLocationSlot()->getLocation()->getAddress()); ?>
Lessons:
				</pre>

				<div class="container" id="container" style="min-height: 100px; position: relative;">

					<div id="sessionContainer" style="display: none;">
						<form name="session"><input type="hidden" name="Number" value=""></form>
							<div id="editSession" style="display: none;">
<br>Subject: <div id="editSessionSubject"></div>
<br>Subject: <div id="editSessionSubject"></div> 
<br>Students: <div id="editSessionStudents"></div> 
<br>Tutors: <div id="editSessionTutors"></div>
<br>Location: <div id="editSessionLocation"></div>
<br>Time: <div id="editSessionTime"></div></div>
							<div id="viewSession" style="display: none;"><pre>
<br>Status: <div id="sessionStatus"></div>
<br>Subject: <div id="sessionSubject"></div> 
<br>Students: <div id="sessionStudents"></div> 
<br>Tutors: <div id="sessionTutors"></div>
<br>Location: <div id="sessionLocation"></div>
<br>Time: <div id="sessionTime"></div></pre></div></div>

					<div id="sessionsContainer" style="display: none;">
						<form name="sessions"><input type="hidden" name="firstNumber" value="<?php echo $lesson->getRepeatNumber('now');?>"></form>
						<div onClick="prevSessions();" style="position: absolute;left:5%;top:35%;width: 50px;height:50px;border: 2px solid;">Left Button</div>
						<div onClick="nextSessions();" style="position: absolute;left:90%;top:35%;width: 50px;height:50px;border: 2px solid;">Right Button</div>
						<div id="viewSessions" ></div></div></div>
Time Slots:
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
								$timeSlotIntervals = $lesson->getTimeIntervals();
								
								$tutorNamesCache = array();
								$tutorFreeTimeIntervalsCache = array();
								
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
											$tutorsAvailable = array();
											foreach ($tutors as $tutor){
												if (! isset($tutorFreeTimeIntervalsCache[$tutor->getName()])) $tutorFreeTimeIntervalsCache[$tutor->getName()] = $tutor->getFreeTimeIntervals();
												if (in_array($gridInterval, $tutorFreeTimeIntervalsCache[$tutor->getName()])){
													if ( ! isset($tutorNames[$tutor->getName()])) $tutorNames[$tutor->getName()]=$tutor->getRealname();
													$tutorsAvailable[$tutor->getName()]=$tutorNames[$tutor->getName()];}}
											$lessonHas = in_array($gridInterval, $timeSlotIntervals);
											echo '<td align="center" id="'.$weekday.sprintf('%04d', $gridTime).'">';
											echo ( $lessonHas ? 'X': ($tutorsAvailable ? implode(',',$tutorsAvailable) :'&nbsp;'));
											echo '</td>';}
										echo '</tr>';}} ?> </tbody> </table> </div> 
										
										
				<script type="text/javascript">

					viewSessions();
	
					function viewSessions(){
						showSessions();
						fetchSessions();}
					
					function viewSession(sessionNumber){
						if(typeof sessionNumber !== "undefined") {
							window.document.session.Number.value = sessionNumber;
							viewSession();}
						else {
							showSession();
							showViewSession();
							sessionNumber = window.document.session.Number.value;
							var sessionInfo = JSON.parse(decodeURIComponent(eval("window.document.session"+sessionNumber+".info.value"))); 
							document.getElementById('sessionStatus').innerHTML = (! sessionInfo['enabled'] ? "Cancelled" : "Normal");
							document.getElementById('sessionSubject').innerHTML = sessionInfo['subjecct'];
							document.getElementById('sessionStudents').innerHTML = (! sessionInfo['enabled'] ? "Cancelled" : "Normal");
							document.getElementById('sessionStatus').innerHTML = (! sessionInfo['enabled'] ? "Cancelled" : "Normal");
							document.getElementById('sessionStatus').innerHTML = (! sessionInfo['enabled'] ? "Cancelled" : "Normal");}}
					
					function showEditSession(){
						document.getElementById('viewSession').style.display = 'none';
						document.getElementById('editSession').style.display = 'block';}
	
					function showViewSession(){
						document.getElementById('viewSession').style.display = 'block';
						document.getElementById('editSession').style.display = 'none';}
					
					function showSession(){
						document.getElementById('sessionContainer').style.display = 'block';
						document.getElementById('sessionsContainer').style.display = 'none';}
					
					function showSessions(){
						document.getElementById('sessionContainer').style.display = 'none';
						document.getElementById('sessionsContainer').style.display = 'block';}
					
					function prevSessions(){
						window.document.sessions.firstNumber.value--;
						fetchSessions();}
	
					function nextSessions(){
						window.document.sessions.firstNumber.value++;
						fetchSessions();}
					
					function fetchSessions(){
						var sessionNumber = window.document.sessions.firstNumber.value;
						var count = getBoxCount();
						var childNode = document.getElementById('viewSessions').firstElementChild;
						while (childNode !== null){
						    if ((+sessionNumber) > (+childNode.id)
								    || (+sessionNumber)+count <= (+childNode.id)) {
							    childNode.style.display = 'none';}
							childNode = childNode.nextElementSibling;}
	
						for (var index = 0; index<count; index++){
							var node = document.getElementById((+sessionNumber)+(+index));
							if (node === null){
								fetchSession( (+sessionNumber)+(+index));}
							else {
								node.style.display = 'inline-block';}}}
					
					function fetchSession(sessionNumber){
						var nodes = document.getElementById('viewSessions').childNodes;
						for(var i=0; i<nodes.length; i++) {
						    if (sessionNumber == (+nodes[i].id)
								    && nodes[i].style.backgroundColor != 'gray') 
						         return;}
	
						var displayCode = (document.getElementById(sessionNumber) == null ? "" : "display: "+document.getElementById(sessionNumber).style.display+";");
						insertHTML('<p>Loading...</p>', sessionNumber, 'viewSessions' ,['class','box','style','background-color: gray;'+displayCode]);
				        
						var request = new XMLHttpRequest();
					    request.onreadystatechange = function() {
					        if (request.readyState == 4
						        	&& request.status == 200) {
					        	fetchedSession(sessionNumber, request.responseText);}}; 
					    request.open("GET", "api/getLessonSession.php?pageId=<?php echo json_decode($pageId);
					    											?>&lesson=<?php echo $lessonname; ?>"+
						    										 "&repeatNumber="+sessionNumber);
					    request.send();}
	
					function fetchedSession(sessionNumber, sessionData){
						try{
							var sessionDataObject = JSON.parse(sessionData);
							var code = "<p>"+sessionDataObject['date']+"</p>"+"<form name=\"session"+sessionNumber+"\"><input type=\"hidden\" name=\"info\" value=\""+encodeURIComponent(sessionData)+"\"></form>";
							var displayCode = (document.getElementById(sessionNumber) == null ? "" : "display: "+document.getElementById(sessionNumber).style.display+";");
							insertHTML(code, sessionNumber, 'viewSessions' ,['class','box','style',displayCode]);
	
							var getClickAction = function(sessionNumber){
								return function(){viewSession(sessionNumber);};};
							
							document.getElementById(sessionNumber).onclick = getClickAction(sessionNumber);}
						catch(err){
							fetchSession(sessionNumber);}}
					
					function insertHTML(code, index, id, params){
						var element = document.getElementById(id);
	
						var isFirst=true;
						var replace=false;
						var childNode = element.firstElementChild;
						while (childNode !== null){
							if (!isNaN(childNode.id)){
								var thisIndex = childNode.id;
								if (thisIndex == index) {
									replace = true;
									break;}
								if (thisIndex > index) break;
								isFirst = false;}
							childNode = childNode.nextElementSibling;}
	
						var keys = [];
						var values = [];
						
						for (var i=0;i<params.length;i++){
						    if ((i+2)%2==0) {
							    keys.push(params[i]);}
						    else {
						        values.push(params[i]);}}						
						
						if (replace){
							childNode.id=index;
							for (var i=0;i<keys.length;i++){
								childNode.setAttribute(keys[i],values[i]);}
							childNode.innerHTML = code;}
						else if (isFirst){
							var paramsString = "";
							for (var i=0;i<keys.length;i++){
								paramsString += " "+keys[i].toString()+"=\""+values[i].toString()+"\"";}
							element.innerHTML = "<div id=\""+index+"\""+paramsString+">" + code + "</div>" + element.innerHTML;}
						else {
							var newNode = document.createElement("div");
							newNode.id=index;
							for (var i=0;i<keys.length;i++){
								newNode.setAttribute(keys[i],values[i]);}
							newNode.innerHTML = code; 
							element.insertBefore(newNode, childNode);}}
	
					function getBoxCount(){
						var containerWidth = document.getElementById("viewSessions").clientWidth;
						var boxWidth = 70;
						return Math.floor(containerWidth/(2*boxWidth));} </script> </div> </body> </html>