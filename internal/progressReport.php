<?php

require_once('dataObject.php');
require_once('Tutor.php');
require_once('Student.php');
require_once('timeSlot.php');

final class progressReport extends dataObject{

	const className='progressReport';
	const classFields=array('tutorPointer','studentPointer',
			'monthTime','content'
	);

	public final function getTutor(){
		return Tutor::fetchInstance($this->getField('tutorPointer'));}
	public final function getStudent(){
		return Student::fetchInstance($this->getField('studentPointer'));}
	public final function getMonthTime(){
		return DateTime::createFromFormat(
			timeSlot::timeFormat
			,$this->getField('monthTime'));}
	public final function getContent(){
		return $this->getField('content');}
	private final function setTutor(Tutor $tutor){
		$this->setField('tutorPointer', $tutor->getName());}
	private final function setStudent(Student $student){
		$this->setField('studentPointer', $student->getName());}
	private final function setMonthTime(DateTime $time){
		$this->setField('monthTime',$time->format(timeInterval::timeFormat));}
	public final function setContent($content){
		$this->setField('content', $content);}
		
	public static final function getProgressReport(Tutor $tutor, Student $student, DateTime $time){
		if (Tutor::fetchTutor($tutor, $student, $time)) return null;
	
		while (static::fetchInstance($name=getRandomString())!==null){}
		$progressReport = progressReport::getInstance($name);
		$progressReport->setTutor($tutor);
		$progressReport->setStudent($student);
		$progressReport->setMonthTime($time);
		
		return $progressReport;}
	public static final function fetchTutor(Tutor $tutor, Student $student, DateTime $time){
		foreach (progressReport::getInstances() as $progressReport){
			if ($progressReport->getTutor() === $tutor &&
					$progressReport->getStudent() === $student &&
					$progressReport->getMonthTime() === $time)
						return $progressReport;}
		return null;}}