<?php

require_once('dataObject.php');
require_once('Tutor.php');
require_once('Student.php');
require_once('Subject.php');
require_once('timeSlot.php');
require_once('location.php');


final class lessons extends dataObject{
	
	const className='lessonSchedule';
	const classFields=array('tutorsPointers','studentsPointers','timeSlotPointer','locationName','locationSlotNane','subject');

	public final function getTutors(){
		$tutors = array();
		foreach ($this->getField('tutorsPointers')
				as $tutorId){
			$tutor = Tutor::getInstance($tutorId);
			$tutors[$tutor->getDisplayname()]=$tutor;}
			return $tutors;}
	public final function getStudents(){
		$students = array();
		foreach ($this->getField('studentsPointers')
				as $studentId){
			$student = Student::getInstance($studentId);
			$students[$student->getDisplayname()]=$student;}
		return $students;}
	public final function getSubject(){
		$subjectName = $this->getField('subject');
		$subject = Subject::getSubject($subjectName);
		return $subject;}
	public final function getLocationSlot(){
		$locationName = $this->getField('locationName');
		$location = location::fetchLocation($locationName);
		$locationSlotName = $this->getField('locationSlotName');
		return $location->
			getSlots()[$locationSlotName];}		
	public final function getTimeSlots(){
		$timeSlotsPointer = $this->getField('timeSlotsPointer');
		$timeSlots = timeSlot::getInstance($timeSlotsPointer);
		return $timeSlots;}

	public final function setTutors($tutors){
		$tutorIds=array();
		foreach ($tutors
				as $tutor){
			$tutorIds[$tutor->getDisplayname()]=$tutor->getName();}
		$this->setField('tutorsPointers', $tutorIds);}
	public final function setStudents($students){
		$studentIds=array();
		foreach ($students
				as $student){
			$studentIds[$student->getDisplayname()]=$student->getName();}
		$this->setField('studentsPointers', $studentIds);}
	public final function setTimeSlots(timeSlot $timeSlots){
		$oldTimeSlots = $this->getTimeSlots();
		$this->setField('timeSlotsPointer', $timeSlots->getName());
if ($this->overlaps()) {
			$this->setField('timeSlotsPointer', $oldTimeSlots->getName());
//			$timeSlotSchedule->destroy();
			throw new Exception('Location not available');}
		else //$nowSchedule->destroy()
;}
	public final function setLocationSlot(locationSlot $locationSlot){
		$this->setField('locationName', $locationSlot->getLocation()->getName());
		$this->setField('locationSlot', $locationSlot->getName());}
	public final function setSubject(Subject $subject){
		$this->setField('subject', $subject->getName());}
	
	public static final function getLessons($tutors, $students, timeSlot $timeSlots, locationSlot $locationSlot, Subject $subject){
		while (lessons::fetchInstance($name=getRandomString())!==null){}
		$lessons = lessons::getInstance($name);
		$lessons->setTutors($tutors);
		$lessons->setStudents($students);
		$lessons->setSubject($subject);
		$lessons->setLocationSlot($locationSlot);
		try{
			$lessons->setTimeSlots($timeSlots);}
		catch(Exception $e){
			$lessons->destroy();
			return null;}
		return $lessons;}
	public final function overlaps(){
		foreach (lessons::getInstances()
				as $lessons){
			if ($lessons !== $this &&
					$lessons->getLocationSlot() === $this->getLocationSlot()) {

				if (timePeriod::overlaps($lessons->getTimeSlots(), $this->getTimeSlots())){
					echo 'overlaps';return true;}}}
		return false;}}
		
final class lesson{
	public $schedule;
	public $repeatNumber;
	public final function cancel(){
		$this->schedule->getTimeSlotSchedule()->cancelRepeat($repeatNumber);}
	public final function uncancel(){		
		$this->schedule->getTimeSlotSchedule()->uncancelRepeat($repeatNumber);
		if ($this->schedule->overlaps()){
			$this->schedule->getTimeSlotSchedule()->cancelRepeat($repeatNumber);
			throw new Exception('Location not available');}}
	public final function getCancelled(){;
		return in_array($this->repeatNumber, 
			$this->schedule->getTimeSlotSchedule()->getCancelledNumbers());}
	public final function getStartTime(){
		$startTime = $schedule->getStartTime();
		for ($repeatNumber=$this->repeatNumber;
		$repeatNumber<$this->schedule->getRepeatNumber();
		$repeatNumber++){
			$startTime = $startTime->add($this->schedule->getRepeatInterval());}
			return $startTime;}
	public final function getEndTime(){
		$endTime = $schedule->getEndTime();
		for ($repeatNumber=$this->repeatNumber;
		$repeatNumber<$this->schedule->getRepeatNumber();
		$repeatNumber++){
			$endTime = $endTime->add($this->schedule->getRepeatInterval());}
			return $endTime;}}