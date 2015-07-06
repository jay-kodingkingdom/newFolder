<?php
if (!isset($_GET['username'])
		|| !isset($_GET['gridTime'])
		|| !isset($_GET['weekday'])
		|| !isset($_GET['value']))
	exit();

if (strlen($_GET['gridTime']) !== 4)
	exit();

$hour = substr($_GET['gridTime'], 0, 2);
$minute = substr($_GET['gridTime'], 2, 2);

if ($hour !== '06'
		&& $hour !== '07'
		&& $hour !== '08'
		&& $hour !== '09'
		&& $hour !== '10'
		&& $hour !== '11'
		&& $hour !== '12'
		&& $hour !== '13'
		&& $hour !== '14'
		&& $hour !== '15'
		&& $hour !== '16'
		&& $hour !== '17'
		&& $hour !== '18'
		&& $hour !== '19'
		&& $hour !== '20'
		&& $hour !== '21'
		&& $hour !== '22'
		&& $hour !== '23' )
	exit();

if ($minute !== '00'
		&& $minute !== '10'
		&& $minute !== '20'
		&& $minute !== '30'
		&& $minute !== '40'
		&& $minute !== '50' )
			exit();
		
$weekday = $_GET['weekday'];

if ($weekday !== 'Monday'
		&& $weekday !== 'Tuesday'
		&& $weekday !== 'Wednesday'
		&& $weekday !== 'Thursday'
		&& $weekday !== 'Friday'
		&& $weekday !== 'Saturday'
		&& $weekday !== 'Sunday' )
			exit();

require_once('../../internal/Tutor.php');
require_once('../../internal/timeSlot.php');
		
$username = $_GET['username'];
$user = null;
	
foreach (
		Tutor::getInstances()
		as
		$someUser){
	if ($username === $someUser->getUsername()) {
		$user = $someUser;
		break;}}

if ($user === null)
	exit();

$value = $_GET['value'];
		
header("content-type:application/json");

if ($value === 'true'){
	$user->getTimeSlot()->addTimePeriod(
			timeGrid::fetchGridSlot($weekday, $gridTime));}
else {
	$user->getTimeSlot()->removeTimePeriod(
			timeGrid::fetchGridSlot($weekday, $gridTime));}

$gridPeriod = timeGrid::fetchGridSlot($weekday, $gridTime);
$result = in_array($gridPeriod, $user->getTimeSlot()->getTimePeriods());	

echo json_encode($result);