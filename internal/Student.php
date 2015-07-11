<?php

require_once('User.php');

final class Student extends User{
	
	use contactInfo;
	
	const className='Student';
	const classFields=array('username','password'
			,'realname','email','mobileNumber'
			,'profile'
	);

	public final function setProfile($profile){
		$this->setField('profile',$profile);}
	public final function getProfile(){
		return $this->getField('profile');}
	
	public final function getUnfreeTimeIntervals(){
		$containsThisStudent = function(lesson $lesson){
			return in_array($this, $lesson->getStudents());};
		$getTimeIntervals = function(lesson $lesson){
			return $lesson->getTimeIntervals();};				
					
		$containsThisStudentLessonTimeIntervalArrays = 
									array_map($getTimeIntervals
										, array_filter(
												lesson::getInstances()
												, $containsThisStudent));
		array_push($containsThisStudentLessonTimeIntervalArrays,array());
			
		return call_user_func_array('array_merge',
					array_values(
							$containsThisStudentLessonTimeIntervalArrays));}
		
	public static final function getStudent($username, $password
			, $realname, $email, $mobileNumber
			, $profile){
		if (Student::fetchStudent($username)
				|| Admin::fetchAdmin($username)
				|| Tutor::fetchTutor($username)) return null;

		$student = Student::getUser($username, $password);
		$student->setRealname($realname);
		$student->setEmail($email);
		$student->setMobileNumber($mobileNumber);
		$student->setProfile($profile);

		return $student;}
	public static final function fetchStudent($username){
		foreach (Student::getInstances() as $student){
			if ($student->getUsername() == $username)
				return $student;}
		return null;}}