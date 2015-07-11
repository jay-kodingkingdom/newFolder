<?php

require_once('dataObject.php');
require_once('Tutor.php');
require_once('Student.php');
require_once('Subject.php');
require_once('timeSlot.php');
require_once('location.php');

final class lesson extends timeSlot{
	
	use lessonInfo;
	
	const className='lesson';
	const classFields=array('timeIntervalsNames'
							,'repeatOffset','repeatLength'
							,'dateSlotsPointers'
							,'tutorsPointers','studentsPointers','subject'
							,'locationName','locationSlot');
	
	const slotsInfoType = 'session';
	
	public final function getRepeatNumber($date){
		return timeGrid::getRepeatOffset(date(timeGrid::dateFormat, strtotime($date)))
					- $this->getRepeatOffset();}

	public final function setTutors($tutors){
		$tutorIds=array();
		foreach ($tutors
				as $tutor){
			$tutorIds[$tutor->getName()]=$tutor->getName();}
		$this->setField('tutorsPointers', $tutorIds);
	
		$getUncompleted = function(dateSlot $dateSlot) {
			return ! $dateSlot->getCompleted();};
		foreach (array_filter($this->getDateSlots()
					, $getUncompleted)
				as $dateSlot){
			$dateSlot->setField('tutorsPointers', $tutorIds);}}
	public final function setStudents($students){
		$studentIds=array();
		foreach ($students
				as $student){
			$studentIds[$student->getName()]=$student->getName();}
		$this->setField('studentsPointers', $studentIds);
	
		$getUncompleted = function(dateSlot $dateSlot) {
			return ! $dateSlot->getCompleted();};
		foreach (array_filter($this->getDateSlots()
					, $getUncompleted)
				as $dateSlot){
			$dateSlot->setField('studentsPointers', $studentIds);}}
	public final function setSubject(Subject $subject){
		$this->setField('subject', $subject->getName());
	
		$getUncompleted = function(dateSlot $dateSlot) {
			return ! $dateSlot->getCompleted();};
		foreach (array_filter($this->getDateSlots()
					, $getUncompleted)
				as $dateSlot){
			$dateSlot->setSubject($subject);}}
	public final function setLocationSlot(locationSlot $locationSlot){
		$this->setField('locationName', $locationSlot->getLocation()->getName());
		$this->setField('locationSlot', $locationSlot->getName());
	
		$getUncompleted = function(dateSlot $dateSlot) {
			return ! $dateSlot->getCompleted();};
		foreach (array_filter($this->getDateSlots()
					, $getUncompleted)
				as $dateSlot){
			$dateSlot->setLocationSlot($locationSlot);}}
	
	public final function getSession($repeatNumber){
		return $this->getDateSlot($repeatNumber);}
			
	public static final function getLesson($tutors, $students, locationSlot $locationSlot, Subject $subject){
		$lesson = lesson::getTimeSlot(array(), 0);
		$lesson->setTutors($tutors);
		$lesson->setStudents($students);
		$lesson->setSubject($subject);
		$lesson->setLocationSlot($locationSlot);
		return $lesson;}
	
		
	public final function isFree(timeInterval $timeInterval){
		foreach ($this->getTutors()
				as $tutor) {
			if ( ! in_array($timeInterval, 
					array_merge( $this->getTimeIntervals()
							, $tutor->getFreeTimeIntervals())))
				return $tutor->getRealname().' is not free';}
		foreach ($this->getStudents()
				as $student) {
			if (in_array($timeInterval, 
					array_merge( $this->getTimeIntervals()
							, $student->getUnfreeTimeIntervals())))
				return $student->getRealname().' is not free';}
		foreach (lesson::getInstances()
				as $lessons){
			if ($lessons !== $this
					&& $lessons->getLocationSlot() === $this->getLocationSlot()) {

				if (in_array($timeInterval
							, $lessons->getTimeIntervals())){
					return $this->getLocationSlot()->getLocation()->getName().' '.$this->getLocationSlot()->getName().' is not free';}}}
		return true;}}
		
final class session extends dateSlot{
	
	use lessonInfo;
	
	const className='session';
	const classFields=array('timeSlotPointer','repeatNumber'
							,'timeIntervalsNames'
							,'enabled'
							,'tutorsPointers','studentsPointers','subject'
							,'locationName','locationSlot'
							,'confirmsPointers');

	const slotInfoType = 'lesson';
	
	public final function getLesson(){
		return $this->getTimeSlot();}
	
	public final function getConfirms(){
		$confirms = $this->getField('confirmsPointers');
		return $confirms;}
		
	public final function addConfirm(User $user, $confirm){
		$confirms = $this->getConfirms();
		$confirms[$user->getName()]=$confirm;
		$this->setConfirms($confirms);}

	public final function removeConfirmed(User $user){
		$confirms = $this->getConfirms();
		unset($confirms[$user->getName()]);
		$this->setConfirms($confirms);}
			
	private final function setConfirms($confirms){
		$this->setField('confirmsPointers', $confirms);}
		
	public static final function getSession(lesson $lesson, $repeatNumber){
		$session = session::getDateSlot($lesson, $repeatNumber);
		$session->setLocationSlot($lesson->getLocationSlot());
		$session->setTutors($lesson->getTutors());
		$session->setStudents($lesson->getStudents());
		$session->setSubject($lesson->getSubject());
		$session->setConfirms(array());
		return $session;}}
		
trait lessonInfo {
	
	public final function getTutors(){
		$tutors = array();
		foreach ($this->getField('tutorsPointers')
				as $tutorId){
			$tutor = Tutor::fetchInstance($tutorId);
			$tutors[$tutor->getName()]=$tutor;}
		return $tutors;}
	public final function getStudents(){
		$students = array();
		foreach ($this->getField('studentsPointers')
				as $studentId){
			$student = Student::fetchInstance($studentId);
			$students[$student->getName()]=$student;}
		return $students;}
	public final function getSubject(){
		$subjectName = $this->getField('subject');
		$subject = Subject::fetchSubject($subjectName);
		return $subject;}
	public final function getLocationSlot(){
		$locationName = $this->getField('locationName');
		$location = location::fetchLocation($locationName);
		$locationSlotName = $this->getField('locationSlot');
		return $location->
			getSlots()[$locationSlotName];}
				
	public final function setTutors($tutors){
		$tutorIds=array();
		foreach ($tutors
				as $tutor){
			$tutorIds[$tutor->getName()]=$tutor->getName();}
		$this->setField('tutorsPointers', $tutorIds);}
	public final function setStudents($students){
		$studentIds=array();
		foreach ($students
				as $student){
			$studentIds[$student->getName()]=$student->getName();}
		$this->setField('studentsPointers', $studentIds);}
	public final function setSubject(Subject $subject){
		$this->setField('subject', $subject->getName());}
	public final function setLocationSlot(locationSlot $locationSlot){
		$this->setField('locationName', $locationSlot->getLocation()->getName());
		$this->setField('locationSlot', $locationSlot->getName());}}