<?php
require_once('../../debug.php');
require_once('../../internal/pureFetchLogin.php');
if (! $loggedin==='true')
	exit();


if (!isset($_GET['lesson'])
		|| !isset($_GET['repeatNumber']))
	exit();

require_once('../../internal/lesson.php');
		
header("content-type:application/json");

$lessonPointer = $_GET['lesson'];
$lesson = lesson::fetchInstance($lessonPointer);
$repeatNumber = $_GET['repeatNumber'];

@$session = $lesson->getSession($repeatNumber);

$sessionData = null;
if ($session !== null) {
	foreach ($session->getDateIntervals() as $dateInterval){
		$sessionData = $dateInterval->startTime->format(timeGrid::dateFormat);
		break;}}

echo json_encode($sessionData);