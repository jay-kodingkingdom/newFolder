<?php

require_once('dataObject.php');
require_once('misc.php');

final class timeSlotSchedule extends dataObject{
	const timeFormat = DateTime::ISO8601;
	const intervalFormat = 'P%YY%MM%DD%HH%IM%SS';
	
	const className='timeSlotSchedule';
	const classFields=array('startTime','endTime','repeatInterval','repeatNumber','cancelledNumbers');

	private final function setStartTime(DateTime $time){
		$this->setField('startTime',$time->format(timeSlotSchedule::timeFormat));}
	private final function setEndTime(DateTime $time){
		$this->setField('endTime',$time->format(timeSlotSchedule::timeFormat));}
	private final function setRepeatInterval(DateInterval $repeatInterval){
		$this->setField('repeatNumber',$repeatInterval->format(timeSlotSchedule::intervalFormat));}
	private final function setRepeatNumber($repeatNumber){
		$this->setField('repeatNumber',$repeatNumber);}
	private final function setCancelledNumbers($cancelledNumbers){
		$this->setField('cancelledNumbers',$cancelledNumbers);}
		
	public final function getStartTime(){
		return DateTime::createFromFormat(
				timeSlot::timeFormat
				,$this->getField('startTime'));}
	public final function getEndTime(){
		return DateTime::createFromFormat(
				timeSlot::timeFormat
				,$this->getField('endTime'));}
	public final function getRepeatInterval(){
		return DateInterval::createFromDateString(
				$this->getField('repeatInterval'));}
	public final function getRepeatNumber(){
		return $this->getField('repeatNumber');}
	public final function getCancelledNumbers(){
		return $this->getField('cancelledNumbers');}
	public final function cancelRepeat($number){
		$cancelledNumbers = $this->getCancelledNumbers();
		$cancelledNumbers[$number]=$number;
		$this->setCancelledNumbers(
				$cancelledNumbers);}
	public final function uncancelRepeat($number){
		$cancelledNumbers = $this->getCancelledNumbers();
		unset($cancelledNumbers[$number]);
		$this->setCancelledNumbers(
				$cancelledNumbers);}

	public final function getTimeSlot($repeatNumber){
		$timeSlot = new timeSlot();
		$timeSlot->schedule = $this;
		$timeSlot->repeatNumber = $repeatNumber;
		return $timeSlot;}

	public static final function getTimeSlotSchedule(DateTime $startTime, DateTime $endTime, $repeatNumber=1, DateInterval $repeatInterval=null){
		while (timeSlot::fetchInstance($name=getRandomString())!==null){}
		$timeSlotSchedule = timeSlot::getInstance($name);
		$timeSlotSchedule->setStartTime($startTime);
		$timeSlotSchedule->setEndTime($endTime);
		$timeSlotSchedule->setRepeatNumber($repeatNumber);
		$timeSlotSchedule->setCancelledNumbers(array());
		if ($repeatNumber > 1){
			$timeSlotSchedule->setRepeatInterval($repeatInterval);}
		return $timeSlotSchedule;}

	public static final function overlaps(timeSlotSchedule $schedule1, timeSlotSchedule $schedule2){
		if ($schedule1->getStartTime()>$schedule2->getStartTime()){
			$firstSchedule=$schedule2;
			$lastSchedule=$schedule1;}
		else {
			$firstSchedule=$schedule1;
			$lastSchedule=$schedule2;}
					
		$lastTime = $lastSchedule->getStartTime();
		$lastInterval = $lastSchedule->getRepeatInterval();
		
		$year = new DateInterval('P1Y');
		$lastestTime=$lastSchedule->getStartTime()->add($year);
		
		$lastTimeSlots=array();
		
		for ($lastRepeats = 1; $lastRepeats <= $lastSchedule->getRepeatNumber() && $lastTime < $lastestTime;$lastRepeats++){
			$timeSlot = $lastSchedule->getTimeSlot($lastRepeats);
			if (! $timeSlot->getCancelled()) $lastTimeSlots[$lastRepeats]=$timeSlot;
			if ($lastInterval !== null) $lastTime->add($lastInterval);}
			
		$firstTime = $firstSchedule->getStartTime();
		$firstInterval = $firstSchedule->getRepeatInterval();
		
		for ($firstRepeats = 1, $lastFirstRepeats = 1; $firstRepeats <= $firstSchedule->getRepeatNumber() && $firstTime < $lastTime;$firstRepeats++){
			$timeSlot = $firstSchedule::getTimeSlot($firstRepeats);
			if (! $timeSlot->getCancelled()) {
				for ($repeats = $lastFirstRepeats; $repeats < $lastRepeats; $repeats++){
					if (timeSlot::overlaps($lastTimeSlots[$repeats], $timeSlot))
						return true;
					else {
						if ($timeSlot->getStartTime() > $lastTimeSlots[$repeats]->getEndTime()) {
							unset($lastTimeSlots[$repeats]);
							$lastFirstRepeats = $repeats + 1;}
						else break;}}}
			if ($firstInterval !== null) $firstTime->add($firstInterval);}

		return false;}}
		
final class timeSlot{
	public $schedule;
	public $repeatNumber;
	public final function cancel(){
		$this->schedule->cancelRepeat($repeatNumber);}
	public final function uncancel(){
		$this->schedule->uncancelRepeat($repeatNumber);}
	public final function getCancelled(){;
		return in_array($this->repeatNumber, 
			$this->schedule->getCancelledNumbers());}
	public final function getStartTime(){
		$startTime = $schedule->getStartTime();
		for ($repeatNumber=$this->repeatNumber;
			$repeatNumber<$this->schedule->getRepeatNumber();
			$repeatNumber++){
				$startTime->add($this->schedule->getRepeatInterval());}
		return $startTime;}
	public final function getEndTime(){
		$endTime = $schedule->getEndTime();
		for ($repeatNumber=$this->repeatNumber;
			$repeatNumber<$this->schedule->getRepeatNumber();
			$repeatNumber++){
				$endTime->add($this->schedule->getRepeatInterval());}
		return $endTime;}
	public static final function overlaps(timeSlot $timeSlot1, timeSlot $timeSlot2){
		if ($timeSlot1->getCancelled() || $timeSlot2->getCancelled()) return false;
		else
			return ($timeSlot1->getStartTime() < $timeSlot2->getEndTime())  &&
			  ($timeSlot1->getEndTime() > $timeSlot2->getStartTime());}}