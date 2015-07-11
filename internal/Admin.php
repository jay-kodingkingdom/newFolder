<?php

require_once('User.php');

final class Admin extends User{
	
	use contactInfo;
	
	const className='Admin';
	const classFields=array('username','password'
			,'realname','email','mobileNumber');
	
	public static final function getAdmin($username, $password, $realname, $email, $mobileNumber){
		if (Student::fetchStudent($username)
				|| Admin::fetchAdmin($username)
				|| Tutor::fetchTutor($username)) return null;
		$admin = Admin::getUser($username, $password);
		$admin->setRealname($realname);
		$admin->setEmail($email);
		$admin->setMobileNumber($mobileNumber);
		return $admin;}
	public static final function fetchAdmin($username){
		foreach (Admin::getInstances() as $admin){
			if ($admin->getUsername() == $username)
						return $admin;}
		return null;}}