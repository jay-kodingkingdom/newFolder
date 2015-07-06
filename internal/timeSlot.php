<?php

require_once('dataObject.php');
require_once('misc.php');

final class timeGrid extends dataObject{

	const className='timeGrid';
	const classFields=array('timePeriodPointers');
	
	public static final function fetchGridSlot($weekday, $gridTime){
		return timePeriod::fetchInstance(
					timeGrid::getTimeGrid()->getField('timePeriodPointers')
					[$weekday][$gridTime]);}
	public static final function fetchGridSlots($weekday, $startGridTime, $endGridTime){
		$timeSlot = timeSlot::getTimeSlots();
		$timePeriods = array();
		$hour = (int)($startGridTime/100);
		$minute = $startGridTime - $hour * 100;
		for ($gridTime = $startGridTime; $gridTime < $endGridTime; $gridTime += ($minute < 50? 10: 50)){
			$hour = (int)($gridTime/100);
			$minute = $gridTime - $hour * 100;
			$timePeriods[$gridTime]=timeGrid::fetchGridSlot($weekday, $gridTime);}
		$timeSlot->setTimePeriods($timePeriods);
		return $timeSlot;}		

	private static final function getTimeGrid(){
		if (count(timeGrid::getInstances())===1){
			foreach (timeGrid::getInstances() as $timeGrid){
				return $timeGrid;}}
		else {
			$slotsPointers = array();
			foreach (array('Monday','Tuesday','Wednesday','Thursday'
					,'Friday','Saturday','Sunday') as $weekday){
				$slotsPointers[$weekday]=array();
				for ($hour = 6; $hour < 24; $hour++){
					for ($minute = 0; $minute < 60; $minute += 10){
						$gridTime= $hour*100 + $minute;
						$slotsPointers[$weekday][$gridTime] = timeGrid::getGridSlot($weekday, $gridTime)->getName();}}}
							
			$timeGrid = timeGrid::getInstance(getRandomString());
			$timeGrid->setField('timePeriodPointers', $slotsPointers);
			return $timeGrid;}}
	
	private static final function getGridSlot($weekday, $gridTime){
		$hour = (int)($gridTime/100);
		$minute = $gridTime - $hour * 100;
		$nextMinute = ($minute + 10 > 60? $minute - 50 : $minute + 10);
		$nextHour = ($nextMinute < 10 ? $hour+1 : $hour);
		$startTime = new DateTime (date(timePeriod::timeFormat, strtotime('this '.$weekday.' '.$hour.':'.$minute, strtotime('05-01-2015'))));
		$endTime = new DateTime (date(timePeriod::timeFormat, strtotime('this '.$weekday.' '.$nextHour.':'.$nextMinute, strtotime('05-01-2015'))));
		$week = new DateInterval('P7D');
		$timeSlot = timePeriod::getTimePeriod($startTime, $endTime, PHP_INT_MAX, $week);
		return $timeSlot;}}

final class timeSlot extends dataObject{

	const className='timeSlot';
	const classFields=array('timePeriodsPointers');
	
	public final function getTimePeriods(){
		$timePeriodNames = $this->getField('timePeriodsPointers');
		$timePeriods = array();
		foreach ($timePeriodNames as
				$timePeriodName){
			$timePeriods[$timePeriodName]=timePeriod::getInstance($timePeriodName);}
		return $timePeriods;}
	public final function setTimePeriods($timePeriods){
		$timePeriodNames = array();
		foreach ($timePeriods as
				$timePeriod){
			$timePeriodName = $timePeriod->getName();
			$timePeriodNames[$timePeriodName]=$timePeriodName;}
		$this->setField('timePeriodsPointers',$timePeriodNames);}
	
	public final function addTimePeriod(timePeriod $timePeriod){
		$timePeriods = $this->getTimePeriods();
		$timePeriods[$timePeriod->getName()]=$timePeriod;
		$this->setTimePeriods($timePeriods);}
	public final function removeTimePeriod(timePeriod $timePeriod){
		$timePeriods = $this->getTimePeriods();
		unset($timePeriods[$timePeriod->getName()]);
		$this->setTimePeriods($timePeriods);}
	public final function addTimePeriods($someTimePeriods){
		$timePeriods = $this->getTimePeriods();
		foreach ($someTimePeriods as $timePeriod) {
			$timePeriods[$timePeriod->getName()]=$timePeriod;}
		$this->setTimePeriods($timePeriods);}
	public final function removeTimePeriods($someTimePeriods){
		$timePeriods = $this->getTimePeriods();
		foreach ($someTimePeriods as $timePeriod) {
			unset($timePeriods[$timePeriod->getName()]);}
		$this->setTimePeriods($timePeriods);}

	public static final function getTimeSlots(){
		while (timeSlot::fetchInstance($name=getRandomString())!==null){}
		$timeSlots = timeSlot::getInstance($name);
		$timeSlots->setTimePeriods(array());
		return $timeSlots;}}

final class timePeriod extends dataObject{
	const timeFormat = DateTime::ISO8601;
	const intervalFormat = 'P%yY%mM%dD';
	
	const className='timePeriod';
	const classFields=array('startTime','endTime','repeatInterval','repeatNumber','cancelledRepeats');

	private final function setStartTime(DateTime $time){
		$this->setField('startTime',$time->format(timePeriod::timeFormat));}
	private final function setEndTime(DateTime $time){
		$this->setField('endTime',$time->format(timePeriod::timeFormat));}
	private final function setRepeatInterval(DateInterval $repeatInterval){
		$this->setField('repeatInterval',$repeatInterval->format(timePeriod::intervalFormat));}
	private final function setRepeatNumber($repeatNumber){
		$this->setField('repeatNumber',$repeatNumber);}
	private final function setCancelledRepeats($cancelledNumbers){
		$this->setField('cancelledRepeats',$cancelledNumbers);}
		
