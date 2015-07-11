<?php

require_once('dataObject.php');
require_once('misc.php');
require_once('timeGrid.php');

class timeSlot extends dataObject{

	use timeInfo;
	
	const className='timeSlot';
	const classFields=array('timeIntervalsNames'
							,'repeatOffset','repeatLength'
							,'dateSlotsPointers'
	);

	const slotsInfoType = 'dateSlot';
	
	
	public final function getCompleted(){
		$getCompleted = function(dateSlot $dateSlot) {
			return $dateSlot->getCompleted();};
		return count(
					array_filter($this->getDateSlots()
								, $getCompleted))
				>= $this->getRepeatLength();}
	
	public final function getRepeatLength(){
		return $this->getField('repeatLength');}
	public final function getRepeatOffset(){
		return $this->getField('repeatOffset');}
	public final function setRepeatOffset($repeatOffset){
		$this->setField('repeatOffset',$repeatOffset);}
	public final function setRepeatLength($repeatLength){
		$this->setField('repeatLength',$repeatLength);
		$this->purgeDateSlots();}
		
	protected final function purgeDateSlots(){
		foreach ($this->getDateSlots() as
					$repeatNumber => $dateSlot){
			if ($repeatNumber > $this->getRepeatLength())
				$this->removeDateSlot($repeatNumber);}}
	
	public final function getDateSlot($repeatNumber){
		if ($repeatNumber < 1 || $repeatNumber > $this->getRepeatLength())
			return null;
		
		if (! isset($this->getDateSlots()[$repeatNumber])){
			$this->addDateSlot($repeatNumber
					, call_user_func(
							static::slotsInfoType. '::get' .ucwords(static::slotsInfoType)
							, $this, $repeatNumber));}
		
		return $this->getDateSlots()[$repeatNumber];}
		
	public final function setTimeIntervals($timeIntervals){
		$timeIntervalNames = array();
		foreach ($timeIntervals as
				$timeInterval){
			$timeIntervalName = $timeInterval->name;
			$timeIntervalNames[$timeIntervalName]=$timeIntervalName;}
		$this->setField('timeIntervalsNames',$timeIntervalNames);
	
		$getUncompleted = function(dateSlot $dateSlot) {
			return ! $dateSlot->getCompleted();};
		foreach (array_filter($this->getDateSlots()
					, $getUncompleted)
				as $dateSlot){
			$dateSlot->setField('timeIntervalsNames',$timeIntervalNames);}}
		
	protected final function getDateSlots(){
		$dateSlotNames = $this->getField('dateSlotsPointers');
		$dateSlots = array();
		foreach ($dateSlotNames as
				$repeatNumber => $dateSlotName){
			$dateSlots[$repeatNumber] =
				call_user_func(static::slotsInfoType.'::fetchInstance',$dateSlotName);}
		return $dateSlots;}
	protected final function setDateSlots($dateSlots){
		$dateSlotNames = array();
		foreach ($dateSlots as
				$repeatNumber => $dateSlot){
			$dateSlotName = $dateSlot->getName();
			$dateSlotNames[$repeatNumber]=$dateSlotName;}
		$this->setField('dateSlotsPointers',$dateSlotNames);}	
	protected final function addDateSlot($repeatNumber, dateSlot $dateSlot){
		$dateSlots = $this->getDateSlots();
		$dateSlots[$repeatNumber]=$dateSlot;
		$this->setDateSlots($dateSlots);}	
	protected final function removeDateSlot($repeatNumber){
		$dateSlots = $this->getDateSlots();
		$dateSlots[$repeatNumber]->destroy();
		unset($dateSlots[$repeatNumber]);		
		$this->setDateSlots($dateSlots);}
		
	public static final function getTimeSlot($timeIntervals=array(), $repeatOffset=0, $repeatLength = PHP_INT_MAX){
		while (timeSlot::fetchInstance($name=getRandomString())!==null){}
		$timeSlot = static::getInstance($name);
		$timeSlot->setDateSlots(array());
		$timeSlot->setTimeIntervals($timeIntervals);
		$timeSlot->setRepeatOffset($repeatOffset);
		$timeSlot->setRepeatLength($repeatLength);
		return $timeSlot;}}
		
		
		
