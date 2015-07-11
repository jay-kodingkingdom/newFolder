<?php

require_once('User.php');
require_once('Subject.php');
require_once(__DIR__.'/lesson.php');

final class Tutor extends User{
	
	use contactInfo;
	
	const className='Tutor';
	const classFields=array('username','password'
			,'realname','email','mobileNumber'
			,'timeSlotPointer'
			,'subjectsPointers','profile','fullTime'
			,'hourlyRate'
	);

	public final function getTimeSlot(){
		return timeSlot::getInstance($this->getField('timeSlotPointer'));}
	public final function setTimeSlot(timeSlot $timeSlot){
		$this->setField('timeSlotPointer',$timeSlot->getName());}
		
	public final function getSubjects(){
		$subjectNames = $this->getField('subjectsPointers');
		$subjects = array();
		foreach ($subjectNames as
				$subjectName){
			$subjects[$subjectName]=Subject::getInstance($subjectName);}
		return $subjects;}
	public final function setSubjects($subjects){
		$subjectNames = array();
		foreach ($subjects as
				$subject){
			$subjectName = $subject->getName();
			$subjectNames[$subjectName]=$subjectName;}
		$this->setField('subjectsPointers',$subjectNames);}
	public final function getProfile(){
		return $this->getField('profile');}
	public final function setProfile($profile){
		$this->setField('profile',$profile);}
	public final function getFullTime(){
		return $this->getField('fullTime');}
	public final function setFullTime($fullTime){
		$this->setField('fullTime',$fullTime);}

	public final function getHourlyRate(){
		return $this->getField('hourlyRate');}
	public final function setHourlyRate($hourlyRate){
		$this->setField('hourlyRate',$hourlyRate);}
	
		
	public final function getFreeTimeIntervals(){
		$timeSlot = $this->getTimeSlot();
		
		$containsThisTutor = function(lesson $lesson){
			return in_array($this, $lesson->getTutors());};
		$getTimeIntervals = function(lesson $lesson){
			return $lesson->getTimeIntervals();};
		$sameInterval = function(timeInterval $interval1, timeInterval $interval2){
			if ($interval1->name > $interval2->name)
				return 1;
			if ($interval1->name < $interval2->name)
				return -1;
			return 0;};							
					
		$containsThisTutorLessonTimeIntervalArrays = 
									array_map($getTimeIntervals
										, array_filter(
												lesson::getInstances()
												, $containsThisTutor));
		array_push($containsThisTutorLessonTimeIntervalArrays,array());
			
		return array_udiff(
					$this->getTimeSlot()->getTimeIntervals()
					, call_user_func_array('array_merge',
							array_values(
									$containsThisTutorLessonTimeIntervalArrays))
					, $sameInterval);}
		
				
	public static final function getTutor($username, $password
			, $realname, $email, $mobileNumber
			, timeSlot $timeSlot
			, $subjects, $profile, $fullTime
			, $hourlyRate){
		if (Student::fetchStudent($username)
				|| Admin::fetchAdmin($username)
				|| Tutor::fetchTutor($username)) return null;
	
		$tutor = Tutor::getUser($username, $password);
		$tutor->setRealname($realname);
		$tutor->setEmail($email);
		$tutor->setMobileNumber($mobileNumber);
		$tutor->setSubjects($subjects);
		$tutor->setFullTime($fullTime);
		$tutor->setHourlyRate($hourlyRate);
		$tutor->setProfile($profile);
		$tutor->setTimeSlot($timeSlot);
		
		return $tutor;}
	public static final function fetchTutor($username){
		foreach (Tutor::getInstances() as $tutor){
			if ($tutor->getUsername() == $username)
						return $tutor;}
		return null;}}