	public final function getStartTime(){
		return DateTime::createFromFormat(
				timeSlot::timeFormat
				,$this->getField('startTime'));}
	public final function getEndTime(){
		return DateTime::createFromFormat(
				timeSlot::timeFormat
				,$this->getField('endTime'));}
	public final function getRepeatInterval(){
		return new DateInterval(
				$this->getField('repeatInterval'));}
	public final function getRepeatNumber(){
		return $this->getField('repeatNumber');}
	public final function getCancelledRepeats(){
		return $this->getField('cancelledRepeats');}

	public final function getTimeInterval($repeatNumber){
		$timeSlot = new timeInterval();
		$timeSlot->timePeriod = $this;
		$timeSlot->repeatNumber = $repeatNumber;
		return $timeSlot;}

	public static final function getTimePeriod(DateTime $startTime, DateTime $endTime, $repeatNumber=1, DateInterval $repeatInterval=null){
		while (timePeriod::fetchInstance($name=getRandomString())!==null){}
		$timeSlot = timePeriod::getInstance($name);
		$timeSlot->setStartTime($startTime);
		$timeSlot->setEndTime($endTime);
		$timeSlot->setRepeatNumber($repeatNumber);
		$timeSlot->setCancelledRepeats(array());
		if ($repeatNumber > 1){
			$timeSlot->setRepeatInterval($repeatInterval);}
		return $timeSlot;}

	/*public static final function overlaps(timePeriod $period1, timePeriod $period2){
		if ($period1->getStartTime()>$period2->getStartTime()){
			$formerPeriod=$period2;
			$latterPeriod=$period1;}
		else {
			$formerPeriod=$period1;
			$latterPeriod=$period2;}
					
		$year = new DateInterval('P1Y');
		$limitTime=$latterPeriod->getStartTime()->add($year);
		
		$getLatterTimeIntervals =
			function($latterTimeIntervalNumber) 
					use ($latterPeriod) {
				
				static $latterTimeIntervals=array();
				static $currentRepeatNumber = 1;
				
				while ($latterTimeIntervalNumber > count($latterTimeIntervals)){
					$currentTimeInterval = $latterPeriod->getTimeInterval($currentRepeatNumber);
					if ($currentTimeInterval->getCancelled()) break;
					$latterTimeIntervals[count($latterTimeIntervals)+1]=$currentTimeInterval;
					$currentRepeatNumber++;}
				return $latterTimeIntervals[$latterTimeIntervalNumber];};
			
		
		$formerPeriodRepeatNumber = 1;
		$currentTimeInterval = $formerPeriod->getTimeInterval($formerPeriodRepeatNumber);
		
		for ($limitLatterRepeatNumber = 1
					; $formerPeriodRepeatNumber <= $formerPeriod->getRepeatNumber()
					&& $currentTimeInterval->getEndTime() < $limitTime
					; $formerPeriodRepeatNumber++){
						
			$currentTimeInterval = $formerPeriod->getTimeInterval($formerPeriodRepeatNumber);
			
			if ($currentTimeInterval->getCancelled()) break;
			
			for ($currentLatterRepeatNumber = $limitLatterRepeatNumber
						; ; $currentLatterRepeatNumber++){
				if (timeInterval::overlaps(
						$currentTimeInterval
						, $getLatterTimeIntervals($currentLatterRepeatNumber)))
					return true;
				else {
					try{
						if ($currentTimeInterval->getStartTime() >
								$getLatterTimeIntervals($currentLatterRepeatNumber)
									->getEndTime()) {
							$limitLatterRepeatNumber = $currentLatterRepeatNumber + 1;
							continue;}}
					catch(Exception $e){}
					
					break;}}}

		return false;}*/}
		
final class timeInterval{
	public $timePeriod;
	public $repeatNumber;
	public final function cancel(){
		$cancelledRepeats = $this->timePeriod
			->getCancelledRepeats();
		$cancelledRepeats[$this->repeatNumber] =
			$this->repeatNumber;
		$this->timePeriod
			->setField('cancelledRepeats',$cancelledRepeats);}
	public final function uncancel(){
		$cancelledRepeats = $this->timePeriod
			->getCancelledRepeats();
		unset($cancelledRepeats[
			$this->repeatNumber]);
		$this->timePeriod
			->setField('cancelledRepeats',$cancelledRepeats);}
	public final function getCancelled(){;
		return in_array($this->repeatNumber, 
			$this->timePeriod->getCancelledRepeats());}
	public final function getStartTime(){
		$startTime = $this->timePeriod->getStartTime();
		for ($repeatNumber=1;
			$repeatNumber<$this->repeatNumber;
			$repeatNumber++){
				$startTime->add($this->timePeriod->getRepeatInterval());}
		return $startTime;}
	public final function getEndTime(){
		$endTime = $this->timePeriod->getEndTime();
		for ($repeatNumber=1;
			$repeatNumber<$this->repeatNumber;
			$repeatNumber++){
				$endTime->add($this->timePeriod->getRepeatInterval());}
		return $endTime;}
	/*public static final function overlaps(timeInterval $timePeriod1, timeInterval $timePeriod2){
		if ($timePeriod1->getCancelled() || $timePeriod2->getCancelled()) return false;
		else
			return ($timePeriod1->getStartTime() < $timePeriod2->getEndTime())  &&
			  ($timePeriod1->getEndTime() > $timePeriod2->getStartTime());}*/}