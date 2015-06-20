<?php

require_once('dataObject.php');
require_once('misc.php');

final class timeDotSchedule extends dataObject{
	const timeFormat = DateTime::ISO8601;
	const intervalFormat = 'P%YY%MM%DD%HH%IM%SS';
	
	const className='timeDotSchedule';
	const classFields=array('time','repeatInterval','repeatNumber','cancelledNumbers');

	private final function setTime(DateTime $time){
		$this->setField('time',$time->format(timeDotSchedule::timeFormat));}
	private final function setRepeatInterval(DateInterval $repeatInterval){
		$this->setField('repeatNumber',$repeatInterval->format(timeDotSchedule::intervalFormat));}
	private final function setRepeatNumber($repeatNumber){
		$this->setField('repeatNumber',$repeatNumber);}
	private final function setCancelledNumbers($cancelledNumbers){
		$this->setField('cancelledNumbers',$cancelledNumbers);}
		
	public final function getTime(){
		return DateTime::createFromFormat(
				timeSlot::timeFormat
				,$this->getField('time'));}
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


	public static final function getTimeDot($repeatNumber){
		$timeDot = new timeDot();
		$timeDot->schedule = $this;
		$timeDot->repeatNumber = $repeatNumber;
		return $timeDot;}	
		
	public static final function getTimeSlotSchedule(DateTime $time, $repeatNumber=1, DateInterval $repeatInterval=null){
		while (timeDotSchedule::fetchInstance($name=getRandomString())!==null){}
		$timeDotSchedule = timeDotSchedule::getInstance($name);
		$timeDotSchedule->setTime($time);
		$timeDotSchedule->setRepeatNumber($repeatNumber);
		$timeDotSchedule->setCancelledNumbers(array());
		if ($repeatNumber > 1){
			$timeDotSchedule->setRepeatInterval($repeatInterval);}
		return $timeDotSchedule;}}
		
final class timeDot{
	public $schedule;
	public $repeatNumber;
	public final function cancel(){
		$this->schedule->cancelRepeat($repeatNumber);}
	public final function uncancel(){
		$this->schedule->uncancelRepeat($repeatNumber);}
	public final function getCancelled(){;
		return in_array($this->repeatNumber, 
			$this->schedule->getCancelledNumbers());}
	public final function getTime(){
		$time = $schedule->getTime();
		for ($repeatNumber=$this->repeatNumber;
			$repeatNumber<$this->schedule->getRepeatNumber();
			$repeatNumber++){
				$time = $time->add($this->schedule->getRepeatInterval());}
		return $time;}}