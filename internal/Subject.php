<?php

require_once('dataObject.php');
require_once('misc.php');
require_once('allRoundConfig.php');


foreach(array_merge(array()
		, Subject::getInstances()) as $subject){
	if (! startsWith($subject->getName(), "Custom"))
		$subject->destroy();}

$addSubjects = function($subjectsType, $subjects)  use ( &$addSubjects ) {
	foreach($subjects
			as $subjectType => $subject){
		if (is_array($subject)) $addSubjects($subjectsType.$subjectType,$subject);
		else Subject::getSubject($subjectsType+$subject);}};
$addSubjects('',json_decode(subjects));

final class Subject extends dataObject{

	const className='Subject';
	const classFields=array();

	public final function getSubjectName(){
		preg_replace('/^' . preg_quote("Custom"
				, '/') . '/', '', $this->getName());}

	public static final function fetchSubject($name){
		return Subject::fetchInstance($name);}
	public static final function getSubject($name){
		return Subject::getInstance($name);}}