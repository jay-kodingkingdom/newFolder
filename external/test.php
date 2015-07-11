<?php

echo date('D', strtotime('2015-08-09'));
echo 'love';

require('../internal/timeGrid.php');

$recurring = ($_GET['recurring'] === 'repeats');
$startTime = substr(str_replace(':', '', $_GET['startTime']),0,4);
$endTime = substr(str_replace(':', '', $_GET['endTime']),0,4);
$startDate = $_GET['startDate'];

$timeIntervals = timeGrid::fetchGridIntervals(
		timeGrid::getWeekday($startDate)
		, $startTime, $endTime);

var_dump($recurring);
var_dump($startTime);
var_dump($endTime);
var_dump(timeGrid::getWeekday($startDate));
var_dump($startDate);
var_dump($timeIntervals);
