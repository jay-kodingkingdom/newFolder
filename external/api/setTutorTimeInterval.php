<?php
require_once('../../debug.php');
require_once('../../internal/pureFetchLogin.php');
if (! $loggedin==='true'
		|| ! $login->getUser()->getClassName()==='Admin')
	exit();


if (!isset($_GET['username'])
		|| !isset($_GET['gridTime'])
		|| !isset($_GET['weekday'])
		|| !isset($_GET['value']))
	exit();

$gridTime = $_GET['gridTime'];
		
if (strlen($gridTime) !== 4)
	exit();

$hour = substr($gridTime, 0, 2);
$minute = substr($gridTime, 2, 2);

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

if ($weekday !== 'Mon'
		&& $weekday !== 'Tue'
		&& $weekday !== 'Wed'
		&& $weekday !== 'Thu'
		&& $weekday !== 'Fri'
		&& $weekday !== 'Sat'
		&& $weekday !== 'Sun' )
			exit();

require_once('../../internal/Tutor.php');
require_once('../../internal/timeSlot.php');
		
$username = $_GET['username'];
$someuser = null;
	
foreach (
		Tutor::getInstances()
		as
		$someUser){
	if ($username === $someUser->getUsername()) {
		$someuser = $someUser;
		break;}}

if ($someuser === null)
	exit();

$value = $_GET['value'];
		
header("content-type:application/json");

if ($value === 'true'){
	$someuser->getTimeSlot()->addTimeInterval(
			timeGrid::fetchGridInterval($weekday, $gridTime));}
else {
	$someuser->getTimeSlot()->removeTimeInterval(
			timeGrid::fetchGridInterval($weekday, $gridTime));}

$gridInterval = timeGrid::fetchGridInterval($weekday, $gridTime);
$result = in_array($gridInterval, $someuser->getTimeSlot()->getTimeIntervals());	

echo json_encode($result);