class dateSlot extends dataObject{
	
	use timeInfo {
		getTimeIntervals as protected;
		setTimeIntervals as protected;
		addTimeIntervals as protected;
		addTimeInterval as protected;
		removeTimeIntervals as protected;
		removeTimeInterval as protected;}
	
	const className='dateSlot';
	const classFields=array('timeSlotPointer','repeatNumber'
							,'timeIntervalsNames'
							,'enabled'
	);
	
	const slotInfoType = 'timeSlot';
	
	public final function getCompleted(){
		$now = new DateTime();
		foreach ($this->getDateIntervals() as $dateInterval){
			if ($dateInterval->endTime > $now) return false;}
		return true;}
	
	public final function getEnabled(){
		return $this->getField('enabled');}
	public final function setEnabled($enabled){
		$this->setField('enabled',$enabled);}
	public final function getRepeatNumber(){
		return $this->getField('repeatNumber');}
	private final function setRepeatNumber($repeatNumber){
		$this->setField('repeatNumber',$repeatNumber);}
	public final function getTimeSlot(){
		return call_user_func(static::slotInfoType.'::fetchInstance',$this->getField('timeSlotPointer'));}
	private final function setTimeSlot(timeSlot $timeSlot){
		$this->setField('timeSlotPointer',$timeSlot->getName());}
			
	public final function getDateIntervals(){
		$timeIntervals = $this->getTimeIntervals();
		$dateIntervals = array();
		foreach ($timeIntervals as $timeInterval){
			$dateIntervals[$timeInterval->name] =
					$timeInterval->fetchDateInterval(
							$this->getRepeatNumber() +
							$this->getTimeSlot()->getRepeatOffset());}
		return $dateIntervals;}
	public final function setDateIntervals(){
		throw new Exception();
	}
		
	public static final function getDateSlot(timeSlot $timeSlot, $repeatNumber){
		while (session::fetchInstance($name=getRandomString())!==null){}
		$dateSlot = static::getInstance($name);
		$dateSlot->setTimeSlot($timeSlot);
		$dateSlot->setRepeatNumber($repeatNumber);
		$dateSlot->setTimeIntervals($timeSlot->getTimeIntervals());
		$dateSlot->setEnabled(true);
		return $dateSlot;}}
		

trait timeInfo{
	
	public final function getTimeIntervals(){
		$timeIntervalNames = $this->getField('timeIntervalsNames');
		$timeIntervals = array();
		foreach ($timeIntervalNames as
				$timeIntervalName){
			$timeIntervals[$timeIntervalName] =
				timeGrid::fetchGridInterval(
						substr($timeIntervalName, 0, 3)
						, substr($timeIntervalName, 3, 4));}
		return $timeIntervals;}
	public final function setTimeIntervals($timeIntervals){
		$timeIntervalNames = array();
		foreach ($timeIntervals as
				$timeInterval){
			$timeIntervalName = $timeInterval->name;
			$timeIntervalNames[$timeIntervalName]=$timeIntervalName;}
		$this->setField('timeIntervalsNames',$timeIntervalNames);}
	public final function addTimeInterval(timeInterval $timeInterval){
		$timeIntervals = $this->getTimeIntervals();
		$timeIntervals[$timeInterval->name]=$timeInterval;
		$this->setTimeIntervals($timeIntervals);}
	public final function removeTimeInterval(timeInterval $timeInterval){
		$timeIntervals = $this->getTimeIntervals();
		unset($timeIntervals[$timeInterval->name]);
		$this->setTimeIntervals($timeIntervals);}
	public final function addTimeIntervals($someTimeIntervals){
		$timeIntervals = $this->getTimeIntervals();
		foreach ($someTimeIntervals as $timeInterval) {
			$timeIntervals[$timeInterval->name]=$timeInterval;}
		$this->setTimeIntervals($timeIntervals);}
	public final function removeTimeIntervals($someTimeIntervals){
		$timeIntervals = $this->getTimeIntervals();
		foreach ($someTimeIntervals as $timeInterval) {
			unset($timeIntervals[$timeInterval->name]);}
		$this->setTimeIntervals($timeIntervals);}}