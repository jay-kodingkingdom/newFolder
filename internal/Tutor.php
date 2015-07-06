<?php

require_once('User.php');
require_once('Subject.php');

final class Tutor extends User{
	
	const className='Tutor';
	const classFields=array('username','password',
			'displayname','timeSlotPointer',
			'subjectsPointers','profile','fullTime','hourlyRate'
	);
	
	public final function setDisplayname($displayname){
		$this->setField('displayname',$displayname);}
	public final function setProfile($profile){
		$this->setField('profile',$profile);}
	public final function setFullTime($fullTime){
		$this->setField('fullTime',$fullTime);}
	public final function setHourlyRate($hourlyRate){
		$this->setField('hourlyRate',$hourlyRate);}
	public final function setSubjects($subjects){
		$subjectNames = array();
		foreach ($subjects as
				$subject){
			$subjectName = $subject->getName();
			$subjectNames[$subjectName]=$subjectName;}
		$this->setField('subjectsPointers',$subjectNames);}
	public final function setTimeSlot(timeSlot $timeSlot){
		$this->setField('timeSlotPointer',$timeSlot->getName());}
	public final function getTimeSlot(){
		return timeSlot::getInstance($this->getField('timeSlotPointer'));}
	public final function getSubjects(){
		$subjectNames = $this->getField('subjectsPointers');
		$subjects = array();
		foreach ($subjectNames as
				$subjectName){
			$subjects[$subjectName]=Subject::getInstance($subjectName);}
		return $subjects;}
	public final function getHourlyRate(){
		return $this->getField('hourlyRate');}
	public final function getFullTime(){
		return $this->getField('fullTime');}
	public final function getProfile(){
		return $this->getField('profile');}
	public final function getDisplayname(){
		return $this->getField('displayname');}

	public static final function getTutor($username, $password, $subjects, timeSlot $timeSlots, $profile, $fullTime, $hourlyRate, $displayname){
		if (Tutor::fetchTutor($displayname)) return null;
	
		$tutor = Tutor::getUser($username, $password);
		$tutor->setDisplayName($displayname);
		$tutor->setSubjects($subjects);
		$tutor->setFullTime($fullTime);
		$tutor->setHourlyRate($hourlyRate);
		$tutor->setProfile($profile);
		$tutor->setTimeSlot($timeSlots);
		
		return $tutor;}
	public static final function fetchTutor($displayname){
		foreach (Tutor::getInstances() as $tutor){
			if ($tutor->getDisplayname() == $displayname)
						return $tutor;}
		return null;}}