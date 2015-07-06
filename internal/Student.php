<?php

require_once('User.php');

final class Student extends User{
	
	const className='Student';
	const classFields=array('username','password',
			'displayname','profile'
	);
	
	public final function setDisplayname($displayname){
		$this->setField('displayname',$displayname);}
	public final function setProfile($profile){
		$this->setField('profile',$profile);}
	public final function getProfile(){
		return $this->getField('profile');}
	public final function getDisplayname(){
		return $this->getField('displayname');}
	
	public static final function getStudent($username, $password, $profile, $displayname){
		if (Student::fetchStudent($displayname)) return null;

		$student = Student::getUser($username, $password);
		$student->setDisplayName($displayname);
		$student->setProfile($profile);

		return $student;}
	public static final function fetchStudent($displayname){
		foreach (Student::getInstances() as $student){
			if ($student->getDisplayname() == $displayname)
				return $student;}
		return null;}}