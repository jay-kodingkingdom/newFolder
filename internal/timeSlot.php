<?php

require_once('dataObject.php');

final class timeSlot extends dataObject{
	const timeFormat = DateTime::ISO8601;
	const intervalFormat = 'P%YY%MM%DD%HH%IM%SS';
	
	const className='timeSlot';
	const classFields=array('startTime','endTime','repeatInterval','repeatNumber','cancelledNumbers');

	private final function setStartTime(DateTime $time){
		$this->setField('startTime',$time->format(timeSlot::timeFormat));}
	private final function setEndTime(DateTime $time){
		$this->setField('endTime',$time->format(timeSlot::timeFormat));}
	private final function setRepeatInterval(DateInterval $repeatInterval){
		$this->setField('repeatNumber',$repeatInterval->format(timeSlot::intervalFormat));}
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
		$cancelledNumbers = $timeSlot->getCancelledNumbers();
		$cancelledNumbers[$number]=$number;
		$timeSlot->setCancelledNumbers(
				$cancelledNumbers);}
	public final function uncancelRepeat($number){
		$cancelledNumbers = $timeSlot->getCancelledNumbers();
		unset($cancelledNumbers[$number]);
		$timeSlot->setCancelledNumbers(
				$cancelledNumbers);}

	public static final function getTimeSlot(DateTime $startTime, DateTime $endTime, DateInterval $repeatInterval=null, $repeatNumber=1){
		while (timeSlot::fetchInstance($name=getRandomString())!==null){}
		$timeSlot = timeSlot::getInstance($name);
		$timeSlot->setStartTime($startTime);
		$timeSlot->setEndTime($endTime);
		if ($repeatInterval !== null){
			$timeSlot->setRepeatInterval($repeatInterval);
			$timeSlot->setRepeatNumber($repeatNumber);
			$timeSlot->setCancelledNumbers(array());}
		return $timeSlot;}}