<?php

require_once('dataObject.php');
require_once('Tutor.php');
require_once('Student.php');
require_once('Subject.php');
require_once('timeSlot.php');
require_once('location.php');


final class lessonSchedule extends dataObject{
	
	const className='lessonSchedule';
	const classFields=array('tutorsPointers','studentsPointers','timeSlotSchedulePointer','location','locationSlot','subject');

	public final function getTutors(){
		$tutors = array();
		foreach (lessonSchedule::getField('tutorsPointers')
				as $tutorId){
			$tutor = Tutor::getInstance($tutorId);
			$tutors[$tutor->getDisplayname()]=$tutor;}
			return $tutors;}
	public final function getStudents(){
		$students = array();
		foreach (lessonSchedule::getField('studentsPointers')
				as $studentId){
			$student = Student::getInstance($studentId);
			$students[$student->getDisplayname()]=$student;}
		return $students;}
	public final function getSubject(){
		$subjectName = lessonSchedule::getField('subject');
		$subject = Subject::getSubject($subjectName);
		return $subject;}
	public final function getLocation(){
		$locationName = lessonSchedule::getField('location');
		$location = location::fetchLocation($locationName);
		return $location;}
	public final function getLocationSlot(){
		$locationSlotName = lessonSchedule::getField('locationSlot');
		return $this->getLocation()->
			getSlots()[$locationSlotName];}		
	public final function getTimeSlotSchedule(){
		$timeSlotScheduleId = lessonSchedule::getField('timeSlotSchedulePointer');
		$timeSlotSchedule = timeSlotSchedule::getInstance($timeSlotScheduleId);
		return $timeSlotSchedule;}

	public final function setTutors($tutors){
		$tutorIds=array();
		foreach ($tutors
				as $tutor){
			$tutorIds[$tutor->getDisplayname()]=$tutor->getName();}
		lessonSchedule::setField('tutorsPointers', $tutorIds);}
	public final function setStudents($students){
		$studentIds=array();
		foreach ($students
				as $student){
			$studentIds[$student->getDisplayname()]=$student->getName();}
		lessonSchedule::setField('studentsPointers', $studentIds);}
	public final function setTimeSlotSchedule(timeSlotSchedule $timeSlotSchedule){
		$nowSchedule = $this->getTimeSlotSchedule();
		lessonSchedule::setField('timeSlotSchedulePointer', $timeSlotSchedule->getName());
		if ($lessonSchedule->overlaps()) 
			lessonSchedule::setField('timeSlotSchedulePointer', $nowSchedule->getName());}
	public final function setLocation(location $location){
		lessonSchedule::setField('location', $location->getName());}
	public final function setLocationSlot(locationSlot $locationSlot){
		lessonSchedule::setField('locationSlot', $locationSlot->getName());}
	public final function setSubject(Subject $subject){
		lessonSchedule::setField('subject', $subject->getName());}
	
	public static final function getLessonSchedule($tutors, $students, timeSlotSchedule $timeSlotSchedule, locationSlot $locationSlot, Subject $subject){
		while (static::fetchInstance($name=getRandomString())!==null){}
		$lessonSchedule = lessonSchedule::getInstance($name);
		$lessonSchedule->setTutors($tutors);
		$lessonSchedule->setStudents($students);
		$lessonSchedule->setSubject($subject);
		$lessonSchedule->setLocation($locationSlot->getLocation());
		$lessonSchedule->setLocationSlot($locationSlot);
		try{
			$lessonSchedule->setTimeSlotSchedule($timeSlotSchedule);}
		catch(Exception $e){
			$lessonSchedule->destroy();
			return null;}
		return $lessonSchedule;}
	
	public final function overlaps(){
		foreach (lessonSchedule::getInstances()
				as $lessonSchedule){
			if ($lessonSchedule !== $this &&
					$lessonSchedule->getLocationSlot() === $this->getLocationSlot()) {
				if (timeSlotSchedule::overlaps($lessonSchedule->getTimeSlotSchedule(), $this->getTimeSlotSchedule()))
					return true;}}
		return false;}}
		
final class lesson{
	public $schedule;
	public $repeatNumber;
	public final function cancel(){
		$this->schedule->getTimeSlotSchedule()->cancelRepeat($repeatNumber);}
	public final function uncancel(){		
		$this->schedule->getTimeSlotSchedule()->uncancelRepeat($repeatNumber);
		if ($this->schedule->overlaps())
			$this->schedule->getTimeSlotSchedule()->cancelRepeat($repeatNumber);}
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