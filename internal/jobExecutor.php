<?php

require_once('dataObject.php');
require_once('misc.php');
require_once('timeSlot.php');

final class JobExecutor extends dataObject{

	const className='JobExecutor';
	const classFields=array('jobPointer','timeSlotPointer');
	
	public static final function scheduleJob($jobPointer, timeSlot $timeSlot){
		while (JobExecutor::fetchInstance($name=getRandomString())!==null){}

		$jobExecutor = JobExecutor::getInstance($name);
		$jobExecutor->setField('jobPointer',$jobPointer);
		$jobExecutor->setField('timeSlotPointer',$timeSlot->getName());
		$jobExecutor->orderJobSchedule();
		return $jobExecutor;}

	public final function destroyExecutor(){
		revokeJobSchedule();
		$this->destroy();}
	public final function freezeExecutor(){
		revokeJobSchedule();
		$this->freeze();}
	public final function unfreezeExecutor(){
		orderJobSchedule();
		$this->unfreeze();}
		
	private final function orderJobSchedule(){}
	private final function revokeJobSchedule(){}}